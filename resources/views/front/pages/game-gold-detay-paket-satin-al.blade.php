@extends('front.layouts.app')
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <style>
        td {
            vertical-align: middle;
        }
    </style>
@endsection
@section('body')
    <?php
    $gold = DB::table('games_titles')->where('link', $gold)->first();
    $pakets = explode("-", $paket);
    $paket = DB::table('games_packages_trade')->where('id', end($pakets))->first();
    array_pop($pakets);
    $ilanIsmi = implode("-", $pakets);
    if (!$paket or $ilanIsmi != Str::slug($paket->title)) {
        header("Location: " . URL::to(route('errors_404')), true, 302);
        exit();
    }
    if (isset($_GET['adet']) and is_numeric($_GET['adet'])) {
        $adet = $_GET['adet'];
    } else {
        $adet = 1;
    }
    ?>
    <section class="bg-gray header-margin pb-100">
        <div class="container">
            <div class="row">

                <section class="game">
                    <div class="container">

                        <div class="row">
                            @if(session('error'))
                                <div class="alert alert-danger" role="alert">
                                    <h4 class="alert-heading">{{session('error')}}</h4>
                                </div>
                            @endif
                        </div>


                        <table class="table table-hover table-bordered text-center item-checkout-table">
                            <thead>
                            <tr class="table-secondary">
                                <th>@lang('general.resim')</th>
                                <th>@lang('general.adi')</th>
                                <th>Adet</th>
                                <th>@lang('general.fiyat')</th>
                                <th>Toplam Tutar</th>
                                <th>Bakiyeniz</th>
                                <th>İşlem Sonrası Bakiyeniz</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="w-25">
                                    <img src="{{asset(env('ROOT').env('FRONT').env('GAMES_PACKAGES_TRADE').$paket->image)}}"
                                         class="card-img-top" alt="{{$paket->alt}}">
                                </td>
                                <td>{{$paket->title}}</td>
                                <td>{{$adet}}</td>
                                <?php
                                if ($durum == 'bize-sat') {
                                    $fiyat = findGamesPackagesTradeBuyPrice($paket->id);
                                    $stok = $paket->alis_stok;
                                } else {
                                    $fiyat = findGamesPackagesTradeSellPrice($paket->id);
                                    $stok = $paket->satis_stok;
                                }
                                ?>
                                <td>{{$fiyat}} TL</td>
                                <td>{{$fiyat * $adet}} TL</td>
                                <td>
                                    @if(isset(Auth::user()->id))
                                        {{Auth::user()->bakiye}} TL
                                    @else
                                        Bakiyenizi Görmek İçin Giriş Yapın
                                    @endif
                                </td>
                                <td>
                                    @if(isset(Auth::user()->id))
                                        @if(Auth::user()->bakiye - $fiyat < 0)
                                            İşlem için yeterli bakiyeniz yok
                                        @else
                                            @if($durum == 'bize-sat')
                                                {{Auth::user()->bakiye + ($fiyat * $adet)}} TL
                                            @else
                                                {{Auth::user()->bakiye - ($fiyat * $adet)}} TL
                                            @endif
                                        @endif
                                    @else
                                        Bakiyenizi Görmek İçin Giriş Yapın
                                    @endif
                                </td>
                            </tr>
                            </tbody>
                        </table>

                        <div class="row">
                            <div class="col-6 offset-md-3 align-self-center">
                                <div class="card text-center mt-100 mb-100">
                                    <div class="card-body">
                                        <h5 class="card-title">@lang('general.siparisOnay')</h5>
                                        @if(isset(Auth::user()->id))
                                            @if(Auth::user()->bakiye >= ($fiyat * $adet) or $durum == 'bize-sat')
                                                @if($adet > $stok)
                                                    <div class="alert alert-danger fade show d-flex align-items-center"
                                                         role="alert">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <h5>
                                                                    Satın Alma işlemi için yeterli stok yok, lütfen
                                                                    miktarı düşürün veya stokların güncellenmesi için
                                                                    bizimle iletişime geçin.
                                                                </h5>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <form method="post" action="{{route('game_gold_satin_al')}}">
                                                        @csrf
                                                        <input type="hidden" name="paket" value="{{$paket->id}}">
                                                        <input type="hidden" name="tur" value="{{$durum}}">
                                                        <input type="hidden" name="adet" value="{{$adet}}">
                                                        <label for="note" class="form-label">Teslim Alan/Eden Kullanıcı
                                                            Adı</label><br>
                                                        <input id="note" type="text" class="form-control" name="note"
                                                               placeholder="Teslim Alan/Eden Kullanıcı Adı" required>
                                                        <button type="submit"
                                                                class="btn btn-primary mt-3"
                                                                onclick="gonderiliyor()">@lang('general.onayla')</button>
                                                        @include('front.plugins.siparisiniz-isleniyor')
                                                    </form>
                                                @endif
                                            @else
                                                <div class="alert alert-danger fade show d-flex align-items-center"
                                                     role="alert">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <h5>
                                                                Satın Alma işlemi için yeterli bakiyeniz yok, lütfen
                                                                bakiye ekleyin.
                                                            </h5>
                                                        </div>
                                                        <div class="col-12">
                                                            <button type="button" class="btn btn-outline-success w-100"
                                                                    onclick="location.href='{{route('bakiye_ekle')}}'">
                                                                Bakiye Ekle
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @else
                                            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center"
                                                 role="alert">
                                                <i class="fas fa-exclamation-triangle me-3"></i>
                                                <h5>
                                                    Bu ilanı satın alabilmek için lütfen giriş yapın.
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                        aria-label="Close"></button>
                                                <button type="button" class="btn btn-outline-success btn-sm"
                                                        onclick="location.href='{{route('giris')}}'">
                                                    Giriş Yap
                                                </button>
                                            </div>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </section>

            </div>
        </div>
    </section>
@endsection
@section('js')
    <script>
        function gonderiliyor() {

            $("body").append($(".process-screen").addClass("open"))

            //bunu form submit fonksiyonuna bağlayabilir misin abi tasarımı bitirdikten sonra

        }
    </script>
@endsection
