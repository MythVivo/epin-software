@if(isset($_GET['silKod']))
    <?php
    DB::table('games_packages_codes')->where('id', $_GET['silKod'])->delete();
    ?>
@endif
@extends('back.layouts.app')
@section('css')
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/datatables/dataTables.bootstrap4.min.css')}}"
          rel="stylesheet" type="text/css"/>
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/datatables/buttons.bootstrap4.min.css')}}"
          rel="stylesheet" type="text/css"/>
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/dropify/css/dropify.min.css')}}"
          rel="stylesheet">
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/sweet-alert2/sweetalert2.min.css')}}"
          rel="stylesheet" type="text/css">
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/select2/select2.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <link type="text/css"
          href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/x-editable/css/bootstrap-editable.css')}}"
          rel="stylesheet">

    <style>
        table.dataTable.nowrap td:nth-child(2) {
            white-space: unset !important;
        }

        .form-control {
            padding: 0 !important;
        }
    </style>
@endsection
@section('body')

    @if(session('success'))
        <div class="row" data-lang="{{getLang()}}">
            <div class="col-12">
                <div class="alert icon-custom-alert alert-outline-success alert-success-shadow" role="alert">
                    <i class="mdi mdi-check-all alert-icon"></i>
                    <div class="alert-text">
                        <strong>{{__('admin.basarili')}}</strong> {{__('admin.basariliMetin')}}
                    </div>
                </div>
            </div>
        </div>
    @endif
    @if(session('error'))
        <div class="row" data-lang="{{getLang()}}">
            <div class="col-12">
                <div class="alert icon-custom-alert alert-outline-danger alert-danger-shadow" role="alert">
                    <i class="mdi mdi-crosshairs alert-icon"></i>
                    <div class="alert-text">
                        <strong>{{__('admin.hata-2')}}</strong>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="row" data-lang="{{getLang()}}">


        <div class="col-12 text-center pb-3">
            <form method="get">
                <div class="row">
                    <div class="col-8">
                        <div class="form-group">
                            <input placeholder="Başlıklar İçinde Ara" class="form-control style-input" name="q"
                                   @if(isset($_GET['q'])) value="{{$_GET['q']}} @endif">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <button class="btn btn-outline-success w-100">Ara</button>
                        </div>
                    </div>
                </div>


            </form>
        </div>

        <div class="col-12 pb-3">
            <div class="card">
                <div class="card-body oyun-toplu-paket">
                    <div class="row">
                        <div class="col-lg-12">
                            <form id="topluGuncelle" method="post" action="{{route('toplu_paket_edit')}}"
                                  enctype="multipart/form-data" autocomplete="off">
                                @csrf
                                {!! getLangInput() !!}
                            </form>
                                <div class="row" id="bilgiler">

                                    @if(isset($_GET['q']))
                                        @php $title = $_GET['q']; @endphp
                                    @else
                                        @php $title = ""; @endphp
                                    @endif
                                    <?php
                                    $sorgu = DB::table('games_packages')
                                        ->select('games_packages.*', 'games_titles.id as gameId', 'games_titles.title as gameTitle', 'games_titles.epin as epin')
                                        ->join('games_titles', 'games_packages.games_titles', '=', 'games_titles.id')
                                        ->join('games', 'games_titles.game', '=', 'games.id')
                                        ->orderBy('games_titles.id', 'asc')
                                        ->orderBy('games_packages.price', 'asc')
                                        ->where('games_titles.title', 'like', '%' . $title . '%')
                                        ->whereNull('games_titles.deleted_at')
                                        ->whereNull('games_packages.deleted_at')
                                        ->whereNull('games.deleted_at')
                                        ->paginate(20);
                                    ?>
                                    @foreach($sorgu as $pk)
                                        <div class="col-lg-3 col-md-3 col-12">
                                            <div class="form-group">
                                                @if($loop->iteration == 1)
                                                    <label for="111">Paket</label>
                                                @endif
                                                <input form="topluGuncelle" name="title" type="text" class="form-control" id="111"
                                                       value="{{$pk->title}}" readonly>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                @if($loop->iteration == 1)
                                                    <label for="222">Fiyat</label>
                                                @endif
                                                <input form="topluGuncelle" name="price_{{$pk->id}}" type="number" class="form-control"
                                                       id="222" step="0.00001"
                                                       value="{{$pk->price}}" required>
                                            </div>
                                        </div>
                                        <?php
                                        $discount_type = $pk->discount_type;
                                        $discount_amount = $pk->discount_amount;
                                        $discount_date = $pk->discount_date;
                                        ?>
                                        <div class="col">
                                            <div class="form-group">
                                                @if($loop->iteration == 1)
                                                    <label for="3">İndirim Tipi</label>
                                                @endif
                                                <select form="topluGuncelle" id="3" name="discount_type_{{$pk->id}}"
                                                        class="select2 form-control form-group">
                                                    <option value="0"
                                                            @if($discount_type == 0) selected @endif>@lang('admin.paketIndirimYok')</option>
                                                    <option value="1"
                                                            @if($discount_type == 1) selected @endif>@lang('admin.paketIndirimYuzde')</option>
                                                    <option value="2"
                                                            @if($discount_type == 2) selected @endif>@lang('admin.paketIndirimTutar')</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                @if($loop->iteration == 1)
                                                    <label for="4">@lang('admin.paketIndirimTutari')</label>
                                                @endif
                                                <input form="topluGuncelle" name="discount_amount_{{$pk->id}}" type="number" step="0.01"
                                                       class="form-control"
                                                       id="4"
                                                       placeholder="@lang('admin.paketIndirimTutari')"
                                                       value="{{$discount_amount}}">
                                            </div>
                                        </div>
                                        <?php
                                        if ($discount_date != NULL) {
                                            $date = \Carbon\Carbon::parse($discount_date);
                                            $date1 = $date->format('Y-m-d');
                                            $date2 = $date->format('H:i');
                                        } else {
                                            $date1 = "";
                                            $date2 = "";
                                        }
                                        ?>
                                        <div class="col">
                                            <div class="form-group">
                                                @if($loop->iteration == 1)
                                                    <label for="44">Son Tarihi</label>
                                                @endif
                                                <input form="topluGuncelle" name="discount_date_{{$pk->id}}" type="date" class="form-control"
                                                       id="44"
                                                       placeholder="İndirim Son Tarihi"
                                                       value="{{$date1}}">
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                @if($loop->iteration == 1)
                                                    <label for="55">Son Saati</label>
                                                @endif
                                                <input form="topluGuncelle" name="discount_date_time_{{$pk->id}}" type="time"
                                                       class="form-control" id="55"
                                                       placeholder="İndirim Son Saati"
                                                       value="{{$date2}}">
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                @if($loop->iteration == 1)
                                                    <label for="66">Kod Ekle</label>
                                                    <br>
                                                @endif
                                                    @if(sayfaIzinKontrol(0))
                                                        <?php /*
                                                        <button type="button" data-toggle="modal" data-target=".kodGoruntule{{$pk->id}}"
                                                                class="btn btn-outline-orange btn-md float-right"><i class="fa fa-eye"></i></button> */ ?>
                                                        <button type="button" onclick="window.open('{{route('oyun_paket_kod_view', $pk->id)}}')" class="btn btn-outline-orange btn-sm float-right"><i class="fa fa-eye"></i></button>

                                                        <?php  /*
                                                        <div class="modal fade kodGoruntule{{$pk->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                                                            <div class="modal-dialog modal-xl">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title mt-0" id="myModalLabel">@lang('admin.kodGoruntule')</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="row">
                                                                            <div class="col-sm-12">
                                                                                <div class="form-group">
                                                                                    <label for="1">{{$pk->title}} -
                                                                                        ₺{{findGamesPackagesPrice($pk->id)}}</label>
                                                                                    <hr>
                                                                                    @foreach(DB::table('games_packages_codes')->where('package_id', $pk->id)->get() as $uuu)
                                                                                        <p>
                                                                                            {{$uuu->code}}
                                                                                            - @if($uuu->is_used == 1) @lang('admin.kullanildi') @else @lang('admin.kullanilmadi') @endif
                                                                                            <button class="btn btn-outline-danger btn-sm"
                                                                                                    onclick="location.href='?silKod={{$uuu->id}}'">Kodu Sil
                                                                                            </button>
                                                                                        </p>
                                                                                    @endforeach
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-outline-secondary waves-effect"
                                                                                data-dismiss="modal">@lang('admin.kapat')</button>
                                                                    </div>
                                                                </div><!-- /.modal-content -->
                                                            </div><!-- /.modal-dialog -->
                                                        </div><!-- /.modal -->*/ ?>
                                                    @endif
                                                    <button type="button" data-toggle="modal" data-target=".kodEkle{{$pk->id}}"
                                                            class="btn btn-outline-beanred"><i class="fa fa-plus"></i></button>
                                                    <div class="modal fade kodEkle{{$pk->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                                                        <div class="modal-dialog modal-xl">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title mt-0" id="myModalLabel">@lang('admin.kodEkle')</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X
                                                                    </button>
                                                                </div>
                                                                <form method="post" action="{{route('oyun_paket_kod_add')}}"
                                                                      enctype="multipart/form-data">
                                                                    @csrf
                                                                    <div class="modal-body">
                                                                        <div class="row">
                                                                            <div class="col-sm-12">
                                                                                <div class="form-group">
                                                                                    <label for="1">{{$pk->title}} -
                                                                                        ₺{{findGamesPackagesPrice($pk->id)}}</label>
                                                                                    <textarea rows="7" name="code" type="text" class="form-control"
                                                                                              id="1"></textarea>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-4">
                                                                                <div class="form-group">
                                                                                    <label for="2">Alış Fiyatı</label>
                                                                                    <input name="alis_fiyati" type="number" step="0.01" class="form-control" id="2">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-4">
                                                                                <div class="form-group">
                                                                                    <label for="3">Kdv Tutarı</label>
                                                                                    <input name="kdv" type="number" step="0.01" class="form-control" id="3">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-4">
                                                                                <div class="form-group">
                                                                                    <label for="3">Tedarikçi</label>
                                                                                    <select name="tedarikci" class="form-control">
                                                                                        <option value="0">Belirsiz</option>
                                                                                        @foreach(DB::table('games_packages_codes_suppliers')->whereNull('deleted_at')->get() as $uuu)
                                                                                            <option value="{{$uuu->id}}">{{$uuu->title}}</option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-outline-secondary waves-effect"
                                                                                data-dismiss="modal">@lang('admin.kapat')</button>
                                                                        <input type="hidden" name="games_titles_package" value="{{$pk->id}}">
                                                                        <button type="submit"
                                                                                class="btn btn-outline-success waves-effect waves-light">@lang('admin.kaydet')</button>
                                                                    </div>
                                                                </form>
                                                            </div><!-- /.modal-content -->
                                                        </div><!-- /.modal-dialog -->
                                                    </div><!-- /.modal -->
                                            </div>
                                        </div>

                                        <div class="col-12"></div>

                                    @endforeach
                                    <div class="col-md-12 text-center">
                                        <nav aria-label="Page navigation example">
                                            <ul class="pagination justify-content-center">
                                                <li class="page-item">
                                                    <a class="page-link" href=?page=1">{{"|<<"}}</a>
                                                </li>
                                                <?php
                                                $prevPage = $sorgu->currentPage() - 1;
                                                if ($prevPage < '1') {
                                                    $prevPage = '1';
                                                }
                                                ?>
                                                <li class="page-item">
                                                    <a class="page-link"
                                                       href="?page={{$prevPage}}">{{"<"}}</a>
                                                </li>
                                                @for($i = 1; $i < $sorgu->lastPage()+1; $i++)
                                                    <li class="page-item @if($sorgu->currentPage() == $i) active @endif">
                                                        <a class="page-link" href="?page={{$i}}">{{$i}}</a></li>
                                                @endfor
                                                <?php
                                                $nextPage = $sorgu->currentPage() + 1;
                                                if ($nextPage > $sorgu->lastPage()) {
                                                    $nextPage = $sorgu->lastPage();
                                                }
                                                ?>
                                                <li class="page-item">
                                                    <a class="page-link"
                                                       href="?page={{$nextPage}}">{{">"}}</a>
                                                </li>
                                                <li class="page-item">
                                                    <a class="page-link"
                                                       href="?page={{$sorgu->lastPage()}}">{{">>|"}}</a>
                                                </li>
                                            </ul>
                                        </nav>
                                    </div>

                                    <?php /*
                                    <div class="col-12">
                                        <div class="spinner-grow text-primary" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                        <div class="spinner-grow text-secondary" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                        <div class="spinner-grow text-success" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                        <div class="spinner-grow text-danger" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                        <div class="spinner-grow text-warning" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                        <div class="spinner-grow text-info" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                        <div class="spinner-grow text-light" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                        <div class="spinner-grow text-dark" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                        Yükleniyor... Lütfen bekleyiniz
                                    </div>
                                    */ ?>
                                </div>

                                <button type="submit" form="topluGuncelle"
                                        class="btn btn-outline-success waves-effect waves-light">@lang('admin.kaydet')</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        /*
         * Bilgiler Ajax

        $.ajax({
            type: 'GET', url: "{{route('panelEpinler')}}",
        success: function (data) {
            $("#bilgiler").html(data);
        }
    });
    */
    </script>
@endsection

