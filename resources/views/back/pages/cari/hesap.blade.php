<? error_reporting(E_PARSE); ?>
@extends('back.layouts.app')
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/dropify/css/dropify.min.css')}}" rel="stylesheet"
          type="text/css">
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/sweet-alert2/sweetalert2.min.css')}}"
          rel="stylesheet" type="text/css">
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
                    <button class="btn btn-sm ekle btn-success">Yeni Hesap</button>
                    <div class="table-responsive">


                        <table lang="{{getLang()}}" id="datatable"
                               class="font-12 table-sm table-bordered table-hover nowrap"
                               style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                            <tr style="text-align: center">
                                <th>No</th>
                                <th>Id</th>
                                <th>Grup</th>
                                <th>Uid</th>
                                <th>Unvan</th>
                                <th>Bakiye</th>
                                <th>Birim</th>
                                <th>Oluşturma</th>
                                <th>Dzn</th>
                            </tr>
                            </thead>
                            <tbody>

                            <?
                                $cg = (object) DB::select("select *, (select ad from cariy_grup where id=grup) gad, (select kur from cariy_kurlar where id=para_birimi) kur  from cariy_hesaplar where deleted_at is null order by gad");
                            $no=1;
                                ?>
                            @foreach($cg as $u )
                                <tr>
                                    <td style="text-align: center">{{$no++, $no}}</td>
                                    <td style="text-align: center">{{$u->id}}</td>
                                    <td style="text-align: center">{{$u->gad}}</td>
                                    <td style="text-align: center">{{$u->user_id}}</td>
                                    <td style="text-align: center">{{$u->unvan}}</td>
                                    <td style="text-align: right ">{{number_format($u->bakiye,2,',','.')}}</td>
                                    <td style="text-align: center">{{$u->kur}}</td>
                                    <td style="text-align: center">{{explode(' ',$u->created_at)[0]}}</td>
                                    <td style="text-align: center">
                                        <i title="Sil"      id="{{$u->id}}" bak="{{$u->bakiye}}" class="btn far fa-trash-alt sil"></i>
                                        <i title="Düzenle"  id="{{$u->id}}" class="btn far fa-edit dzn"></i>
                                        <i title="Loglar"   id="{{$u->id}}" class="btn fa fa-search log"></i>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="/back/assets/plugins/sweet-alert2/sweetalert2.min.js"></script>
    <script>

        $('.ekle').click(function () {
            $.post('cari_api', {kur_oku: 316, _token: $('meta[name="csrf-token"]').attr('content')}, function (w) {
                $.post('{{route('cari_api')}}', {
                    gr_oku: 316,
                    _token: $('meta[name="csrf-token"]').attr('content')
                }, function (x) {
                    swal.fire({
                        html: "Cari Hesap Ekleme <div style='text-align: left; padding-top: 10px'>" +
                            "<div class='form-floating'><select style='width: 100%' class='form-control' id='gr'><option>Grup Seçin</option>" + x + "</select><label>Grup Seçin</label></div>" +
                            "<div class='form-floating'><input style='width: 100%' placeholder='Ünvan' id='had' class='form-control border' type='text'><label>Ünvan</label></div>" +
                            "<div class='form-floating'><select style='width: 100%' class='form-control' id='brm'><option value='sec'>Kur Seçin</option>" + w + "</select><label>Para Birim</label></div>" +
                            "<div class='form-floating'><input style='width: 100%' placeholder=' ' id='bak' class='form-control border' min='0' value='0' type='number'><label>Açılış Bakiyesi</label></div>" +
                            "<div class='form-floating'><input style='width: 100%' placeholder='Vergi Daire' id='vd' class='form-control border' type='text'><label>Vergi Daire</label></div>" +
                            "<div class='form-floating'><input style='width: 100%' placeholder='Vergi No' id='vn' class='form-control border' type='text'><label>Vergi No</label></div>" +
                            "<div class='form-floating'><input style='width: 100%' placeholder='Adres' id='adres' class='form-control border' type='text'><label>Adres</label></div>" +
                            "</div>",

                        showCancelButton: true,
                        confirmButtonText: 'Kayıt',
                        cancelButtonText: 'İptal',
                        reverseButtons: true,
                        allowOutsideClick: false,

                        preConfirm: () => {
                            setTimeout(function (){$('.swal2-validation-message').hide('slow');$('.swal2-cancel').removeAttr('disabled');$('.swal2-confirm').removeAttr('disabled');},2000);
                            if ($('#brm').val() == 'sec') {Swal.showValidationMessage('Hesap birimi seçilmedi!');return}
                        }

                    }).then((result) => {
                        if (result.value) {

                            if($('#brm').val()=='sec'){}

                            $.post('{{route('cari_api')}}', {
                                hes_ekle: 316,
                                gr: $('#gr').val(),
                                unvan: $('#had').val(),
                                brim: $('#brm').val(),
                                bak: $('#bak').val(),
                                vd: $('#vd').val(),
                                vn: $('#vn').val(),
                                adres: $('#adres').val(),
                                _token: $('meta[name="csrf-token"]').attr('content')
                            }, function (x) {
                                location.reload();
                            });
                        }
                    });
                });
            });
        });

        $('.dzn').click(function () {
            let id = $(this).attr('id');
            if(id==3) {swal.fire({icon: 'error', title: 'Komisyon Hesabı Düzenlenemez!', showConfirmButton: false, timer: 2500});return;}

            let x, y, z;
            $.post('cari_api', {id: id,hes_oku: 316,_token: $('meta[name="csrf-token"]').attr('content')}, function (y) {
                console.table(y);y = JSON.parse(y);console.dir(y);z = JSON.parse(y[0].fatura_info);console.log(z);


                $.post('cari_api', {gr_oku: 316, _token: $('meta[name="csrf-token"]').attr('content')}, function (x) {
                    $.post('cari_api', {kur_oku: 316,_token: $('meta[name="csrf-token"]').attr('content')}, function (w) {
                        swal.fire({
                            html: "Cari Hesap Düzenleme <div style='text-align: left; padding-top: 10px'>" +
                                "<div class='form-floating'><select style='width: 100%' class='form-control' id='gr'><option>Grup Seçin</option>" + x + "</select><label>Grup Seçin</label></div>" +
                                // "<div class='form-floating'><input value='" + y[0].user_id + "' style='width: 100%' placeholder='User ID' id='uid' class='form-control border' type='text'><label>User ID</label></div>" +
                                "<div class='form-floating'><input value='" + y[0].unvan + "' style='width: 100%' placeholder='Ünvan' id='had' class='form-control border' type='text'><label>Ünvan</label></div>" +
                                "<div class='form-floating'><select style='width: 100%' class='form-control' id='kur'><option value='sec'>Kur Seçin</option>" + w + "</select><label>Kur</label></div>" +
                                "<div class='form-floating'><input value='" + z.VD + "' style='width: 100%' placeholder='Vergi Daire' id='vd' class='form-control border' type='text'><label>Vergi Daire</label></div>" +
                                "<div class='form-floating'><input value='" + z.VN + "' style='width: 100%' placeholder='Vergi No' id='vn' class='form-control border' type='text'><label>Vergi No</label></div>" +
                                "<div class='form-floating'><input value='" + z.ADRES + "' style='width: 100%' placeholder='Adres' id='adres' class='form-control border' type='text'><label>Adres</label></div>" +
                                "</div>",
                            showCancelButton: true,
                            confirmButtonText: 'Kayıt',
                            cancelButtonText: 'İptal',
                            reverseButtons: true,
                            allowOutsideClick: false,

                            preConfirm: () => {
                                setTimeout(function (){$('.swal2-validation-message').hide('slow');$('.swal2-cancel').removeAttr('disabled');$('.swal2-confirm').removeAttr('disabled');},2000);
                                if ($('#kur').val() == 'sec') {Swal.showValidationMessage('Hesap kuru seçilmedi!');return}
                            }

                        }).then((result) => {
                            if (result.value) {
                                $.post('{{route('cari_api')}}', {hes_dzn: 316,id: id,uid: $('#uid').val(),gr: $('#gr').val(),unvan: $('#had').val(),brim: $('#kur').val(),vd: $('#vd').val(),vn: $('#vn').val(),adres: $('#adres').val(),_token: $('meta[name="csrf-token"]').attr('content')}, function (x) {
                                    location.reload();
                                });
                            }

                        });
                    });
                    setTimeout(function () {$('#gr option[value=' + y[0].grup + ']').attr('selected', 'selected');}, 500);
                    setTimeout(function () {$('#kur option[value=' + y[0].para_birimi + ']').attr('selected', 'selected');}, 700);
                });
            });
        });

        $('.sil').click(function () {
            let id = $(this).attr('id');
            if($(this).attr('bak')>0) {swal.fire({icon: 'error', html: 'İşlem gören hesaplar Silinemez!', showConfirmButton: false, timer: 2500});return;}
            if(id==3) {swal.fire({icon: 'error', html: 'Komisyon Hesabı Silinemez!', showConfirmButton: false, timer: 2500});return;}

            swal.fire({
                title: "Seçilen kayıt silinecek",
                text: "",
                icon: "info",
                showCancelButton: true,
                confirmButtonText: 'Devam Et',
                cancelButtonText: 'İPTAL',
                reverseButtons: true,
                allowOutsideClick: false
            }).then((result) => {
                if (result.value) {
                    $.post('{{route('cari_api')}}', {
                        id: id,
                        hes_dzn: 316,
                        sil: 316,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    }, function (x) {
                        location.reload();
                    });
                } else if (
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swal.fire({icon:'error',html:'İşlemden Vazgeçildi',showConfirmButton: false,timer:1500});
                }
            });
        });

        $('.log').click(function (){
            let id = $(this).attr('id');
            window.open('/log.php?token=8b3907f569b42069ae2005de94624f74&id='+id,'logs');
        });

    </script>
@endsection
