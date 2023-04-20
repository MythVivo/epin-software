<?
//use Illuminate\Support\Facades\Mail;
use Illuminate\swiftmailer\swiftmailer;

#----------------- Kullanıcı hareket dökümü
if(isset($_GET['dokum']) && $_GET['dokum']==316 && isset($_GET['uid'])) {$p = (object) $_GET; $al=DB::table('users')->where('id',$p->uid)->first();?>
<style>
    table, th, td {border: 1px solid black;
    border-collapse: collapse;
    }
    .sag {text-align: right}
</style>
<div style="text-align: center; font-family: monospace; width: fit-content; margin: 0 auto">
<div style="text-align: left">
    <image src="https://oyuneks.com/brand/brandicon.png" style="height: 50px" ></image>
    <image src="https://oyuneks.com/brand/brandtext_white.png" style="height: 25px" ></image>
</div>
<table style="margin-top: 50px">
    <tr><td colspan="6" style="background: #C6EFCE; color: #006100; text-align: center; font-weight: bold">KULLANICI BİLGİLERİ</td></tr>
    <tr><td colspan="6" style="height: 20px" ></td></tr>
    <tr><td colspan="6" style="color: #9C0006; background: #ffc7ce">
              Adı.. : {{$al->name}} | Tel: {{$al->telefon}} | Kayıt: {{$al->created_at}} | Email: {{$al->email}} | Bakiye: {{$al->bakiye}} |Ç.Bakiye: {{$al->bakiye_cekilebilir}} | Bağ.Bakiye: {{$al->bagis_bakiye}}
        </td></tr>
    <tr><td colspan="6" style="height: 20px" ></td></tr>


    <tr><td colspan="6" style="background: #C6EFCE; color: #006100; text-align: center; font-weight: bold">KULLANICI HAREKETLERİ</td></tr>
    <tr><td colspan="6" style="height: 20px" ></td></tr>
    <tr style="background: #FFCC99; "><th>NO</th><th>ID</th><th>AÇIKLAMA</th><th>IP</th><th>PORT</th><th>TARİH</th></tr>
    <?
    $es= DB::select("SELECT * from logs where user='$p->uid' order by created_at desc");

    if(count($es)>0) {
        $no = 0;
        foreach ($es as $e) {
            $no++;
            $port = explode(':', $e->text);
            if (count($port) > 1 && is_int((int)$port[0])) {
                $ip = explode('- ', $port[1]);
                if (count($ip) > 1 && substr_count($ip[0],'.')==3) {
                    echo "<tr><td>$no</td> <td>$e->id</td> <td>$ip[1]</td> <td>$ip[0]</td> <td>$port[0]</td> <td>$e->created_at</td> </tr>";
                }
            }
        }
    }

    ?>
    <tr><td colspan="6" style="height: 20px" ></td></tr>


    <tr><td colspan="6" style="background: #C6EFCE; color: #006100; text-align: center; font-weight: bold">KULLANICI ÖDEMELERİ</td></tr>
    <tr><td colspan="6" style="height: 20px" ></td></tr>
    <tr style="background: #FFCC99; "><th>NO</th><th>ID</th><th>AÇIKLAMA</th><th>KANAL</th><th>TUTAR</th><th>Tarih</th></tr>
<?
    $es= DB::select("SELECT od.* , pc.name, u.name admin FROM odemeler od left join payment_channels pc on pc.id=od.channel left join users u on u.id=od.islemYapan WHERE od.user = '$p->uid' order by od.created_at desc");

    if(count($es)>0) {
    $no=0;foreach ($es as $e){$no++;echo "<tr><td>$no</td><td>$e->id</td><td>$e->description</td><td>$e->name</td><td class='sag'>$e->amount TL</td><td>$e->created_at</td></tr>";}
    }
    ?>
    <tr><td colspan="6" style="height: 20px" ></td></tr>


    <tr><td colspan="6" style="background: #C6EFCE; color: #006100; text-align: center; font-weight: bold">KULLANICI SATIN ALMA İŞLEMLERİ</td></tr>
    <tr><td colspan="6" style="height: 20px" ></td></tr>
    <tr style="background: #FFCC99; "><th>NO</th><th>ID</th><th>ALINAN KOD</th><th>ÜRÜN</th><th>TUTAR</th><th>TARİH</th></tr>
<?
        $es=DB::select("SELECT es.* , gp.title, esc.code FROM epin_satis es left join games_packages gp on gp.id=es.paketId left join epin_satis_kodlar esc on esc.epin_satis=es.id where user ='$p->uid' order by es.created_at desc");

        $no=0;
        if(count($es)>0) {
            foreach ($es as $e) {$no++;$f = $e->price / $e->adet;echo "<tr><td>$no</td><td>$e->id</td><td>" . \epin::DEC($e->code) . "</td><td>$e->title</td><td class='sag'>$f TL</td><td>$e->created_at</td></tr>";}
        }
    ?>
    <tr><td colspan="6" style="height: 20px" ></td></tr>


</table>

    <p style="text-align: center">Belirtilen email adresinin WWW.OYUNEKS.COM  web sitesi üzerindeki tüm hareketleri yukarıda listelenmiştir.	</p>
    <p>Dökümantasyon Tarihi : {{date('H:i:s  d-m-Y')}}</p>
</div>

<script>
    alert("PDF almak için aşağıdaki adımları takip edin\n\nCHROME tarayıcı ile sayfayı açın\nCTRL+P\nYazıcı PDF,\nKağıt boyu A3,\nDüzen YATAY,\nSeçeneklerden ARKA PLAN GRAFİKLERİNİ YAZDIR\n(IP adresi bulunmayan eski LOG lar listelenmemiştir.)");
</script>

    <?
    die("end.");
}

