<div class="row title-area" data-lang="{{getLang()}}">
    <div class="col-sm-12 col-md-9 title">
        <h1 class="heading-primary">@lang('general.haberler')</h1>
    </div>
    <div class="col-sm-12 col-md-3 button-area">
        <a href="#">@lang('general.tumunuGoruntule') >></a>
    </div>
</div>

@foreach(\App\Models\News::whereNull('deleted_at')->where('lang', getLang())->orderBy('created_at', 'desc')->take(4)->get() as $u)
    <?php
    $image = explode(".", $u->image);
    $image = $image[0] . "@2x." . $image[1];
    ?>
    @if($loop->first)
        <div class="card h2x mb-100">
            <div class="row">
                <div class="col-md-6">
                    <img src="{{asset(env('root').env('front').env('news').$image)}}" alt="{{$u->title}}">
                </div>
                <div class="col-md-6">
                    <div class="card-body">
                        <h5 class="card-title heading-secondary">{{$u->title}}</h5>
                        <p class="card-text">{{$u->text_short}}</p>
                        <div class="button-right-pos"><button class="btn">@lang('general.devaminiOku') <span> >> </span></button></div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="card h1x mb-30">
            <div class="row">
                <div class="col-md-4">
                    <img src="{{asset(env('root').env('front').env('news').$image)}}" alt="{{$u->title}}">
                </div>
                <div class="col-md-8">
                    <div class="card-body news-body">
                        <h5 class="card-title heading-secondary">{{$u->title}}</h5>
                        <p class="card-text">{{$u->text_short}}</p>
                        <div class="button-right-pos"><button class="btn">@lang('general.devaminiOku') <span> >> </span></button></div>

                    </div>
                </div>
            </div>
        </div>

    @endif
@endforeach
<div class="row justify-content-md-center mt-5">
    <div class="col-3">
        <button class="btn btn-first btn-block">@lang('general.tumunuGoruntule')</button>
    </div>
</div>
