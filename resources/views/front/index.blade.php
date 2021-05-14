@extends('front.layouts.app')
@section('body')
    <div class="container-fluid">
        @include('front.modules.slider')
    </div>
    <div class="container popular-game">
        @include('front.modules.popular-games')
    </div>
    <div class="container news">
        @include('front.modules.news')
    </div>
    <div class="container comments">
        @include('front.modules.comments')
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function(){
            $('.owl-carousel').owlCarousel({
                loop:true,
                margin:10,
                nav:false,
                responsiveClass:true,
                responsive:{
                    0:{
                        items:1,
                        nav:true
                    },
                    600:{
                        items:3,
                        nav:false
                    },
                    1000:{
                        items:4,
                        nav:true,
                        loop:true
                    }
                }
            })
            var owl = $('.owl-carousel');
            owl.owlCarousel();
            $('.customNextBtn').click(function() {
                owl.trigger('next.owl.carousel');
            })
            $('.customPrevBtn').click(function() {
                owl.trigger('prev.owl.carousel', [300]);
            })
        });
    </script>
    @endsection