#--------------------------------------sms ve email resend api
if(isset($mid)) {
    $al=DB::select("select * from sms_log where id='$mid'");
    if(count($al)<1) {die("Hata oluştu");}

    if($al[0]->telefon!=''){sendSms($al[0]->telefon, gzuncompress($al[0]->text));}
    else
    {
    $to_name = "Oyuneks";
    $to_email = $al[0]->email;



    # eskiler yedek
        $backup = Mail::getSwiftMailer();


    #yeni smtp
        $transport = new Swift_SmtpTransport('smtp-relay.sendinblue.com', 587, '');
        $transport->setUsername('it@oyuneks.com');
        $transport->setPassword('PUGr2R5KFS1pqNJc');
    # build & deploy
        $blue = new Swift_Mailer($transport);
        Mail::setSwiftMailer($blue);

//    setEnv('MAIL_HOST', 'smtp-relay.sendinblue.com');
//    setEnv('MAIL_PORT', '587');
//    setEnv('MAIL_USERNAME', 'it@oyuneks.com');
//    setEnv('MAIL_PASSWORD', 'PUGr2R5KFS1pqNJc');

    Mail::send( [], [],
    function ($message) use ($to_name, $to_email, $al) {
    $message->to($to_email, $to_name)->subject($al[0]->konu . ' - ' . getSiteName());
    //$message->from(getSiteSenderMail(), getSiteName());
    $message->from('postmaster@oyuneks.com', 'Oyuneks');
    $message->setBody(gzuncompress($al[0]->text),'text/html');
    });

#restore org.

        Mail::setSwiftMailer($backup);



    }
die("Mesaj tekrar gönderildi");
}
#-------------------------------------------------------------
?>

@extends('back.layouts.app')
@section('css')
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/dropify/css/dropify.min.css')}}" rel="stylesheet">
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/sweet-alert2/sweetalert2.min.css')}}" rel="stylesheet" type="text/css">
    <style>
        table.dataTable.nowrap td:nth-child(2) {white-space: unset !important;}
        .genis{width: 900px !important;}
    </style>
    </style>
@endsection

