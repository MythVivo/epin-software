<?  error_reporting(E_PARSE); ?>
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
                    <button class="btn btn-sm ekle btn-success">Yeni Gider Türü</button>
                    <div class="table-responsive">
                                                <table lang="{{getLang()}}" id="datatable" class="font-12 table-sm table-bordered table-hover nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                                    <thead><tr style="text-align: center"><th>Id</th><th>Gider Türü</th><th>Dzn</th></tr></thead>
                                                    <tbody>
                                                    <?
                                                        $cg=DB::table('cariy_tur')->whereNull("deleted_at")->get();
                                                    ?>
                                                    @foreach($cg as $u )
                                                        <tr>
                                                            <td style="text-align: center">{{$u->id}}</td>
                                                            <td style="text-align: center">{{$u->ad}}</td>
                                                            <td style="text-align: center"><i title="Sil" id="{{$u->id}}" bak="{{count(DB::select(" select turu from cariy_fisler where deleted_at is null and turu='$u->id' limit 1"))}}" class="btn far fa-trash-alt sil"></i></td>
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
                    html: "<br><br><div class='form-floating'><input id='ad'   class='form-control border' placeholder=' ' type='text'><label>Gider Türü</label></div>",

                    showCancelButton: true,
                    confirmButtonText: 'Kayıt',
                    cancelButtonText: 'İptal',
                    reverseButtons: true,
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.value) {
                        $.post('{{route('cari_api')}}', {ad:$('#ad').val(), tur_ekle:316, _token:$('meta[name="csrf-token"]').attr('content')}, function (x) {location.reload();});
                    }
                });

        });


        $('.sil').click(function(){
            let id=$(this).attr('id');
            if($(this).attr('bak')==1){swal.fire({icon:'error',html:'Fişler içerisinde bu açıklama kullanılmış. Önce ilgili fişi düzenleyin ',showConfirmButton: false,timer:1500}); return;}

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
                    $.post('{{route('cari_api')}}', {id:id , tur_sil:316, _token: $('meta[name="csrf-token"]').attr('content')}, function (x) { location.reload();});
                } else if (
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swal.fire('İşlem İptal Edildi','','error');
                }
            });

        });

    </script>
@endsection
