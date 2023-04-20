@extends('front.layouts.app')
@section('body')
    <?php
    $sef = $goldTitle;
    /* $gold = DB::table('games_titles')
        ->where('link', $gold)
        ->whereNull('deleted_at')
        ->first();
    if (!$gold) {
        header('Location: ' . URL::to(route('errors_404')), true, 302);
        exit();
    } */
    ?>
    <section class="bg-gray pb-40">
        @if ($sef == 'knight-online-goldbar-alim-satim')
            <style>
                @media only screen and (max-width: 1600px) {
                    .gbbar {
                        display: none !important;
                    }
                }

                @media only screen and (max-width: 600px) {

                    .game-info-text,
                    .heading-secondary-title {
                        display: none;
                    }
                }
            </style>
            <div class="gbbar container mb-2" style="text-align: center;">
                <a href="{{ route('item_detay', ['item-satis']) }}">
                    <img style="border-radius:10px" src="https://oyuneks.com/public/front/images/topbar/gbbar.gif" alt="">
                </a>
            </div>
        @endif
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
                            <img src="{{ asset(env('ROOT') . env('FRONT') . env('GAMES_TITLES') . $gold->image) }}"
                                alt="{{ $gold->alt }}">
                        </figure>
                        <h5 class="heading-secondary-title">{{ $gold->title }}</h5>
                        <div class="game-info-text">
                            {!! $gold->text !!}
                        </div>

                    </div>
                </div>
                <div class="col-md-8 col-sm-12">
                    <div class="items-collection-wrapper">
                        @php
                            /* $products = \App\Models\GamesPackagesTrade::where('games_titles', $gold->id)
                                ->orderBy('sira', 'asc')
                                ->get(); */
                            
                            $productIds = [];
                            foreach ($products as $product) {
                                $productIds[] = $product->id;
                            }
                            $productIds = ['type' => 'game-gold', 'products' => $productIds];
                            App\Helpers\Swx::PriceGuard($productIds);
                        @endphp
                        <style>
                            body:not(.dark) h6>p>span {
                                color: #6C16FF !important
                            }
                        </style>
                        @foreach ($products as $u)
                            <article class="item-col-wrapper">
                                <figure>
                                    <img src="{{ asset(env('ROOT') . env('FRONT') . env('GAMES_PACKAGES_TRADE') . $u->image) }}"
                                        alt="{{ $u->alt }}">
                                </figure>
                                <div class="item-col-center">
                                    <h5 class="heading-secondary">
                                        <a
                                            href="{{ route('game_gold_detay_paket', [$gold->link, Str::slug($u->title) . '-' . $u->id]) }}">{{ $u->title }}
                                        </a>
                                    </h5>

                                    <h6>{!! $u->description !!}
                                        <?
                                                                    $sor=DB::select("select * from games_packages_trade where isnull(deleted_at) and id='$u->id'");
                                                                    if(strlen($sor[0]->indirim)>3 && strpos($sor[0]->indirim,'/')>0){
                                                                        echo "<hr> "; #. $sor[0]->indirim;
                                                                        echo "<p style='text-align: center; class='small'>İndirimler satın aldığınızda otomatik tanımlanmaktadır.";

                                                                        $in=explode(':',$sor[0]->indirim);
                                                                        $rakam=findGamesPackagesTradeMusteriyeSatPrice($u->id);
                                                                        foreach($in as $i)
                                                                        {
                                                                            $al=explode('/',$i);
                                                                            $fiyat = str_replace('.',',',ceil((100 - $al[1]) *$rakam)/100);
                                                                           // $fiyat=str_replace('.',',',number_format($rakam-(($rakam*$al[1])/100),2));
                                                                            echo "<div style='color: #808080;background-color: orange;' class='mb-1 radius20 text-danger text-center text-xl-center'><i class='far fa-arrow-alt-right'>&nbsp&nbsp</i>".$al[0]." adet ve üzeri alımlarda  ₺$fiyat <i class='far fa-arrow-alt-left'></i></div>";

                                                                        }

                                                                        echo "</p>";
                                                                    }
                                                                    ?>

                                    </h6>
                                </div>
                                <div class="item-col-buy type-2">
                                    <?php $maxStokAlis = $u->alis_stok; ?>

                                    <?php
                                    $btnClass = '';
                                    if (!$maxStokAlis > 0) {
                                        $btnClass = 'passive';
                                    }
                                    if (isset(Auth::user()->id)) {
                                        $id = Auth::user()->id;
                                        $check = DB::select("select status from game_gold_satis where user='$id' and status='0' and tur='bize-sat' and isnull(deleted_at)");
                                        if ($id == 20285) {
                                            // klasgame coklu satis.
                                            $check = [];
                                        }
                                    }
                                    ?>
                                    @if (findGamesPackagesTradeMusteridenAlPrice($u->id) != 0)
                                        <div class="satinal">
                                            <h5>Alış :
                                                <span>₺</span>{{ MF(findGamesPackagesTradeMusteridenAlPrice($u->id)) }}
                                            </h5>
                                            @if (isset(Auth::user()->id))
                                                <button type="button"
                                                    class="btn-inline color-darkgreen small {{ $btnClass }}"
                                                    @if (count($check) != 0) title="Bekleyen satış işleminiz varken yeni bir satış yapamazsınız!"
                                                data-bs-toggle="modal"
                                                data-bs-target=".sell-warning"
                                            @else
                                                    @if ($maxStokAlis > 0)
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
                                                <button onclick="location.href='{{ route('giris') }}'" type="button"
                                                    class="btn-inline color-darkgreen small">Oturum Aç
                                                </button>
                                            @endif

                                        </div>
                                    @endif
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
                                            <button type="button" class="btn-inline color-blue small" {{--                                                @if (count($check) != 0) title="Bekleyen işleminiz varken yeni bir işlem yapamazsınız!" --}}
                                                {{--                                                data-bs-toggle="modal" --}} {{--                                                data-bs-target=".sell-warning" --}} {{--                                            @else --}}
                                                data-bs-toggle="modal" data-bs-target=".satinAl{{ $u->id }}"
                                                @if ($maxStokSatis < 1) disabled
                                                    title="Stok olmadığı için satın alma işlemi yapamazsınız!" @endif
                                                {{--                                                @endif --}}>

                                                @if ($maxStokSatis < 1)
                                                    Stok Bulunmuyor!
                                                @else
                                                    @if ($gold->link == 'rise-online-nft-gonderimi-ve-oyuna-aktarim-bedelleri')
                                                        Satın Al
                                                    @else
                                                        Bizden Al
                                                    @endif
                                                @endif
                                            </button>
                                        @else
                                            <button onclick="location.href='{{ route('giris') }}'" type="button"
                                                class="btn-inline color-blue small">Oturum Aç
                                            </button>
                                        @endif
                                    </div>
                                </div>


                            </article>
                            @if (isset(Auth::user()->id))
                                <div class="modal fade sell-warning" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered style-modal h-modal" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">

                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close">
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

                                <div class="modal fade bizeSat{{ $u->id }}" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered style-modal h-modal" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Bize Sat <span id="bakiye"
                                                        style="display: none;">{{ Auth::user()->bakiye }}</span> |
                                                    Alış : <span
                                                        id="satis">{{ findGamesPackagesTradeMusteridenAlPrice($u->id) }}</span>
                                                    TL
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close">
                                                    <i class="far fa-times"></i></button>
                                            </div>
                                            <form method="post" onsubmit="return preventDuplicate()"
                                                action="{{ route('game_gold_satin_al') }}" autocomplete="off">
                                                <div class="modal-body">
                                                    <h6 class="warning-title">Not:Lütfen sipariş vermeden hazır bulununuz..
                                                    </h6>
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <label for="adetSat{{ $u->id }}"
                                                                class="form-label">@lang('general.adet')</label>
                                                            <input id="adetSat{{ $u->id }}"
                                                                max="{{ $maxStokAlis }}" min="1" step="1"
                                                                class="form-control piece" type="number" name="adet"
                                                                placeholder="Lütfen miktar girin" required>
                                                        </div>
                                                        <div class="col-md-12 mt-3">
                                                            <label for="fiyat{{ $u->id }}" class="form-label"><a
                                                                    href="javascript:void(0)">Fiyat</a></label>
                                                            <input id="fiyat{{ $u->id }}"
                                                                min="{{ findGamesPackagesTradeMusteridenAlPrice($u->id) }}"
                                                                step="{{ findGamesPackagesTradeMusteridenAlPrice($u->id) }}"
                                                                value="{{ findGamesPackagesTradeMusteridenAlPrice($u->id) }}"
                                                                class="form-control calc-fiyat"
                                                                data-fiyat="{{ findGamesPackagesTradeMusteridenAlPrice($u->id) }}"
                                                                type="number" name="fiyat"
                                                                placeholder="Lütfen fiyat girin" required>
                                                        </div>
                                                        <div class="col-md-12 mt-3">
                                                            <label for="note" class="form-label">Teslim
                                                                Edecek
                                                                Kullanıcı
                                                                Adı</label><br>
                                                            <input id="note" type="text" class="form-control"
                                                                name="note" placeholder="Lütfen nick girin" required>
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
                                <div class="modal fade satinAl{{ $u->id }}" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered style-modal h-modal" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">
                                                    Bizden Al <span id="bakiye"
                                                        style="display: none;">{{ Auth::user()->bakiye }}</span>
                                                    |
                                                    Satış : <span
                                                        id="satis">{{ findGamesPackagesTradeMusteriyeSatPrice($u->id) }}</span>
                                                    TL
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"><i class="far fa-times"></i></button>
                                            </div>
                                            <?php
                                            if ($u->stok >= $u->satis_stok) {
                                                $maxStokSatis = $u->satis_stok;
                                            } else {
                                                $maxStokSatis = $u->stok;
                                            }
                                            ?>
                                            <form class="game_gold_satin_al" onsubmit="return preventDuplicate()"
                                                method="post" action="{{ route('game_gold_satin_al') }}"
                                                autocomplete="off">
                                                <div class="modal-body">

                                                    @if ($gold->link == 'rise-online-nft-gonderimi-ve-oyuna-aktarim-bedelleri')
                                                        <h6 class="warning-title">Not:Metamask Cüzdan adresinizi hatasız
                                                            yazmanız gereklidir.(iade işlemi yapılamamaktadır)</h6>
                                                    @else
                                                        <h6 class="warning-title">Not:Lütfen sipariş vermeden hazır
                                                            bulununuz..</h6>
                                                    @endif
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <label for="adetAl{{ $u->id }}"
                                                                class="form-label">@lang('general.adet')</label>
                                                            <input id="adetAl{{ $u->id }}"
                                                                max="{{ $maxStokSatis }}" min="1" step="1"
                                                                class="form-control piece" type="number" name="adet"
                                                                placeholder="Lütfen miktar girin" required>
                                                        </div>
                                                        <div class="col-md-12 mt-3">
                                                            <label for="fiyat{{ $u->id }}" class="form-label"><a
                                                                    onclick="hesaplaTumBakiye(this)"
                                                                    href="javascript:void(0)">Fiyat (Tüm Bakiye İle
                                                                    Al)</a></label>
                                                            <input id="fiyat{{ $u->id }}"
                                                                max="{{ Auth::user()->bakiye }}"
                                                                min="{{ findGamesPackagesTradeMusteriyeSatPrice($u->id) }}"
                                                                value="{{ findGamesPackagesTradeMusteriyeSatPrice($u->id) }}"
                                                                class="form-control calc-fiyat"
                                                                data-fiyat="{{ findGamesPackagesTradeMusteriyeSatPrice($u->id) }}"
                                                                data-indirim="{{ $sor[0]->indirim }}"; type="text"
                                                                name="fiyat" placeholder="Lütfen fiyat girin" required>
                                                            <div class="custom-alert">Yeterli bakiyeniz yok</div>
                                                        </div>
                                                        <div class="col-md-12 mt-3">
                                                            @if ($gold->link == 'rise-online-nft-gonderimi-ve-oyuna-aktarim-bedelleri')
                                                                <label for="note" class="form-label">Metamask Cüzdan
                                                                    adresiniz</label>
                                                                <br>
                                                                <input id="note" type="text" class="form-control"
                                                                    name="note"
                                                                    placeholder="Metamask Cüzdan adresinizi girin"
                                                                    required>
                                                            @else
                                                                <label for="note" class="form-label">Teslim Alacak
                                                                    Kullanıcı Adı</label>
                                                                <br>
                                                                <input id="note" type="text" class="form-control"
                                                                    name="note" placeholder="Lütfen nick girin"
                                                                    required>
                                                            @endif
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
    <script>
        var sent = false;

        function preventDuplicate() {
            if (sent)
                return false;
            sent = true;
        }
    </script>
    <script>
        function hesaplaTumBakiye(that) {
            $(that).parent().parent().find('input').val($(".header-mini-user .bakiye").text().replace(".", "").replace(",",
                "."));
            $(that).parent().parent().find('input').trigger("change");
        }
    </script>
@endsection

