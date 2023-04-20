<? error_reporting(E_PARSE);

if(@$_GET['fad']){
    $p = (object) $_GET;
    $al=DB::table("fatura_adresleri")->where('user',$p->fad)->first();
die(json_encode($al));
}
if(@$_GET['fduz']){
    $p = (object) $_GET;
    $tar=date('YmdHis');
    $al=DB::select("update fatura_adresleri set deleted_at='$tar' where user='$p->fduz'");
    die();
}

?>
@extends('back.layouts.app')
@section('css')
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/dropify/css/dropify.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/sweet-alert2/sweetalert2.min.css')}}" rel="stylesheet" type="text/css">
    <style>
        table.dataTable.nowrap td:nth-child(2) {white-space: unset !important;}
        .tik{
            cursor: pointer;
        }
    </style>

@endsection
@section('body')


    <div class="row" data-lang="{{getLang()}}">
        <div class="col-12">

            <div class="card">
                <div class="card-body">
                    <h4 class="mt-0 header-title mb-3">
                        <div class="row">
                            <div class="col-md-6 col-sm-6 " >
                                EPİN / E-fatura İşlemleri
                            </div>
                            <div class="col-md-6 col-sm-6 text-right" >
                            <a href="?token=316" class="btn btn-small tkn">Token</a>
                            <a href="epinf" class="btn btn-small">Epin</a>
                            <a href="goldf" class="btn btn-small">GameGold</a>
                            <a href="pazarf" class="btn btn-small">Pazar Yeri</a>
                            </div>
                        </div>
                    </h4>
                    <div class="table-responsive">


                        <?
                            #-------------------------------------------------------------- Token olayları
                        if(isset($_GET['token']) && $_GET['token']==316){
                            $p = (object) $_GET;
                            if(isset($p->toks) && $p->toks!=''){ DB::table('e_token')->insert(['token' => $p->toks]);}
                            if(isset($p->tsil) && $p->tsil!=''){ DB::select("delete from e_token where id='$p->tsil'");}
                            if(isset($p->aktif) && $p->aktif!=''){ DB::select("update e_token set aktif='2' where aktif='1' "); DB::select("update e_token set aktif=1  where id='$p->aktif'");}
                    ?>
                        <div id="token" style="text-align:-webkit-center;border-style: solid;border-color: antiquewhite; padding: 10px;">
                            <form><input size="50" type="text" placeholder="Enter Token" name="toks"><button class="btn btn-sm">Add</button><input type="hidden" name="token" value="316"></form>
                            <table class="font-12 table table-bordered table-condensed " style="width: auto; text-align: center">
                                <tr><th>No</th><th>Token</th><th>Del</th><th>Active</th></tr>

                                <? $sor=DB::select("select * from e_token");$no=0;
                                foreach ($sor as $al){$no++;
                                    if($al->aktif==1){$renk='alert-success'; $text='Active';}
                                    elseif($al->aktif==2){$renk='alert-orange'; $text='Used';}
                                    else{ $renk='alert-secondary'; $text='Ready';}

                                echo "<tr><td>$no</td><td>$al->token</td><td><a href='?token=316&tsil=$al->id'>Del</a></td><td class='$renk'><a href='?token=316&aktif=$al->id'>$text</a></td></tr>";
                                }
                                ?>
                            </table>
                        </div>
                    <?}

