<?php
use Carbon\Carbon;
?>
@extends('front.layouts.app')
@section('css')
    <link
            rel="stylesheet"
            href="https://unpkg.com/swiper@8/swiper-bundle.min.css"
    />
@endsection
@section('body')
    <?php /*
    if (Auth::user()->izin != 0) {
        header("Location: " . URL::to(route('errors_403')), true, 302);
        exit();
    }*/
    $cdkey = DB::table('muve_games')->where('link', $cdkey)->whereNull('deleted_at')->first();
    if (!$cdkey) {
        header("Location: " . URL::to(route('errors_404')), true, 302);
        exit();
    }
    if ($cdkey->winSup != 1) {
        $cdkey->winGer = false;
    }
    if ($cdkey->macSup != 1) {
        $cdkey->macGer = false;
    }
    if ($cdkey->linuxSup != 1) {
        $cdkey->linuxGer = false;
    }
    $cdkey->categories = explode("\n", $cdkey->categories);
    $cdkey->images = explode("\n", $cdkey->images);
    $cdkey->videos = explode("\n", $cdkey->videos);
    if ($cdkey->steamId == 0) {
        $cdkey->image = asset('public/front/games/' . $cdkey->image);
        $cdkey->background = asset('public/front/games/' . $cdkey->background);
    }
    ?>
    <section class="bg-gray pb-100">
        <div class="container">
            <div class="row">
                <div class="col-12">

                    <div class="game-content">
                        <div class="row">
                            <div class="col-md-8 col-sm-12 game-cont-body">
                                <div class="game-content-wrapper">
                                    <div class="swiper gameswiper">
                                        <!-- Additional required wrapper -->
                                        <div class="swiper-wrapper">
                                            <!-- Slides -->

                                            @if($cdkey->videos[0] != '')
                                                @foreach($cdkey->videos as $vid)
                                                    <?php
                                                    $vidPoster = $vid;
                                                    if(gettype(strpos($vid, "https")) != 'integer')
                                                    $vid = str_replace("http", "https", $vid);
                                                    
                                                    if ($cdkey->steamId > 0) {
                                                        $vidExp = explode("/", $vidPoster);
                                                        $vidExp[6] = "movie.293x165.jpg";
                                                        $vidPoster = $vidExp[0] . '//';
                                                        $vidPoster .= $vidExp[2] . '/';
                                                        $vidPoster .= $vidExp[3] . '/';
                                                        $vidPoster .= $vidExp[4] . '/';
                                                        $vidPoster .= $vidExp[5] . '/';
                                                        $vidPoster .= $vidExp[6];
                                                    } else {
                                                        $vidPoster = asset('public/front/games/' . $cdkey->image);
                                                    }
                                                    ?>
                                                    <div class="swiper-slide">
                                                        <video class="swiper-lazy" controls src="{{$vid}}"
                                                               poster="{{$vidPoster}}">
                                                            <source data-src="{{$vid}}" type="video/webm">
                                                            Your browser does not support HTML video.
                                                        </video>
                                                    </div>
                                                @endforeach
                                            @endif
                                            @foreach($cdkey->images as $img)
                                                <div class="swiper-slide">
                                                    <img class="swiper-lazy" src="{{$img}}" alt="{{$cdkey->alt}}">
{{--                                                    <div class="swiper-lazy-preloader swiper-lazy-preloader-white"></div>--}}
                                                </div>
                                            @endforeach

                                        </div>
                                        <!-- If we need pagination -->
                                        <div class="swiper-pagination"></div>

                                        <!-- If we need navigation buttons -->
                                        <div class="swiper-button-prev"></div>
                                        <div class="swiper-button-next"></div>

                                        <!-- If we need scrollbar -->
                                        <div class="swiper-scrollbar"></div>
                                    </div>
                                    <div class="Thumbswiper-wrapper">
                                        <div thumbsSlider="" class="swiper Thumbswiper">
                                            <div class="swiper-wrapper">
                                                @if($cdkey->videos[0] != '')
                                                    @foreach($cdkey->videos as $vid)
                                                        <?php
                                                        if ($cdkey->steamId > 0) {
                                                            $vidExp = explode("/", $vid);
                                                            $vidExp[6] = "movie.293x165.jpg";
                                                            $vid = $vidExp[0] . '//';
                                                            $vid .= $vidExp[2] . '/';
                                                            $vid .= $vidExp[3] . '/';
                                                            $vid .= $vidExp[4] . '/';
                                                            $vid .= $vidExp[5] . '/';
                                                            $vid .= $vidExp[6];
                                                        } else {
                                                            $vid = asset('public/front/games/' . $cdkey->image);
                                                        }
                                                        ?>
                                                        <div class="swiper-slide video-click">
                                                            <img class="swiper-lazyx" src="{{$vid}}" alt="{{$cdkey->alt}}">
                                                        </div>
                                                    @endforeach
                                                @endif
                                                @foreach($cdkey->images as $img)
                                                    <div class="swiper-slide"><img class="swiper-lazyx" src="{{$img}}" alt="{{$cdkey->alt}}">
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <div class="textarea">
                                        {!! $cdkey->description !!}
                                    </div>

                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12 game-cont-right">
                                <div class="game-right-wrap stickDOM">
                                    <picture class="coverPicture"><img src="{{$cdkey->image}}" alt="{{$cdkey->alt}}"></picture>


                                    <div class="game-info">
                                        <h3>{{$cdkey->title}}</h3>
                                        <h6>{{$cdkey->developers}}</h6>
                                        <p>{{$cdkey->shortDesc}}</p>

                                    </div>
                                    <ul class="buy">

                                        <li class="price">

                                            <?php
                                            $simdiki = date('Y-m-d H:i:s');
                                            $dateTimeS = new DateTime($simdiki);
                                            $timestampS = $dateTimeS->format('U');
                                            $kaydedilen = Carbon::parse($cdkey->discount_date)->format('Y-m-d H:i:s');
                                            $dateTimeK = new DateTime($kaydedilen);
                                            $timestampK = $dateTimeK->format('U');
                                            $kalanSaniyeToplam = $timestampK - $timestampS;
                                            ?>
                                            @if($cdkey->discount_type != 0 and $kalanSaniyeToplam >= 0)
                                                <div class="indirim">
                                                    <div class="timer" data-time="{{$kalanSaniyeToplam}}">
                                                        <div class="gun"><span>00</span>
                                                            <p>Gün</p></div>
                                                        <div class="saat"><span>00</span>
                                                            <p>Saat</p></div>
                                                        <div class="dk"><span>00</span>
                                                            <p>dk</p></div>
                                                        <div class="saniye"><span>00</span>
                                                            <p>sn</p></div>
                                                    </div>
                                                    @endif
                                                    @if($cdkey->discount_type != 0 and $kalanSaniyeToplam >= 0)
                                                        @if($cdkey->discount_type == 1)
                                                            <div class="discount">

                                                                <p class="indirim-tutari">
                                                                    %{{$cdkey->discount_amount}}</p>
                                                                <p class="eski-fiyat">
                                                                    <span>₺</span>{{MF(currencyConverter($cdkey->muvePrice, $cdkey->muveCurrency, 'TRY'))}}</p>
                                                                <p class="yeni-fiyat">
                                                                    <span>₺</span>{{MF(getMuveGamesPrice($cdkey->id))}}
                                                                </p>
                                                            </div>
                                                        @else
                                                            <div class="discount">

                                                                <p class="indirim-tutari">{{$cdkey->discount_amount}}
                                                                    TL</p>
                                                                <p class="eski-fiyat">
                                                                    <span>₺</span>{{MF(currencyConverter($cdkey->price, $cdkey->muveCurrency, 'TRY'))}}</p>
                                                                <p class="yeni-fiyat">
                                                                    <span>₺</span>{{MF(getMuveGamesPrice($cdkey->id))}}
                                                                </p>
                                                            </div>

                                                        @endif
                                                </div>
                                            @else
                                                <p class="price">
                                                    <span>₺</span>{{MF(getMuveGamesPrice($cdkey->id))}}</p>
                                            @endif

                                        </li>
                                        <li class="price-button"><a class="btn-inline color-darkgreen small"
                                                                    href="{{route('cd_key_detay_satin_al', $cdkey->link)}}">Satın
                                                Al</a></li>

                                    </ul>
                                    <ul class="game-title-body">
                                        <li class="li-row"><strong>Geliştirici
                                                Firma</strong><span>{{$cdkey->developers}}</span></li>
                                        <li class="li-row"><strong>Yayınlanma
                                                Tarihi</strong><span>{{$cdkey->releaseDate}}</span></li>
                                        <li class="li-row"><strong>Kategoriler</strong>
                                            <ul class="list">
                                                @foreach($cdkey->categories as $kat)
                                                    <li>{{$kat}}</li>
                                                @endforeach
                                            </ul>
                                        </li>
                                    </ul>
                                    @if($cdkey->metaScore)
                                        <ul class="metascore">
                                            <?php
                                            $metaclass = "";
                                            if ($cdkey->metaScore < 100 && $cdkey->metaScore > 74) {
                                                $metaclass = "perfectScore";
                                            } elseif ($cdkey->metaScore < 75 && $cdkey->metaScore > 49) {
                                                $metaclass = "middleScore";
                                            } else {
                                                $metaclass = "badScore";
                                            }
                                            ?>
                                            <li>
                                                <a class="metalink" href="{{$cdkey->metaLink}}"><img src="{{asset('front/images/icons/metascore-icon.svg')}}">
                                                    <strong>Metascore</strong>
                                                    <span class="{{$metaclass}}">{{$cdkey->metaScore}}</span></a>
                                            </li>
                                        </ul>
                                    @endif
                                    <ul class="system-req">
                                        <li class="li-col">
                                            <strong class="title">Desteklenen Platformlar</strong>
                                            @if($cdkey->winSup) <i class="fab fa-windows"></i> Windows @endif
                                            @if($cdkey->macSup)<i class="fab fa-apple"></i> Mac @endif
                                            @if($cdkey->linuxSup)<i class="fab fa-linux"></i> Linux @endif
                                        </li>
                                        <li class="li-col">
                                            <strong class="title">Desteklenen Diller</strong>
                                            {!!$cdkey->supLang!!}
                                        </li>
                                        @if($cdkey->winSup)
                                            <li class="li-col">
                                                <strong class="title">Windows Gereksinimler</strong>
                                                {!!$cdkey->winGer!!}
                                            </li>
                                        @endif
                                        @if($cdkey->macSup)
                                            <li class="li-col">
                                                <strong class="title">Mac Gereksinimler</strong>
                                                {!!$cdkey->macGer!!}
                                            </li>
                                        @endif
                                        @if($cdkey->linuxSup)
                                            <li class="li-col">
                                                <strong class="title">Linux Gereksinimler</strong>
                                                {!!$cdkey->linuxGer!!}
                                            </li>
                                        @endif


                                    </ul>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
        <div class="banner-background">
{{--            <picture><img src="{{$cdkey->background}}"></picture>--}}

        </div>
    </section>
@endsection
@section('js')
    <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

    <script>
        $(function () {
            "use strict";
            var swiper2 = new Swiper(".Thumbswiper", {

                spaceBetween: 10,
                slidesPerView: 8,
                freeMode: true,
                watchSlidesProgress: true,
            });
            var swiper = new Swiper('.gameswiper', {
                // Optional parameters
                lazy: false,
                loop: false,

                // If we need pagination

                // Navigation arrows
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },

                // And if we need scrollbar
                scrollbar: {
                    el: '.swiper-scrollbar',
                },
                thumbs: {
                    swiper: swiper2,
                },
            });

            swiper.on('slideChange', function () {
                if (swiper.slides[this.previousIndex].children[0].tagName == "VIDEO") {
                    if (!swiper.slides[this.previousIndex].children[0].paused) {
                        swiper.slides[this.previousIndex].children[0].pause()
                    }
                }
            });

        });
    </script>
@endsection
