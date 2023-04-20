<div class="row title-area" data-lang="{{ getLang() }}">
    <div class="col-sm-12 col-md-12 title s-title">
        <h1 class="heading-primary style-2"><span>Çok Satanlar</span></h1>
    </div>
    <!--
    <div class="col-sm-12 col-md-3 button-area">
        <button class="cat_newsPrevBtn d-flex justify-content-center btn btn-group text-center">
            <i class="fas fa-angle-left align-self-center"></i>
        </button>
        <button class="cat_newsNextBtn btn btn-group text-center">
            <i class="fas fa-angle-right align-self-center"></i>
        </button>
    </div>-->
</div>
<div class="row">
    <div class="cok-satanlar owl-carousel">

        <?php
        $epin = getCacheEpinPopular();
        $gameGold = getCacheGameGoldPopular();
        ?>
        @foreach ($epin as $u)
        @if (DB::table('games_packages')->where('id', $u->paketId)->whereNull('deleted_at')->count() > 0)
        <?php
        $epin = getCacheEpinPopularSingle($u->game_title);
        $paket = getCacheEpinPopularSinglePackage($u->paketId);
        ?>
        <div class="category-item">
            <a href="{{ route('epin_detay_paket', [$epin->link, Str::slug($paket->title) . '-' . $paket->id]) }}">
                <figure img="{{ asset(env('ROOT') . env('FRONT') . env('GAMES_PACKAGES') . $paket->image) }}">

                    <img class="owl-lazy" style="width: 100%;height: 100%;" src="data:image/gif;base64,R0lGODdhAQABAPAAAMPDwwAAACwAAAAAAQABAAACAkQBADs=" data-src="{{ cdn(env('ROOT') . env('FRONT') . env('GAMES_PACKAGES') . $paket->image, 289, 289) }}" width="289" height="289" alt="{{ $paket->title }} görseli" width="289" height="289">
                </figure>
                <div class="text-container flex-flat">
                    <h5>{{ $paket->title }}</h5>
                    <span><i>₺</i>{{ MF(findGamesPackagesPrice($paket->id)) }}</span>
                </div>
            </a>
        </div>
        @endif
        @endforeach
        @foreach ($gameGold as $u)
        <?php
        $paket = getCacheGameGoldPopularSingle($u->paket);
        $gold = getCacheGameGoldPopularSinglePackage($paket->games_titles);
        ?>

        <div class="category-item">
            <a href="{{ route('game_gold_detay_paket', [$gold->link, Str::slug($paket->title) . '-' . $paket->id]) }}">
                <figure img="{{ asset(env('ROOT') . env('FRONT') . env('GAMES_PACKAGES_TRADE') . $paket->image) }}">
                    <img class="owl-lazy" style="width: 100%;height: 100%;" data-src="{{ cdn(env('ROOT') . env('FRONT') . env('GAMES_PACKAGES_TRADE') . $paket->image, 289, 289) }}" alt="{{ $paket->title }} görseli" width="289" height="289">
                </figure>
                <div class="text-container flex-flat">
                    <h5>{{ $paket->title }}</h5>
                    <span><i>₺</i>{{ findGamesPackagesTradeMusteriyeSatPrice($paket->id) }}</span>
                </div>
            </a>
        </div>
        @endforeach
    </div>
</div>