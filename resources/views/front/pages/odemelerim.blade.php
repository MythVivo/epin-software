@extends('front.layouts.app')
@section('css')
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/datatables/dataTables.bootstrap4.min.css')}}"
          rel="stylesheet" type="text/css"/>
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/datatables/buttons.bootstrap4.min.css')}}"
          rel="stylesheet" type="text/css"/>
@endsection
@section('body')
    <section class="bg-gray pb-40">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    @if(session('success'))
                        <div class="alert alert-success" role="alert">
                            <h4 class="alert-heading">{{session('success')}}</h4>
                        </div>
                    @endif
                        @if(session('error'))
                            <div class="alert alert-danger" role="alert">
                                <h4 class="alert-heading">{{session('error')}}</h4>
                            </div>
                        @endif
                </div>
                @include('front.modules.user-menu')
                <div class="col-md-9">
                    <div class="row">
                        <div class="col-12">
                        <?php
                        $odemeler = DB::table('odemeler')->where('user', Auth::user()->id)->whereNull('deleted_at')->orderBy('created_at');
                        ?>
                        @if($odemeler->count() > 0)

                            <table id="datatable" class="table table-sm table-hover table-striped ">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Ödeme Tutarı</th>
                                    <th>İşlem Türü</th>
                                    <th>İşlem Durumu</th>
                                    <th>Tarih</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($odemeler->get() as $u)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td style="text-align: center">{{$u->amount}}</td>
                                        <td>{{findPaymentChannel($u->channel)}}</td>
                                        <td>{!! findPaymentStatus($u->status) !!}
                                            @if($u->channel=='17')
                                                <br> <small>({{$u->description}})</small>
                                            @endif
                                            @if(strpos($u->description,'~')!==false)
                                                <br> <small>({{$u->description}})</small>
                                            @endif </td>
                                        </td>


                                        <td>{{$u->created_at}}</td>
                                    </tr>
                                @endforeach
                                </tbody>

                            </table>

                        @else

                            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center"
                                 role="alert">
                                <i class="fas fa-exclamation-triangle me-3"></i>
                                <h5>
                                    Henüz bir ödemeniz bulunmuyor. İlk ödemenizi yaptıktan sonra bu ekrandan ödemeniz
                                    hakkında bilgi alabilirsiniz.
                                </h5>

                                <button type="button" class="btn-inline color-blue border"
                                        onclick="location.href='{{route('bakiye_ekle')}}'">
                                    Bakiye Ekle
                                </button>
                            </div>

                        @endif
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </section>
@endsection
@section('js')
    <script
            src="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script
            src="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            $('#datatable').DataTable({
                columnDefs: [{orderable: false, targets: [3]}],
                pageLength: 10,
                "paging": false,
                "order": [[4, "desc"]],
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
        });
    </script>
@endsection
