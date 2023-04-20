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
                            <input placeholder="Oyun Paraları İçinde Ara" class="form-control style-input" name="q"
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
                            <form id="topluGuncelle" method="post" action="{{route('toplu_oyun_parasi_edit')}}"
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
                                    $sorgu = DB::table('games_packages_trade')
                                        ->select('games_packages_trade.*', 'games_titles.id as gameId', 'games_titles.title as gameTitle', 'games_titles.epin as epin')
                                        ->join('games_titles', 'games_packages_trade.games_titles', '=', 'games_titles.id')
                                        ->join('games', 'games_titles.game', '=', 'games.id')
                                        ->orderBy('games_titles.id', 'asc')
                                        ->orderBy('games_packages_trade.sira', 'asc')
                                        ->where('games_titles.title', 'like', '%' . $title . '%')
                                        ->whereNull('games_titles.deleted_at')
                                        ->whereNull('games_packages_trade.deleted_at')
                                        ->whereNull('games.deleted_at')
                                        ->paginate(50);
                                    ?>
                                    @foreach($sorgu as $uu)
                                        <div class="col-lg-3 col-md-3 col-12">
                                            <div class="form-group">
                                                @if($loop->iteration == 1)
                                                    <label for="111">Paket</label>
                                                @endif
                                                <input form="topluGuncelle" name="title" type="text" class="form-control" id="111"
                                                       value="{{$uu->title}}" readonly>
                                            </div>
                                        </div>
                                            <?php
                                            $toplamSatislar = DB::table('game_gold_satis')->where('paket', $uu->id)->where('tur', 'bizden-al')->whereNull('deleted_at')->where('status', '1');
                                            $toplamSatisSayi = $toplamSatislar->count();
                                            $toplamSatisAdet = $toplamSatislar->sum('adet');
                                            $toplamSatisTutar = $toplamSatislar->sum('price');
                                            if ($toplamSatisSayi > 0) {
                                                $toplamSatis = substr($toplamSatisTutar / $toplamSatisAdet, 0, 6);
                                            } else {
                                                $toplamSatis = findGamesPackagesTradeMusteriyeSatPrice($uu->id);
                                            }
                                            ?>
                                        <div class="col">
                                            <div class="form-group">
                                                @if($loop->iteration == 1)
                                                    <label for="222">Genel Stok</label>
                                                @endif
                                                <input form="topluGuncelle" type="number" class="form-control"
                                                       id="222" step="0.00001"
                                                       value="{{$uu->stok}}" readonly>
                                            </div>
                                        </div>
                                            <div class="col">
                                                <div class="form-group">
                                                    @if($loop->iteration == 1)
                                                        <label for="222">Max Alış Stok</label>
                                                    @endif
                                                    <input form="topluGuncelle" name="alis_stok_{{$uu->id}}" type="number" class="form-control"
                                                           id="222" step="0.00001"
                                                           value="{{$uu->alis_stok}}">
                                                </div>
                                            </div>
                                            <?php
                                            $toplamAlislar = DB::table('game_gold_satis')->where('paket', $uu->id)->where('tur', 'bize-sat')->where('status', '1')->whereNull('deleted_at');
                                            $toplamAlisSayi = $toplamAlislar->count();
                                            $toplamAlisAdet = $toplamAlislar->sum('adet');
                                            $toplamAlisTutar = $toplamAlislar->sum('price');


                                            /*echo "Toplam müşteriden alış adedi : " . $toplamSatisAdet;
                                            echo "<br>Toplam müşteriden alış tutarı : " . $toplamSatisTutar;
                                            echo "<br>Toplam müşterilere satış adedi - Toplam müşteriden alış adedi  : " . $toplamAlisAdet - $toplamSatisAdet;
                                            echo "<br>Toplam müşterilere satış tutarı - Toplam müşteriden alış tutarı : " . $toplamAlisTutar - $toplamSatisTutar;
                                            echo "<br> Satışların çıkmış ortalaması : " . substr(($toplamAlisTutar - $toplamSatisTutar) / ($toplamAlisAdet - $toplamSatisAdet), 0, 6);
                                            echo "<br><br><br>";*/


                                            if ($toplamAlisAdet > 0) {
                                                $toplamAlis = substr($toplamAlisTutar / $toplamAlisAdet, 0, 6);
										
										/*if ($toplamAlisTutar > 0) {
												$rt=substr($toplamAlisTutar / ($toplamAlisAdet - $toplamSatisAdet), 0, 6);}*/
												
                                            } else {
                                                $toplamAlis = findGamesPackagesTradeMusteridenAlPrice($uu->id);
                                            }
                                            ?>
                                        <div class="col">
                                            <div class="form-group">
                                                @if($loop->iteration == 1)
                                                    <label for="4">Müşteriden Alış Ortalama</label>
                                                @endif
                                                <input form="topluGuncelle" type="number" step="0.01"
                                                       class="form-control"
                                                       id="4"
                                                       value="{{$toplamAlis}}" readonly>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                @if($loop->iteration == 1)
                                                    <label for="44">Paket Alış Fiyatı</label>
                                                @endif
                                                <input form="topluGuncelle" name="alisT_{{$uu->id}}" type="number" step="0.01" class="form-control"
                                                       id="44"
                                                       value="{{$uu->alis_fiyat}}">
                                            </div>
                                        </div>
                                            <div class="col">
                                                <div class="form-group">
                                                    @if($loop->iteration == 1)
                                                        <label for="44">Paket Satış Fiyatı</label>
                                                    @endif
                                                    <input form="topluGuncelle" name="satisT_{{$uu->id}}" type="number" step="0.01" class="form-control"
                                                           id="44"
                                                           value="{{$uu->satis_fiyat}}">
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

