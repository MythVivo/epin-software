<? error_reporting(E_PARSE);
if(@$_GET['sil']){$p = (object) $_GET; DB::select("delete from bayi_odeme where id='$p->sil'");die();}
if(@$_GET['obayi']&&@$_GET['otut']){$p = (object) $_GET; DB::table('bayi_odeme')->insert(['bayi_id' => $p->obayi,'odeme' => $p->otut,'aciklama' => $p->oacik,'created_at' => date('YmdHis')]);
    die();}
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

    <div class="row" data-lang="{{getLang()}}">
        <div class="col-12">

            <div class="card">
                <div class="card-body">
                    <h4 class="mt-0 header-title mb-3">
                        <div class="row">
                            <div class="col-md-6 col-sm-6 " >
                                Bayi İşlemleri
                            </div>
                            <div class="col-md-6 col-sm-6 text-right" >
                                <a href="#" class="ekle btn btn-small">Bayi Ekle</a>
                                <a href="#" class="rapor btn btn-small">Bayi Rapor</a>
                                <a class="btn btn-small odeme">Ödeme Gir</a>
                            </div>
                        </div>
                    </h4>
                    <div class="table-responsive">

                        <table lang="{{getLang()}}" id="datatable" class="font-12 table-sm table-bordered table-hover nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead><tr style="text-align: center"><th>Id</th><th>Bayi Adı</th><th>Müşteri Say.</th><th colspan="3">İndirim Oranları</th><th>Mail</th><th>Pass</th><th>Dzn</th><th>Sil</th></tr></thead>
                            <tbody>

                            @php
                            $bayi=DB::select("SELECT *, (select COUNT(*) from bayi_musteri where bayi_id=bayi.id) mus FROM bayi");
                            @endphp
                            @foreach($bayi as $u)
                                <tr>
                                    <td style="text-align: center">{{$u->id}}</td>
                                    <td style="text-align: center" class="tik" id="s_{{$u->id}}">{{$u->ad}}<br>{{$u->firma_ad}}</td>
                                    <td style="text-align: center; cursor: pointer" class="mus" id="x_{{$u->id}}">{{$u->mus}}</td>
                                    <td style="text-align: center">Epin %{{$u->epin}}</td>
                                    <td style="text-align: center">GG %{{$u->gg}}</td>
                                    <td style="text-align: center">Pazar %{{$u->py}}</td>
                                    <td style="text-align: center">{{$u->email}}</td>
                                    <td style="text-align: center">{{$u->pass}}</td>
                                    <td style="text-align: center; cursor: pointer" class="dzn btn-sm" id="D_{{$u->id}}">Düzenle</td>
                                    <td style="text-align: center; cursor: pointer" class="sil btn-sm" id="D_{{$u->id}}">Sil</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <div id="odemeler" class="mt-5" @if($_GET['ac']!=1) style="display: none" @endif>

<div style="width: 400px; margin: 0 auto" class="text-center">
                            <select class="form-control" id="bay">
                                @foreach(DB::select("select * from bayi group by uid") as $by)
                                <option value="{{$by->uid}}">{{$by->ad}}</option>
                                @endforeach
                            </select>
                            <input type="text" id="tutar" placeholder="Tutar" class="form-control">
                            <input type="text" id="acik" placeholder="Açıklama" class="form-control">
                            <button class="btn btn-sm btn-gradient-success mt-3 oek">Kayıt</button>

</div>

                            <h4>Ödemeler</h4>
                            <table class="table font-12">
                                <tr><th>No</th><th>Bayi</th><th>Tarih</th><th>Açıklama</th><th>Tutar</th><th>Sil</th></tr>
                        <?
                        $odeme=DB::select("select *, (select ad from bayi where uid=bayi_id limit 1) bayi from bayi_odeme order by created_at desc");
                        $no=0;
                        foreach ($odeme as $ode){$no++;
                            echo "<tr><td>$no</td><td>$ode->bayi</td><td>$ode->created_at</td><td>$ode->aciklama</td><td>$ode->odeme</td><td id='$ode->id' style='cursor: pointer' class='silo'>Sil</td></tr>";
                         }
                        ?>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/sweet-alert2/sweetalert2.min.js')}}"></script>
    <script type="text/javascript">
