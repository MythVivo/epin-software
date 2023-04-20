<? error_reporting(E_PARSE); ?>
@extends('back.layouts.app')
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/dropify/css/dropify.min.css')}}" rel="stylesheet"type="text/css">
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/sweet-alert2/sweetalert2.min.css')}}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">

    <style>
        table.dataTable.nowrap td:nth-child(2) {
            white-space: unset !important;
        }
    </style>
@endsection
@section('body')

    <div class="row" data-lang="{{getLang()}}">
        <div class="col-12">

            <div class="card">
                <div class="card-body">
                    @include('back.pages.cari.menu')
                    <button class="btn ekle btn-success mr-3 btn-bildirim" style="float: left">Yeni Fiş</button>
{{--                    <button class="btn trun btn-warning btn-bildirim" style="float: left">Reset</button>--}}

                        @if(isset($_GET['date1']) and isset($_GET['date2']))<? $date1 = $_GET['date1'];$date2 = $_GET['date2'];?> @else <? $date1 = date('Y-m-d', strtotime('-0 days')); $date2 = date('Y-m-d');?>
                    @endif

                        <form class="ml-2 row" method="get">
                            <div class='form-floating col-auto'><input type="date" id="date1" class="form-control style-input" name="date1" value="{{$date1}}" required> <label>İlk Tarih</label></div>
                            <div class='form-floating col-auto'><input type="date" id="date2" class="form-control style-input" name="date2" value="{{$date2}}" required><label>Son Tarih</label></div>
                            <select class="col-auto btn-bildirim mr-2" id="firma" name="h" data-live-search="true"></select>
                            <button class="btn btn-info col-auto btn-gradient-primary btn-bildirim" style="float: left">Ara</button>
                        </form>

                        <div class="table-responsive">

                        <table lang="{{getLang()}}" id="datatable"
                               class="font-12 table-sm table-bordered table-hover nowrap"
                               style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                            <tr style="text-align: center">
                                <th>Id</th>
                                <th>Kaynak</th>
                                <th>Hedef</th>
                                <th>Çıkan</th>
                                <th>Aktarılan</th>
                                <th>Açıklama</th>
                                <th>Gider Türü</th>
                                <th>User</th>
                                <th>Tarih</th>
                                <th>Sil</th>
                            </tr>
                            </thead>
                            <tbody>

                            @php
                            if(isset($_GET['h']) && $_GET['h']>0) {$z=$_GET['h']; $ek=" and (kaynak_cari='$z' or hedef_cari='$z')";} else{$ek='';}
                                $cg = (object) DB::select("select *,
                            (select name from users where id=admin_id) user,
                            (select unvan from cariy_hesaplar where id=kaynak_cari) kcar,
                            (select kur from cariy_kurlar where id=(select para_birimi from cariy_hesaplar where id=kaynak_cari)) brm,
                            (select kur from cariy_kurlar where id=(select para_birimi from cariy_hesaplar where id=hedef_cari)) brm1,
                            (select unvan from cariy_hesaplar where id=hedef_cari) hcar,
                            (select ad from cariy_tur where id=turu) turx
                            from cariy_fisler where deleted_at is null and date(created_at) between '$date1' and '$date2' ".$ek);
                            @endphp
                            @foreach($cg as $u )
                                <tr>
                                    <td style="text-align: center">{{$u->id}}</td>
                                    <td style="text-align: left">{{$u->kcar}} ({{$u->brm}}) </td>
                                    <td style="text-align: left">{{$u->hcar}} ({{$u->brm1}})</td>
                                    <td style="text-align: right">{{$u->brm}}  {{$u->cikan_tutar}}      </td>
                                    <td style="text-align: right">{{$u->brm1}} {{$u->aktarilan_tutar}}   </td>
                                    <td style="text-align: left">{{$u->aciklama}}</td>
                                    <td style="text-align: center">{{$u->turx}}</td>
                                    <td style="text-align: center">{{$u->user}}</td>
                                    <td style="text-align: left">{{$u->created_at}}</td>
                                    <td style="text-align: center">
                                        <i title="Sil" hc="{{$u->hedef_cari}}" id="{{$u->id}}" class="btn far fa-trash-alt sil"></i>
{{--                                        <i title="Düzenle" id="{{$u->id}}" class="btn far fa-edit dzn"></i>--}}
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                            <tfoot>
                            <tr style="text-align: center">
                                <th>Id</th>
                                <th>Kaynak</th>
                                <th>Hedef</th>
                                <th>Çıkan</th>
                                <th>Aktarılan</th>
                                <th>Açıklama</th>
                                <th>Gider Türü</th>
                                <th>User</th>
                                <th>Tarih</th>
                                <th></th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script src="/back/assets/plugins/sweet-alert2/sweetalert2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
    <script>
        var bg = <?=date('Y-m-d')?>;
        var ek='',not,kay,hed,tarih,ctut,atut,acik,baglanti,atut1,htut1,acik1,hkur,kkur,parite,kcns,hcns,fark,tur;


        $('.ekle').click(function () {
            $.post('{{route('cari_api')}}', {gr_oku: 316,all: 316,_token: $('meta[name="csrf-token"]').attr('content')}, function (y) {

                swal.fire({
                    html: "Yeni Cari Fiş <div style='text-align: left; padding-top: 10px'>" +
                        "<div class='form-floating'> <input style='width: 100%' class= 'form-control' id='tarih' type='date' min='<?=date('Y-m-d')?>' value='<?=date('Y-m-d')?>'></input><label>Fiş Tarihi</label></div>" +
                        "<div class='form-floating'> <select style='width: 100%' class='form-control text-secondary' id='kayg'><option value='sec'>Kaynak Cari Grup</option>" + y + "</select><label>Kaynak Cari Grup</label></div>" +
                        "<div class='form-floating'> <select style='width: 100%' class='form-control' id='kay'><option value='sec'></option></select><label>Kaynak Cari</label></div>" +
                        "<div class='form-floating'> <select style='width: 100%' class='form-control text-secondary' id='hedg'><option value='sec'>Hedef Cari Grup</option>" + y + "</select><label>Hedef Cari Grup</label></div>" +
                        "<div class='form-floating'> <select style='width: 100%' class='form-control' id='hed'><option value='sec'></option></select><label>Hedef Cari</label></div>" +
                        "<div class='form-floating'> <input style='width: 100%' onfocus='this.select()' placeholder='Çıkan Tutar' id='ctut' class='form-control border' min='1' type='number'> <label>Çıkan Tutar <i id='cb'></i></label></div>" +
                        "<div class='form-floating'> <input style='width: 100%' onfocus='this.select()' placeholder='Aktarılan Tutar' id='atut' class='form-control border' min='1' type='number'> <label>Aktarılan Tutar <i id='ab'></i></label>" +
                        "<div class='form-floating'> <input style='width: 100%' placeholder='Açıklama' id='acik' class='form-control border' type='text'> <label>Açıklama</label>" +
                        "<div class='form-floating'> <select style='width: 100%' class='form-control' id='tur'><option value='sec'>Gider Türü</option></select><label>Gider Türü</label>" +
                        "<div class='form-floating form-control-sm text-monospace text-beanred text-center mb-4' id='kurlar'></div>"+
                        "</div>",
                    showCancelButton: true,
                    confirmButtonText: 'Kayıt',
                    cancelButtonText: 'İptal',
                    reverseButtons: true,
                    allowOutsideClick: false,

                    preConfirm: () => {

                        setTimeout(function (){ // valid. mess. hide after 2 sec.
                            $('.swal2-validation-message').hide();$('.swal2-cancel').removeAttr('disabled');$('.swal2-confirm').removeAttr('disabled');},2000);

                        if ($('#kay').val() == 'sec' || $('#hed').val() == 'sec') {Swal.showValidationMessage('Kaynak ve Hedef hesap seçilmeli!');return}
                        else if(parseFloat($('#ctut').val()) > parseFloat($('#kay :selected').text().split('(')[1].split(' ')[0])) {Swal.showValidationMessage('Kaynak hesap bakiyesi yetersiz!');return}

                        if ($('#kay').val() == $('#hed').val()) {Swal.showValidationMessage('Kaynak ve Hedef hesap aynı olamaz!');return}
                        if (parseFloat($('#ctut').val()) < parseFloat($('#atut').val()) && parite <=1) {Swal.showValidationMessage('Aktarılan tutar Çıkandan büyük olamaz!');return}
                        if ($('#ctut').val() < 1 || $('#atut').val() < 1) {Swal.showValidationMessage('Aktarılan ve Çıkan tutar 0 dan büyük olmalı!');return}


                        // if(hcns=='TL'||kcns=='TL'){
                        //
                        //     if (hkur * $('#atut').val() > $('#ctut').val()) {Swal.showValidationMessage('Kaynak hesap bakiyesi yetersiz! 1');return;}
                        //     if ($('#atut').val() > kkur * $('#ctut').val()) {Swal.showValidationMessage('Kaynak hesap bakiyesi yetersiz! 2');return;}
                        //
                        // }
                        // else {
                        //     if (hkur - kkur < 0) {
                        //         if (hkur * $('#atut').val() > $('#ctut').val()) {Swal.showValidationMessage('Kaynak hesap bakiyesi yetersiz! 1');return;}
                        //         if ($('#atut').val() > kkur * $('#ctut').val()) {Swal.showValidationMessage('Kaynak hesap bakiyesi yetersiz! 2');return;}
                        //     } else {
                        //         if (hkur / $('#atut').val() > $('#ctut').val()) {Swal.showValidationMessage('Kaynak hesap bakiyesi yetersiz! 3');return;}
                        //         if ($('#atut').val() > kkur / $('#ctut').val()) {Swal.showValidationMessage('Kaynak hesap bakiyesi yetersiz! 4');return;}
                        //     }
                        // }
                    }

                }).then((result) => {
                    if (result.value) {
                        kay = $('#kay').val();hed = $('#hed').val();tarih = $('#tarih').val();ctut = $('#ctut').val();atut = $('#atut').val();acik = $('#acik').val(); tur=$('#tur').val();


                        console.log(kkur);
                        console.log(hkur);


                        if(hkur >0 || kkur >0){ // fiş Döviz üzerinden gidiyorsa-----------------------------------------------------------------------

                            if(kcns=='TL'){     // Çıkış hesabı TL ise
                                fark=$('#ctut').val() / hkur - $('#atut').val();
                                not=fark.toFixed(2)+' '+hcns;
                                fark=fark*hkur;
                                fark=fark.toFixed(2);
                            }
                            else if(hcns=='TL'){ // hedef hesap TL ise
                                fark=$('#ctut').val() * kkur - $('#atut').val();
                               // fark=fark/kkur;
                                not=fark.toFixed(2)+' '+kcns;
                                fark=fark.toFixed(2);
                            }
                            else if(kcns==hcns){ // aynı cins döviz den dövize ise
                                fark=$('#ctut').val() - $('#atut').val();
                                not=fark.toFixed(2)+' '+kcns;
                                fark=fark*kkur;
                                fark=fark.toFixed(2);
                            }
                            else if(kcns != 'TL' && hcns != 'TL') { // farklı cins dövizden dövize
                             fark = $('#ctut').val()*kkur - $('#atut').val()*hkur;       // Fark TL
                                not=fark.toFixed(2)+' '+kcns;
                                fark=fark*kkur;
                                fark=fark.toFixed(2);

                            }

                            console.log(fark);
                            if(hcns!='TL'){ek='- Kur:'+hkur;} else {ek='- Kur:'+kkur;}

                            if(fark>0) { //fark varsa

                                swal.fire({
                                    html: "Çıkan ile aktarılan tutar arasındaki fark TL olarak komisyon hesabına aktarılacak" +
                                        "<div class='form-floating'><select style='width: 100%' class='form-control text-secondary' id='hed1'><option value='3'>Komisyon</option></select><label>Hedef Cari</label></div>" +
                                        "<div class='form-floating'><input style='width: 100%' value='" + fark + "' placeholder='Aktarılan Tutar' readonly id='atut1' class='form-control border' type='text'> <label>Aktarılan Tutar (TL)</label></div>" +
                                        "<div class='form-floating'><input style='width: 100%' value='Komisyon bedeli "+not+"' placeholder='Açıklama' id='acik1' class='form-control border' type='text'> <label>Açıklama</label></div>",
                                    preConfirm: () => {
                                        if ($('#hed1').val() == 'sec') {Swal.showValidationMessage('Aktarılacak hesabı belirtmelisiniz!');return}
                                        if ($('#hed1').val() == kay) {Swal.showValidationMessage('Kaynak hesaba aktaramazsınız!');return}
                                    }
                                }).then((result) => { // Fark var ise baglantili 2. fis icin
                                    if (result.value) {
                                        hed1 = $('#hed1').val();atut1 = $('#atut1').val();acik1 = $('#acik1').val();//Önce ana fiş  post edilip ID alınacak
                                        console.log(hcns);

                                        $.post('{{route('cari_api')}}', {fis_ekle: 316,kay: kay,hed: hed,ctut: ctut,atut: atut, tur:tur, acik: acik+ek,_token: $('meta[name="csrf-token"]').attr('content')}, function (x) {
                                            //Sonra fark fişi ID gelince post edilir
                                        $.post('{{route('cari_api')}}', {fis_ekle: 316,baglanti: x,kay: kay,hed: hed1,ctut: 0,atut: atut1, tur:tur, acik: acik1 + ' (Ana fiş ' + x + ek+')',_token: $('meta[name="csrf-token"]').attr('content')}, function (x) {location.reload();});
                                        });
                                    }
                                });
                                setTimeout(function () {$('#hed1 option[value=3]').attr('selected', 'selected');}, 500);
                            }    else { //fark yok ise
                                    $.post('{{route('cari_api')}}', {fis_ekle: 316,kay: kay,hed: hed,ctut: ctut,atut: atut, tur:tur, acik:acik+ek,_token: $('meta[name="csrf-token"]').attr('content')}, function (x) {location.reload();});
                                }

                        }
                        else { // fiş TL üzerinden gidiyorsa---------------------------------------------------------------------------------
                            if (parseFloat($('#atut').val()) < parseFloat($('#ctut').val())) { // Fark var ise baglantili 2. fis pops
                                let fark = $('#ctut').val() - $('#atut').val();
                                swal.fire({
                                    html: "Çıkan ile aktarılan tutar arasındaki fark TL olarak komisyon hesabına aktarılacak" +
                                        "<div class='form-floating'><select style='width: 100%' class='form-control text-secondary' id='hed1'><option value='3'>Komisyon</option></select><label>Hedef Cari</label></div>" +
                                        "<div class='form-floating'><input style='width: 100%' value='" + fark + "' placeholder='Aktarılan Tutar' readonly id='atut1' class='form-control border' type='text'> <label>Aktarılan Tutar</label></div>" +
                                        "<div class='form-floating'><input style='width: 100%' value='Komisyon bedeli' placeholder='Açıklama' id='acik1' class='form-control border' type='text'> <label>Açıklama</label></div>",
                                    preConfirm: () => {
                                        if ($('#hed1').val() == 'sec') {Swal.showValidationMessage('Aktarılacak hesabı belirtmelisiniz!');return}
                                        if ($('#hed1').val() == kay) {Swal.showValidationMessage('Kaynak hesaba aktaramazsınız!');return}
                                    }
                                }).then((result) => { // Fark var ise baglantili 2. fis icin
                                    if (result.value) {hed1 = $('#hed1').val();atut1 = $('#atut1').val();acik1 = $('#acik1').val();//Önce ana fiş  post edilip ID alınacak
                                        $.post('{{route('cari_api')}}', {fis_ekle: 316,kay: kay,hed: hed,ctut: ctut,atut: atut, tur:tur, acik: acik,_token: $('meta[name="csrf-token"]').attr('content')}, function (x) {
                                            //Sonra fark fişi ID gelince post edilir
                                            $.post('{{route('cari_api')}}', {fis_ekle: 316,baglanti: x,kay: kay,hed: hed1,ctut: 0,atut: atut1, tur:tur, acik: acik1 + ' (Ana fiş ' + x + ek+')',_token: $('meta[name="csrf-token"]').attr('content')}, function (x) {location.reload();});
                                        });
                                    }
                                });
                                setTimeout(function () {$('#hed1 option[value=3]').attr('selected', 'selected');}, 500);
                            } else {     // Fark yok ise tek fis post
                                if (result.value) {
                                    $.post('{{route('cari_api')}}', {fis_ekle: 316,kay: kay,hed: hed,ctut: ctut,atut: atut, tur:tur, acik: acik + ek,_token: $('meta[name="csrf-token"]').attr('content')}, function (x) {location.reload();});
                                }
                            }
                        }
                    }
                });
            });

            $.post('cari_api', {tur_oku: 316, _token: $('meta[name="csrf-token"]').attr('content')}, function (x) { setTimeout(function() {$('#tur').append(x)},1000) });
        }); // ekle click sonu


/*-----------> Firma Listesi Live Search -----------------------------------------------------------------------------------------*/
            $.post('cari_api', {hes_oku:316,live:316, _token: $('meta[name="csrf-token"]').attr('content')}, function (x) {$('#firma').append(x);setTimeout(function (){$('#firma').addClass('selectpicker');$('.selectpicker').selectpicker();},1000);});
/*-------------------------------------------------------------------------------K Grup değişimi----------------------------------*/
            $(document).on('change','#kayg',function() {$.post('{{route('cari_api')}}', {hes_oku: 316, gid:$('#kayg').val() ,_token: $('meta[name="csrf-token"]').attr('content')}, function (x) {$('#kay').empty().append(x);$('#kay').trigger('change');});});
/*-------------------------------------------------------------------------------H Grup değişimi----------------------------------*/
            $(document).on('change','#hedg',function() {$.post('cari_api', {hes_oku: 316, gid:$('#hedg').val() ,_token: $('meta[name="csrf-token"]').attr('content')}, function (x) {$('#hed').empty().append(x);$('#hed').trigger('change');});});
/*-------------------------------------------------------------------------------Hesap değişimi-----------------------------------*/
            $(document).on('change','#kay, #hed',function(){
                    if ($('#kay :selected').attr('ad') != 'TL' || $('#hed :selected').attr('ad')!='TL'){

                        kkur=$('#kay :selected').attr('kur');
                        hkur=$('#hed :selected').attr('kur');

                        console.log("hedef=", hkur,"----","Kanak=",kkur);

                        if(hkur<1 || kkur<1 || hkur=='' || kkur==''){swal.fire({icon:'error',html:'Dövizli işlemler için önce kurları girmelisiniz.'});return;}
                        kkur=parseFloat($('#kay :selected').attr('kur'));
                        hkur=parseFloat($('#hed :selected').attr('kur'));


                            kcns=$('#kay :selected').attr('ad');
                            hcns=$('#hed :selected').attr('ad');
                            let x=kkur>1?kcns+' : '+kkur:''; // kur ekranı
                            let y=hkur>1?hcns+' : '+hkur:'';
                            if (($('#kay :selected').val()>1)){$('#cb').text('('+$('#kay :selected').text().split('(')[1].split(' ')[2]);} // label
                            if (($('#hed :selected').val()>1)){$('#ab').text('('+$('#hed :selected').text().split('(')[1].split(' ')[2]);}
                            if(kkur>hkur){parite=kkur/hkur;} else {parite=hkur/kkur;}
                            $('#kurlar').empty().html(x +'<br>'+ y +'<button title="Otomatik kur çevirici Aktif/Pasif" class="btn btn-sm cevir alert-success welcome-img" >Oto Kur</button>');
                            console.log(hkur+hcns);
                            console.log(kkur+kcns);

                    } else {$('#kurlar').empty();parite=0;hkur=0;kkur=0;}
            });
/*-------------------------------------------------------------------------------Oto kur aç kapa----------------------------------*/
            $(document).on('click','.cevir',function(){$(this).toggleClass('alert-success');});
/*-------------------------------------------------------------------------------Oto çıkan kur hesap------------------------------*/
            $(document).on('keyup','#ctut',function(){
                if($('.cevir').hasClass('alert-success')){
                    if($('#hed :selected').val()!='sec' && $('#kay :selected').val()!='sec') {
                        if ($('#hed :selected').val() != $('#kay :selected').val()) {
                            if(kkur<hkur){$('#atut').val(($('#ctut').val() / parite).toFixed(2));} else {$('#atut').val(($('#ctut').val() * parite).toFixed(2));}
                        }
                    }
                }
            });
/*-------------------------------------------------------------------------------Oto aktarılan kur hesap--------------------------*/
            $(document).on('keyup change','#atut',function(){

    console.log($('#atut').val() * parite, $('#ctut').val());

                if(kkur>hkur){
                    if( $('#atut').val() / parite> $('#ctut').val()) {Swal.showValidationMessage('Çıkan tutarı aşamazsınız 1');$('.swal2-confirm').attr('disabled','disabled');} else {usil(10);}
                } else{
                    if( $('#atut').val() * parite> $('#ctut').val()) {Swal.showValidationMessage('Çıkan tutarı aşamazsınız 2');$('.swal2-confirm').attr('disabled','disabled');}else {usil(10);}
                }

                if($('.cevir').hasClass('alert-success')) {
                    if ($('#hed :selected').val() != 'sec' && $('#kay :selected').val() != 'sec') {
                        if ($('#hed :selected').val() != $('#kay :selected').val()) {
                            if(kkur>hkur){$('#ctut').val(($('#atut').val() / parite).toFixed(2));} else {$('#ctut').val(($('#atut').val() * parite).toFixed(2));}
                        }
                    }
                }
            });
/*-------------------------------------------------------------------------------DB RESET-----------------------------------------*/
        //     $('.trun').click(function() {
        //     if (confirm('Hesaplar ve Fişler Temizlenecek..!!')) {
        //     $.post('cari_api', {truncate: 316, _token: $('meta[name="csrf-token"]').attr('content')}, function () {
        //         swal.fire({icon: 'success', title: 'Temizlendi..', showConfirmButton: false, timer: 1500}); location.reload();
        //     });
        // } else { swal.fire({icon:'error',title:'İşlemden Vazgeçildi',showConfirmButton: false,timer:1500});}
        // });
/*-------------------------------------------------------------------------------Fiş sil------------------------------------------*/
            $('.sil').click(function () {
            if ($(this).attr('hc')==3){ swal.fire({icon:'error',title:'Bağlantılı fişi silemezsiniz',showConfirmButton: false,timer:2500}); return}
            let id = $(this).attr('id');

            swal.fire({
                title: "",
                text: "Seçilen fiş ve varsa bağlantılı fişler silinecek devam edilsin mi ?",
                icon: "info",
                showCancelButton: true,
                confirmButtonText: 'Devam Et',
                cancelButtonText: 'İPTAL',
                reverseButtons: true,
                allowOutsideClick: false
            }).then((result) => {
                if (result.value) {
                    $.post('{{route('cari_api')}}', {id: id,fis_dzn:316,sil: 316,_token: $('meta[name="csrf-token"]').attr('content')}, function (x) {location.reload();});
                } else if (result.dismiss === Swal.DismissReason.cancel) {swal.fire('İşlem İptal Edildi', '', 'error');}
            });
        });
/*-------------------------------------------------------------------------------DT olayları--------------------------------------*/
            $(document).ready(function () {
            $('#datatable thead tr').clone(true).appendTo('#datatable thead');
            $('#datatable thead tr:eq(1) th').each(function (i) {
                var title = $(this).text();
                if(title!=='Sil') {
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
                    var j = 3;
                    var e = 4;
                    var intVal = function ( i ) {return typeof i === 'string' ?i.replace(/[\€,\₺,\$,\£,]/g, '')*1 :typeof i === 'number' ?i : 0;};
                    var intVal2 = function ( i ) {return typeof i === 'string' ?i.replace(/[\€,\₺,\$,\£,]/g, '')*1 :typeof i === 'number' ?i : 0;};
                    while (j < 4) {
                        total = api.column(j).data().reduce( function (a, b) {return intVal(a) + intVal(b);}, 0 );
                        pageTotal = api.column( j, { page: 'current'} ).data().reduce( function (a, b) {return intVal(a) + intVal(b);}, 0 );
                        sumCol4Filtered = display.map(el => data[el][j]).reduce((a, b) => intVal(a) + intVal(b), 0 );
                        $( api.column(j).footer() ).html(sumCol4Filtered.toFixed(2) );
                        j++;
                    }
                    while (e < 5) {
                        total = api.column(e).data().reduce( function (a, b) {return intVal2(a) + intVal2(b);}, 0 );
                        pageTotal = api.column( e, { page: 'current'} ).data().reduce( function (a, b) {return intVal2(a) + intVal2(b);}, 0 );
                        sumCol4Filtered = display.map(el => data[el][e]).reduce((a, b) => intVal2(a) + intVal2(b), 0 );
                        $( api.column(e).footer() ).html(sumCol4Filtered.toLocaleString());
                        e++;
                    }
                },
                columnDefs: [{orderable: false, targets: [8]}],
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
                            columns: [0, 1, 2, 3, 4,5,6,7,8]
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
                            columns: [0, 1, 2, 3, 4,5,6,7,8]
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
                            columns: [0, 1, 2, 3, 4,5,6,7,8]
                        }
                    },

                ],
                "order": [[0, "desc"]],
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
            $('#datatable_filter').css({'display': 'none'});
            table.buttons().container().appendTo('#datatable_wrapper .col-md-6:eq(1)');



        });

            function usil(x){
                setTimeout(function (){ // valid. mess. hide after 2 sec.
                $('.swal2-validation-message').hide();$('.swal2-cancel').removeAttr('disabled');$('.swal2-confirm').removeAttr('disabled');},x);
            }
    </script>
@endsection
