@extends('front.layouts.app')
@section('css')
<?php /*
    <style>
        .skeleton-box {
            display: inline-block;
            position: relative;
            overflow: hidden;
            background-color: #dddbdd;
            width: 100%;
            height: 267px;
        }

        .skeleton-box::after {
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            transform: translateX(-100%);
            background-image: linear-gradient(90deg, rgba(255, 255, 255, 0) 0, rgba(255, 255, 255, 0.2) 20%, rgba(255, 255, 255, 0.5) 60%, rgba(255, 255, 255, 0));
            -webkit-animation: shimmer 2s infinite;
            animation: shimmer 2s infinite;
            content: "";
        }


        .skeleton-box-items {
            display: inline-block;
            position: relative;
            overflow: hidden;
            background-color: #dddbdd;
            width: 80px;
            height: 80px;
            border-radius: 50%;
        }

        .skeleton-box-items::after {
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            transform: translateX(-100%);
            background-image: linear-gradient(90deg, rgba(255, 255, 255, 0) 0, rgba(255, 255, 255, 0.2) 20%, rgba(255, 255, 255, 0.5) 60%, rgba(255, 255, 255, 0));
            -webkit-animation: shimmer 2s infinite;
            animation: shimmer 2s infinite;
            content: "";
        }

        @-webkit-keyframes shimmer {
            100% {
                transform: translateX(100%);
            }
        }

        @keyframes shimmer {
            100% {
                transform: translateX(100%);
            }
        }
    </style> */ ?>
@endsection
@section('body')
<section class="oyuneks_slider bg-style-1 header-margin">
    <div class="container">
        @include('front.modules.slider')
    </div>
</section>

<section class="game-icon-section bg-style-2">
    <div class="container" id="games-icons">
        @include('front.modules.games-icons')
    </div>
</section>

<section class="page-border bg-gray bg-style-1 pb-40">
    <div class="container">
        @include('front.modules.popular-games')
    </div>
</section>

<section class="page-border sec_shadow bg-gray bg-style-2 pb-40">
    <div class="container" id="popular-products">
        @include('front.modules.popular-products')
    </div>
</section>
<section class="tw-section sec_shadow bg-gray pb-40 bg-style-1 page-border">
    <div class="container" id="twitch">
        @include('front.modules.twitch')
    </div>
</section>
<section class="news-section sec_shadow bg-gray pb-40 bg-style-2 page-border">
    {{-- <div class="container" id="news">
        @include('front.modules.news')
    </div> --}}
</section>
<section class="sss bg-gray pb-40 page-border bg-style-1">
    <div class="container" id="faq">
        @include('front.modules.faq')
    </div>

</section>
@endsection
@section('js')

<script>
    $(document).ready(function() {
        $('#oyuneks-slider').owlCarousel({
            loop: true,
            margin: 0,
            nav: false,
            dots: true,
            autoplay: true,
            autoplaySpeed: 1000,
            smartSpeed: 450,
            responsiveClass: true,
            autoplayHoverPause:true,
            items: 1
        })
        var owl2 = $('.game_newss');
        owl2.owlCarousel();
        $('.game_newsNextBtn').click(function() {
            owl2.trigger('next.owl.carousel');
        })
        $('.game_newsPrevBtn').click(function() {
            owl2.trigger('prev.owl.carousel', [300]);
        })


        $('.icon-wrapper').owlCarousel({
            loop: false,
            margin: 10,
            nav: false,
            dots: false,
            autoplay: true,
            autoplaySpeed: 1000,
            smartSpeed: 450,
            responsiveClass: true,
            callback: deneme(),
            lazyLoad: true,
            responsive: {
                0: {
                    items: 6,
                    nav: true
                },
                600: {
                    items: 8,
                    nav: false
                },
                1000: {
                    items: 10,
                    nav: true,
                    loop: true,
                },
                1200: {
                    items: 12,
                }
            }
        })

        var owl_cat = $('.cok-satanlar');
        $('.cok-satanlar').owlCarousel({

            loop: true,
            lazyLoad: true,
            margin: 20,
            nav: true,
            autoplay: true,
            autoplaySpeed: 1000,
            smartSpeed: 450,
            responsiveClass: true,
            autoplayHoverPause:true,
            responsive: {
                0: {
                    items: 3,
                    nav: true
                },
                600: {
                    items: 3,
                    nav: false
                },
                1000: {
                    items: 4,
                    nav: true,
                    loop: true,

                },
                1200: {
                    items: 5,
                    nav: true,
                    loop: true,

                }
            }
        });
        $('.cat_newsNextBtn').click(function() {
            owl_cat.trigger('next.owl.carousel');
        })
        $('.cat_newsPrevBtn').click(function() {
            owl_cat.trigger('prev.owl.carousel', [300]);
        })

        function deneme() {
            var icons = $(".icon-items")

            for (var i = 0; i < icons.length; i++) {

                dn(icons[i], i)

            }
        }

        function dn(a, b) {

            setTimeout(function() {

                $(a).addClass("ready")

            }, b * 60);
        }


    });
    $(window).on('load', function() {
       /*  $('.lazyload').on('load', function() {
            $(this).addClass("loaded")
        });
        var  images = document.querySelectorAll(".lazyload");
        new LazyLoad(images, {
            root: null,
            rootMargin: "0px",
            threshold: 0
        }); */
        <?php /*
            /*
           * Oyun İkonları Ajax

            $.ajax({
                type: 'GET', url: "{{route('indexGamesIcons')}}",
                success: function (data) {
                    $("#games-icons").html(data);
                    $('.icon-wrapper').owlCarousel({

                        loop: false,
                        margin: 10,
                        nav: false,
                        dots: false,
                        autoplay: true,
                        autoplaySpeed: 1000,
                        smartSpeed: 450,
                        responsiveClass: true,
                        callback: deneme(),
                        responsive: {
                            0: {
                                items: 6,
                                nav: true
                            },
                            600: {
                                items: 8,
                                nav: false
                            },
                            1000: {
                                lazyLoad: true,
                                items: 10,
                                nav: true,
                                loop: true,

                            }, 1200: {

                                items: 12,


                            }
                        }
                    })
                }
            });
            */

        /*
             * Çok satanlar ajax

            $.ajax({
                type: 'GET', url: "{{route('indexCokSatanlar')}}",
                success: function (data) {
                    $("#popular-products").html(data);
                    $('.cok-satanlar').owlCarousel({

                        loop: true,
                        margin: 20,
                        nav: true,
                        autoplay: true,
                        autoplaySpeed: 1000,
                        smartSpeed: 450,
                        responsiveClass: true,
                        responsive: {
                            0: {
                                items: 3,
                                nav: true
                            },
                            600: {
                                items: 3,
                                nav: false
                            },
                            1000: {
                                lazyLoad: true,
                                items: 4,
                                nav: true,
                                loop: true,

                            }, 1200: {
                                lazyLoad: true,
                                items: 5,
                                nav: true,
                                loop: true,

                            }
                        }
                    })
                }
            });
            */
        /*
             * Twitch Ajax

            $.ajax({
                type: 'GET', url: "{{route('indexTwitch')}}",
                success: function (data) {
                    $("#twitch").html(data);
                }
            });
            */
        /*
             * News Ajax

            $.ajax({
                type: 'GET', url: "{{route('indexNews')}}",
                success: function (data) {
                    $("#news").html(data);
                }
            });
            */
        /*
             * Faq Ajax

            $.ajax({
                type: 'GET', url: "{{route('indexFaq')}}",
                success: function (data) {
                    $("#faq").html(data);
                }
            });
            
            */ ?>
        


    });
</script>
@endsection