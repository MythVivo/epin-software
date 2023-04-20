<?php
$u = \App\Models\News::where('link', $haber)->whereNull('deleted_at')->first();
if($u == NULL) {
    header("Location: " . URL::to(route('errors_404')), true, 302);
    exit();
}
?>
@extends('front.layouts.app')

@section('head')
    <meta name="description" content="{{$u->title}}">
    <meta name="keywords" content="{{$u->title}}">
@endsection

@section('body')

    <section class="game-news-page bg-gray pb-40">
        <div class="container">
            <div class="row">

                <div class="col-md-8">
                    <div class="page-wrapper">
                        <figure>
                            <div class="news-in-date">{{findNewsTime($u->id)}} <span></span>
                            </div>
                            <img src="{{asset(env('ROOT').env('FRONT').env('NEWS').$u->image)}}" alt="{{$u->alt}}">
                            <h1 class="heading-primary"> {{$u->title}}</h1>
                        </figure>
                        <article>

                            <h4 class="short-text"> {{$u->text_short}}</h4>
                            <hr>
                            {!! $u->text !!}
                        </article>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="more-news">

                                <h4 class="heading-secondary ">Son Eklenenler</h4>
                                @foreach(\App\Models\News::whereNull('deleted_at')->orderBy('created_at', 'desc')->where('status', '1')->take(3)->get() as $uu)
                            <div class="more-news-card">
                                <a href="{{route('haber_detay', $uu->link)}}"
                                   class="card-title">
                                    <?php
                                    $image = explode(".", $uu->image);
                                    $image = $image[0] . "@2x." . $image[1];
                                    if(!file_exists(asset(env('ROOT').env('FRONT').env('NEWS').$image))) {
                                        $image = $uu->image;
                                    }
                                    ?>
                                    <img src="{{asset(env('ROOT').env('FRONT').env('NEWS').$image)}}" alt="{{$uu->title}}">
                                    <div class="news-thumb-title"><span>{{$uu->title}}</span></div>
                                </a>
                            </div>
                                @endforeach


                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
