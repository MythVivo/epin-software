@extends('front.layouts.app')
@section('body')
<section class="game header-margin pt-100 pb-100">
    <div class="container">
        <div class="row">
            @foreach(\App\Models\Games::whereNull('deleted_at')->where('status', '1')->get() as $u)
            <?php
            $image = explode(".", $u->image);
            $image = $image[0] . "@2x." . $image[1];
            ?>

            <div class="col-md-6 col-lg-4">
                <div class="game-card">
                    <figure>
                        <img src="{{asset(env('root').env('front').env('games').$image)}}">
                    </figure>
                    <div class="game-card-description">
                        <a class="btn-inline color-blue title-button" href="{{route('oyun_baslik', $u->link)}}"> {{$u->title}} </a>
                        <p>{!! Str::limit(strip_tags($u->text), $limit = 100, $end = '...') !!}</p>
                    </div>
                </div>
            </div>

            @endforeach
        </div>
    </div>
</section>
@endsection
