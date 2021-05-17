@extends('front.layouts.app')
@section('body')
    <?php
    $oyun = \App\Models\Games::where('link', $oyun)->first();
    ?>

    @foreach(\App\Models\GamesTitles::whereNull('deleted_at')->where('status', '1')->where('game', $oyun->id)->get() as $u)
        <img src="{{asset(env('root').env('front').env('games_titles').$u->image)}}">
        <a href="#"> {{$u->title}} </a>
        {!! Str::limit(strip_tags($u->text), $limit = 100, $end = '...') !!}

    @endforeach


    {{$oyun->title}}
    {!! $oyun->text !!}


@endsection
