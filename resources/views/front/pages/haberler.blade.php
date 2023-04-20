@extends('front.layouts.app')
@section('body')
<section class="bg-gray pb-40">
    <div class="container">
        <div class="row">

            <?
            $haber=\App\Models\News::whereNull('deleted_at')->where('lang', getLang())->orderBy('created_at','desc')->where('status', '1')->paginate(20);
            ?>
            @foreach($haber as $u)
            <?php
            $image = explode(".", $u->image);
            $image = $image[0] . "@2x." . $image[1];
                if(!file_exists(asset(env('ROOT').env('FRONT').env('NEWS').$image))) {
                    $image = $u->image;
                }
            ?>
            <div class="col-md-6 col-lg-3 d-flex mb-4">
                <div class="news-body">
                    <a href="{{route('haber_detay', $u->link)}}">
                    <figure>
                        <img src="{{asset(env('ROOT').env('FRONT').env('NEWS').$image)}}" alt="{{$u->alt}}">
                    </figure>
                    </a>
                    <div class="news-description">
                        <a href="{{route('haber_detay', $u->link)}}" class="card-title heading-secondary">{{$u->title}}</a>
                        <p class="card-text">{{$u->text_short}}</p>
                        <span class="news-date">{{findNewsTime($u->id)}}</span>

                    </div>



                </div>
            </div>
            @endforeach

        </div>
    </div>

    @if(isset($haber) && $haber->total()>5)
        <div class="text-center">
            <div title="Önceki" class="prw btn btn-success">&lt;&lt;</div>
            <span class="about btn btn-outline-success font-monospace" style="cursor: default">{{$haber->currentPage()}} > <?=ceil($haber->total()/$haber->perPage())?></span>
            <div title="Sonraki" class="nxt btn btn-success">&gt;&gt;</div>
        </div>
    @endif
</section>
@endsection

@section('js')
    <script>
        @if(isset($haber) && strlen($haber->previousPageUrl())>10)
        $('.prw').click(function(){ location.href="{{$haber->previousPageUrl()}}"});
        @endif

        @if(isset($haber) && strlen($haber->nextPageUrl())>10)
        $('.nxt').click(function(){ location.href="{{$haber->nextPageUrl()}}"});
    @endif
    </script>
@endsection
