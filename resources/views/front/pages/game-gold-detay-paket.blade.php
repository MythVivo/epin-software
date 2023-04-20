@extends('front.layouts.app')
<?php
$gold = DB::table('games_titles')
    ->where('link', $gold)
    ->first();
$pakets = explode('-', $paket);
$paket = DB::table('games_packages_trade')
    ->where('id', end($pakets))
    ->first();
array_pop($pakets);
$ilanIsmi = implode('-', $pakets);
if (!$paket or $ilanIsmi != Str::slug($paket->title)) {
    header('Location: ' . URL::to(route('errors_404')), true, 302);
    exit();
}
?>
@section('head')
    <meta name="description" content="{{ @$paket->title }}">
    <meta name="keywords" content="{{ @$paket->title }}">
@endsection
@section('body')
    @php
        $productIds = ["type"=>"game-gold", "products"=>[$paket->id]];
        App\Helpers\Swx::PriceGuard($productIds);
    @endphp
    <section class="bg-gray pb-40">
        <div class="container">
            <div class="row">

                @if (session('error'))
                    <div class="alert alert-danger" role="alert">
                        <h4 class="alert-heading">{{ session('error') }}</h4>
                    </div>
                @endif

                <div class="col-12">
                    <div class="row">

                        <div class="col-md-4 col-sm-12">

                            <div class="card" style="width: 100%;">
                                <img src="{{ asset(env('ROOT') . env('FRONT') . env('GAMES_PACKAGES_TRADE') . $paket->image) }}"
                                    class="card-img-top" alt="{{ $paket->alt }}">
                            </div>

                        </div>

                        <div class="col-md-8 col-sm-12">

                            <div class="card card-bg" style="width: 100%;">
                                <div class="card-body">
                                    <h3 class="card-title">{{ $paket->title }}</h3>
                                    <p class="card-text">{!! $paket->description !!}</p>
                                    @if (findGamesPackagesTradeMusteridenAlPrice($paket->id) != 0)
                                        <div class="row collect-row">
                                            <div class="col-md-4 float-left">
                                                <h4 class="card-text">Bize
                                                    Sat {{ findGamesPackagesTradeMusteridenAlPrice($paket->id) }}
                                                    TL</h4>
                                            </div>
                                            <div class="col-md-8">
                                                <?php
                                                $maxStokAlis = $paket->alis_stok;
                                                ?>
                                                <form method="post" action="{{ route('game_gold_satin_al') }}"
                                                    autocomplete="off">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label for="adetSat{{ $paket->id }}"
                                                                class="form-label">@lang('general.adet')</label>
                                                            <input id="adetSat{{ $paket->id }}"
                                                                max="{{ $maxStokAlis }}" min="1" step="1"
                                                                class="form-control piece style-input" type="number"
                                                                name="adet" placeholder="Lütfen miktar girin" required>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="fiyat{{ $paket->id }}" class="form-label"><a
                                                                    href="javascript:void(0)">Fiyat</a></label>
                                                            <input id="fiyat{{ $paket->id }}"
                                                                min="{{ findGamesPackagesTradeMusteridenAlPrice($paket->id) }}"
                                                                step="{{ findGamesPackagesTradeMusteridenAlPrice($paket->id) }}"
                                                                value="{{ findGamesPackagesTradeMusteridenAlPrice($paket->id) }}"
                                                                data-fiyat="{{ findGamesPackagesTradeMusteridenAlPrice($paket->id) }}"
                                                                class="form-control style-input calc-fiyat" type="number"
                                                                placeholder="Lütfen fiyat girin" name="fiyat" required>
                                                        </div>
                                                        <div class="col-md-12 mt-3">
                                                                <label for="note" class="form-label">Teslim Edecek Kullanıcı Adı</label>
                                                                <br>
                                                                <input id="note" type="text"  class="form-control style-input" name="note" placeholder="Lütfen nick girin" required>
                                                        </div>
                                                    </div>

                                                    <input type="hidden" name="paket" value="{{ $paket->id }}">
                                                    <input type="hidden" name="tur" value="bize-sat">
                                                    <button class="btn-inline color-darkgreen border mt-4 w-100">
                                                        Sat
                                                    </button>
                                                </form>

                                            </div>
                                        </div>
                                    @endif
                                    <div class="row collect-row mt-4">
                                        <div class="col-md-4 float-left">
                                            <h4 class="card-text">Bizden
                                                Al {{ findGamesPackagesTradeMusteriyeSatPrice($paket->id) }}
                                                TL</h4>
                                        </div>
                                        <div class="col-md-8">
                                            <?php
                                            if ($paket->stok >= $paket->satis_stok) {
                                                $maxStokSatis = $paket->satis_stok;
                                            } else {
                                                $maxStokSatis = $paket->stok;
                                            }
                                            ?>
                                            <form method="post" action="{{ route('game_gold_satin_al') }}"
                                                autocomplete="off">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label for="adetAl{{ $paket->id }}"
                                                            class="form-label">@lang('general.adet')</label>
                                                        <input id="adetAl{{ $paket->id }}" max="{{ $maxStokSatis }}"
                                                            min="1" step="1"
                                                            class="form-control piece style-input" type="number"
                                                            name="adet" placeholder="Lütfen miktar girin" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        @if (isset(Auth::user()->id))
                                                            <label for="fiyat{{ $paket->id }}" class="form-label"><a
                                                                    class="bakiye_kullan" href="javascript:void(0)"
                                                                    data-bakiye="{{ Auth::user()->bakiye }}">Fiyat
                                                                    (Tüm Bakiye İle Al)</a></label>
                                                        @else
                                                            <label for="fiyat{{ $paket->id }}"
                                                                class="form-label">Fiyat</label>
                                                        @endif

                                                        <input id="fiyat{{ $paket->id }}"
                                                            @if (isset(Auth::user()->id)) max="{{ Auth::user()->bakiye }}"
                                                               @else
                                                               max="{{ $maxStokSatis * findGamesPackagesTradeMusteriyeSatPrice($paket->id) }}" @endif
                                                            min="{{ findGamesPackagesTradeMusteriyeSatPrice($paket->id) }}"
                                                            step="{{ findGamesPackagesTradeMusteriyeSatPrice($paket->id) }}"
                                                            value="{{ findGamesPackagesTradeMusteriyeSatPrice($paket->id) }}"
                                                            data-fiyat="{{ findGamesPackagesTradeMusteriyeSatPrice($paket->id) }}"
                                                            class="form-control calc-fiyat style-input" type="number"
                                                            name="fiyat" placeholder="Lütfen fiyat girin" required>
                                                    </div>
                                                    <div class="col-md-12 mt-3">
                                                        @if($gold->link == 'rise-online-nft-gonderimi-ve-oyuna-aktarim-bedelleri')
                                                            <label for="note" class="form-label">Metamask Cüzdan adresiniz</label>
                                                            <br>
                                                            <input id="note" type="text"  class="form-control style-input" name="note" placeholder="Metamask Cüzdan adresinizi girin" required>
                                                        @else
                                                            <label for="note" class="form-label">Teslim Alacak Kullanıcı Adı</label>
                                                            <br>
                                                            <input id="note" type="text" class="form-control style-input" name="note"  placeholder="Lütfen nick girin" required>
                                                        @endif
                                                    </div>
                                                </div>
                                                <input type="hidden" name="paket" value="{{ $paket->id }}">
                                                <input type="hidden" name="tur" value="bizden-al">
                                                <button class="btn-inline color-blue border w-100 mt-4"
                                                    @if ($maxStokSatis < 1) disabled
                                                        title="Stok olmadığı için satın alma işlemi yapamazsınız!" @endif>
                                                    @if ($maxStokSatis < 1)
                                                        Stok Bulunmuyor!
                                                    @else
                                                        Satın Al
                                                    @endif
                                                </button>
                                            </form>
                                        </div>
                                    </div>


                                </div>
                            </div>

                        </div>


                    </div>
                </div>


            </div>
        </div>
    </section>
@endsection
@section('js')
@endsection