@section('body')

    <div class="row" data-lang="{{getLang()}}">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <?php
                    if (isset($_GET['uid'])) {  # varsa user işlem sayılarını bul
                        $uid = $_GET['uid'];
                        $u=(object) DB::select("SELECT * FROM users WHERE id = '$uid'")[0];
                        $tw=DB::select("SELECT count(*) sayi FROM twitch_support_donates       WHERE user = '$uid'       ");

                        $ps=DB::select("SELECT count(*) sayi FROM pazar_yeri_ilanlar 	      WHERE user = '$uid'       ");
                        $pa=DB::select("SELECT count(*) sayi FROM pazar_yeri_ilanlar_buy      WHERE user = '$uid' ");
                        $pm=DB::select("SELECT count(*) sayi FROM pazar_yeri_ilan_satis 	  WHERE satin_alan = '$uid'");

                        $pc=DB::select("SELECT count(*) sayi FROM para_cek 				      WHERE user = '$uid'       ");
                        $od=DB::select("SELECT count(*) sayi FROM odemeler 				      WHERE user = '$uid'       ");
                        $mg=DB::select("SELECT count(*) sayi FROM muve_games_satis 		      WHERE user = '$uid'       ");
                        $gg=DB::select("SELECT count(*) sayi FROM game_gold_satis 		      WHERE user = '$uid'       ");
                        $ef=DB::select("SELECT count(*) sayi FROM e_fatura 				      WHERE user = '$uid' and length(ettn)>35 group by ettn");
                        $es=DB::select("SELECT count(*) sayi FROM epin_satis 			      WHERE user = '$uid'       ");
                        $ep=DB::select("SELECT count(*) sayi FROM epin_siparisler 		      WHERE user = '$uid'       ");
                        $lg=  DB::select("SELECT count(*) sayi FROM logs 				      WHERE user = '$uid'       ");
                        $smss=DB::select("SELECT count(*) sayi FROM sms_log		              WHERE user_id = '$uid'    ");

                    }
                    else {$uid = "";}
                    ?>

                    <div class="col" style="text-align: left; float: right ">
                        <form method="get">
                            <div class="form-group">
                                <input <? echo $uid!=''?"value='$uid'":""?>    placeholder="Kullanıcı ID" type="number" name="uid" class="col-md-2 form-check-inline form-control text-center">
                                <button class="btn btn-success form-control-sm" type="submit">Getir</button>
                                <a class="btn btn-warning form-control-sm dokum" >Döküm Al</a>
                                @if($uid>1)
                                                                        <span style="float: right;border: 1px;border-color: green;border-style: double;padding: 10px 10px 0 10px;">
                                                                            <a title="Üye yönetime git" href="https://oyuneks.com/panel/uye-detay/{{$u->email}}" target="_blank">
<pre><b>Ad:</b> {{$u->name}} | <b>Tel:</b> {{$u->telefon}} | <b>Kayıt:</b> {{$u->created_at}} | <b>Silinme:</b> {{$u->deleted_at}}
<b>Email:</b> {{$u->email}} | <b>Bakiye:</b> {{ number_format($u->bakiye,2)}} | <b>Ç.Bakiye:</b> {{ number_format($u->bakiye_cekilebilir)}} | <b>Bağış Bak:</b> {{$u->bagis_bakiye}}</pre>
                                                                            </a>
                    </span>
                                @endif
                            </div>
                        </form>

                        <?
                        if($uid>1){
                        $tw=(object) $tw[0];$ps=(object) $ps[0];$pa=(object) $pa[0];$pc=(object) $pc[0];$od=(object) $od[0];$pm=(object) $pm[0];
                        $mg=(object) $mg[0];$gg=(object) $gg[0];$es=(object) $es[0];$ep=(object) $ep[0];$lg=(object) $lg[0];$smss=(object) $smss[0];

                        ?>
                        <br>
                        <a href="?uid={{$uid}}&log"  class="btn text-justify @if($lg->sayi>0)   btn-gradient-purple @else btn-gradient-info @endif">[{{$lg->sayi}}] Loglar</a>
                        <a href="?uid={{$uid}}&ode"  class="btn text-justify @if($od->sayi>0)   btn-gradient-purple @else btn-gradient-info @endif">[{{$od->sayi}}] Ödemeler</a>
                        <a href="?uid={{$uid}}&cek"  class="btn text-justify @if($pc->sayi>0)   btn-gradient-purple @else btn-gradient-info @endif">[{{$pc->sayi}}] Para Çekim</a>
                        <a href="?uid={{$uid}}&twc"  class="btn text-justify @if($tw->sayi>0)   btn-gradient-purple @else btn-gradient-info @endif">[{{$tw->sayi}}] Twitch</a>
                        <a href="?uid={{$uid}}&pys"  class="btn text-justify @if($ps->sayi>0)   btn-gradient-purple @else btn-gradient-info @endif">[{{$ps->sayi}}] Satış It.</a>
                        <a href="?uid={{$uid}}&pya"  class="btn text-justify @if($pa->sayi>0)   btn-gradient-purple @else btn-gradient-info @endif">[{{$pa->sayi}}] Alım Talep</a>
                        <a href="?uid={{$uid}}&pym"  class="btn text-justify @if($pm->sayi>0)   btn-gradient-purple @else btn-gradient-info @endif">[{{$pm->sayi}}] Aldığı It.</a>
                        <a href="?uid={{$uid}}&muv"  class="btn text-justify @if($mg->sayi>0)   btn-gradient-purple @else btn-gradient-info @endif">[{{$mg->sayi}}] Muve Alış</a>
                        <a href="?uid={{$uid}}&ess"  class="btn text-justify @if($es->sayi>0)   btn-gradient-purple @else btn-gradient-info @endif">[{{$es->sayi}}] Epin Alış</a>
                        <a href="?uid={{$uid}}&esp"  class="btn text-justify @if($ep->sayi>0)   btn-gradient-purple @else btn-gradient-info @endif">[{{$ep->sayi}}] Epin Sipariş</a>
                        <a href="?uid={{$uid}}&ggs"  class="btn text-justify @if($gg->sayi>0)   btn-gradient-purple @else btn-gradient-info @endif">[{{$gg->sayi}}] Game Gold</a>
                        <a href="?uid={{$uid}}&fat"  class="btn text-justify @if(count($ef)>0)  btn-gradient-purple @else btn-gradient-info @endif">[{{count($ef)}}] E-Fatura</a>
                        <a href="?uid={{$uid}}&sms"  class="btn text-justify @if($smss->sayi>0) btn-gradient-purple @else btn-gradient-info @endif">[{{$smss->sayi}}] SMS-Email</a>


                        <? }?>
                        <br>
                        <hr>
                    </div>
                    <div class="table-responsive">
                        <table id="datatable" style="font-size: 11px; color: currentcolor; width: 100%" class="table table-bordered table-hover table-sm">
                            <?
