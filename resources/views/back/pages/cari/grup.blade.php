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
                    <button class="btn btn-sm ekle btn-success">Yeni Grup</button>
                    <div class="table-responsive">
                                                <table lang="{{getLang()}}" id="datatable" class="font-12 table-sm table-bordered table-hover nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                                    <thead><tr style="text-align: center"><th>Id</th><th>Grup Adı</th><th>Gruptaki Öge</th><th>Dzn</th></tr></thead>
                                                    <tbody>
                                                    <?
                                                        $cg=DB::select("select cg.* , (SELECT count(*) FROM `cariy_hesaplar` where grup=cg.id) top
                                                                        from cariy_grup cg
                                                                        where cg.deleted_at is null
                                                                        ");
                                                    ?>
                                                    @foreach($cg as $u )
                                                        <tr>
                                                            <td style="text-align: center">{{$u->id}}</td>
                                                            <td style="text-align: center">{{$u->ad}}</td>
                                                            <td style="text-align: center">{{$u->top}}</td>
                                                            <td style="text-align: center"><i title="Sil" id="{{$u->id}}" oge="{{$u->top}}" class="btn far fa-trash-alt sil"></i><i title="Düzenle" id="{{$u->id}}" class="btn far fa-edit dzn"></i></td>
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
                    html: "<br><br><div class='form-floating'><input id='ad'   class='form-control border' placeholder=' ' type='text'><label>Grup Adı</label></div>",

                    showCancelButton: true,
                    confirmButtonText: 'Kayıt',
                    cancelButtonText: 'İptal',
                    reverseButtons: true,
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.value) {
                        $.post('{{route('cari_api')}}', {ad:$('#ad').val(), gr_ekle:316, _token:$('meta[name="csrf-token"]').attr('content')}, function (x) {location.reload();});
                    }
                });

        });



        $('.dzn').click(function(){
            let id=$(this).attr('id');

            $.post('cari_api', {id:id, gr_oku:316, _token:$('meta[name="csrf-token"]').attr('content')}, function (x) {
                swal.fire({
                    html: "<br><br><div class='form-floating'><input id='ad' value='"+x+"' class='form-control border' placeholder=' ' type='text'><label>Grup Adı</label></div>",

                    showCancelButton: true,
                    confirmButtonText: 'Kayıt',
                    cancelButtonText: 'İptal',
                    reverseButtons: true,
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.value) {
                        $.post('{{route('cari_api')}}', {id:id, gr_dzn:316, ad:$('#ad').val(), _token:$('meta[name="csrf-token"]').attr('content')}, function (x) {location.reload();});
                    }
                });
            });
        });


        $('.sil').click(function(){
            let id=$(this).attr('id');
            if(parseInt($(this).attr('oge'))>0) {swal.fire({icon:'error',html:'Grup altında tanımlı hesaplar varken silemezsiniz.'});return;}

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
                    $.post('{{route('cari_api')}}', {id:id , gr_sil:316, _token: $('meta[name="csrf-token"]').attr('content')}, function (x) { location.reload();});
                } else if (
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swal.fire('İşlem İptal Edildi','','error');
                }
            });
        });

    </script>
@endsection
