@extends('front.layouts.app')
@section('body')
<section class="game-news-page header-margin pt-100 pb-100">
    <div class="container">
        <div class="row">
            <?php
            $u = \App\Models\News::where('link', $haber)->first();
            $image = explode(".", $u->image);
            $image = $image[0] . "@2x." . $image[1];
            ?>

            <div class="col-md-8">
                <figure>
                    <span class="news-in-date">1 Ay Önce</span>
                    <img src="{{asset(env('root').env('front').env('news').$image)}}" alt="{{$u->title}}">
                    <h1 class="heading-primary"> {{$u->title}}</h1>
                </figure>
                <article>

                    <h4 class="short-text"> {{$u->text_short}}</h4>
                    <hr>
                    {{$u->text}}
                </article>
            </div>
        </div>
    </div>
</section>
@endsection