#---------------------------------------------------------------------------------------------
                            if(isset($_GET['ode'])){
                                $es=(object) DB::select("SELECT od.* , pc.name, u.name admin FROM odemeler od left join payment_channels pc on pc.id=od.channel left join users u on u.id=od.islemYapan WHERE od.user = '$uid' order by od.created_at desc");
                                $tr="<thead><tr><th>No</th><th>Id</th><th>Tutar</th><th>Tür</th><th>Açıklama</th><th>Admin</th><th>Tarih</th><th>S.Tarih</th></tr></thead><tbody>";
                                $no=0;

                                foreach ($es as $e){$no++;
                                    $tr.="<tr><td>$no</td><td>$e->id</td><td>$e->amount</td><td>$e->name</td><td>$e->description</td><td>$e->admin</td><td>$e->created_at</td><td>$e->deleted_at</td></tr>";
                                }
                                $tr.="</tbody><tfoot><tr><th>No</th><th>Id</th><th>Tutar</th><th>Tür</th><th>Açıklama</th><th>Admin</th><th>Tarih</th><th>S.Tarih</th></tr></tfoot>";
                                echo $tr;
                            }

#---------------------------------------------------------------------------------------------
                            if(isset($_GET['sms'])){
                                $es=(object) DB::select("SELECT * FROM sms_log where user_id='$uid' order by created_at desc");
                                $tr="<thead><tr><th>No</th><th>Tip</th><th>Tel No</th><th>Email</th><th>Konu</th><th>İçerik</th><th>Olay</th><th>Tarih</th></tr></thead><tbody>";
                                $no=0;$tip='';$link='';

                                foreach ($es as $e){$no++;
                                    if($e->telefon==''){$mesaj="<a style='cursor: pointer' class='gor' id='$e->id'>Mesajı Gör</a>";$tip='Email-Ok';}
                                    else {$mesaj=gzuncompress($e->text);
                                        if($e->sonuc==200){$tip='SMS-Ok';$link='';}
                                        else {
                                            $tip="SMS-Hata";
                                            $link="<a target='new' href='https://www.a2psmsapi.com/en/status-codes-and-messages/'>SMS Hata<br>($e->sonuc Detay)</a>";
                                        }

                                    }
                                    $link=$link==''?$tip:$link;
                                    $tr.="<tr><td>$no</td><td style='text-align: center'>$link</td><td>$e->telefon</td><td>$e->email</td><td>$e->konu</td><td style='text-align: center'>$mesaj</td><td title='İletiyi tekrar gönderir' mid='$e->id' tip='$tip' class='gonder' style='cursor: pointer; text-align: center'>T.Gönder</td><td style='white-space: nowrap;'>$e->created_at</td></tr>";

                                }
                                $tr.="<tfoot><tr><th>No</th><th>Tip</th><th>Tel No</th><th>Email</th><th>Konu</th><th>İçerik</th><th>Olay</th><th>Tarih</th></tr></tfoot>";
                                echo $tr;
                            }
