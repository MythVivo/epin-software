@extends('front.layouts.app')
@section('body')
<section class="game header-margin pt-100 pb-100">
    <div class="container">
        <div class="row">

            @foreach(\App\Models\News::whereNull('deleted_at')->where('lang', getLang())->orderBy('created_at',
            'desc')->where('status', '1')->get() as $u)
            <?php
            $image = explode(".", $u->image);
            $image = $image[0] . "@2x." . $image[1];
            ?>

            <div class="col-md-6 col-lg-4">
                <div class="game-card">
                    <figure>
                        <img src="{{asset(env('ROOT').env('FRONT').env('NEWS').$image)}}" alt="{{$u->title}}">
                    </figure>

                    <div class="game-card-description">
                        <span class="date">1 gün önce</span>
                        <a class="btn-inline color-blue title-button" href="{{route('haber_detay', $u->link)}}">
                            {{$u->title}} </a>
                        <p>{{$u->text_short}}</p>
                    </div>
                </div>
            </div>
            @endforeach

        </div>
    </div>
</section>
@endsection
