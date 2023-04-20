<? error_reporting(E_PARSE); ?>
@extends('back.layouts.app')
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
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
                    @include('back.pages.cari.menu')
                    <button class="btn btn-sm ekle btn-success">Yeni Kur</button>
                    <div class="table-responsive">

                        <table lang="{{getLang()}}" id="datatable" class="font-12 table-sm table-bordered table-hover nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead><tr style="text-align: center"><th>Id</th><th>Ad</th><th>Sembol</th><th>Oran</th><th>Güncelleme</th><th>Dzn</th></tr></thead>
                            <tbody>


                            @php
                                $cg=DB::table('cariy_kurlar')->whereNull("deleted_at")->get();
                            @endphp
                            @foreach($cg as $u )
                                <tr>
                                    <td style="text-align: center">{{$u->id}}</td>
                                    <td style="text-align: center">{{$u->ad}}</td>
                                    <td style="text-align: center">{{$u->kur}}</td>
                                    <td style="text-align: center">{{$u->oran}}</td>
                                    <td style="text-align: center">{{$u->created_at}}</td>
                                    <td style="text-align: center">@if($u->id!=1)<i title="Sil" id="{{$u->id}}" class="btn far fa-trash-alt sil"></i><i title="Düzenle" id="{{$u->id}}" class="btn far fa-edit dzn"></i>@endif</td>
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


        $('.ekle').click(function(){

            swal.fire({
                html: "<br>Kur Tanımlama<br>" +
                      "<div class='form-floating'><input id='ad' placeholder='Kur Sembol' class='form-control border' type='text'><label>Kur Cinsi</label></div>" +
                      "<div class='form-floating'><input id='sem' placeholder='Kur Adı' class='form-control border' type='text'><label>Sembol</label></div>" +
                      "<div class='form-floating'><input id='oran' placeholder='Oran'    class='form-control border' type='text'><label>Oran</label></div>",
                showCancelButton: true,
                confirmButtonText: 'Kayıt',
                cancelButtonText: 'İptal',
                reverseButtons: true,
                allowOutsideClick: false
            }).then((result) => {
                if (result.value) {
                    $.post('{{route('cari_api')}}', {ad:$('#ad').val(), sem:$('#sem').val() ,oran:$('#oran').val(), kur_ekle:316, _token:$('meta[name="csrf-token"]').attr('content')}, function (x) {location.reload();});
                }
            });

        });


        $('.dzn').click(function(){
            let id=$(this).attr('id');

            $.post('cari_api', {id:id, kur_oku:316, _token:$('meta[name="csrf-token"]').attr('content')}, function (x) { let y=JSON.parse(x);
                swal.fire({
                    html: "<br>Kur Düzenleme<br>" +
                        "<div class='form-floating'><input id='ad' value='"+y[0].ad+"' placeholder='Kur Sebmol' class='form-control border' type='text'> <label>Kur Cinsi</label></div>" +
                        "<div class='form-floating'><input id='sem' value='"+y[0].kur+"' placeholder='Kur Adı' class='form-control border' type='text'><label>Sembol</label></div>" +
                        "<div class='form-floating'><input id='oran' value='"+y[0].oran+"' placeholder='Oran' class='form-control border' type='text'>   <label>Oran</label></div>",
                    showCancelButton: true,
                    confirmButtonText: 'Kayıt',
                    cancelButtonText: 'İptal',
                    reverseButtons: true,
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.value) {
                        $.post('{{route('cari_api')}}', {id:id, oran:$('#oran').val(), sem:$('#sem').val(), kur_dzn:316, ad:$('#ad').val(), _token:$('meta[name="csrf-token"]').attr('content')}, function (x) {location.reload();});
                    }
                });
            });
        });


        $('.sil').click(function(){
            let id=$(this).attr('id');

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
                    $.post('{{route('cari_api')}}', {id:id , kur_dzn:316, sil:316, _token: $('meta[name="csrf-token"]').attr('content')}, function (x) { location.reload();});
                } else if (
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swal.fire('İşlem İptal Edildi','','error');
                }
            });
        });


    </script>
@endsection
