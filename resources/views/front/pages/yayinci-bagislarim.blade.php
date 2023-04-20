@extends('front.layouts.app')

@section('css')

    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/datatables/dataTables.bootstrap4.min.css')}}"

          rel="stylesheet" type="text/css"/>

    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/datatables/buttons.bootstrap4.min.css')}}"

          rel="stylesheet" type="text/css"/>

    <style>
pb-40
        .modal {

            z-index: 9999;

        }

    </style>

@endsection

@section('body')

    <section class="bg-gray pt-40 pb-40">

        <div class="container">





            <div class="row">

                @include('front.modules.user-menu')

                <div class="col-md-9">

                    <form method="get">

                        <div class="row mb-3">



                            <div class="col-sm-12 col-md-5">

                                <div class="mb-3 row">

                                    <label class="col-sm-3 col-form-label" for="userinput1">İlk Tarih</label>

                                    <div class="col-sm-9">

                                        <input type="date" id="userinput1" class="form-control border-radius-20"

                                               name="date1"

                                               value="{{date('Y-m-d', strtotime('-5 days'))}}" required>

                                    </div>

                                </div>

                            </div>

                            <div class="col-sm-12 col-md-5">

                                <div class="mb-3 row">

                                    <label class="col-sm-3 col-form-label" for="userinput2">Son Tarih</label>

                                    <div class="col-sm-9">

                                        <input type="date" id="userinput2" class="form-control border-radius-20"

                                               name="date2"

                                               value="{{date('Y-m-d')}}" required>

                                    </div>

                                </div>

                            </div>

                            <div class="col-sm-12 col-md-2">

                                <button type="submit" class="btn-inline color-blue w-100">Sorgula

                                </button>

                            </div>



                        </div>

                    </form>

                    <div class="row">

                        @if(session('success'))

                            <div class="alert alert-success d-flex align-items-center" role="alert">

                                <i class="fas fa-check me-2"></i>

                                <div>{{session('success')}}</div>

                            </div>

                        @endif

                        @if(session('error'))

                            <div class="alert alert-error d-flex align-items-center" role="alert">

                                <i class="fas fa-exclamation-triangle me-2"></i>

                                <div>{{session('error')}}</div>

                            </div>

                        @endif

                    </div>





                    <div class="row">

                        @if(isset($_GET['date1']) and isset($_GET['date2']))

                            <?php

                            $date1 = $_GET['date1'];

                            $date2 = $_GET['date2'];

                            ?>

                        @else

                            <?php

                            $date1 = date('Y-m-d', strtotime('-5 days'));

                            $date2 = date('Y-m-d');

                            ?>

                        @endif

                        <?php

                        $streamer = DB::table('twitch_support_donates')->whereDate('created_at', '>=', $date1)->whereDate('created_at', '<=', $date2)->where('user', Auth::user()->id)->whereNull('deleted_at')->orderBy('created_at', 'desc');

                        ?>

                        @if($streamer->count() > 0)

                            <table id="datatable" class="table table-hover table-striped ">

                                <thead>

                                <tr>

                                    <th>#</th>

                                    <th>Yayıncı</th>

                                    <th>Gönderim İsmi</th>

                                    <th>Açıklama</th>

                                    <th>Tutar</th>

                                    <th>Tarih</th>

                                </tr>

                                </thead>

                                <tbody>

                                @foreach($streamer->get() as $u)

                                    <tr>

                                        <td>{{$loop->iteration}}</td>

                                        <td>{{DB::table('twitch_support_streamer')->where('id', $u->streamer)->first()->title}}</td>

                                        <td>{{$u->title}}</td>

                                        <td>{{$u->text}}</td>

                                        <td>{{$u->amount}} TL</td>

                                        <td>{{$u->created_at}}</td>

                                    </tr>

                                @endforeach

                                </tbody>

                                <tfoot>

                                <tr>

                                    <th>#</th>

                                    <th>Yayıncı</th>

                                    <th>Gönderim İsmi</th>

                                    <th>Açıklama</th>

                                    <th>Tutar</th>

                                    <th>Tarih</th>

                                </tr>

                                </tfoot>



                            </table>



                        @else

                            <div class="col-12">

                                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center"

                                     role="alert">

                                    <i class="fas fa-exclamation-triangle me-3"></i>

                                    <h5>

                                        Henüz bir bağış göndermemişsiniz. İlk bağışınızdan sonra burada bağışlarınız

                                        gözükecektir.

                                    </h5>

                                </div>

                            </div>



                        @endif



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

                pageLength: 10,

                "order": [[5, "desc"]],

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



            $('#datatable2').DataTable({

                pageLength: 10,

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

        });

    </script>

@endsection