#---------------------------------------------------------------------------------------------
                            if(isset($_GET['cek'])){
                                $es=(object) DB::select("SELECT pc.*, od.title FROM para_cek pc left join odeme_kanallari od on od.id=pc.odeme_kanali where pc.user='$uid' order by pc.created_at desc");
                                $tr="<thead><tr><th>No</th><th>Id</th><th>Tutar</th><th>Kesinti</th><th>Banka</th><th>Açıklama</th><th>Tarih</th><th>S.Tarih</th></tr></thead><tbody>";
                                $no=0;

                                foreach ($es as $e){$no++;
                                    $tr.="<tr><td>$no</td><td>$e->id</td><td>$e->amount</td><td>$e->kesinti</td><td>$e->title</td><td>$e->text</td><td>$e->created_at</td><td>$e->deleted_at</td></tr>";
                                }
                                $tr.="</tbody><tfoot><tr><th>No</th><th>Id</th><th>Tutar</th><th>Kesinti</th><th>Banka</th><th>Açıklama</th><th>Tarih</th><th>S.Tarih</th></tr></tfoot>";
                                echo $tr;
                            }
#---------------------------------------------------------------------------------------------
                            if(isset($_GET['muv'])){
                                $es=(object) DB::select("SELECT mgs.* , mg.title, mg.alis FROM `muve_games_satis` mgs left JOIN muve_games mg on mg.muveId=mgs.muveId where user='$uid' order by mgs.created_at desc");
                                $tr="<thead><tr><th>No</th><th>Id</th><th>Oyun</th><th>Alış</th><th>Satış</th><th>Muve ID</th><th>Tarih</th><th>S.Tarih</th></tr></thead><tbody>";
                                $no=0;

                                foreach ($es as $e){$no++;
                                    $tr.="<tr><td>$no</td><td>$e->id</td><td>$e->title</td><td>$e->alis</td><td>$e->price</td><td>$e->muveId</td><td>$e->created_at</td><td>$e->deleted_at</td></tr>";
                                }
                                $tr.="</tbody><tfoot><tr><th>No</th><th>Id</th><th>Oyun</th><th>Alış</th><th>Satış</th><th>Muve ID</th><th>Tarih</th><th>S.Tarih</th></tr></tfoot>";
                                echo $tr;
                            }
#---------------------------------------------------------------------------------------------
                            if(isset($_GET['ggs'])){
                                $es=(object) DB::select("SELECT ggs.* ,gp.title FROM game_gold_satis ggs left join games_packages_trade gp on gp.id=ggs.paket where user='$uid' order by ggs.created_at desc");
                                $tr="<thead><tr><th>No</th><th>İşlem</th><th>Adet</th><th>Tutar</th><th>Paket</th><th>Tes.Nick</th><th>Durum</th><th>Talep T.</th><th>İşlem T.</th><th>Sil T.</th></tr></thead><tbody>";
                                $no=0;

                                foreach ($es as $e){$no++; $nck=substr($e->teslim_nick,0,15); if($e->status==1){$dr="Başarılı";} elseif($e->status==2){$dr="İptal";} else{$dr="Bekliyor";}
                                    $tr.="<tr><td>$no</td><td>$e->tur</td><td>$e->adet</td><td>$e->price</td><td>$e->title</td><td>$nck</td><td>$dr</td><td>$e->created_at</td><td>$e->updated_at</td><td>$e->deleted_at</td></tr>";
                                }
                                $tr.="</tbody><tfoot><tr><th>No</th><th>İşlem</th><th>Adet</th><th>Tutar</th><th>Paket</th><th>Tes.Nick</th><th>Durum</th><th>Talep T.</th><th>İşlem T.</th><th>Sil T.</th></tr></tfoot>";
                                echo $tr;
                            }
#---------------------------------------------------------------------------------------------
                            if(isset($_GET['ess'])){
                                $es=(object) DB::select("SELECT es.* , gp.title, esc.code FROM epin_satis es left join games_packages gp on gp.id=es.paketId left join epin_satis_kodlar esc on esc.epin_satis=es.id where user ='$uid' order by es.created_at desc");
                                $tr="<thead><tr><th>No</th><th>Id</th><th>EPIN</th><th>Ürün</th><th>Adet</th><th>Tutar</th><th>Alış</th><th>Kdv</th><th>İşlemT.</th></tr></thead><tbody>";
                                $no=0;

                                foreach ($es as $e){$no++;$f=$e->price/$e->adet;
                                    $tr.="<tr><td>$no</td><td>$e->id</td><td>".\epin::DEC($e->code)."</td><td>$e->title</td><td>1</td><td>$f TL</td><td>$e->alis</td><td>$e->kdv</td><td>$e->created_at</td></tr>";
                                }
                                $tr.="</tbody><tfoot><tr><th>No</th><th>Id</th><th>EPIN</th><th>Ürün</th><th>Adet</th><th>Tutar</th><th>Alış</th><th>Kdv</th><th>İşlemT.</th></tr></tfoot>";
                                echo $tr;
                            }