let id;


$('.odeme').click(function (){
    $('#odemeler').show();
});

$('.silo').click(function(x){
        id=$(this).attr('id');
        if(confirm("Kayıt silinecek devam edilsin mi ?")) {
            $.get('', {sil:id}, function (x) {location.reload();});
        } else { swal.fire({icon:'info',html:'İşlemden Vazgeçildi',showConfirmButton: false,timer:1500});}
})


$('.oek').click(function(){
    $.get('', {oacik:$('#acik').val(), otut:$('#tutar').val(),obayi:$('#bay').val() }, function (x) {
        location.href="https://oyuneks.com/panel/bayi?ac=1";
    });
})



$('.ekle').click(function(x){

    swal.fire({

        html:'<p>Bayi Ekle</p>' +
            '<span style="width: 150px" class="float-left btn-sm">Bayi Adı</span>       <span><input style="width: 200px !important"  id="ad"    type="text" placeholder="Bayi adı" class="form-control"></span>' +
            '<span style="width: 150px" class="float-left btn-sm">Epin İndirim</span>   <span><input style="width: 200px !important"  id="epin"  type="number" placeholder="Epin Oran %" class="form-control"></span>' +
            '<span style="width: 150px" class="float-left btn-sm">GG İndirim</span>     <span><input style="width: 200px !important"  id="gg"    type="number" placeholder="GG Oran %" class="form-control"></span>' +
            '<span style="width: 150px" class="float-left btn-sm">Pazar İndirim</span>  <span><input style="width: 200px !important"  id="py"    type="number" placeholder="Pazar Oran %" class="form-control"></span>' +
            '<span style="width: 150px" class="float-left btn-sm">Email Adresi</span>   <span><input style="width: 200px !important"  id="email" type="text" placeholder="Email adresi" class="form-control"></span>' +
            '<span style="width: 150px" class="float-left btn-sm">Bayi Şifre</span>     <span><input style="width: 200px !important"  id="pass"  type="text" placeholder="Şifre" class="form-control"></span>' +
            '<span class="btn-sm form-group form-control btn-sm" style="height: auto">Bayi müşterisi eklemek Kayıt düzenlemeye gidin',

        showCancelButton: true,
        confirmButtonText: 'EKLE',
        cancelButtonText: 'İPTAL',
        allowOutsideClick: false
    }).then((result) => {

        if (result.value) {
            $.post('/ykp.php', {bayid:'', ad: $('#ad').val(),epin:$('#epin').val(),gg:$('#gg').val(),py:$('#py').val(),pass:$('#pass').val(),email:$('#email').val(),veri:$('#veri').val(),tur:$('#tur').val(),bayi_ekle:316}, function (x) {
                location.reload();
            });
        }
    });
});

$('.mus').click(function(x){
                let id=$(this).attr('id').split('_')[1];

            swal.fire({title:'Alt müşteri listesi düzenleme modunda eklenecek',
            html: '<div id="icerik"></div>'
            });

            $.post('/ykp.php', {alt_bayi:316,bayi:id }, function (x) { $('#icerik').html(x); });
});


