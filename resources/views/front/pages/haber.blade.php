@extends('front.layouts.app')
@section('body')
        <?php
            $u = \App\Models\News::where('link', $haber)->first();
        $image = explode(".", $u->image);
        $image = $image[0] . "@2x." . $image[1];
        ?>
        <img src="{{asset(env('root').env('front').env('news').$image)}}" alt="{{$u->title}}">
        {{$u->title}}
        {{$u->text_short}}
        {{$u->text}}

@endsection
