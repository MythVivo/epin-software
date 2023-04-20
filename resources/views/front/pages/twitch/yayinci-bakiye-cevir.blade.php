@extends('front.layouts.app')

@section('css')

    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/datatables/dataTables.bootstrap4.min.css')}}"

          rel="stylesheet" type="text/css"/>

    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/datatables/buttons.bootstrap4.min.css')}}"

          rel="stylesheet" type="text/css"/>

    <style>

        .modal {

            z-index:pb-40

        }

    </style>

@endsection

@section('body')

    <?php

    $yayinci = DB::table('twitch_support_streamer')->where('user', Auth::user()->id)->first();

    ?>

    <section class="pt-40 pb-40">

        <div class="container">



            <div class="row">

                @include('front.modules.user-menu')

                <div class="col-md-9">

                    <div class="row">

                    @if(session('success'))

                        <!--Mesaj bildirimi--->

                            <div class="alert alert-success d-flex align-items-center" role="alert">

                                <i class="fas fa-check me-2"></i>

                                <div>{{session('success')}}</div>

                            </div>

                            <!--Mesaj bildirim END--->

                    @endif

                    @if(session('error'))

                        <!--Mesaj bildirimi--->

                            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center"

                                 role="alert">

                                <i class="fas fa-exclamation-triangle me-3"></i>

                                <h5>{{session('error')}}</h5>

                            </div>

                            <!--Mesaj bildirim END--->

                        @endif

                    </div>





                    <div class="row g-3">



                        <div class="col-md-12">

                            <div class="card border-radius-20 mb-3">

                                <div class="card-body">

                                    <h5 class="card-title mb-0">Biriken Bağış Bakiyeniz

                                        : {{Auth::user()->bagis_bakiye}}

                                        TL

                                    </h5>

                                </div>

                            </div>

                        </div>





                        <div class="col-sm-12">



                            <form class="needs-validation"

                                  action="{{route('twitch_support_yayinci_bakiye_cevir_post')}}"

                                  method="POST"

                                  autocomplete="off" novalidate>

                                <?php

                                if (DB::table('twitch_kesintisiz_yayinci')->where('streamer', Auth::user()->id)->count() > 0) {

                                    if (DB::table('twitch_kesintisiz_yayinci')->where('streamer', Auth::user()->id)->first()->status == 1) {

                                        $kesinti = 0;

                                    } else {

                                        $kesinti = DB::table('settings')->first()->yayin_komisyon;

                                    }

                                } else {

                                    $kesinti = DB::table('settings')->first()->yayin_komisyon;

                                }



                                ?>

                                @csrf

                                <div class="row">

                                    @if(DB::table('twitch_kesintisiz_yayinci')->where('streamer', Auth::user()->id)->count() > 0)

                                        @if(DB::table('twitch_kesintisiz_yayinci')->where('streamer', Auth::user()->id)->first()->status == 1)

                                            <div class="col-12 mb-4">

                                                <div class="alert alert-success fade show"

                                                     role="alert">

                                                    <h6>

                                                        Kesintisiz yayıncı programımız ile artık yapacağınız hiç bir çekimde kesinti uygulanmaz!

                                                    </h6>

                                                </div>

                                            </div>

                                        @endif

                                    @else

                                        <div class="col-12 mb-5">

                                            <button type="button"

                                                    onclick="location.href='{{route('twitch_support_yayinci_kesintisiz')}}'"

                                                    class="btn-inline color-blue w-100">Kesintisiz Yayıncı Ol

                                            </button>

                                        </div>

                                    @endif

                                    <div class="col-md-4">

                                        <label for="1" class="form-label">Çevrilecek Bakiye Tutarı</label>

                                        <input onkeyup="hesaplaGec(this.value)" type="number" name="amount"

                                               class="form-control" id="1"

                                               min="{{DB::table('settings')->first()->yayin_min}}" required>

                                        <small class="text-danger">Minimum

                                            dönüştürme {{DB::table('settings')->first()->yayin_min}} TL</small>

                                        <div class="invalid-feedback">

                                            Lütfen minimum {{DB::table('settings')->first()->yayin_min}} TL tutarında

                                            bir değer girin.

                                        </div>

                                    </div>



                                    <div class="col-md-4">

                                        <label for="2" class="form-label">Banka Hesabınıza <span id="hesapla">0</span>

                                            TL

                                            geçecek. </label>

                                        @if(DB::table('odeme_kanallari')->where('user', Auth::user()->id)->whereNull('deleted_at')->count() > 0)

                                            <button id="2" type="button" data-bs-toggle="modal"

                                                    data-bs-target="#odemeTalebi"

                                                    class="btn btn-outline-success w-100" name="site_bakiye"

                                                    value="2">{{$kesinti}}

                                                TL kesinti ile para çek

                                            </button>

                                            <div class="modal fade" id="odemeTalebi" tabindex="-1"

                                                 aria-labelledby="exampleModalLabel"

                                                 aria-hidden="true">

                                                <div class="modal-dialog modal-dialog-centered modal-lg">

                                                    <div class="modal-content">

                                                        <div class="modal-header">

                                                            <h5 class="modal-title" id="exampleModalLabel">

                                                                Yeni Ödeme Talebi

                                                            </h5>

                                                            <button type="button" class="btn-close"

                                                                    data-bs-dismiss="modal"

                                                                    aria-label="Close"></button>

                                                        </div>

                                                        <div class="modal-body">

                                                            <div class="row">

                                                                <div class="col-md-12">

                                                                    <label class="form-label" for="2">Ödeme

                                                                        Kanalı</label>

                                                                    <select id="2" class="form-control"

                                                                            name="odeme_kanali"

                                                                            required>

                                                                        <option disabled>Lütfen Ödeme Kanalı

                                                                            Seçin

                                                                        </option>

                                                                        @foreach(DB::table('odeme_kanallari')->where('user', Auth::user()->id)->whereNull('deleted_at')->get() as $u)

                                                                            <option value="{{$u->id}}">{{$u->title}}</option>

                                                                        @endforeach

                                                                    </select>

                                                                </div>

                                                            </div>

                                                        </div>

                                                        <div class="modal-footer">

                                                            <button type="button" class="btn btn-secondary"

                                                                    data-bs-dismiss="modal">Kapat

                                                            </button>

                                                            <button type="submit" class="btn btn-outline-success"

                                                                    name="site_bakiye"

                                                                    value="2">Çek

                                                            </button>

                                                        </div>

                                                    </div>

                                                </div>

                                            </div>

                                        @else

                                            <button style="cursor: not-allowed" id="2" type="button"

                                                    class="btn-inline color-red w-100" name="site_bakiye"

                                                    value="2">Banka çekimi için lütfen ödeme kanalı ekleyin

                                            </button>

                                        @endif





                                    </div>

                                    <div class="col-md-4">

                                        <label for="3" class="form-label">Bakiyenize <span id="hesapla2">0</span> TL

                                            geçecek. </label>

                                        <button id="3" class="btn-inline color-darkgreen w-100" name="site_bakiye"

                                                value="1">Site Bakiyesine Çevir

                                        </button>

                                    </div>

                                </div>

                            </form>





                        </div>

                    </div>



                    <div class="row g-3 mt-3">

                        <?php

                        $cevirmeler = DB::table('twitch_support_cevirmeler')->where('user', Auth::user()->id)->whereNull('deleted_at')->orderBy('created_at', 'desc');

                        ?>

                        @if($cevirmeler->count() > 0)

                            <table id="datatable" class="table table-hover table-striped ">

                                <thead>

                                <tr>

                                    <th>#</th>

                                    <th>Çevirme Tutarı</th>

                                    <th>Kesinti</th>

                                    <th>Tür</th>

                                    <th>Durum</th>

                                    <th>Tarih</th>

                                </tr>

                                </thead>

                                <tbody>

                                @foreach($cevirmeler->get() as $u)

                                    <tr>

                                        <td>{{$loop->iteration}}</td>

                                        <td>{{$u->amount}} TL</td>

                                        <td>{{$u->kesinti}} TL</td>

                                        <td>

                                            @if($u->tur == 1)

                                                Site Bakiyesine Çevirme

                                            @else

                                                Para Çekim Talebi

                                                ({{DB::table('odeme_kanallari')->where('id', $u->odeme_kanali)->first()->title}}

                                                )

                                            @endif

                                        </td>

                                        <td>

                                            @if($u->status  == 0)

                                                Onay aşamasında

                                            @elseif($u->status == 1)

                                                Onaylandı

                                            @else

                                                Reddedildi

                                            @endif

                                        </td>

                                        <td>{{$u->created_at}}</td>

                                    </tr>

                                @endforeach

                                </tbody>

                                <tfoot>

                                <tr>

                                    <th>#</th>

                                    <th>Çevirme Tutarı</th>

                                    <th>Kesinti</th>

                                    <th>Tür</th>

                                    <th>Durum</th>

                                    <th>Tarih</th>

                                </tr>

                                </tfoot>



                            </table>



                        @else

                            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center"

                                 role="alert">

                                <i class="fas fa-exclamation-triangle me-3"></i>

                                <h5>

                                    Henüz bir çevirme işlemi gerçekleştirmemişsiniz. İlk çevirme işleminiz ardından

                                    burada önceki yaptığınız çevirmeler görünecektir.

                                </h5>

                            </div>

                        @endif



                    </div>



                </div>





            </div>

        </div>

    </section>

@endsection

@section('js')

    <script>

        function hesaplaGec(tutar) {

            @if(DB::table('twitch_kesintisiz_yayinci')->where('streamer', Auth::user()->id)->count() > 0)

                @if(DB::table('twitch_kesintisiz_yayinci')->where('streamer', Auth::user()->id)->first()->status == 1)

                    kesinti = 0;

                @else

                    kesinti = {{DB::table('settings')->first()->yayin_komisyon}};

                @endif

            @else

                kesinti = {{DB::table('settings')->first()->yayin_komisyon}};

            @endif

            if (tutar - kesinti < 0) {

                $("#hesapla").html("0");

                $("#hesapla2").html("0");

            } else {

                $("#hesapla").html(tutar - kesinti);

                $("#hesapla2").html(tutar);

            }



        }



        (function () {

            'use strict'

            var forms = document.querySelectorAll('.needs-validation')

            Array.prototype.slice.call(forms)

                .forEach(function (form) {

                    form.addEventListener('submit', function (event) {

                        if (!form.checkValidity()) {

                            event.preventDefault()

                            event.stopPropagation()

                        }

                        form.classList.add('was-validated')

                    }, false)

                })

        })()

    </script>

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