$('.sil').click(function(x){
            let id=$(this).attr('id').split('_')[1];

    swal.fire({
        icon: 'warning',
        title:'!!..Dikkat..!!',
        html:'<p>Bayi ve altındaki tüm tanımlı hesaplar veritabanından KALICI olarak silinerek indirim tanımları iptal edilecektir.<br>Bu işlem <b>GERİ</b> alınamaz.<br>Ne yaptığınızın farkındaysanız işleme devam ediniz. </p>',
        showCancelButton: true,
        confirmButtonText: 'SİL',
        cancelButtonText: 'VAZGEÇ',
        allowOutsideClick: false
    }).then((result) => {

        if (result.value) {
             $.post('/ykp.php', {bayi_sil:316, bayid:id}, function (x) {location.reload();});
        } else {swal.fire({icon:'error',title:'İşlemden Vazgeçildi',showConfirmButton: false,timer:1500});}
    });
});



$('.dzn').click(function(x){
            id=$(this).attr('id').split('_')[1];

            $.post('/ykp.php', {bayi_det:316, bayi_id: id}, function (x) {
                let w=JSON.parse(x);

        swal.fire({

           html:'<p>Bayi Düzenleme</p>' +
                '<span style="width: 150px" class="float-left btn-sm">Bayi Adı</span>       <span><input style="width: 200px !important" value="'+w[0].ad+'"   id="ad" type="text" placeholder="Bayi adı" class="form-control"></span>' +
                '<span style="width: 150px" class="float-left btn-sm">Epin İndirim</span>   <span><input style="width: 200px !important" value="'+w[0].epin+'" id="epin" type="number" placeholder="Epin Oran %" class="form-control"></span>' +
                '<span style="width: 150px" class="float-left btn-sm">GG İndirim</span>     <span><input style="width: 200px !important" value="'+w[0].gg+'"   id="gg" type="number" placeholder="GG Oran %" class="form-control"></span>' +
                '<span style="width: 150px" class="float-left btn-sm">Pazar İndirim</span>  <span><input style="width: 200px !important" value="'+w[0].py+'"   id="py" type="number" placeholder="Pazar Oran %" class="form-control"></span>' +
                '<span style="width: 150px" class="float-left btn-sm">Email Adresi</span>   <span><input style="width: 200px !important" value="'+w[0].email+'"id="email" type="text" placeholder="Email adresi" class="form-control"></span>' +
                '<span style="width: 150px" class="float-left btn-sm">Bayi Şifre</span>     <span><input style="width: 200px !important" value="'+w[0].pass+'" id="pass" type="text" placeholder="Şifre" class="form-control"></span>' +
                '<span class="btn-sm form-group form-control btn-sm" style="height: auto">Bayi müşterisi eklemek için her satıra bir adet VKN veya Kullanıcı ID girin.  Girilecek veri türünü seçin <br> ' +
                '<select id="tur"><option value="0">Veri Türü</option><option value="1">VKN</option><option value="2">USER ID</option></select>' +
                '<textarea id="veri" rows="7" style="font: 12px; display: none" class="form-group form-control" placeholder=""></textarea>',

            showCancelButton: true,
            confirmButtonText: 'Kayıt',
            cancelButtonText: 'İPTAL',
            allowOutsideClick: false
        }).then((result) => {

            if (result.value) {
                $.post('/ykp.php', {bayid:id, ad: $('#ad').val(),epin:$('#epin').val(),gg:$('#gg').val(),py:$('#py').val(),pass:$('#pass').val(),email:$('#email').val(),veri:$('#veri').val(),tur:$('#tur').val(),bayi_duzen:316}, function (x) {
                location.reload();
                });
            }
        });
        });
    });

        $(document).on('change','#tur',function(x){
            if($('#tur').val()=='1') {$('#veri').attr('placeholder','VKN leri her satıra 1 tane olacak şekilde giriniz');$('#veri').removeAttr('disabled');}
            else if($('#tur').val()=='2') {$('#veri').attr('placeholder','Kullanıcı ID lerini her satıra 1 tane olacak şekilde giriniz');$('#veri').removeAttr('disabled');}
            else if($('#tur').val()=='0') {$('#veri').attr('placeholder','Veri tipini seçiniz');$('#veri').attr('disabled','disabled');}

            $('#veri').show();})                              ;
    </script>
@endsection
