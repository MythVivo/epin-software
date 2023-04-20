<?php
if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off') {
    if (!empty($_SERVER['HTTP_HOST'])) {
        $host = $_SERVER['HTTP_HOST'];
    } else {
        $host = env('APP_URL_CLEAR');
    }
    $location = 'https://' . $host . $_SERVER['REQUEST_URI'];
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $location);
    exit();
}


if (isset(Auth::user()->id) and Auth::user()->status == 0) {
    Auth::logout();
    header('Location: /errors-100');
    die();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="tr">

<head>
    <link rel="canonical" href="{{ 'https://' . $_SERVER['HTTP_HOST'] . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) }}" />
    <!-- <link rel="preload" as="font" type="font/woff2" crossorigin="anonymous" href="" /> -->
    {{-- getStatistic() --}}
    {{ lastSeeAt() }}
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>
        @if (Route::currentRouteName() == 'baslik_detay')
        @if (is_numeric($oyun))
        {{ FindApiGame($oyun) }} E-pin
        @else
        {{ $baslik->title }}
        @endif
        @elseif(Route::currentRouteName() == 'oyun_baslik')
        {{ $oyun->title }}
        @elseif(Route::currentRouteName() == 'satis_duzenle')
        {{ substr($ilan->title, 0, 50) }} @if (strlen($ilan->title) > 50)
        ...
        @endif Düzenle
        @elseif(Route::currentRouteName() == 'item_detay')
        <?php
        $item = DB::table('games_titles')
            ->where('link', $item->link)
            ->first();
        echo DB::table('games')
            ->where('id', $item->game)
            ->first()->title .
            ' - ' .
            $item->title;
        ?>
        @elseif(Route::currentRouteName() == 'item_canli_akis')
        <?php
        $item = DB::table('games_titles')
            ->where('link', $item->link)
            ->first();
        echo DB::table('games')
            ->where('id', $item->game)
            ->first()->title .
            ' - ' .
            $item->title;
        ?> - Canlı Akış
        @elseif(Route::currentRouteName() == 'item_buy_detay')
        <?php
        $item = DB::table('games_titles')
            ->where('link', $item->link)
            ->first();
        echo DB::table('games')
            ->where('id', $item->game)
            ->first()->title .
            ' - ' .
            $item->title .
            ' Alış';
        ?>
        @elseif(Route::currentRouteName() == 'item_ic_detay')
        {{ $u->title . ' | #' . $u->id }}
        @elseif(Route::currentRouteName() == 'item_buy_ic_detay')
        {{ $u->title }}
        @elseif(Route::currentRouteName() == 'item_ic_detay_satin_al')
        {{ $u->title }} Satın Al
        @elseif(Route::currentRouteName() == 'item_buy_ic_detay_satin_al')
        {{ $u->title }} Sat
        @elseif(Route::currentRouteName() == 'cd_key_detay_satin_al')
        {{ $cdkey->title }} Satın Al
        @elseif(Route::currentRouteName() == 'satici')
        Satıcı {{ substr($satici->name, 0, 2) }}**** ******
        @elseif(Route::currentRouteName() == 'sayfa')
        {{ $u->title }}
        @elseif(Route::currentRouteName() == 'game_gold_detay')
        {{ $gold->title }}
        @elseif(Route::currentRouteName() == 'game_gold_detay_paket')
        {{ $paket->title }}
        @elseif(Route::currentRouteName() == 'game_gold_detay_paket_satin_al')
        {{ $paket->title }} Satın Al / Sat
        @elseif(Route::currentRouteName() == 'epin_detay')
        {{ $epin->title }}
        @elseif(Route::currentRouteName() == 'epin_detay_paket')
        {{ $paket->title }}
        @elseif(Route::currentRouteName() == 'epin_detay_paket_satin_al')
        {{ $paket->title }} Satın Al
        @elseif(Route::currentRouteName() == 'haber_detay')
        {{ $u->title }}
        @elseif(Route::currentRouteName() == 'twitch_support_yayinci')
        {{ $yayinciDB->title }}
        @elseif(Route::currentRouteName() == 'cd_key_detay')
        {{ $cdkey->title }}
        @else
        @if (getPage() != '/')
        {{ getPageTitle(getPage(), getLang()) }}
        @endif
        @endif
        @if (getPage() == '/')
        {{ getSiteName() }}
        @endif

    </title>
    <meta name="robots" content="@if (getCacheSetings()->robots == 1 && !noIndex()) index, follow @else noindex, nofollow @endif">

    @sectionMissing('head')
    @if (Route::currentRouteName() == 'oyun_baslik')
    <meta name="description" content="{{ getMetaSpecial(Route::currentRouteName(), $oyun->id, 0) }}">
    <meta name="keywords" content="{{ getMetaSpecial(Route::currentRouteName(), $oyun->id, 1) }}">
    @elseif(Route::currentRouteName() == 'game_gold_detay')
    <meta name="description" content="{{ getMetaSpecial(Route::currentRouteName(), $gold->id, 0) }}">
    <meta name="keywords" content="{{ getMetaSpecial(Route::currentRouteName(), $gold->id, 1) }}">
    @elseif(Route::currentRouteName() == 'epin_detay')
    <meta name="description" content="{{ getMetaSpecial(Route::currentRouteName(), $epin->id, 0) }}">
    <meta name="keywords" content="{{ getMetaSpecial(Route::currentRouteName(), $epin->id, 1) }}">
    @elseif(Route::currentRouteName() == 'item_detay')
    <meta name="description" content="{{ getMetaSpecial(Route::currentRouteName(), $item->id, 0) }}">
    <meta name="keywords" content="{{ getMetaSpecial(Route::currentRouteName(), $item->id, 1) }}">
    @elseif(Route::currentRouteName() == 'cd_key_detay')
    <meta name="description" content="{{ getMetaSpecial(Route::currentRouteName(), $cdkey->id, 0) }}">
    <meta name="keywords" content="{{ getMetaSpecial(Route::currentRouteName(), $cdkey->id, 1) }}">
    @else
    <meta name="description" content="{{ getMeta(getPage(), 0) }}">
    <meta name="keywords" content="{{ getMeta(getPage(), 1) }}">
    @endif
    @endif
    @yield('head')

    <link rel="icon" type="image/png" href="{{ asset('public/front/site/' . getCacheSetings()->favicon) }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <?php

    ?>
    @if(in_array(@$_SERVER['HTTP_USER_AGENT'],["Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/45.0.2454.101 Safari/537.36","Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/45.0.2454.0 Safari/537.36"]))
    <link rel="stylesheet" href="https://cdn.usebootstrap.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <?php $fileTime = filemtime(env('ROOT') . env('FRONT') . env('CSS') . 'style_akinsoft.css'); ?>
    <link href="{{ asset(env('ROOT') . env('FRONT') . env('CSS') . 'style_akinsoft.css') . '?' . $fileTime }}" rel="stylesheet">
    @else
    <link href="{{ asset(env('ROOT') . env('FRONT') . env('CSS') . 'bootstrap.min.css') }}" rel="stylesheet">
    <?php $fileTime = filemtime(env('ROOT') . env('FRONT') . env('CSS') . 'style.css'); ?>
    <link href="{{ asset(env('ROOT') . env('FRONT') . env('CSS') . 'style.css') . '?' . $fileTime }}" rel="stylesheet">
    @endif

    <link href="{{ asset(env('ROOT') . env('FRONT') . env('CSS') . 'OverlayScrollbars.min.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset(env('ROOT') . env('FRONT') . env('JS') . 'owlcarousel/assets/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset(env('ROOT') . env('FRONT') . env('JS') . 'owlcarousel/assets/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="{{ asset(env('ROOT') . env('FRONT') . env('VENDORS') . 'fontawesome/css/all.css') }}">
    <link href="{{ asset(env('ROOT') . env('FRONT') . env('CSS') . 'select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset(env('ROOT') . env('FRONT') . env('CSS') . 'toastr.min.css') }}" rel="stylesheet" />

    <link rel="preload" href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" as="style">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" media="print" onload="this.media='all'">

    {!! getCacheSetings()->meta !!}




    @yield('css')
</head>
@if (!isset($_COOKIE['theme']) or isset($_COOKIE['theme']) and $_COOKIE['theme'] == 'dark')

<body class="dark">
    <!-- dark theme -->
    @else

    <body>
        @endif
        <!-- Global site tag (gtag.js) - Google Ads: 10839620537 -->
        <!-- <script async src="https://www.googletagmanager.com/gtag/js?id=AW-10839620537"></script>
<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }
    gtag('js', new Date());
    gtag('config', 'AW-10839620537');
