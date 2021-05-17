@extends('front.layouts.app')
@section('body')
    <div class="container">
        @foreach(\App\Models\Games::whereNull('deleted_at')->where('status', '1')->get() as $u)
            <?php
            $image = explode(".", $u->image);
            $image = $image[0] . "@2x." . $image[1];
            ?>
            <img src="{{asset(env('root').env('front').env('games').$image)}}">
                <a href="{{route('oyun_baslik', $u->link)}}"> {{$u->title}} </a>
            {!! Str::limit(strip_tags($u->text), $limit = 100, $end = '...') !!}
        @endforeach
    </div>

@endsection
