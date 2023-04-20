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
    </style>
@endsection
@section('body')

    @if(session('success'))
        <div class="row" data-lang="{{getLang()}}">
            <div class="col-12">
                <div class="alert icon-custom-alert alert-outline-success alert-success-shadow" role="alert">
                    <i class="mdi mdi-check-all alert-icon"></i>
                    <div class="alert-text">
                        <strong>{{__('admin.basarili')}}</strong> {{__('admin.basariliMetin')}}
                    </div>
                </div>
            </div>
        </div>
    @endif
    @if(session('error'))
        <div class="row" data-lang="{{getLang()}}">
            <div class="col-12">
                <div class="alert icon-custom-alert alert-outline-danger alert-danger-shadow" role="alert">
                    <i class="mdi mdi-bell alert-icon"></i>
                    <div class="alert-text">
                        <strong>Hata</strong> {{session('error')}}
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="row" data-lang="{{getLang()}}">
        <div class="col-12">

            <div class="card">
                <div class="card-body">
                    <h4 class="mt-0 header-title mb-3">
                        <div class="row">
                            <div class="col-md-6 col-sm-6 " >
                                GGOLD / E-fatura İşlemleri
                            </div>
                            <div class="col-md-6 col-sm-6 text-right" >
                                <a href="epinf?token=316" class="btn btn-small tkn">Token</a>
                                <a href="epinf" class="btn btn-small">Epin</a>
                                <a href="goldf" class="btn btn-small">GameGold</a>
                                <a href="pazarf" class="btn btn-small">Pazar Yeri</a>
                            </div>
                        </div>
                    </h4>
                    <div class="table-responsive">

                        <?
