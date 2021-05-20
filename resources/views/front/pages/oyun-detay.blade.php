@extends('front.layouts.app')
@section('body')
<?php
$oyun = \App\Models\Games::where('link', $oyun)->first();
?>
<section class="pt-140">
    <div class="container">
        <div class="row">
            <div class="col-md-8">

                <div class="game-image">

                </div>
                <h1 class="heading-primary">{{$oyun->title}}</h1>
                <p>{!! $oyun->text !!}</p>


            </div>
            <div class="col-md-4">
                <div class="game-aside">
                    @foreach(\App\Models\GamesTitles::whereNull('deleted_at')->where('status', '1')->where('game',
                    $oyun->id)->get() as $u)

                    <figure>
                        <img src="{{asset(env('root').env('front').env('games_titles').$u->image)}}">
                        <a href="#"> {{$u->title}} </a>
                        <p>{!! Str::limit(strip_tags($u->text), $limit = 100, $end = '...') !!}</p>
                    </figure>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
