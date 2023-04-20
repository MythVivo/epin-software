@extends('front.layouts.app')
@section('body')

<section class="bg-gray pb-40">
    <div class="container">
        @if (session('success'))
        <div class="alert alert-success d-flex align-items-center" role="alert">
            <i class="fas fa-check me-2"></i>
            <h5>{{ session('success') }}</h5>
        </div>
        @endif

        @if (session('error'))
        <div class="alert alert-danger d-flex align-items-center" role="alert">
            <i class="fas fa-check me-2"></i>
            <h5>{{ session('error') }}</h5>
        </div>
        @endif

        <div class="row">
            <div class="col-md-4 col-sm-12">
                <div class="game-info-col">
                    <figure>
                        <img src="{{ asset(env('ROOT') . env('FRONT') . env('GAMES_TITLES') . $gold->image) }}" alt="{{ $gold->alt }}">
                    </figure>
                    <h5 class="heading-secondary-title">{{ $gold->title }}</h5>
                    <p>{!! $gold->text !!}</p>
                </div>
            </div>
            <div class="col-md-8 col-sm-12">
                <div class="items-collection-wrapper">
                    @foreach ($packages as $u)
                    <article class="item-col-wrapper">
                        <figure>
                            <img src="{{ asset(env('ROOT') . env('FRONT') . env('GAMES_PACKAGES_TRADE') . $u->image) }}" alt="{{ $u->alt }}">
                        </figure>
                        <div class="item-col-center">
                            <h5 class="heading-secondary">
                                <a href="{{ route('game_gold_detay_paket', [$gold->link, Str::slug($u->title) . '-' . $u->id]) }}">{{ $u->title }}
                                </a>
                            </h5>
                            <h6>{!! $u->description !!}
                                <?
                                $sor = DB::select("select * from games_packages_trade where isnull(deleted_at) and id='$u->id'");
                                if (strlen($sor[0]->indirim) > 3 && strpos($sor[0]->indirim, '/') > 0) {
                                    echo "<hr> "; #. $sor[0]->indirim;
                                    echo "<p style='text-align: center; class='small'>İndirimler satın aldığınızda otomatik tanımlanmaktadır.";

                                    $in = explode(':', $sor[0]->indirim);
                                    $rakam = findGamesPackagesTradeMusteriyeSatPrice($u->id);
                                    foreach ($in as $i) {
                                        $al = explode('/', $i);
                                        $fiyat = str_replace('.', ',', number_format($rakam - (($rakam * $al[1]) / 100), 2));
                                        echo "<div style='color: #808080;background-color: orange;' class='mb-1 radius20 text-danger text-center text-xl-center'><i class='far fa-arrow-alt-right'>&nbsp&nbsp</i>" . $al[0] . " adet ve üzeri alımlarda  ₺$fiyat <i class='far fa-arrow-alt-left'></i></div>";
                                    }

                                    echo "</p>";
                                }
                                ?>

                            </h6>
                        </div>
                        <div class="item-col-buy type-2">
                            <div class="satinal">
                                <h5>Alış : <span>₺</span>{{ MF(findGamesPackagesTradeMusteridenAlPrice($u->id)) }}
                                </h5>
                                <?php $maxStokAlis = $u->alis_stok; ?>

                                <?php
                                $btnClass = '';
                                if (!$maxStokAlis > 0) {
                                    $btnClass = 'passive';
                                }
                                if (isset(Auth::user()->id)) {
                                    $id = Auth::user()->id;
                                    $check = DB::select("select status from game_gold_satis where user='$id' and status='0' and isnull(deleted_at)");
                                }
                                ?>


                                @if (isset(Auth::user()->id))

                                <button type="button" class="btn-inline color-darkgreen small {{ $btnClass }}" @if (count($check) !=0) title="Bekleyen işleminiz varken yeni bir işlem yapamazsınız!" data-bs-toggle="modal" data-bs-target=".sell-warning" @else @if ($maxStokAlis> 0)
                                    data-bs-toggle="modal"
                                    data-bs-target=".bizeSat{{ $u->id }}"
                                    @else
                                    title="Stok fazlasından dolayı şuan satamazsınız!"
                                    data-bs-toggle="modal"
                                    data-bs-target=".sell-warning" @endif
                                    @endif
                                    >

                                    Bize Sat

                                </button>
                                @else
                                <button onclick="location.href='{{ route('giris') }}'" type="button" class="btn-inline color-darkgreen small">Oturum Aç
                                </button>
                                @endif
                            </div>
                            <div class="satis">
                                <h5>Satış :
                                    <span>₺</span>{{ MF(findGamesPackagesTradeMusteriyeSatPrice($u->id)) }}
                                </h5>
                                @if (isset(Auth::user()->id))
                                <?php
                                if ($u->stok >= $u->satis_stok) {
                                    $maxStokSatis = $u->satis_stok;
                                } else {
                                    $maxStokSatis = $u->stok;
                                }
                                ?>
                                <button type="button" class="btn-inline color-blue small" @if (count($check) !=0) title="Bekleyen işleminiz varken yeni bir işlem yapamazsınız!" data-bs-toggle="modal" data-bs-target=".sell-warning" @else data-bs-toggle="modal" data-bs-target=".satinAl{{ $u->id }}" @if ($maxStokSatis < 1) disabled title="Stok olmadığı için satın alma işlemi yapamazsınız!" @endif @endif>

                                    @if ($maxStokSatis < 1) Stok Bulunmuyor! @else Bizden Al @endif </button>
                                        @else
                                        <button onclick="location.href='{{ route('giris') }}'" type="button" class="btn-inline color-blue small">Oturum Aç
                                        </button>
                                        @endif
                            </div>
                        </div>


                    </article>
                    @if (isset(Auth::user()->id))
                    <div class="modal fade sell-warning" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered style-modal h-modal" role="document">
                            <div class="modal-content">
                                <div class="modal-header">

                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                        <i class="far fa-times"></i></button>
                                </div>
                                <div class="modal-body">
                                    @if (count($check) != 0)
                                    <h6>Bekleyen işleminiz varken yeni bir işlem yapamazsınız!</h6>
                                    @else
                                    <h6>Stok fazlasından dolayı şuan satamazsınız!</h6>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade bizeSat{{ $u->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered style-modal h-modal" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Bize Sat <span id="bakiye" style="display: none;">{{ Auth::user()->bakiye }}</span> |
                                        Alış : <span id="satis">{{ findGamesPackagesTradeMusteridenAlPrice($u->id) }}</span>
                                        TL
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                        <i class="far fa-times"></i></button>
                                </div>
                                <form method="post" action="{{ route('game_gold_satin_al') }}" autocomplete="off">
                                    <div class="modal-body">
                                        <h6 class="warning-title">Not:Lütfen sipariş vermeden hazır
                                            bulununuz..</h6>
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="adetSat{{ $u->id }}" class="form-label">@lang('general.adet')</label>
                                                <input id="adetSat{{ $u->id }}" max="{{ $maxStokAlis }}" min="1" step="1" class="form-control piece" type="number" name="adet" placeholder="Lütfen miktar girin" required>
                                            </div>
                                            <div class="col-md-12 mt-3">
                                                <label for="fiyat{{ $u->id }}" class="form-label"><a href="javascript:void(0)">Fiyat</a></label>
                                                <input id="fiyat{{ $u->id }}" min="{{ findGamesPackagesTradeMusteridenAlPrice($u->id) }}" step="{{ findGamesPackagesTradeMusteridenAlPrice($u->id) }}" value="{{ findGamesPackagesTradeMusteridenAlPrice($u->id) }}" class="form-control calc-fiyat" data-fiyat="{{ findGamesPackagesTradeMusteridenAlPrice($u->id) }}" type="number" name="fiyat" placeholder="Lütfen fiyat girin" required>
                                            </div>
                                            <div class="col-md-12 mt-3">
                                                <label for="note" class="form-label">Teslim
                                                    Edecek
                                                    Kullanıcı
                                                    Adı</label><br>
                                                <input id="note" type="text" class="form-control" name="note" placeholder="Lütfen nick girin" required>
                                            </div>
                                        </div>

                                        <input type="hidden" name="paket" value="{{ $u->id }}">
                                        <input type="hidden" name="tur" value="bize-sat">

                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn-inline color-blue">@lang('general.onayla')
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade satinAl{{ $u->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered style-modal h-modal" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">
                                        Bizden Al <span id="bakiye" style="display: none;">{{ Auth::user()->bakiye }}</span>
                                        |
                                        Satış : <span id="satis">{{ findGamesPackagesTradeMusteriyeSatPrice($u->id) }}</span>
                                        TL
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="far fa-times"></i></button>
                                </div>
                                <?php
                                if ($u->stok >= $u->satis_stok) {
                                    $maxStokSatis = $u->satis_stok;
                                } else {
                                    $maxStokSatis = $u->stok;
                                }
                                ?>
                                <form class="game_gold_satin_al" method="post" action="{{ route('game_gold_satin_al') }}" autocomplete="off">
                                    <div class="modal-body">
                                        <h6 class="warning-title">Not:Lütfen sipariş vermeden hazır bulununuz..
                                        </h6>
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="adetAl{{ $u->id }}" class="form-label">@lang('general.adet')</label>
                                                <input id="adetAl{{ $u->id }}" max="{{ $maxStokSatis }}" min="1" step="1" class="form-control piece" type="number" name="adet" placeholder="Lütfen miktar girin" required>
                                            </div>
                                            <div class="col-md-12 mt-3">
                                                <label for="fiyat{{ $u->id }}" class="form-label"><a onclick="hesaplaTumBakiye(this)" href="javascript:void(0)">Fiyat (Tüm Bakiye İle
                                                        Al)</a></label>
                                                <input id="fiyat{{ $u->id }}" max="{{ Auth::user()->bakiye }}" min="{{ findGamesPackagesTradeMusteriyeSatPrice($u->id) }}" value="{{ findGamesPackagesTradeMusteriyeSatPrice($u->id) }}" class="form-control calc-fiyat" data-fiyat="{{ findGamesPackagesTradeMusteriyeSatPrice($u->id) }}" data-indirim="{{ $sor[0]->indirim }}" ; type="text" name="fiyat" placeholder="Lütfen fiyat girin" required>
                                                <div class="custom-alert">Yeterli bakiyeniz yok</div>
                                            </div>
                                            <div class="col-md-12 mt-3">
                                                <label for="note" class="form-label">Teslim Alacak
                                                    Kullanıcı Adı</label><br>
                                                <input id="note" type="text" class="form-control" name="note" placeholder="Lütfen nick girin" required>
                                            </div>
                                        </div>

                                        <input type="hidden" name="paket" value="{{ $u->id }}">
                                        <input type="hidden" name="tur" value="bizden-al">

                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn-inline color-blue">@lang('general.onayla')
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endif
                    @endforeach


                </div>
            </div>

        </div>
        <div class="row">
            <div class=" col-sm-12">
                <a name="yorums" id="yorums"></a>
                {{ view('front.pages.yorumlar', ['epin' => $gold, 'table' => 'gold']) }}
            </div>
        </div>

    </div>
</section>
@endsection
@section('js')
{{-- <script> --}}
{{-- function hesapla(a) { --}}
{{-- adet = $(a).val(); --}}
{{-- bakiye = $("#bakiye").text(); --}}
{{-- satis = $(a.parentElement.parentElement.parentElement.parentElement.parentElement.children[0].children[0].children[1]).text(); --}}
{{-- fiyat = $(a.parentElement.parentElement.children[1].children[1]).val(adet * satis); --}}
{{-- } --}}

{{-- function hesaplaAdet(a) { --}}
{{-- fiyat = $(a).val(); --}}
{{-- bakiye = $("#bakiye").text(); --}}
{{-- satis = $(a.parentElement.parentElement.parentElement.parentElement.parentElement.children[0].children[0].children[1]).text(); --}}
{{-- var hesap = fiyat / satis; --}}
{{-- adet = $(a.parentElement.parentElement.children[0].children[1]).val(hesap.toFixed(2)); --}}
{{-- } --}}

{{-- function hesaplaTumBakiye(a) { --}}
{{-- bakiye = $("#bakiye").text(); --}}
{{-- satis = $(a.parentElement.parentElement.parentElement.parentElement.parentElement.parentElement.children[0].children[0].children[1]).text(); --}}
{{-- maxAdet = Math.floor(bakiye / satis) --}}
{{-- fiyat = $(a.parentElement.parentElement.children[1]).val(maxAdet * satis); --}}
{{-- adet = $(a.parentElement.parentElement.parentElement.children[0].children[1]).val(maxAdet); --}}
{{-- } --}}
{{-- </script> --}}
@endsection