#----------------------------------------------------------------Kesilen faturalar gösterim

                    if(isset($_GET['fkes']) && $_GET['fkes']==316) {
                            if(isset($_GET['fkt'])) {$fkt=$_GET['fkt'];} else {$fkt=date('Y-m-d');$_GET['fkt']=$fkt;}
                        ?>
                        <h3>Kesilen Faturalar</h3>
                        Fatura Kesim Tarihi :
                <select name="fkt_" id="fkt_">
                        @foreach(DB::select("select date_format(fkt,'%Y-%m-%d') fkt, COUNT(*) adet from e_ettn et,e_fatura ef where ef.ettn=et.ettn and ef.islem=1 GROUP by fkt order by et.fkt desc") as $u)
                            <option value="{{$u->fkt}}" @if($_GET['fkt']==$u->fkt) selected @endif>{{$u->fkt}} </option>
                        @endforeach
                </select>

<?
                            if($_GET['fkt']=='') {$ek=" isnull(et.fkt)"; } else {$ek=" date(et.fkt)='$fkt'";}  // kesim tarihi olmayanlar için
                            $sorgu=DB::select("
                            SELECT ef.*, et.fkt,u.tcno, u.name,u.email,u.id uid, count(*) kalem
                            FROM e_fatura ef
                            left join e_ettn et on et.ettn=ef.ettn
                            left join users u on u.id=ef.user
                            where $ek
                            and ef.islem=1
                            group by ef.ettn
                            order by ef.id desc
                            ");
                            $liste_toplam=0;$k18adt=0;$k0adt=0;$k0=0;
?>
                        <br>
                        Fatura adedi = <?=count($sorgu)?> <br>
                        Toplam = <span id='gt'></span><br>
                        KDV-18 TOPLAM = <span id='k18t'></span> / <span id='k18adt'></span><br>
                        KDV-0  TOPLAM = <span id='k0t'></span> / <span id='k0adt'></span><br>
                        <br><br>


                        <table lang="{{getLang()}}" id="datatable" class="font-12 table-sm table-bordered table-hover nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead><tr style="text-align: center"><th>Id</th><th>İsim</th><th>TC</th><th>Kalem</th><th>Tutar</th><th>Kesim</th><th>F.Kes</th></tr></thead>
                            <tbody>

                            @foreach($sorgu as $al)
                                <?if($al->kdv==18){$k18+=$al->gtop;$k18adt++;} else {$k0+=$al->gtop;$k0adt++;}?>
                                <tr>
                                    <td class="fdet" id="uid_{{$al->uid}}">{{$al->id}} <i class="fa fa-search"></i></td>
                                    <td class="tikf" id="{{$al->fid}}">{{$al->name}}</td>
                                    <td style="text-align: center">{{$al->tcno}}</td>
                                    <td style="text-align: center">{{$al->kalem}} </td>
                                    <td class="tikf" id="{{$al->fid}}" style="text-align: right">{{$al->gtop}} </td>
                                    <td id="son_{{$al->id}}" style="text-align: center">@if(strpos($al->fid,'M_')!==false) Manuel @else Entegratör @endif </td>
                                    <td style="text-align: center">
                                        <li class="btn far fa-trash-alt mip" id="{{$al->ettn}}" title="Faturayı iptal edip kesilmedi olarak işaretle"></li>
                                        @if(strpos($al->fid,'M_')===false)
                                            <i title="Faturayı Gör" id="{{$al->ettn}}" class="btn fa fa-search pdf" aria-hidden="true"></i>
                                        @else
                                            <i class="btn fa fa-search" onclick="alert('Manuel kesilen faturayı entegratörden çekemeyiz\nDetay için isim/tutar üzerine tıklayın.')" aria-hidden="true"></i>
                                        @endif
                                    </td>
                                </tr>
                                <?$liste_toplam+=$al->gtop;?>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Id</th>
                                <th>İsim </th>
                                <th>TC </th>
                                <th style="text-align: center">İşlem</th>
                                <th style="text-align: right">Tutar</th>
                                <th id="secili"></th>
                            </tr>
                            </tfoot>
                        </table>


<?


                    } else {
                        #---------------------------------------------------------------------------------------Tablo datatable----------------------------------------------------------------------------------?>

                        <div class="col-md-12 mb-4 mt-4">
                            @if(isset($_GET['date1']) and isset($_GET['date2']))
                                <?php
                                $date1 = $_GET['date1'];
                                $date2 = $_GET['date2'];
                                ?>
                            @else
                                <?php
                                $date1 = date('Y-m-d', strtotime('-0 days'));
                                $date2 = date('Y-m-d');
                                ?>
                            @endif
                                <div class="float-sm-right col-md-2">
                                    <div class="mb-3">
                                        <label class="form-label" >Kesim Tarihi</label>
                                        <input type="date" id="fkt" class="form-control style-input" name="fkt" value="{{$date2}}" required>
                                    </div>
                                </div>
                            <form class="row" method="get">
                                <div class="col-sm-12 col-md-2">
                                    <div class="mb-3">
                                        <label class="form-label" for="userinput1">İlk Tarih</label>
                                        <input type="date" id="date1" class="form-control style-input" name="date1" value="{{$date1}}" required>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-2">
                                    <div class="mb-3">
                                        <label class="form-label" for="userinput2">Son Tarih</label>
                                        <input type="date" id="date2" class="form-control style-input" name="date2" value="{{$date2}}" required>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-1 d-flex justify-content-sm-start justify-content-md-end align-items-center">
                                    <button type="submit" class="btn-gradient-secondary btn mt-2 m-lg-n3">F.Kesilmeyen </button>

                                </div>
                                <div class="col-md-1 d-flex justify-content-sm-start  align-items-center">
                                    <a href="?fkes=316" class="btn btn-gradient-secondary ">F.Kesilenler </a>
                                </div>
{{--                                <div class="col-md-3 d-flex justify-content-sm-start  align-items-center">--}}
{{--                                    <select class="form-control" id="kesilenler" style="font-size: 10px"><option>Kesilen Faturalar</option>--}}
{{--                                        @foreach(DB::select("SELECT format(sum(e.gtop),2) t,  date_format(ettn.tarih,'%d-%m-%Y') it, date_format(ettn.fkt,'%d-%m-%Y') kt FROM e_fatura e left join e_ettn ettn on e.fid=ettn.id group by ettn.fkt") as $u)--}}
{{--                                        <option value="">İşlem={{$u->it}} / F.Tar={{$u->kt}} / Toplam={{$u->t}}</option>--}}
{{--                                        @endforeach--}}
{{--                                    </select>--}}
{{--                                </div>--}}
                            </form>

                                <div class="col-sm-4 d-flex  align-items-center">
                                    <button class="btn btn-outline-warning fat mr-2 mt-2">Fatura Kes</button>
                                    <button onclick="pencere()" class="btn btn-info mt-2" data-backdrop="false" >Pencere</button>
                                </div>

                                <div class='align-items-center mt-2 ml-3'>
                                    <input type="checkbox" id="gizle" value="1" checked> Başarılı gönderimleri listeden çıkar
                                </div>
                        </div>


                            <?php

                            if (isset($_GET['status'])) {$status = $_GET['status'];} else{ $status='0';}

                            #------------------------Epin satışları


                            $sorgu=DB::select("
                    SELECT DISTINCT sum(es.alis) alist, es.kdv,u.tcno, esc.code,u.id,u.name, u.email, date_format(es.created_at,'%Y-%m-%d') tarih, (es.price/es.adet) adet_fiyat, count(*) adet, sum(es.price/es.adet) top
                    FROM epin_satis es, epin_satis_kodlar esc, users u, games_titles gt,games_packages_codes gpc
                    where date(es.created_at) BETWEEN '$date1' and '$date2'
                    and esc.epin_satis=es.id
                    and gt.id=es.game_title
                    and gt.fatura_kes=1
                    and u.id=es.user
                    and isnull(es.deleted_at)
                    and isnull(u.deleted_at)
                    and isnull(es.fatura)
                    and gpc.id=esc.code_id
                    and gpc.tedarikci<>32  # kripto ile alinanlari gec
                    and es.user not in (SELECT user FROM `odemeler` WHERE ulke is not null and ulke!='' group by user)
                    group by user,kdv
                    order by es.id
                    #limit 5
");

//dd($sorgu);

$ktop=0;
                            foreach ($sorgu as $s)   {
                                    if (!isset($user[$s->id])){
                                        $user[$s->id] = array('tutar', 'isim', 'id', 'adet');
                                    }
                                        $user[$s->id]['tutar'] += $s->top;
                                        $user[$s->id]['isim'] = $s->name;
                                        $user[$s->id]['adet'] += $s->adet;
                                        $user[$s->id]['tcno'] = $s->tcno;
                                        $gtop += $s->top;

                                // echo $s->kdv."/t=".$s->top."/a=". $s->alist."/F=".$s->top."<br>";

                                        if ($s->kdv == 18) {$kdv18 += $s->top;$kdv18a += $s->alist;} else {$kdv0 += $s->alist;$st += $s->top;
                                        }

                            }

#---------------------------------------------------------------------------------------------------Sipariş epinler

                            $sorgu=DB::select("
                    SELECT DISTINCT sum(esp.alis) alist, esp.kdv, u.tcno, u.id ,u.name, u.email, date_format(esp.created_at,'%Y-%m-%d') tarih, (esp.tutar/esp.adet) adet_fiyat, count(*) islem, sum(esp.adet) adet, sum(esp.tutar) top
                    FROM epin_siparisler esp
                    left JOIN users u on u.id=esp.user
                    where date(esp.created_at) BETWEEN '$date1' and '$date2'
                    and isnull(esp.deleted_at)
                    and isnull(u.deleted_at)
                    and isnull(esp.fatura)
                    and durum='Başarılı'
                    and tedarikci<>32           # kripto ile alinanlari gec
                    and esp.user not in (SELECT user FROM `odemeler` WHERE ulke is not null and ulke!='' group by user)
                    group by esp.user,esp.kdv
                    order by esp.id
                    ");

                            foreach ($sorgu as $s)   {
                                if (!isset($user[$s->id])) {$user[$s->id] = array('tutar', 'isim', 'id', 'adet');}

                                $user[$s->id]['tutar'] += $s->top;
                                $user[$s->id]['isim'] = $s->name;
                                $user[$s->id]['adet'] += $s->adet;
                                $user[$s->id]['tcno'] = $s->tcno;
                                $gtop += $s->top;

                                // echo $s->kdv."/t=".$s->top."/a=". $s->alist."/F=".$s->top."<br>";

                                if ($s->kdv == 18) {$kdv18 += $s->top; $kdv18a += $s->alist;} else {$kdv0 += $s->alist;$st += $s->top;}

                            }


                            ?>
<div class="row">
                            <div class="col-5"><pre>
   = Seçili Tarih Aralığındaki Fatura Toplamı =
    KDV-18 DAHİL TOPLAM = <?=number_format($kdv18,2)?>

    KDV-18 ALIS  TOPLAM = <?=number_format($kdv18a,2)?> / Fark (<?=number_format($kdv18-$kdv18a,2)?>)
    KDV-0 ALIS TOPLAMI  = <?=number_format($kdv0,2)?> / (<?=number_format($st-$kdv0+($kdv0),2)?>)
    KOMİSYON            = <?=number_format($st-$kdv0,2) ?>
                                </pre>
                            </div>

</div>
                            <table lang="{{getLang()}}" id="datatable" class="font-12 table-sm table-bordered table-hover nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead><tr style="text-align: center"><th>Id</th><th>İsim</th><th>TC</th><th>İşlem</th><th>Tutar</th><th>Sonuç</th><th>F.Kes</th></tr></thead>
                                <tbody>

                            @foreach($user as $u => $r)
                                <tr>
                                    <td class="fdet" id="u_{{$u}}">{{$u}} <i class="fa fa-search"></i></td>
                                    <td class="tik" id="{{$u}}">{{$r['isim']}}</td>
                                    <td style="text-align: center">{{$r['tcno']}}</td>
                                    <td style="text-align: center">{{$r['adet']}} </td>
                                    <td  id="t_{{$u}}" style="text-align: right">{{ cut($r['tutar'],'.',2) }} </td>
                                    <td id="son_{{$u}}"></td>
                                    <td style="text-align: center">
                                        <input type="checkbox" value="{{$u}}" name="kes" class="kes" checked>
                                        <li class="btn fas fa-check mkes" id="{{$u}}" title="Kesildi olarak işaretle"></li>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Id</th>
                                <th>İsim </th>
                                <th>TC </th>
                                <th style="text-align: center">İşlem</th>
                                <th style="text-align: right">Tutar</th>
                                <th id="secili"></th>
                            </tr>
                            </tfoot>
                        </table>

                        <? } #-------------------------------------------------------------------------------------------------------------------------------------------------------------------------?>

                    </div>
                </div>
            </div>

        </div>
    </div>

    <?

//$r='{"INFO_BELGE_HAREKET":[{"SIRA_NO":"'.$sira.'","MIKTAR":"'.$adet.'","BARKOD":"0000000000000","STOK_KODU":"STK-0000'.$stok_kod.'","STOK_ADI":"'.$stok_adi.'","SATIR_ACIKLAMA":"Hareket Açıklama Alanı","KDV_ORANI":"'.$kdv.'","BIRIM_ADI":"Adet","ISK1":"0","ISK2":"0","ISK3":"0","ISK4":"0","ISTISNA_KODU":"'.$istisna_kod.'","ISTISNA_ACIKLAMA":"'.$istisna_metin.'","INDIRIM_TUTARI":"0","DAHIL_BIRIM_FIYAT":"'.$birim.'","DAHIL_TUTAR":"'.$tutar.'"}],"INFO_BELGE_BASLIK":{"ENTEGRATOR":"EDM_BILISIM","ETTN":"'.$ettn.'","BELGE_NO":"BLG2022000000919","FATURA_NO":"","CR_DATE":"'.$tarih.'","BELGE_TARIHI":"'.$tarih.'","BELGE_SAATI":"'.$saat.'","DOVIZ_BIRIMI":"TRY","DOVIZ_KURU":"1","ALICI_VKN":"'.$alici_tc.'","ALICI_UNVAN":"'.$alici_ad.'" "'.$alici_soyad.'","ALICI_POSTA":"'.$alici_mail.'","NOT_1":"","NOT_2":"","NOT_3":"","NOT_4":"","NOT_5":"","NOT_6":"","NOT_7":"","NOT_8":"","NOT_9":"","NOT_10":"","NOT_DETAYLI":"","GENEL_TOPLAM":"'.$toplam.'"},INFO_MUSTERI":{"VKN_TCKN":"'.$alici_tc.'","ADI":"'.$alici_ad.'","SOYADI":"'.$alici_soyad.'","UNVAN":"'.$alici_ad.'" "'.$alici_soyad.'","ULKE":"'.$alici_ulke.'","IL":"'.$alici_sehir.'","ILCE":"'.$alici_ilce.'","MAIL":"'.$alici_mail.'","GSM":"'.$alici_tel.'","TELEFON":"'.$alici_tel.'"},"INFO_GONDERICI":{"VKN_TCKN":"6491093608","ADI":"OYUNEKS BİLİŞİM VE OYUN HİZMETLERİ LİMİTED ŞİRKETİ","SOYADI":"OYUNEKS BİLİŞİM VE OYUN HİZMETLERİ LİMİTED ŞİRKETİ","UNVAN":"OYUNEKS BİLİŞİM VE OYUN HİZMETLERİ LİMİTED ŞİRKETİ","ULKE":"TÜRKİYE","IL":"ANTALYA","ILCE":"MERKEZ","MAIL":"","GSM":"5422593737","TELEFON":""},"INFO_CONNECTOR":{"ENTEGRATOR":"NEO_PORTAL","FIRMA_VKN":"6491093608","FIRMA_UNVAN":"OYUNEKS BİLİŞİM VE OYUN HİZMETLERİ LİMİTED ŞİRKETİ","FIRMA_ADRES":"VARLIK MH. 179 SK. NO:12 MURATPAŞA/ANTALYA","FIRMA_GSM":"5422593737","AUTO_SEND":"0"}}';

//    $veri[]=array('adet'=>2, 'stok_ad'=>'kalem','istisna_kod'=>350,'istisna_not'=>'Jeton','kdv'=>18,'birim'=>200,'satir_top'=>200);
//    $veri[]=array('adet'=>3, 'stok_ad'=>'kalem','istisna_kod'=>350,'istisna_not'=>'Jeton','kdv'=>18,'birim'=>250,'satir_top'=>400);
//    $veri[]=array('adet'=>4, 'stok_ad'=>'kalem','istisna_kod'=>350,'istisna_not'=>'Jeton','kdv'=>18,'birim'=>350,'satir_top'=>600);



function fatura_kes($user,$iid,$ettn,$veri,$genel_toplam){
                /*  veri array(adet-stok_ad-kdv-istisna_kod-istisna_not-birim-satir_top)
                 *
                 *   olması gerekenler
            $sira		   =
            $adet          =
            $stok_ad       =
            $iid           =
            $birim         =
            $kdv           =
            $satir_top     =
            $istisna_not   =
            $istisna_kod   =
            $genel_toplam  =
            $ettn          =
            $alici_tc      =*
            $alici_ad      =*
            $alici_mail    =*
            $alici_ulke    =*
            $alici_il      =*
            $alici_ilce    =*
            $alici_tel     =*

                 *
                 *
                 * */


    #---------------------------------------------------Fatura sahibi bilgileri
    $k=DB::table('users')->where('id',$user)->first();
    $i=DB::table('fatura_adresleri')->where('user',$user)->first();

    if($i->user>0){ // fatura adresi varsa

            $alici_ad=$i->ad_soyad;
            $alici_soy=".";
            $alici_il=DB::table('iller')->where('id',$i->il)->first()->il_adi;
            $alici_ilce=DB::table('ilceler')->where('id',$i->ilce)->first()->ilce_adi;
            $alici_ulke=$i->ulke;
            $alici_tel=$i->telefon;
            $alici_mail=$k->email;
            $alici_tc=$i->tc_no;
            $alici_adr='Belirtilmedi';
        } else {

        $alici_ad=$k->name;
        $alici_soy=".";
        $alici_il="Antalya";
        $alici_ilce="Kepez";
        $alici_ulke="Türkiye";
        $alici_tel=$k->telefon;
        $alici_mail=$k->email;
        $alici_tc=$k->tcno;
        $alici_adr='Belirtilmedi';

    }
    $alici_soy=$alici_soy==''?'.':$alici_soy;
    $alici_il=$alici_il==''?'Antalya':$alici_il;
    $alici_ilce=$alici_ilce==''?'Kepez':$alici_ilce;
    $alici_ulke=$alici_ulke==''?'Türkiye':$alici_ulke;
    $alici_adr=$alici_adr==''?'Belirtilmedi':$alici_adr;
#-----------------------------------------------------------------------------------
    $x1='{"INFO_BELGE_HAREKET":[';
    foreach ($veri as $ic){$sira++;
    $x2.='{"SIRA_NO":"'.$sira.'","MIKTAR":"'.$ic['adet'].'","BARKOD":"40329253","STOK_ADI":"'.$ic['stok_ad'].'","STOK_KODU":"STK-001","SATIR_ACIKLAMA":"","KDV_ORANI":"'.$ic['kdv'].'","BIRIM_ADI":"Adet","ISK1":"0","ISK2":"0","ISK3":"0","ISK4":"0","ISTISNA_KODU":"'.$ic['istisna_kod'].'","ISTISNA_ACIKLAMA":"'.$ic['istisna_not'].'","INDIRIM_TUTARI":"0","DAHIL_BIRIM_FIYAT":"'.$ic['birim'].'","DAHIL_TUTAR":"'.$ic['satir_top'].'"},';
}
    $x2=substr($x2, 0, -1); // son virgülü at
    $x3='],"INFO_BELGE_BASLIK":{"ENTEGRATOR":"EDM_BILISIM","ETTN":"'.$ettn.'","BELGE_NO":"'.$iid.'","FATURA_NO":"","CR_DATE":"'.date('d-m-Y').'","BELGE_TARIHI":"'.date('d-m-Y').'","BELGE_SAATI":"'.date('H:i:s').'","DOVIZ_BIRIMI":"TRY","DOVIZ_KURU":"1","ALICI_VKN":"'.$alici_tc.'","ALICI_UNVAN":"'.$alici_ad.'","ALICI_POSTA":"'.$alici_mail.'","NOT_1":"","NOT_2":"","NOT_3":"","NOT_4":"","NOT_5":"","NOT_6":"","NOT_7":"","NOT_8":"","NOT_9":"","NOT_10":"","NOT_DETAYLI":"","GENEL_TOPLAM":"'.$genel_toplam.'"},"INFO_MUSTERI":{"VKN_TCKN":"'.$alici_tc.'","ADI":"'.$alici_ad.'","SOYADI":"","UNVAN":"'.$alici_ad.'","ULKE":"'.$alici_ulke.'","IL":"'.$alici_il.'","ILCE":"'.$alici_ilce.'","MAIL":"'.$alici_mail.'","GSM":"'.$alici_tel.'","TELEFON":"","ADRES":"'.$alici_adr.'"},"INFO_GONDERICI":{"VKN_TCKN":"6491093608","ADI":"OYUNEKS","SOYADI":" BİLİŞİM","UNVAN":"OYUNEKS BİLİŞİM VE OYUN HİZMETLERİ LTD ŞTİ","ULKE":"TÜRKİYE","IL":"İSTANBUL","ILCE":"SANCAKTEPE","MAIL":"fatura@oyuneks.com","GSM":"850 308 00 07","TELEFON":"850 308 00 07"},"INFO_CONNECTOR":{"ENTEGRATOR":"EDM_BILISIM","FIRMA_VKN":"6491093608","FIRMA_UNVAN":"OYUNEKS BİLİŞİM VE OYUN HİZMETLERİ LTD ŞTİ","FIRMA_ADRES":"MECLİS MAH. ŞENER SK. NO: 18 KAPI NO: 5","FIRMA_GSM":"5363017922","AUTO_SEND":0}}';

$top=$x1.$x2.$x3;

return $top;
}


    function cut($string,$cutpoint,$length)
    {
        $temp = explode($cutpoint,$string);
        if(count($temp)<=1) {return $string.'.00';}
        $int = $temp[0];
        $sub = $temp[1];
        return number_format($int,0,'','').'.'.substr($sub,0,$length);
    }
    ?>

<div id="myModal" class="modal fade"  style="position: relative">
<div class="modal-dialog" style="position: fixed" >
<div class="modal-content" style="background-color: #49568f">
<div class="modal-header" style="background-color: #229dff; padding: 10px; cursor: move"><i class="fa fa-arrows-alt" aria-hidden="true"> Bilgi Penceresi</i></div>
<div class="modal-body" id="rapor"></div>
</div></div></div>

@endsection
@section('js')
    <script src="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/sweet-alert2/sweetalert2.min.js')}}"></script>
    <script src="//cdn.datatables.net/plug-ins/1.11.3/api/sum().js"></script>
    <script type="text/javascript">

        $('.fdet').click(function(x){
                        let id=$(this).attr('id').split('_')[1];
            $.get('?', {fad:id} , function (x) {
                if(x.length<5) { swal.fire({html:'Kayıtlı adres yok',showConfirmButton: false,timer:500});  return false;}
                var x=JSON.parse(x);
                swal.fire({html:"<ul>" +
                        "<li>Adres Adı: " +x.adres_ismi+"</li>"+
                        "<li>Ad : " +x.ad_soyad+"</li>"+
                        "<li>Adres: " +x.adres+"</li>"+
                        "<li>TC: " +x.tc_no+"</li>"+
                        "<li>Tel: " +x.telefon+"</li>"+
                        "<li>V.D. " +x.vergi_dairesi+"</li>"+
                        "<li>V.No: " +x.vergi_no+"</li>"+
                        "</ul>"+
                        "Yukarıdaki Fatura bilgilerini kullanıcı boş bırakmış ise gönderimde Ünvan hatası alırsınız.\n",
                    showCancelButton:true,
                    confirmButtonText: 'Fat.Bilgisini Sil',
                    cancelButtonText: 'Kapat',
                    reverseButtons: true,
                    allowOutsideClick: false
            }).then((result) => {
                    if (result.value) {
                        $.get('?', {fduz: id}, function () {
                        });
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        swal.fire({icon: 'error', title: 'İşlemden Vazgeçildi', showConfirmButton: false, timer: 1500});
                    }
                })
            })
        })




        //-------------------------------------------------------------------------------
        if (!($('.modal.in').length)) {$('.modal-dialog').css({top: 0,left: 320});}
        $('.modal-dialog').draggable({proxy: ".modal-header" });
        $('#myModal').modal({backdrop: false, show: false});
        $(".modal-header").mouseenter(function(){$("body").css("overflow", "hidden");}).mouseleave(function(){$("body").css("overflow", "visible");});
        //-------------------------------------------------------------------------------


    var sayi=0,say=0,g=0,id=[],ok=0,red=0,gon,j=0,resp=0;

// #----------PDF down
    $('.pdf').click(function(x){ ln=$(this); ln.html('<i class="fa fa-spinner fa-spin" aria-hidden="true"></i>');
        let ettn=$(this).attr('id');
        $.post('/ykp.php', {pdf_al:ettn }, function (x) {
            let pdf_ = window.open("","PDF")
            pdf_.document.write("<iframe width='100%' style='border: none' height='100%' src='data:application/pdf;base64, " + x + "'></iframe>");
            ln.html('');
        });

    })


    //#-------------------------------------------------------------------------------------------
    $('.mkes').click(function (){
        let id = $(this).attr('id'); //.split('_')[1];
        if(confirm('Bu kayıt manuel kesildi olarak işaretlenip kayıtlara girilecek ve bu listede görünmeyecek..')){
        $.post('/ykp.php',{manuel:316, kisi:id, d1:'{{$date1}}',tip:1, d2:'{{$date2}}', ettn:'M_'+ettn(), fkt:$('#fkt').val()},function (){
            location.reload();
        });
        }
    });
    //#-------------------------------------------Manuel kes iptal------------------------------------------------
    $('.mip').click(function (){
        let ettn = $(this).attr('id'); //.split('_')[1];
        //if($(this).attr('id').split('_')[0].slice(0,1)=='M') {ettn='M_'+ettn;}

        if(confirm('Fatura bizim sistemde kesilmedi olarak kayıtlara işlenecek. Entegratör firma tarafında manuel olarak faturayı iptal etmeniz gerekiyor. API desteği yok.')){
            $.post('/ykp.php',{manuel_ipt:316, ettn:ettn,tip:1,kisi:'sil'},function (){
                location.reload();
            });
        }
    });

    //#-------------------------------------------------------------------------------------------

    $('#fkt_').change(function (){
        location.href="?fkes=316&fkt="+$(this).val();
    })

//#-------------------------------------------------------------------------------------------
function basla(){pencere();
    $('input:checkbox:checked[name=kes]').map(function() {id.push(parseInt(this.value))}); // secili id leri toparla
    const interval_id = window.setInterval(function(){}, Number.MAX_SAFE_INTEGER); // tüm int. iptal
    for (var i = 1; i <= interval_id; i++) {window.clearInterval(i);} // bildirim cubuğunu kapat
    gon = setInterval(gonder, 1000); // 1 saniye arayla post
    swal.fire({icon:'info',title:'Gönderim başlıyor..',showConfirmButton: false,timer:1000}); // uyarı
} // basla

    //#-------------------------------------------------------------------------------------------

function gonder(){
    if(g>=id.length) { swal.fire({icon:'success',title:ok+'/'+red+'/'+g+' Gönderim işlemi bitti, yanıtlar için biraz bekleyin..',toast: true, background: 'darkred', allowOutsideClick: false,  position: 'center'});clearInterval(gon);
        setTimeout(function (){if(g==red+ok ) {swal.fire({icon:'success',title:'Yanıtların hepsi geldi.',toast: true, background: 'darkgreen', allowOutsideClick: false,  position: 'center'});}},2000);
        return false;}
    else {
        $('#son_' + id[g]).html('İşleme alındı, Sonuç bekleniyor..');
        $.post('/ykp.php', {kisi: id[g], top: sayi, d1: '{{$date1}}', d2: '{{$date2}}', ettn: ettn(), fkt: $('#fkt').val(), tip: 1}, function (x) {
            var impor = x.split('^')[0];var sonuc = x.split('^')[1];var user = x.split('^')[2];
            if (impor == '1')
                {ok++;$('#son_' + user).html('Teslim Edildi..');$('#son_' + user).parent('tr').addClass('badge-success');
                    if($('#gizle').is(':checked')) {$('#son_' + user).parent('tr').hide('slow');}
                    $('#rapor').html('Seçilen = '+sayi+'<br>Giden = '+g+'<br>Başarılı = '+ok+'<br>Başarısız = '+red);
                    if(g==red+ok+1 && g==sayi) {swal.fire({icon:'success',title:'Yanıtların hepsi geldi.',toast: true, background: 'darkgreen', allowOutsideClick: false,  position: 'center'});}
                }
            else
                {red++;$('#son_'+ user).html(atob(sonuc));$('#son_' + user).addClass('badge-danger');
                    if(atob(sonuc).indexOf('Token')>-1) {const interval_id = window.setInterval(function(){}, Number.MAX_SAFE_INTEGER); // tüm int. iptal
                        for (var i = 1; i <= interval_id; i++) {window.clearInterval(i);} swal.fire({icon:'error',title:'Token Hatası!\r\nDeğiştirin..\r\n Gönderim kuyruğu durduruldu..'}); return false;
                    }
                    $('#rapor').html('Seçilen = '+sayi+'<br>Giden = '+g+'<br>Başarılı = '+ok+'<br>Başarısız = '+red);
                    if(g==red+ok+1 && g==sayi) {swal.fire({icon:'success',title:'Yanıtların hepsi geldi.',toast: true, background: 'darkgreen', allowOutsideClick: false,  position: 'center'});}
                }

        });
    }
    g++; /// mükerrer gönderimi önlemek için burada artım yapılıyor..
    console.log(g, ok, red, red+ok+1);
}
    //#-------------------------------------------------------------------------------------------
        $('.fat').click(function(){
            var but=$(this);
             sayi = $('.kes:checkbox:checked').length;
            if(sayi<=0) {swal.fire({icon:'error',html:'Hiçbir kayıt seçilmedi..!',showConfirmButton: false,timer:1500}); return;}

                swal.fire({
                    title: "Fatura Kesim Tarihini Kontrol Edin "+$('#fkt').val(),
                    text: "Seçilen ("+sayi+") kayda fatura kesilecek",
                    //icon: "info",
                    showCancelButton: true,
                    confirmButtonText: 'Devam Et',
                    cancelButtonText: 'İPTAL',
                    reverseButtons: true,
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.value) {
                        but.attr('disabled','disabled');
                        but.attr('title','Listeyi yenilemeniz gerekiyor');
                        basla();
                    } else if (
                        result.dismiss === Swal.DismissReason.cancel
                    ) {
                        swal.fire(
                            '{{__("admin.iptal-edildi")}}',
                            '',
                            'error'
                        )
                    }
                });
        });

    $('.tikf').click(function(){
        prelo(1);
        var id = $(this).attr('id'); //.split('*')[1],
        var ana='', no=0,ad='',tops=0,c='';
        $.post('/ykp.php',{epin:316,kesilen:316,user:id},function(y) {
            if (y != 'null') {
                var z = JSON.parse(y);
                for (var i = 0; i < z.ykp.length; i++) {c = z.ykp[i];no++;
                    ana += '<tr><td>' + no + '</td><td align="left">' + c.urun + '</td>' +
                        '<td>' + c.adet + '</td>' +
                        '<td style="text-align: right">' + c.birim + '</td>' +
                        '<td>' + c.kdv + '</td>' +
                        '<td style="text-align: right">' + c.stop + '</td>' +
                        ' </tr>';
                }
                prelo(2);
                veri(ana,c.usx,c.gtop);
            }

        });
    })

    //#-------------------------------------------------------------------------------------------
        $('.tik').click(function(){

            prelo(1);

            let id = $(this).attr('id'); //.split('_')[1],
            var  ana='', no=0,ad='',tops=0;
            //alert($(this).attr('id'));

let kart=0, kar=0;
            $.post('/ykp.php',{epin:316,date1:'<?=$date1?>',date2:'<?=$date2?>',user:id},function(y) {
                if (y != 'null') {
                    var z = JSON.parse(y);
                    for (var i = 0; i < z.ykp.length; i++) {
                        no++;
                        var c = z.ykp[i];
                        let adet = c.tadet;
                        let toplam = c.total;
                        let birim = toplam / adet;
                        let alis = c.alis;
                        let ek = '';

                        if (c.kdv < 1) {
                            birim=alis;
                            toplam = alis * adet;
                            kar = c.total - (toplam);
                            kart+=kar;
                            //let kdvsiz = kar / 1.18;
                            c.total=alis*adet;
                        }

                        if (birim.toString().indexOf('.') > 0) {birim = birim.toString().substring(0, birim.toString().indexOf('.') + 3)}
                        if (toplam.toString().indexOf('.') > 0) {toplam = toplam.toString().substring(0, toplam.toString().indexOf('.') + 3)} else {toplam = toplam + '.00';}

                        ana += '<tr><td>' + no + '</td><td align="left">' + c.title + '</td>' +
                            '<td>' + c.adet + '</td>' +
                            '<td style="text-align: right">' + birim + '</td>' +
                            '<td>' + c.kdv + '</td>' +
                            '<td style="text-align: right">' + toplam + '</td>' +
                            ' </tr>';

                        ad = c.name;
                        tops +=parseFloat(c.total);
                        //console.log(tops,'--',c.total,"--",kart);
                    }
                    if(kart>0) { // komisyon satırı eklenecek mi ?
                        let kdvsiz = kart / 1.18;
                        if (kdvsiz.toString().indexOf('.') > 0) {
                            kdvsiz = kdvsiz.toString().substring(0, kdvsiz.toString().indexOf('.') + 3)
                        }
                        if (kart.toString().indexOf('.') > 0) {
                            kart = kart.toString().substring(0, kart.toString().indexOf('.') + 3)
                        }

                        ek = '<tr><td>' + (no + 1) + '</td><td style="text-align:left"> E-pin Komisyon Bedeli</td><td>1</td>' +
                            '<td style="text-align: right">' + kdvsiz + '</td><td>18</td>' +
                            '<td style="text-align: right">' + kart + '</td>' +
                            '</tr>';
                        ana += ek;
                        tops += parseFloat(kart);
                    }
                }

                prelo(2);
                veri(ana,ad,tops);
                //$('td :checkbox').prop('checked', 'true');
               // $('th :checkbox').prop('checked', 'true');

            }) // post
        }) // click
    //#-------------------------------------------------------------------------------------------
    function ettn(){
        var dt = new Date().getTime();
        var uuid = 'xxxxxxxx-xxxx-4xxx-axxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
            var r = (dt + Math.random()*16)%16 | 0;
            dt = Math.floor(dt/16);
            return (c=='x' ? r :(r&0x3|0x8)).toString(16);
        });
        return uuid;
    }
    //#-------------------------------------------------------------------------------------------
        $(document).ready(function () {

            $('#datatable thead tr').clone(true).appendTo('#datatable thead');
            $('#datatable thead tr:eq(1) th').each(function (i) {
                var title = $(this).text();
                if(title!=='F.Kes') {
                    $(this).html('<input type="text" placeholder="' + title + '" />');
                } else {$(this).html('<input type="checkbox" checked class="select_all">');}
                $('input[type=text]', this).on('keyup change', function () {
                    if (table.column(i).search() !== this.value) {
                        table.column(i).search(this.value).draw();
                    }
                });
            });
            $('#datatable input[type="text"]').css(
                {'width': '100%', 'display': 'inline-block'}
            );
            var table = $('#datatable').DataTable({
                "footerCallback": function (row, data, start, end, display) {
                    var api = this.api();
                    nb_cols = api.columns().nodes().length;
                    var j = 4;
                    var e = 3;
                    var intVal = function ( i ) {return typeof i === 'string' ?i.replace(/[\Adet,]/g, '')*1 :typeof i === 'number' ?i : 0;};
                    var intVal2 = function ( i ) {return typeof i === 'string' ?i.replace(/[\TL,]/g, '')*1 :typeof i === 'number' ?i : 0;};
                    while (j < 5) {
                        total = api.column(j).data().reduce( function (a, b) {return intVal(a) + intVal(b);}, 0 );
                        pageTotal = api.column( j, { page: 'current'} ).data().reduce( function (a, b) {return intVal(a) + intVal(b);}, 0 );
                        sumCol4Filtered = display.map(el => data[el][j]).reduce((a, b) => intVal(a) + intVal(b), 0 );
                        $( api.column(j).footer() ).html(sumCol4Filtered.toFixed(2) );
                        j++;
                    }
                    while (e < 4) {
                        total = api.column(e).data().reduce( function (a, b) {return intVal2(a) + intVal2(b);}, 0 );
                        pageTotal = api.column( e, { page: 'current'} ).data().reduce( function (a, b) {return intVal2(a) + intVal2(b);}, 0 );
                        sumCol4Filtered = display.map(el => data[el][e]).reduce((a, b) => intVal2(a) + intVal2(b), 0 );
                        $( api.column(e).footer() ).html(sumCol4Filtered.toLocaleString());
                        e++;
                    }
                },
                columnDefs: [{orderable: false, targets: [5]}],
                pageLength: 200,
                orderCellsTop: true,
                fixedHeader: true,
                lengthChange: false,
                //ajax: '{{route('getGameGold')}}',
                buttons: [
                    {
                        extend: 'excelHtml5',
                        title: '{{getPageTitle(getPage(), getLang())}} - Excel',
                        text: '<i class="far fa-file-excel"></i>',
                        className: 'btn btn-outline-success',
                        init: function (api, node, config) {
                            $(node).removeClass('btn-secondary')
                        },
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        }
                    },
                    {
                        extend: 'csvHtml5',
                        title: '{{getPageTitle(getPage(), getLang())}} - Csv',
                        text: '<i class="fas fa-file-csv"></i>',
                        className: 'btn btn-outline-info',
                        init: function (api, node, config) {
                            $(node).removeClass('btn-secondary')
                        },
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        title: '{{getPageTitle(getPage(), getLang())}} - Pdf',
                        className: 'btn btn-outline-danger',
                        init: function (api, node, config) {
                            $(node).removeClass('btn-secondary')
                        },
                        text: '<i class="far fa-file-pdf"></i>',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        }
                    },

                ],
                "order": [[1, "desc"]],
                "language": {
                    "decimal": "",
                    "emptyTable": "{{__('admin.hic-veri-yok')}}",
                    "info": "{{__('admin.adet-veri-gosterim', ['TOTAL' => '_TOTAL_', 'START' => '_START_', 'END' => '_END_'])}}",
                    "infoEmpty": "{{__('admin.sifir-veri-var')}}",
                    "infoFiltered": "{{__('admin.adet-veri-araniyor', ['MAX' => '_MAX_'])}}",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "{{__('admin.veri-gosteriliyor', ['MENU' => '_MENU_'])}}",
                    "loadingRecords": "{{__('admin.yukleniyor')}}",
                    "processing": "{{__('admin.isleniyor')}}",
                    "search": "{{__('admin.ara')}}",
                    "zeroRecords": "{{__('admin.eslesen-veri-bulunamadi')}}",
                    "paginate": {
                        "first": "{{__('admin.ilk')}}",
                        "last": "{{__('admin.son')}}",
                        "next": "{{__('admin.sonraki')}}",
                        "previous": "{{__('admin.onceki')}}"
                    },
                }
            });
            //setTimeout(function () {
            table.buttons().container().appendTo('#datatable_wrapper .col-md-6:eq(1)');
            //}, 3000);
            /*setInterval( function () {
                table.ajax.reload();
            }, 3000 );*/
            $('#datatable_filter').css(
                {'display': 'none'}
            );



        });
    //#-------------------------------------------------------------------------------------------
        function prelo(x){
            if(x==1) {
                Swal.fire({
                    html: '<h6>Kullancı işlem geçmişi alınıyor.. <i class="fa fa-spinner fa-spin"></i></h6>',
                    showCloseButton: false,
                    focusConfirm: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    width: '600px',
                    showConfirmButton: false
                });
            } else { Swal.close();}
        }
    //#-------------------------------------------------------------------------------------------
        function veri(x,ad,ttoplam){
            if(ttoplam.toString().indexOf('.')>0) {ttoplam = ttoplam.toString().substring(0,ttoplam.toString().indexOf('.')+3)} else {ttoplam=ttoplam;}

            let t1='Fatura Detay ('+ad+') <br><table class="font-15 table table-sm table-hover">'+
                '<th>No</th><th>Ürün Adı</th><th>Adet</th><th>Birim</th><th>KDV</th><th>Toplam</th>';
            let t2='<tr><td></td><td style="text-align: right">TOPLAM</td><td></td><td></td><td></td><td style="text-align: right">'+ttoplam+'</td><td></td></tr>';
            let t3='</table>';

            Swal.fire({
                html: t1 + x + t2 + t3,
                showCloseButton: true,
                focusConfirm: false,
                //allowOutsideClick: false,
                //allowEscapeKey: false,
                width: '1200px',
                showConfirmButton: false
            });
        }
    //#-------------------------------------------------------------------------------------------
        $(document).on('click','.select_all',function() {
            var c = this.checked;
            $('td :checkbox').prop('checked', c);

            let ttr=0;
            var wq=$('input:checkbox:checked[name=kes]');
            wq.map(function() {  // id leri toparla
                ttr=ttr + parseFloat($('#t_'+parseInt(this.value)).text()); // seçilen tutarlar
            });
            pencere();
            $('#rapor').html('Seçilen = '+wq.length+' / '+ttr.toFixed(2)+' TL<br>Giden = '+g+'<br>Başarılı = '+ok+'<br>Başarısız = '+red)
        });
    //#-------------------------------------------------------------------------------------------
    $('.kes').click(function(){let ttr=0;
            pencere();
        var wq=$('input:checkbox:checked[name=kes]');
        wq.map(function() {  // id leri toparla
            ttr=ttr + parseFloat($('#t_'+parseInt(this.value)).text()); // seçilen tutarlar
        });
        $('#rapor').html('Seçilen = '+wq.length+' / '+ttr.toFixed(2)+' TL<br>Giden = '+g+'<br>Başarılı = '+ok+'<br>Başarısız = '+red)
    });

    //#-------------------------------------------------------------------------------------------
    $('.fk').click(function (){
        location.href="https://test.oyuneks.com/panel/epinf?date1="+$('#date1').val()+"&date2="+$('#date2').val()+"&fk=316";
    })

    $('#date2').change(function(){ $('#fkt').val($('#date2').val())})

        function pencere() {
            $('#myModal').modal({backdrop: false, show: true});
            $("body").css("overflow", "visible");
        }


        $('#gt').text('<?=number_format($liste_toplam,2)?>');
        $('#k18t').text('<?=number_format($k18,2)?>');
        $('#k0t').text('<?=number_format($k0,2)?>');
        $('#k18adt').text('<?=$k18adt?>'+ ' Adet');
        $('#k0adt').text('<?=$k0adt?>'+ ' Adet');


        const tarayici = () => {
            let browserInfo = navigator.userAgent;
            let browser;
            if (browserInfo.includes('Opera') || browserInfo.includes('Opr')) {
                browser = 'Opera';
            } else if (browserInfo.includes('Edg')) {
                browser = 'Edge';
            } else if (browserInfo.includes('Chrome')) {
                browser = 'Chrome';
            } else if (browserInfo.includes('Safari')) {
                browser = 'Safari';
            } else if (browserInfo.includes('Firefox')) {
                browser = 'Firefox'
            } else {
                browser = 'unknown'
            }
            return browser;
        }

        console.log(tarayici());
        if(tarayici()!='Chrome') {swal.fire({icon:'error',title:'Fatura modülü bu ('+tarayici()+') tarayıcıda test edilmedi !!',html:'Güncel CHROME tarayıcı kullanarak fatura kesme işlemini yapınız. Diğer tarayıcılarda kararsızlık sorunları ve problemler yaşanabilir.!', showConfirmButton: true,allowOutsideClick: false        });}

    </script>
@endsection