#---------------------------------------------------------------------------------------------
                            if(isset($_GET['esp'])){
                                $es=(object) DB::select("SELECT es.* , gt.title,t.title td FROM epin_siparisler es left join games_packages gt on gt.id=es.oyun left JOIN games_packages_codes_suppliers t on t.id=es.tedarikci  where es.user='$uid' order by es.created_at desc");
                                $tr="<thead><tr><th>No</th><th>Id</th><th>Kod</th><th>Ürün</th><th>Durum</th><th>Tutar</th><th>Açıklama</th><th>Tedarikçi</th><th>İşlemT.</th></tr></thead><tbody>";
                                $no=0;

                                foreach ($es as $e){$no++;
                                    $tr.="<tr><td>$no</td><td>$e->id</td><td>".\epin::DEC($e->kod)."</td><td>$e->title</td><td>$e->durum</td><td>$e->tutar</td><td>$e->notx</td><td>$e->td</td><td>$e->created_at</td></tr>";
                                }
                                $tr.="</tbody><tfoot><tr><th>No</th><th>Id</th><th>Kod</th><th>Ürün</th><th>Durum</th><th>Tutar</th><th>Açıklama</th><th>Tedarikçi</th><th>İşlemT.</th></tr></tfoot>";
                                echo $tr;
                            }
#---------------------------------------------------------------------------------------------
                            if(isset($_GET['twc'])){
                                $es=(object) DB::select("SELECT tw.*, s.title al FROM twitch_support_donates tw left join twitch_support_streamer s on s.id=tw.streamer where tw.user='$uid' order by tw.created_at desc");
                                $tr="<thead><tr><th>No</th><th>Id</th><th>Alıcı</th><th>Tutar</th><th>Başlık</th><th>Not</th><th>Donate Id</th><th>İşlem T.</th><th>Silme T.</th></tr></thead><tbody>";
                                $no=0;

                                foreach ($es as $e){$no++;
                                    $tr.="<tr><td>$no</td><td>$e->id</td><td>$e->al</td><td>$e->amount</td><td>$e->title</td><td>$e->text</td><td>$e->donate_id</td><td>$e->created_at</td><td>$e->deleted_at</td></tr>";
                                }
                                $tr.="</tbody><tfoot><tr><th>No</th><th>Id</th><th>Alıcı</th><th>Tutar</th><th>Başlık</th><th>Not</th><th>Donate Id</th><th>İşlem T.</th><th>Silme T.</th></tr></tfoot>";
                                echo $tr;
                            }
#---------------------------------------------------------------------------------------------
                            if(isset($_GET['pys'])){
                                $es=(object) DB::select("SELECT gt.title oyun,gt.text plt, py.* FROM pazar_yeri_ilanlar py left join games_titles gt on gt.id=py.pazar WHERE user ='$uid' order by py.created_at desc");
                                $tr="<thead><tr><th>No</th><th>Id</th><th>Oyun</th><th>Platform</th><th>Başlık</th><th>Server</th><th>Fiyat</th><th>Komisyon</th><th>Not</th><th>Durum</th><th>İşlem T.</th><th>Silme T.</th></tr></thead><tbody>";
                                $no=0;

                                foreach ($es as $e){$no++;
                                    $tr.="<tr><td>$no</td><td>$e->id</td><td>$e->oyun</td><td>".strip_tags($e->plt)."</td><td>$e->title</td><td>$e->sunucu</td><td>$e->price</td><td>".$e->price-$e->moment_komisyon."</td><td>$e->red_neden</td><td>".findIlanStatus($e->status)."</td><td>$e->created_at</td><td>$e->deleted_at</td></tr>";
                                }
                                $tr.="</tbody><tfoot><tr><th>No</th><th>Id</th><th>Oyun</th><th>Platform</th><th>Başlık</th><th>Server</th><th>Fiyat</th><th>Komisyon</th><th>Not</th><th>Durum</th><th>İşlem T.</th><th>Silme T.</th></tr></tfoot>";
                                echo $tr;
                            }