#----------------------------------------------------------------Kesilen faturalar gösterim

                        if(isset($_GET['fkes']) && $_GET['fkes']==316) {
                            if(isset($_GET['fkt'])) {$fkt=$_GET['fkt'];} else {$fkt=date('Y-m-d');$_GET['fkt']=$fkt;}
                            ?>
                        <h3>Kesilen Faturalar</h3>
                        Fatura Kesim Tarihi :
                        <select name="fkt_" id="fkt_">
                            @foreach(DB::select("select date_format(fkt,'%Y-%m-%d') fkt, COUNT(*) adet from e_ettn et,e_fatura ef where ef.ettn=et.ettn and ef.islem=2 GROUP by fkt order by et.fkt desc") as $u)
                                <option value="{{$u->fkt}}" @if($_GET['fkt']==$u->fkt) selected @endif>{{$u->fkt}}</option>
                            @endforeach
                        </select>

                            <?
                            if($_GET['fkt']=='') {$ek=" isnull(et.fkt)"; } else {$ek=" date(et.fkt)='$fkt'";}  // kesim tarihi olmayanlar için
                            $sorgu=DB::select("
                            SELECT ef.*, et.fkt,u.tcno, u.id uid, u.name,u.email FROM e_fatura ef left join e_ettn et on et.ettn=ef.ettn left join users u on u.id=ef.user
                            where $ek
                            and ef.islem=2
                            group by ef.ettn
                            order by ef.id desc
");
                            echo " Fatura adedi : ". count($sorgu). " | Toplam : <span id='gt'></span>";  // ilgili tarihte kaç fat. var
                            $liste_toplam=0;
?>



                        <table lang="{{getLang()}}" id="datatable" class="font-12 table-sm table-bordered table-hover nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead><tr style="text-align: center"><th>Id</th><th>İsim</th><th>TC</th><th>Adet</th><th>Tutar</th><th>Kesim</th><th>F.Kes</th></tr></thead>
                            <tbody>

                            @foreach($sorgu as $al)
                                <tr>
                                    <td class="fdet" id="uid_{{$al->uid}}">{{$al->id}} <i class="fa fa-search"></i></td>
                                    <td class="tikf" id="{{$al->fid}}">{{$al->name}}</td>
                                    <td style="text-align: center">{{$al->tcno}}</td>
                                    <td style="text-align: center">{{$al->adet}} </td>
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
                        <? } else {   #--------------Kesilen gösterim sonu


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
                                </form>
                                <div class="col-sm-4 d-flex  align-items-center">
                                    <button class="btn btn-outline-warning fat mr-2 mt-2">Fatura Kes</button>
                                    <button class="btn btn-info mt-2 " onclick="pencere()" data-backdrop="false" >Pencere</button>
                                </div>

                                <div class='align-items-center mt-2 ml-3'>
                                    <label><input type="checkbox" id="gizle" value="1" checked> Başarılı gönderimleri listeden çıkar</label>
                                </div>
                        </div>



                        <div style="text-align: -webkit-center">
<i>{{$date1}} - {{$date2}} Tarihleri arası Canlı Stok Durumu</i>
                                <table  class="table-bordered table-hover nowrap small" style="border-collapse: collapse; border-spacing: 0; width: 50%" >
                                    <thead><tr style="text-align: center"><th>Paket</th><th>G.Gold</th><th>Alınan</th><th>Satılan</th><th>Stok</th><th>Alış</th><th>SAAFT</th><th>Satış</th><th>Kar</th><th>O.Ort.</th><th>M.Ort.</th></tr></thead>

                            <?php

                            if (isset($_GET['status'])) {$status = $_GET['status'];} else{ $status='0';}
                        if(isset($_GET['fk']) && $_GET['fk']==316) { $ek=" and ggs.fatura is not null";} else {$ek=' and isnull(ggs.fatura) ';}

                        #------------------------GG satışları

                        # bu sorgu verilen tarih aralığındaki tüm GG alım satım kalan satış kar bilgilerini verir
//$date1="2020-01-01";
//$date2="2022-06-27";
                            $sor=DB::select("
                SELECT
                ggs.paket AS paket,gpt.title AS title,SUM(ggs.adet) AS ALINAN,SATIS.Tadet AS SATILAN,SUM(ggs.adet) - SATIS.Tadet AS STOK,SUM(ggs.price) AS ALISF,SATIS.tprice AS SATISF,SATIS.tprice - SUM(ggs.price) AS KAR
                FROM  game_gold_satis ggs
                LEFT JOIN games_packages_trade gpt ON gpt.id = ggs.paket
                JOIN
                    (SELECT ggs.paket AS paket,ggs.tur AS tur,SUM(ggs.adet) AS Tadet,SUM(ggs.price) AS tprice,gpt.title AS title
                    FROM
                    game_gold_satis ggs
                    LEFT JOIN games_packages_trade gpt ON gpt.id = ggs.paket
                    WHERE ggs.tur = 'bizden-al'  AND  ggs.status = 1  AND ggs.deleted_at IS NULL #and ggs.user <> 24239
                    and date(ggs.created_at) BETWEEN '$date1' and '$date2'
                    GROUP BY ggs.paket
                    ORDER BY ggs.paket)  SATIS ON  SATIS.paket = ggs.paket
                WHERE ggs.tur = 'bize-sat'  AND  ggs.status = 1  AND  ggs.deleted_at IS NULL #and ggs.user <> 24239
                and date(ggs.created_at) BETWEEN '$date1' and '$date2'
                GROUP BY ggs.paket ORDER BY ggs.paket

    ");

                            foreach ($sor as $gg){

                                $gold_id        = $gg->paket;
                                $gold_alinan    = $gg->ALINAN;
                                $gold_satilan   = $gg->SATILAN;
                                $fark           = $gold_alinan-$gold_satilan;
                                // $satis_ortalama = $gg->SATISF/$gg->SATILAN;

                                if($fark<0) {$fat_kes_adet=$gold_alinan;}                   // fazlası satılmış ise
                                else {$fat_kes_adet=$gold_satilan;}                     // stokta kalan varsa

                                $t=satanlar($date1,$date2,$gold_id,$ek);                          // verilen tarihler arası bize satanları al gel, paket paket
//echo "<pre>";
//print_r($t);
//echo "</pre>";
                                foreach ($t as $x){                                     // gelen verileri kişi bazında grupla kalemleri ayır faturaya hazırla
                                    if(!isset($user[$x->user])){                        // bu kişi ilk defamı geldi
                                        $user[$x->user]=array();                        // evet ise dizie al
                                        $user[$x->user]['name']=$x->name;               // adını al
                                    }
                                    if(!isset($user[$x->user][$x->paket])){                 // bu kişinin paketi daha önce varmı
                                        $user[$x->user][$x->paket] =array();            // yoksa paket dizi
                                        $user[$x->user][$x->paket]['oyun']=$x->title;   // paket adı
                                    }
                                    $user[$x->user][$x->paket]['fatura'] =$x->fatura;
                                    $user[$x->user][$x->paket]['adet']  +=$x->tadet;
                                    $user[$x->user][$x->paket]['tutar'] +=$x->total;
                                    $user[$x->user][$x->paket]['tarih']  =$x->created_at;
                                    $user[$x->user][$x->paket]['paket']  =$x->paket;
                                    $user[$x->user][$x->paket]['iid']    =$x->iid;
                                    //$user[$x->user][$x->paket]['sort']  =$satis_ortalama;

                                    $tot+=$x->tadet;
                                    /*
                                                                            if($tot>$fat_kes_adet) {  // kesilecek olan adedi geçersek son eklediğini çıkar ve break
                                                                                //echo $fat_kes_adet,"-->", $tot, "<br>";
                                                                                $tot=0;
                                                                                $user[$x->user][$x->paket]['adet'] -=$x->tadet;
                                                                                $user[$x->user][$x->paket]['tutar']-=$x->total;
                                                                            break;

                                                                            }
                                    */
                                } $tot=0;

                                echo  "<tr>
                                            <td>$gold_id</td><td>$gg->title</td><td style='text-align: right'>$gold_alinan</td><td style='text-align: right'>$gold_satilan</td><td style='text-align: right'>$gg->STOK</td>
                                            <td style='text-align: right'>".number_format($gg->ALISF,2,'.',',')."</td>
                                            <td style='text-align: right'>". number_format($gg->ALISF/$gold_alinan*$gold_satilan,2) ."</td>
                                            <td style='text-align: right'>".number_format($gg->SATISF,2,'.',',')."</td>
                                            <td style='text-align: right'>".number_format($gg->SATISF - $gg->ALISF/$gold_alinan*$gold_satilan  ,2,'.',',')."</td>
                                            <td style='text-align: right'>".number_format(($gg->SATISF - $gg->ALISF/$gold_alinan*$gold_satilan)/$gold_satilan,2) ."</td>
                                            <td style='text-align: center'><input style='width: 80px; text-align: right' type='text' id='gg_$gold_id' class='ort' value='".number_format(($gg->SATISF - $gg->ALISF/$gold_alinan*$gold_satilan)/$gold_satilan,2)."'></td>
                                    </tr>";

                                #-------- oranlar DB ye alınıyor
                                $oran=number_format(($gg->SATISF - $gg->ALISF/$gold_alinan*$gold_satilan)/$gold_satilan,2);
                                $paket=DB::select("select * from gg_paket where paket='$gold_id'")[0]->paket;
                                if($paket==$gold_id){DB::select("update gg_paket set oran='$oran' where paket='$gold_id'");}
                                else
                                {DB::select("insert into gg_paket values('$gold_id','$oran') ");}
                                #-----------------------

                                $topal+=$gg->ALISF;
                                $topsat+=$gg->SATISF;
                                $topsaft+=$gg->ALISF/$gold_alinan*$gold_satilan;
                                $kar += $gg->SATISF - $gg->ALISF/$gold_alinan*$gold_satilan;

                            }
                            echo "<tr class='font-weight-bold'><td></td><td>TOPLAM</td><td></td><td></td><td></td><td style='text-align: right'>".number_format($topal,2,'.',',')."</td><td style='text-align: right'>".number_format($topsaft,2)."</td><td style='text-align: right'>".number_format($topsat,2,'.',',')."</td><td style='text-align: right'>".number_format($kar,2,'.',',')."</td><td></td></tr></table></div>";

#-------------------------------------------------------------------------Özet tablo bitimi
//echo "<pre>";
//print_r($user);
//echo "</pre>";
//$tadet=0;
                            ?>

{{--                                    <div class="header-title" style=" background-color: #f0f8ffff; width: fit-content; padding: 20px; position: fixed; z-index: 10; border: groove; margin: 0 0 0 -230px; " id="rapor" class="text-danger">Durum Penceresi</div>--}}




                            <table  class="font-12 table-sm table-bordered table-hover nowrap" id="datatable"  style="border-collapse: collapse; border-spacing: 0; width: 100%;" >
                                <thead><tr><th>Id</th><th>İsim</th><th>İşlem</th><th>Tutar</th><th>Matrah</th><th>Sonuç</th><th>F.Kes</th></tr></thead>
                                <tbody>

{{--                                {{dd($user)}}--}}
                            @foreach($user as $u => $r)
                                <? foreach(ictoplam($u,$date1,$date2) as $rr =>$e ) {$z += $e->oran * $e->adet; } if($z<1) {continue;} ?>
                                <tr>
                                    <td class="fdet" id="u_{{$u}}">{{$u}} <i class="fa fa-search"></i></td>
                                    <td class="tik" id="s_{{$u}}">{{$r['name']}} </td>
                                    <td style="text-align: center">{{count($r)-1}}</td>
                                    <td class="tik" id="s_{{$u}}" style="text-align: right">{{number_format(toplam($u,$date1,$date2)[0]->top,2) }}</td>
                                    <td style="text-align: right" id="k_{{$u}}" class="mth"> <? echo $z; ?> </td>
                                    <td id="son_{{$u}}"></td>
                                    <td style="text-align: center">
                                        <input type="checkbox" value="{{$u}}" name="kes" @if($z<1) disabled @else checked class="kes" @endif  >
                                        <? $z=0; ?>
                                       @if($_GET['fk']!=316)
                                        <li class="btn btn-sm fas fa-check mkes" id="{{$u}}" title="Kesildi olarak işaretle"></li>
                                        @else
                                            <li class="btn btn-sm fas fa-times mip" id="{{$u}}" gid="{{next($r)['fatura']}}"  title="Kesilmedi olarak işaretle"></li>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                                </tbody>
                            <tfoot><tr><th>Id</th><th>İsim</th><th>İşlem</th><th>Tutar</th><th style="text-align: right" id="mtrh">Matrah</th><th>Sonuç</th><th>F.Kes</th></tr></tfoot>
                        </table>
                        <? } #-------------------------------------------------------------------------------------------------------------------------------------------------------------------------?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?
    //$r='{"INFO_BELGE_HAREKET":[{"SIRA_NO":"0","MIKTAR":"1","BARKOD":"40329253","STOK_KODU":"STK-000","KDV_ORANI":"18","BIRIM_ADI":"Adet","ISK1":"0","ISK2":"0","ISK3":"0","ISK4":"0","ISTISNA_KODU":"","ISTISNA_ACIKLAMA":"","INDIRIM_TUTARI":"0","DAHIL_BIRIM_FIYAT":"100","DAHIL_TUTAR":"100"},{"SIRA_NO":"1","MIKTAR":"1","BARKOD":"40329253","STOK_KODU":"STK-001","KDV_ORANI":"18","BIRIM_ADI":"Adet","ISK1":"0","ISK2":"0","ISK3":"0","ISK4":"0","ISTISNA_KODU":"","ISTISNA_ACIKLAMA":"","INDIRIM_TUTARI":"0","DAHIL_BIRIM_FIYAT":"100","DAHIL_TUTAR":"100"}],"INFO_BELGE_BASLIK":{"ENTEGRATOR":"EDM_BILISIM","ETTN":"2d3a3513-680c-469b-9958-378c97e132d5","BELGE_NO":"Localinizdeki belge numarası","FATURA_NO":"Bu Alan Entegratör tarafından bizim aracılığımız ile set edileck boş gönderin !!!","CR_DATE":"26.04.2022","BELGE_TARIHI":"26.04.2022","BELGE_SAATI":"13:04:58","DOVIZ_BIRIMI":"TRY","DOVIZ_KURU":"1","ALICI_VKN":"53032318162","ALICI_UNVAN":"MUHAMMED AHMET BİLİCİ","ALICI_POSTA":"ahmetbilici50@gmail.com","NOT_1":"","NOT_2":"","NOT_3":"","NOT_4":"","NOT_5":"","NOT_6":"","NOT_7":"","NOT_8":"","NOT_9":"","NOT_10":"","NOT_DETAYLI":"","GENEL_TOPLAM":"100"},"INFO_MUSTERI":{"VKN_TCKN":"11111111111","ADI":"Ahmet","SOYADI":"BİLİCİ","UNVAN":"AHMET BİLİCİ","ULKE":"Türkiye","IL":"KONYA","ILCE":"MERKEZ","MAIL":"ahmetbilici50@gmail.com","GSM":"5363017922","TELEFON":"03322547788"},"INFO_GONDERICI":{"VKN_TCKN":"6311534002","ADI":"NEO","SOYADI":"YAZILIM","UNVAN":"NEO YAZILIM LTD ŞTİ","ULKE":"Türkiye","IL":"KONYA","ILCE":"MERKEZ","MAIL":"ahmetbilici50@gmail.com","GSM":"5363017922","TELEFON":"03322547788"},"INFO_CONNECTOR":{"ENTEGRATOR":"EDM_BILISIM","FIRMA_VKN":"11111111111 entegrasyonu yapan firmanın TCKN/VKN Alanı","FIRMA_UNVAN":"Developer Test Firması","FIRMA_ADRES":"denemesi mahallesi,deneme sokak no:3/1 KONYA / MERKEZ","FIRMA_GSM":"5363017922","AUTO_SEND":"Direk gönderim için 1,taslak için 0"}}';

    //echo "<pre>";
    //print_r(json_decode($r,true));
    //echo "</pre>";

    function cut($string,$cutpoint,$length)
    {
        $temp = explode($cutpoint,$string);
        if(count($temp)<=1) {return $string.'.00';}
        $int = $temp[0];
        $sub = $temp[1];
        return number_format($int,0,'','').'.'.substr($sub,0,$length);
    }




#-----GG Ön tablo için toplamlar

function toplam($uid,$date1,$date2)
{         $sor=DB::select("
                SELECT ggs.id iid, u.name, u.id user, sum(ggs.price) top
                FROM game_gold_satis ggs
                inner join users u on u.id=ggs.user
                WHERE isnull(ggs.deleted_at)
                and date(ggs.created_at) BETWEEN '$date1' and '$date2'
                and ggs.tur='bize-sat'
                and ggs.status=1
                and (select GGS.paket from game_gold_satis GGS where GGS.tur='bizden-al' and paket=ggs.paket limit 1) is not null
                and ggs.user not in (SELECT user FROM `odemeler` WHERE ulke is not null and ulke!='' group by user)
                and user = '$uid'");
 return $sor;
}




#----- GG için
    function satanlar($date1,$date2,$paket,$ek){  // func. ismine takılmayın sürekli değişiyor
    $sor= DB::select("
                        SELECT ggs.id iid, sum(ggs.price) total, u.name, u.id user,count(*) adet, sum(adet) tadet, gpt.title,ggs.paket,ggs.created_at,ggs.fatura
                        FROM game_gold_satis  ggs
                        left join users u on u.id=ggs.user
                        left join games_packages_trade gpt on gpt.id=ggs.paket
                        WHERE isnull(ggs.deleted_at)
                        and paket='$paket'
                        and date(ggs.created_at) BETWEEN '$date1' and '$date2'
                        and ggs.tur='bize-sat'
                        ".$ek."
                        and ggs.status=1
                        and ggs.user not in (SELECT user FROM `odemeler` WHERE ulke is not null and ulke!='' group by user)
                        group by u.id
                        order by ggs.created_at
                         ");
        return $sor;
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
        var sayi=0,say=0,g=0,id=[],ok=0,red=0,gon,j=0,resp=0;orans={};

        // #----------PDF down
        $('.pdf').click(function(x){ ln=$(this); ln.html('<i class="fa fa-spinner fa-spin" aria-hidden="true"></i>');
            let ettn=$(this).attr('id');
            $.post('/ykp.php', {pdf_al:ettn }, function (x) {
                let pdf_ = window.open("","PDF")
                pdf_.document.write("<iframe width='100%' style='border: none' height='100%' src='data:application/pdf;base64, " + x + "'></iframe>");
                ln.html('');
            });

        })


        function getCookie(cookieName) {let cookie = {};document.cookie.split(';').forEach(function(el) {let [key,value] = el.split('='); cookie[key.trim()] = value;}); return cookie[cookieName];}

        $('.ort').each(function(){orans[$(this).attr('id')] = $(this).val(); document.cookie= $(this).attr('id')+'='+ $(this).val() + ' ; expires=Thu, 09 Jun 2029 12:00:00 UTC; path=/' });
        //$.post('/ykp.php',{oranlar_db:orans});

        $('.ort').blur(function(){let id=$(this).attr('id');document.cookie= id+'='+ $(this).val() + ' ; expires=Thu, 09 Jun 2029 12:00:00 UTC; path=/';});

        for(let i=0;i<200;i++){let eski = getCookie('gg_'+i);if(eski!='') { $('#gg_'+i).val(eski); $('#o_'+i).text(eski);}}


        //#-------------------------------------------------------------------------------------------
        function basla(){ pencere();
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
                $.post('/ykp.php', {kisi: id[g], top: sayi, d1:'{{$date1}}', d2:'{{$date2}}',ettn:ettn(),tip:2, oranlar: orans, fkt:$('#fkt').val() }, function(x){
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
        $('.mkes').click(function (){
            let id = $(this).attr('id'); //.split('_')[1];
            if(confirm('Bu kayıt manuel kesildi olarak işaretlenip kayıtlara girilecek ve bu listede görünmeyecek..')){
                $.post('/ykp.php',{manuel:316, kisi:id, d1:'{{$date1}}',tip:2, d2:'{{$date2}}', ettn:'M_'+ettn(), oranlar: orans, fkt:$('#fkt').val()},function (x){console.log(x);
                    location.reload();
                });
            }
        });
        //#-------------------------------------------Manuel kes iptal------------------------------------------------
        $('.mip').click(function (){
            let ettn = $(this).attr('id'); //.split('_')[1];
            //if($(this).attr('id').split('_')[0].slice(0,1)=='M') {ettn='M_'+ettn;}

            if(confirm('Fatura bizim sistemde kesilmedi olarak kayıtlara işlenecek. Entegratör firma tarafında manuel olarak faturayı iptal etmeniz gerekiyor. API desteği yok.')){
                $.post('/ykp.php',{manuel_ipt:316, ettn:ettn,tip:2,kisi:'sil'},function (){
                    location.reload();
                });
            }
        });

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

        //#-------------------------------------------------------------------------------------------

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


        $('.tik').click(function(){

                    prelo(1);

                    let id = $(this).attr('id').split('_')[1], ana='', no=0,ad='',tops=0;
                    //alert($(this).attr('id'));


                    $.post('/ykp.php',{islem:'bize-sat',date1:'<?=$date1?>',date2:'<?=$date2?>',user:id},function(y) {
                if (y != 'null') {
                    var z = JSON.parse(y);
                    for (var i = 0; i < z.ykp.length; i++) {
                        no++;
                        var c = z.ykp[i];
                        let adet = c.adet;
                        let birim = '';
                        let toplam = 0;
                        let alis = c.alis;
                        let ek = '';


                        birim = getCookie('gg_'+c.paket);
                        console.log(c.paket);
                        if(birim>0) {
                            toplam += birim * adet;
                            ana += '<tr><td>' + no + '</td><td align="left">' + c.title + '</td>' +
                                '<td>' + adet + '</td>' +
                                '<td style="text-align: right">' + birim + '</td>' +
                                '<td>18</td>' +
                                '<td style="text-align: right">' + parseFloat(birim * adet).toFixed(2) + '</td>' +
                                ' </tr>';
                        }

                        ana += ek;
                        ek = '';
                        ad = c.name;
                        tops += parseFloat(toplam);
                    }
                }

                prelo(2);
                veri(ana,ad,tops);
               // $('td :checkbox').prop('checked', 'true');
               // $('th :checkbox').prop('checked', 'true');

            }) // post
        }) // click



        function prelo(x){
            if(x==1) {
                Swal.fire({
                    html: '<h6>Kullanıcı işlem geçmişi alınıyor.. <i class="fa fa-spinner fa-spin"></i></h6>',
                    showCloseButton: false,
                    focusConfirm: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    width: '600px',
                    showConfirmButton: false
                });
            } else { Swal.close();}
        }


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

        $(document).on('click','.select_all',function() {
            var c = this.checked;
            $('td :checkbox:not(:disabled)').prop('checked', c);

            let ttr=0;
            var wq=$('input:checkbox:checked[name=kes]');
            wq.map(function() {  // id leri toparla
                ttr=ttr + parseFloat($('#k_'+parseInt(this.value)).text()); // seçilen tutarlar
            });
            $('#rapor').html('Seçilen = '+wq.length+' / '+ttr.toFixed(2)+' TL<br>Giden = '+g+'<br>Başarılı = '+ok+'<br>Başarısız = '+red)
            pencere();
        });


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
                        $( api.column(e).footer() ).html(sumCol4Filtered.toFixed(2));
                        e++;
                    }
                },
                columnDefs: [{orderable: false, targets: [4]}],
                pageLength: 200,
                orderCellsTop: true,
                fixedHeader: true,
                lengthChange: false,
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
                //"order": [[1, "desc"]],
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

        $('.kes').click(function(){let ttr=0;
            pencere();
            var wq=$('input:checkbox:checked[name=kes]');
            wq.map(function() {  // id leri toparla
                ttr=ttr + parseFloat($('#k_'+parseInt(this.value)).text()); // seçilen tutarlar
            });
            $('#rapor').html('Seçilen = '+wq.length+' / '+ttr.toFixed(2)+' TL<br>Giden = '+g+'<br>Başarılı = '+ok+'<br>Başarısız = '+red)
        });

        //#-------------------------------------------------------------------------------------------

        $('#date2').change(function(){$('#fkt').val($('#date2').val())})

        $('#fkt_').change(function (){
            location.href="?fkes=316&fkt="+$(this).val();
        })

function pencere() {
    $('#myModal').modal({backdrop: false, show: true});
    $("body").css("overflow", "visible");
}
        $('#gt').text('<?=number_format($liste_toplam,2)?>');

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
        if(tarayici()!='Chrome') {swal.fire({icon:'error',title:'Fatura modülü bu ('+tarayici()+') tarayıcıda test edilmedi !!',html:'Güncel CHROME tarayıcı kullanarak fatura kesme işlemini yapınız. Diğer tarayıcılarda kararsızlık sorunları ve problemler yaşanabilir.!', showConfirmButton: true,allowOutsideClick: false});}

    </script>
@endsection