</script>

<script>
    gtag('event', 'conversion', {
        'send_to': 'AW-10839620537/GMl_CP2V1cQDELn33bAo',
        'transaction_id': ''
    });
</script> -->

        <!-- Dijital Uzmani - Google Tag Manager (noscript) <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-W2Z4J89" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>  -->
        <?php /*
        <div id="loader">
            <div class="spinner-border m-5" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div> */
        ?>
        <section class="site-header-area @if (getPage() == '/') main-page @endif">

            <header>
                {{-- @include('front.layouts.structures.topbar') --}}
                <div class="header-top-nav">
                    <div class="container">
                        <div class="row">
                            <div class="col-6">
                                <ul class="header-top-links">
                                    <li>
                                        <a href="{{ route('blog') }}">Blog</a>
                                        <!--a href="{{ route('yorumlarTum') }}">Yorumlar</a-->
                                        <a href="{{ route('sayfa', 'hakkimizda') }}">Hakkımızda</a>
                                        <a href="{{ route('sssSayfasi') }}">SSS</a>
                                        <a href="{{ route('marka_yonergeleri') }}">Marka Yönergeleri</a>
                                        <a href="https://wa.me/908503080007" target='blank'><i class="fa-whatsapp fab"></i>
                                            WhatsApp Destek</a>
                                        <i class="fa fa-donate" style="color: #80f1b1 !important;"> </i> <a href="https://oyuneks.com/twitch-support" style="color: #80f1b1 !important;">Yayıncı
                                            Destekle</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-6">

                                <ul class="header-top-social-links">

                                    <?php $settings = getCacheSetings(); ?>
                                    <li><a class="in" href="https://discord.gg/oyuneks" rel="nofollow noreferrer noopener" target="_blank"><i class="fab fa-discord"></i></a></li>
                                    @if ($settings->twitch != '')
                                    <li><a class="fb" href="{{ $settings->twitch }}" rel="nofollow noreferrer noopener" target="_blank"><i class="fab fa-twitch"></i></a></li>
                                    @endif
                                    @if ($settings->facebook != '')
                                    <li><a class="fb" href="{{ $settings->facebook }}" rel="nofollow noreferrer noopener" target="_blank"><i class="fab fa-facebook"></i></a></li>
                                    @endif
                                    @if ($settings->twitter != '')
                                    <li><a class="tw" href="{{ $settings->twitter }}" rel="nofollow noreferrer noopener" target="_blank"><i class="fab fa-twitter"></i></a></li>
                                    @endif
                                    @if ($settings->youtube != '')
                                    <li><a class="yt" href="{{ $settings->youtube }}" rel="nofollow noreferrer noopener" target="_blank"><i class="fab fa-youtube"></i></a></li>
                                    @endif
                                    @if ($settings->linkedin != '')
                                    <li><a class="li" href="{{ $settings->linkedin }}" rel="nofollow noreferrer noopener" target="_blank"><i class="fab fa-linkedin"></i></a></li>
                                    @endif
                                    @if ($settings->instagram != '')
                                    <li><a class="in" href="{{ $settings->instagram }}" rel="nofollow noreferrer noopener" target="_blank"><i class="fab fa-instagram"></i></a></li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="oyuneks_header">
                    <div class="container">
                        <div class="header-body">

                            <div class="header-site-logo">

                                <a href="{{ route('homepage') }}" class="site-logo">
                                    <img class="light" src="{{ asset('public/front/site/' . getCacheSetings()->logo) }}" alt="Logo">
                                    <img class="dark" src="{{ asset('public/front/site/' . getCacheSetings()->logo_white) }}" alt="Logo">
                                </a>
                            </div>

                            <div class="header-site-search">
                                <div class="input-group header-search-element">
                                    <label>
                                        <input class="form-control" type="text" name="q" autocomplete="off">
                                        <button class="btn btn-outline-secondary header-search-btn" type="button" id="button-addon2"><i class="fas fa-search"></i></button>
                                    </label>
                                    <div class="search-prom">
                                        <div class="search-prom-top">
                                            <span>En Popüler Aramalar</span>
                                            <article>
                                                <?php
                                                $epinH = getCacheEpinPopular();
                                                $gameGoldH = getCacheGameGoldPopular();
                                                ?>
                                                @foreach ($epinH as $uH)
                                                @if (DB::table('games_packages')->where('id', $uH->paketId)->whereNull('deleted_at')->count() > 0)
                                                <?php
                                                $epinHH = getCacheEpinPopularSingle($uH->game_title);
                                                $paketHH = getCacheEpinPopularSinglePackage($uH->paketId);
                                                ?>
                                                <a href="{{ route('epin_detay_paket', [$epinHH->link, Str::slug($paketHH->title) . '-' . $paketHH->id]) }}">
                                                    <img class="lazyload" data-src="{{ asset(env('ROOT') . env('FRONT') . env('GAMES_PACKAGES') . $paketHH->image) }}" alt="Searched Image">
                                                    <span>{{ $paketHH->title }}</span>
                                                    <span class="moneysymbol">₺</span>{{ MF(findGamesPackagesPrice($paketHH->id)) }}
                                                </a>
                                                @endif
                                                @endforeach
                                                @foreach ($gameGoldH as $uH)
                                                <?php
                                                $paketHH = getCacheGameGoldPopularSingle($uH->paket);
                                                $goldHH = getCacheGameGoldPopularSinglePackage($paketHH->games_titles);
                                                ?>
                                                <a href="{{ route('game_gold_detay_paket', [$goldHH->link, Str::slug($paketHH->title) . '-' . $paketHH->id]) }}">
                                                    <img class="lazyload" data-src="{{ asset(env('ROOT') . env('FRONT') . env('GAMES_PACKAGES_TRADE') . $paketHH->image) }}" alt="Searched Image">
                                                    <span>{{ $paketHH->title }}</span>
                                                    <span class="moneysymbol">₺</span>{{ findGamesPackagesTradeMusteriyeSatPrice($paketHH->id) }}
                                                </a>
                                                @endforeach

                                            </article>
                                        </div>
                                    </div>
                                    <div class="search-result"></div>


                                </div>
                            </div>
                            <div class="header-loginRegister">
                                @if (isset(Auth::user()->id))
                                @include('front.layouts.structures.bildirimler')
                                <div class="dropdown user_button">
                                    <a href="#" class="header-btn-inline user_button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <div class="avatar"><img class="avatar-img" alt="Searched Image" @if (Auth::user()->avatar != '') src="{{ asset('/public/front/avatars/' . Auth::user()->avatar) }}"
                                            @else src="{{ asset('/public/front/avatars/brandiconsquare.png') }}" @endif
                                            alt="{{ Auth::user()->name }} Avatarı">
                                        </div>
                                        <span class="webusername">{{ Auth::user()->name }}</span>
                                    </a>


                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton2">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('bakiye_ekle') }}">
                                                <span><i class="fas fa-wallet"></i></span> <span class="moneysymbol">₺</span><span class="bakiye">{{ MF(Auth::user()->bakiye + Auth::user()->bakiye_cekilebilir) }}</span></a>
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><a class="dropdown-item" href="{{ route('hesabim') }}"><span><i class="fal fa-user"></i></span>Hesabım</a></li>
                                        <li><a class="dropdown-item" href="{{ route('odemelerim') }}"><span><i class="fal fa-money-check-alt"></i></span>Ödemelerim</a>
                                        </li>
                                        <li><a class="dropdown-item" href="{{ route('ayarlarim') }}"><span><i class="fal fa-cog"></i></span>Ayarlarım</a></li>
                                        <li><a class="dropdown-item" href="{{ route('satici_panelim') }}"><span><i class="fal fa-cart-arrow-down"></i></span>Satıcı Paneli</a>
                                        </li>
                                        <li><a class="dropdown-item" href="{{ route('alici_panelim') }}"><span><i class="fal fa-cart-arrow-down"></i></span>Alıcı
                                                Paneli</a>
                                        </li>
                                        @if (DB::table('twitch_support_streamer')->where('user', Auth::user()->id)->count() > 0)
                                        <li><a class="dropdown-item" href="{{ route('twitch_support_yayinci_panelim') }}">
                                                <span><i>
                                                        <img src="{{ asset('public/front/images/streamlabs.png') }}" alt="Searched Image" width="22" height="22">
                                                    </i>
                                                </span>
                                                Yayıncı Paneli</a>
                                        </li>
                                        @endif
                                        @if (Auth::user()->role == 0)
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><a class="dropdown-item" href="{{ route('site_yonetim') }}"><span><i class="fas fa-columns"></i></span>Panel</a></li>
                                        @endif
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><a class="dropdown-item" href="{{ route('cikis') }}"><span><i class="fal fa-sign-out-alt"></i></span>Çıkış Yap</a>
                                        </li>
                                    </ul>
                                </div>
                                @else
                                <a class="header-btn-inline login_button" href="{{ route('giris') }}">Giriş Yap</a>
                                <a class="header-btn-inline register_button" href="{{ route('kayit') }}">Kayıt Ol</a>
                                @endif

                                <div class="form-theme-switch">
                                    <label>
                                        <input class="change-theme" type="checkbox" id="flexSwitchCheckDefault" @if (!isset($_COOKIE['theme']) or isset($_COOKIE['theme']) and $_COOKIE['theme']=='dark' ) checked @endif>
                                        <span>
                                            <i class="fas fa-sun"></i>
                                            <i class="fas fa-moon"></i>
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="header_bottom">
                    <div class="container">
                        <div class="header-bottom_container">
                            <div class="mobile-nav-button"><i class="fas fa-bars"></i></div>
                            <div class="header_bottom_left">


                                <a href="{{ route('homepage') }}" class="site-logo">
                                    <img src="{{ asset(env('ROOT') . env('BRAND') . 'brandicon.png') }}" alt="Logo">
                                </a>
                                <a href="{{ route('homepage') }}" class="site-logo-mobile">
                                    <img src="{{ asset(env('ROOT') . env('BRAND') . 'brandtext_white.png') }}" alt="Logo">
                                </a>
                                <ul class="main-nav">
                                    @foreach (getCacheHeaderMenu() as $a)
                                    @if ($a->sub_menu == 0)
                                    <li class="list-item">
                                        <a href="{{ $a->link }}" @if(strtolower($a->title) == 'blog') target="_blank" @endif>{{ $a->title }}</a>
                                        <!-- ana başlık -->
                                        <?php
                                        $header_menu_sub_1 = getCacheHeaderMenuSub1Count($a->id);
                                        ?>
                                        @if ($header_menu_sub_1 > 0)
                                        <span><i class="fas fa-chevron-right"></i></span>
                                        <?php // alt menü
                                        ?>
                                        <ul class="sub-menu">
                                            <?php
                                            $header_menu_sub_1_get = getCacheHeaderMenuSub1Get($a->id);
                                            ?>
                                            @foreach ($header_menu_sub_1_get as $uu)
                                            <?php
                                            $header_menu_sub_2 = getCacheHeaderMenuSub2Count($uu->id);
                                            ?>
                                            @if ($header_menu_sub_2 > 0)
                                            <li class="sub-item child">
                                                @else
                                            <li class="sub-item">
                                                @endif
                                                <a href="{{ $uu->link }}">
                                                    @if ($uu->image != '')
                                                    <img src="{{ asset('public/front/mega_menu/' . $uu->image) }}" alt="Menu Image">
                                                    @endif
                                                    <span>{{ $uu->title }}</span>
                                                </a>
                                                <?php
                                                $header_menu_sub_3 = getCacheHeaderMenuSub3Count($uu->id);
                                                ?>
                                                @if ($header_menu_sub_3 > 0)
                                                <ul class="sub-menu">
                                                    <?php
                                                    $header_menu_sub_3_get = getCacheHeaderMenuSub3Get($uu->id);
                                                    ?>
                                                    @foreach ($header_menu_sub_3_get as $uuu)
                                                    <li class="sub-item">
                                                        <a href="{{ $uuu->link }}">

                                                            <span>{{ $uuu->title }}</span>
                                                        </a>
                                                    </li>
                                                    @endforeach
                                                </ul>
                                                @endif
                                            </li>
                                            @endforeach
                                        </ul>
                                        @endif
                                    </li>
                                    @endif
                                    @endforeach
                                </ul>
                            </div>


                            @if (isset(Auth::user()->id))
                            <div class="header_bottom_right">
                                @php
                                $caseclass="";
                                $checked="";
                                @endphp
                                @if (!isset($_COOKIE['caseflash']) or isset($_COOKIE['caseflash']) and $_COOKIE['caseflash'] == 'on')
                                @php
                                $caseclass="flash";
                                $checked="checked";
                                @endphp
                                @endif

                                <div class="header-mini-user {{ $caseclass }}">
                                    <div class="more-cash">
                                        <a href="{{ route('bakiye_ekle') }}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Bakiye Ekle"><i class="far fa-plus"></i></a>

                                    </div>
                                    <div class="cash">
                                        <span class="moneysymbol">₺</span>
                                        <span class="bakiye">{{ MF(Auth::user()->bakiye + Auth::user()->bakiye_cekilebilir, 2) }}</span>
                                    </div>
                                    <div class="cash-sh-button">
                                        <label>
                                            <input type="checkbox" {{ $checked }} autocomplete="off" name="cashflasher">
                                        </label>
                                        <i class="far fa-eye"></i>
                                        <i class="far fa-eye-slash"></i>
                                    </div>
                                    <div class="hbr-login dropdown">

                                        <a href="#" class="webuser" data-bs-toggle="dropdown">
                                            <div class="avatar"><img class="avatar-img" alt="Avatar" @if (Auth::user()->avatar != '') src="{{ asset('/public/front/avatars/' . Auth::user()->avatar) }}"
                                                @else src="{{ asset('/public/front/avatars/brandiconsquare.png') }}" @endif
                                                alt="{{ Auth::user()->name }} Avatarı">
                                            </div>
                                        </a>
                                        <ul class="user-megamenu dropdown-menu" aria-labelledby="dropdownMenuButton2">
                                            <li><a class="dropdown-item" href="{{ route('hesabim') }}"><span><i class="fal fa-user"></i></span>Hesabım</a></li>
                                            <li><a class="dropdown-item" href="{{ route('odemelerim') }}"><span><i class="fal fa-money-check-alt"></i></span>Ödemelerim</a></li>
                                            <li><a class="dropdown-item" href="{{ route('ayarlarim') }}"><span><i class="fal fa-cog"></i></span>Ayarlarım</a></li>
                                            <li><a class="dropdown-item" href="{{ route('satici_panelim') }}"><span><i class="fal fa-cart-arrow-down"></i></span>Satıcı Paneli</a>
                                            </li>
                                            <li><a class="dropdown-item" href="{{ route('alici_panelim') }}"><span><i class="fal fa-cart-arrow-down"></i></span>Alıcı Paneli</a></li>
                                            @if (DB::table('twitch_support_streamer')->where('user', Auth::user()->id)->count() > 0)
                                            <li><a class="dropdown-item" href="{{ route('twitch_support_yayinci_panelim') }}"><span><i>
                                                            <img src="{{ asset('public/front/images/streamlabs.png') }}" alt="Avatar" width="22" height="22">
                                                        </i>
                                                    </span>
                                                    Yayıncı Paneli</a>
                                            </li>
                                            @endif
                                            @if (Auth::user()->role == 0)
                                            <li><a class="dropdown-item border-line" href="{{ route('panel') }}"><span><i class="fas fa-columns"></i></span>Panel</a></li>
                                            @endif
                                            <li><a class="dropdown-item" href="{{ route('cikis') }}"><span><i class="fal fa-sign-out-alt"></i></span>Çıkış Yap</a> </li>
                                        </ul>


                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="header_bottom_right loginframe">
                                <a class="hr-button st-btn" href="{{ route('giris') }}">Giriş Yap</a>
                                <a class="hr-button st-btn" href="{{ route('kayit') }}">Kayıt Ol</a>
                            </div>
                            <div class="mobile-login">
                                <a href="{{ route('giris') }}">Giriş Yap</a>
                            </div>
                            @endif


                            <div class="mobile-nav-user-button"></div>
                        </div>
                        <div class="row">
                            <div class="mobile-container"></div>
                        </div>
                        <div class="row">
                            <div class="mobile-user-container"></div>
                        </div>
                    </div>
                </div>
            </header>
        </section>
        @if (getPage() != '/')
        @include('front.layouts.structures.breadcrumb')
        @endif