#---------------------------------------------------------------------------------------------
                            if(isset($_GET['pya'])){
                                $es=(object) DB::select("SELECT gt.title oyun, gt.text plt, pyi.* FROM pazar_yeri_ilanlar_buy pyi left join games_titles gt on gt.id=pyi.pazar where user='$uid' order by pyi.created_at desc");
                                $tr="<thead><tr><th>No</th><th>Id</th><th>Oyun</th><th>Platform</th><th>Başlık</th><th>Server</th><th>Fiyat</th><th>Komisyon</th><th>Not</th><th>Durum</th><th>İşlem T.</th><th>Silme T.</th></tr></thead><tbody>";
                                $no=0;

                                foreach ($es as $e){$no++;
                                    $tr.="<tr><td>$no</td><td>$e->id</td><td>$e->oyun</td><td>".strip_tags($e->plt)."</td><td>$e->title</td><td>$e->sunucu</td><td>$e->price</td><td>".$e->price-$e->moment_komisyon."</td><td>$e->red_neden</td><td>".findIlanStatus($e->status)."</td><td>$e->created_at</td><td>$e->deleted_at</td></tr>";
                                }
                                $tr.="</tbody><tfoot><tr><th>No</th><th>Id</th><th>Oyun</th><th>Platform</th><th>Başlık</th><th>Server</th><th>Fiyat</th><th>Komisyon</th><th>Not</th><th>Durum</th><th>İşlem T.</th><th>Silme T.</th></tr></tfoot>";                                echo $tr;
                            }
#---------------------------------------------------------------------------------------------
                            if(isset($_GET['pym'])){
                                $es=(object) DB::select("SELECT pyis.*, pyi.title, pyi.text, pyi.user, u.name, gt.text plt FROM pazar_yeri_ilan_satis pyis left JOIN pazar_yeri_ilanlar pyi on pyi.id=pyis.ilan left JOIN users u on u.id=pyi.user left JOIN games_titles gt on gt.id=pyi.pazar where satin_alan='$uid' order by pyis.created_at desc");
                                $tr="<thead><tr><th>No</th><th>Id</th><th>Satıcı</th><th>Item</th><th>Platform</th><th>Fiyat</th><th>Not</th><th>İşlem T.</th><th>Silme T.</th></tr></thead><tbody>";
                                $no=0;

                                foreach ($es as $e){$no++;
                                    $tr.="<tr><td>$no</td><td>$e->id</td><td>$e->name</td><td>$e->title</td><td>".strip_tags($e->plt)."</td><td>$e->price</td><td>$e->text</td><td>$e->created_at</td><td>$e->deleted_at</td></tr>";
                                }
                                $tr.="</tbody><tfoot><tr><th>No</th><th>Id</th><th>Satıcı</th><th>Item</th><th>Platform</th><th>Fiyat</th><th>Not</th><th>İşlem T.</th><th>Silme T.</th></tr></tfoot>";
                                echo $tr;
                            }
#---------------------------------------------------------------------------------------------
                            if(isset($_GET['log'])){
                                $es=(object) DB::select("SELECT * from logs where user='$uid' order by created_at desc");
                                $tr="<thead><tr><th>No</th><th>Id</th><th>Log</th><th>İşlem T.</th></tr></thead><tbody>";
                                $no=0;

                                foreach ($es as $e){$no++;
                                    $tr.="<tr><td>$no</td><td>$e->id</td><td>$e->text</td><td>$e->created_at</td></tr>";
                                }
                                $tr.="</tbody><tfoot><tr><th>No</th><th>Id</th><th>Log</th><th>İşlem T.</th></tr></tfoot>";
                                echo $tr;
                            }
#---------------------------------------------------------------------------------------------
                            if(isset($_GET['fat'])){
                                $es=(object) DB::select("SELECT *  FROM e_fatura where user='$uid' group by ettn order by id desc");
                                $tr="<thead><tr><th>No</th><th>Ettn</th><th>Tutar</th><th>Fatura T.</th><th>PDF</th></tr></thead><tbody>";
                                $no=0;

                                foreach ($es as $e){$no++;
                                    $tr.="<tr><td>$no</td><td>$e->ettn</td><td>$e->gtop</td><td>$e->tarih</td><td ";
                                    if(strlen($e->ettn)>35) {$tr.=" id='$e->ettn' class='pdf' style='cursor: pointer' ";}
                                    $tr.=">Faturayı Gör</td></tr>";

                                }
                                $tr.="</tbody><tfoot><tr><th>No</th><th>Ettn</th><th>Tutar</th><th>Fatura T.</th><th>PDF</th></tr></tfoot>";
                                echo $tr;
                            }


                            ?>

                        </table>
                    </div>
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->

