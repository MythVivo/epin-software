@extends('front.layouts.app')
@section('body')
    @foreach(\App\Models\News::whereNull('deleted_at')->where('lang', getLang())->orderBy('created_at', 'desc')->where('status', '1')->get() as $u)
        <?php
        $image = explode(".", $u->image);
        $image = $image[0] . "@2x." . $image[1];
        ?>
        <img src="{{asset(env('root').env('front').env('news').$image)}}" alt="{{$u->title}}">
        {{$u->title}}
        {{$u->text_short}}
        <a href="{{route('haber_detay', $u->link)}}">sdfsdf</a>
    @endforeach
@endsection
