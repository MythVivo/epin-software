<?

if (isset($_GET['o']) && isset($_GET['o1']) && $_GET['o1'] > 100) {
    if(DB::table('user_group_users')->where('user',Auth::user()->id)->first()->user_group==3) {
        $p = (object)$_GET;
        $al = DB::select("select durum from odeme_limit where id='$p->o' and oid='$p->o1'");
        if (count($al) > 0 && $al[0]->durum == 1) {
            DB::select("update users set bakiye=bakiye+(select amount from odemeler where id='$p->o1') where id=(select user from odemeler where id='$p->o1')");
            DB::select("update odeme_limit set durum=2 where id='$p->o'");
            $mesaj = 200;
        } else {
            $mesaj = 400;
        }
    } else{ $mesaj=700; }
}

if (isset($_GET['r']) && isset($_GET['r1']) && $_GET['r1'] > 100) {
    if(DB::table('user_group_users')->where('user',Auth::user()->id)->first()->user_group==3) {
        $p = (object)$_GET;
        $al = DB::select("select durum from odeme_limit where id='$p->r' and oid='$p->r1'");
        if (count($al) > 0 && $al[0]->durum == 1) {
            DB::select("update odeme_limit set durum=3 where id='$p->r'");
            $mesaj = 300;
        } else {
            $mesaj = 500;
        }
    }  else {$mesaj=700;}
}

odeme_kontrol(); // Ödeme limit kontrol kendimiz çağıralım burada helpers içinde
?>

@extends('back.layouts.app')
@section('css')
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/dropify/css/dropify.min.css')}}"          rel="stylesheet">
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/sweet-alert2/sweetalert2.min.css')}}"          rel="stylesheet" type="text/css">
    <style>
        table.dataTable.nowrap td {
            white-space: unset !important;
        }
    </style>
@endsection

@section('body')
    <div class="row" >
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-2 col-lg">
                                <a href="?red=316"><p class="btn btn-sm">Red edilenleri göster</p></a>

                                <table class="font-12 table table-bordered table-sm text-body text-center">
                                    <thead><th>No</th><th>User</th><th>Bakiye</th><th>Ödeme Kanalı</th><th>Limit</th><th>Ödeme</th><th>Tarih</th><th>Olay</th></thead>
                                    <tbody>
                                    <?
                                    if(isset($_GET['red']) && $_GET['red']==316) {$ek="ol.durum=3";} else {$ek="ol.durum=1";}
                                    $al=DB::select("SELECT ol.*, o.amount,u.name, u.email, u.bakiye+u.bakiye_cekilebilir as bakiye, ok.title bnk, pc.name knl, bbo.tutar lmt
                                        FROM odeme_limit ol
                                        left join odemeler o on o.id=ol.oid
                                        left join users u on u.id=o.user
                                        left join odeme_kanallari ok on ok.id=o.channel
                                        left join payment_channels pc on pc.id=o.channel
                                        left JOIN bakiye_bloke_odeme bbo on bbo.kanal=o.channel
                                        where  ".$ek." order by ol.oid desc

                                    ");$no=0;
                                    foreach ($al as $x){$no++;
                                        echo "<tr>
                                                <td>$no</td>
                                                <td style='cursor: pointer' onclick=\"window.open('". route('uye_detay', [$x->email]). "')\">".strtoupper($x->name)."</td>
                                                <td>".number_format($x->bakiye,2)."</td>
                                                <td>$x->knl / $x->bnk</td>
                                                <td>$x->lmt</td>
                                                <td>$x->amount</td>
                                                <td>$x->created_at</td>
                                                <td><i title='Onayla' style='cursor: pointer' id='$x->id' oid='$x->oid' class='btn btn-sm btn-success fa-check fas okey mr-2'></i> <i title='Red' style='cursor: pointer' id='$x->id' oid='$x->oid' class='fas fa-times btn btn-sm btn-danger red'></i></td>
                                              </tr>";
                                    }
                                    ?>

                                    </tbody>

                                </table>
                                <pre>
Server Time = <? echo date("H : i : s");?>

Server Date = <? echo date("d-m-Y");?>
</pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/sweet-alert2/sweetalert2.min.js')}}"></script>
    <script>
        <? if(isset($mesaj) && $mesaj==200) {echo "swal.fire({icon:'success',title:'Bakiye ekleme işlemi başarılı.',showConfirmButton: false,timer:1500});"; }
        if(isset($mesaj) && $mesaj==400) {echo "swal.fire({icon:'error',title:'ID hatası oluştu, daha önceden durumu değiştirilmiş kayıt. Bakiye eklenemedi',showConfirmButton: false,timer:4500});"; }
        if(isset($mesaj) && $mesaj==300) {echo "swal.fire({icon:'success',title:'İlgili kayıt listeden çıkarıldı.',showConfirmButton: false,timer:1500});"; }
        if(isset($mesaj) && $mesaj==500) {echo "swal.fire({icon:'error',title:'ID hatası oluştu, daha önceden durumu değiştirilmiş kayıt. İşlem yapılamadı.',showConfirmButton: false,timer:4500});"; }
        if(isset($mesaj) && $mesaj==700) {echo "swal.fire({icon:'error',title:'Yönetim yetkiniz yok maalesef, değişiklik yapamazsınız..',showConfirmButton: false,timer:3500});"; }
        ?>


        $('.okey').click(function(x){
            var id=$(this).attr('id');
            var oid=$(this).attr('oid');
            swal.fire({
                html:'Bekletilen tutar kullanıcının bakiyesine eklenecek emin misiniz ?',
                icon: "question",
                showCancelButton: true,
                confirmButtonText: 'Devam',
                cancelButtonText: 'Vazgeç',
                allowOutsideClick: false
            }).then((result) => {
                if (result.value) { location.href="?o="+id+"&o1="+oid; }
                else if (result.dismiss === Swal.DismissReason.cancel) {
                    swal.fire({icon:'error',title:'İşlemden Vazgeçildi',showConfirmButton: false,timer:1500});
                }
            });
        });

        $('.red').click(function(x){
            var id=$(this).attr('id');
            var oid=$(this).attr('oid');
            swal.fire({
                html:'İade sürecini manuel olarak ilgili kanal üzerinden yürütmeniz gerekiyor. Kayıt bu listeden silinecek ve sistem üzerinden tutar iadesi ile ilgili hiç bir işlem yapılmayacaktır. ' +
                    'Tutarın iadesi tamamen bu kaydı iptal edenin sorumluluğundadır. Devam edilsin mi ?',
                icon: "question",
                showCancelButton: true,
                confirmButtonText: 'Devam',
                cancelButtonText: 'Vazgeç',
                allowOutsideClick: false
            }).then((result) => {
                if (result.value) { location.href="?r="+id+"&r1="+oid; }
                else if (result.dismiss === Swal.DismissReason.cancel) {
                    swal.fire({icon:'error',title:'İşlemden Vazgeçildi',showConfirmButton: false,timer:1500});
                }
            });
        });

    </script>

@endsection