@endsection
@section('js')
    <script src="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/sweet-alert2/sweetalert2.min.js')}}"></script>
    <script src="//cdn.datatables.net/plug-ins/1.11.3/api/sum().js"></script>
    <script>

$('.dokum').click(function (){
    <? if(isset($_GET['uid'])){?>
    window.open('https://oyuneks.com/panel/uye-aktivite?uid={{$_GET['uid']}}&dokum=316','erh');
    <?}?>
});


        $('.pdf').click(function(x){ ln=$(this); ln.html('Bağlanıyor..->Neo->EDM <i class="fa fa-spinner fa-spin" aria-hidden="true"></i>');
                        let ettn=$(this).attr('id');
            $.post('/ykp.php', {pdf_al:ettn }, function (x) {
                let pdf_ = window.open("","PDF")
                pdf_.document.write("<iframe width='100%' style='border: none' height='100%' src='data:application/pdf;base64, " + x + "'></iframe>");
                ln.text('Yüklendi');
            });

        })


        $('.gonder').click(function(){
            if(!confirm("Bu mesaj aynı kişiye tekrar iletilecek, emin misiniz ?")) {return false;}
            let mid=$(this).attr('mid');
            let tip=$(this).attr('tip');

            $.post('https://oyuneks.com/tsend', {mid:mid , _token: $('meta[name="csrf-token"]').attr('content')}, function (x) {alert(x);});

        });


$('.gor').click(function(){
    var id=$(this).attr('id');
    $.post('/ykp.php', {sms:id}, function (x) {
        swal.fire({customClass:'genis', html: "<iframe width='600px' height='600px' src='data:text/html;base64,"+x+"'></iframe>" ,showConfirmButton: true});
    });
});


var table='';

    $(document).ready(function () {

        $('#datatable thead tr').clone(true).appendTo('#datatable thead');
        $('#datatable thead tr:eq(1) th').each(function (i) {
            var title = $(this).text();
            if(title!=='F.Kes') {
                $(this).html('<input type="text" size="5" placeholder="' + title + '" />');
            } else {$(this).html('<input type="checkbox" checked class="select_all">');}
            $('input[type=text]', this).on('keyup change', function () {
                if (table.column(i).search() !== this.value) {
                    table.column(i).search(this.value).draw();
                }
            });
        });
        $('#datatable input[type="text"]').css({'width': '100%', 'display': 'inline-block'});



        table = $('#datatable').DataTable({
            <? if(!isset($_GET['sms'])){ ?>
            "footerCallback": function (row, data, start, end, display) {
                var api = this.api();
                nb_cols = api.columns().nodes().length;
                var j = 3;
                var e = 2;
                var intVal = function ( i ) {return typeof i === 'string' ?i.replace(/[\Adet,]/g, '')*1 :typeof i === 'number' ?i : 0;};
                var intVal2 = function ( i ) {return typeof i === 'string' ?i.replace(/[\TL,]/g, '')*1 :typeof i === 'number' ?i : 0;};
                while (j < 4) {
                    total = api.column(j).data().reduce( function (a, b) {return intVal(a) + intVal(b);}, 0 );
                    pageTotal = api.column( j, { page: 'current'} ).data().reduce( function (a, b) {return intVal(a) + intVal(b);}, 0 );
                    sumCol4Filtered = display.map(el => data[el][j]).reduce((a, b) => intVal(a) + intVal(b), 0 );
                    $( api.column(j).footer() ).html(sumCol4Filtered.toFixed(2) );
                    j++;
                }
                while (e < 3) {
                    total = api.column(e).data().reduce( function (a, b) {return intVal2(a) + intVal2(b);}, 0 );
                    pageTotal = api.column( e, { page: 'current'} ).data().reduce( function (a, b) {return intVal2(a) + intVal2(b);}, 0 );
                    sumCol4Filtered = display.map(el => data[el][e]).reduce((a, b) => intVal2(a) + intVal2(b), 0 );
                    $( api.column(e).footer() ).html(sumCol4Filtered.toLocaleString());
                    e++;
                }
            },
            <? }?>
            columnDefs: [{orderable: false, targets: [4]}],
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

        $('#datatable_filter').css({'display': 'none'});
        table.buttons().container().appendTo('#datatable_wrapper .col-md-6:eq(1)');
    });

    </script>
@endsection
