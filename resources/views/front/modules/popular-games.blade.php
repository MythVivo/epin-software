<div class="row title-area" data-lang="{{getLang()}}">
    <div class="col-sm-12 col-md-9 title">
        <h1 class="heading-primary">@lang('general.populerOyunlar')</h1>
    </div>
    <div class="col-sm-12 col-md-3 button-area">
        <button class="customPrevBtn d-flex justify-content-center btn btn-group text-center">
            <i class="fas fa-angle-left align-self-center"></i>
        </button>
        <button class="customNextBtn btn btn-group text-center">
            <i class="fas fa-angle-right align-self-center"></i>
        </button>
    </div>
</div>
<div class="owl-carousel">
    @foreach(\App\Models\Games::whereNull('deleted_at')->where('lang', getLang())->get() as $u)
        <div class="card">
            <img src="{{asset(env('ROOT').env('FRONT').env('GAMES').$u->image)}}" class="card-img" alt="{{$u->title}}">
                <h5 class="card-title text-center">{{$u->title}}</h5>
        </div>
    @endforeach
</div>
