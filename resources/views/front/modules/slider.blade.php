<div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-indicators">
        @foreach(\App\Models\Slider::whereNull('deleted_at')->get() as $u)
        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="{{$loop->index}}" @if ($loop->first) class="active carouselIndicators" aria-current="true" @else class="carouselIndicators" @endif aria-label="Slide 1"></button>
        @endforeach
    </div>
    <div class="carousel-inner">
        @foreach(\App\Models\Slider::whereNull('deleted_at')->get() as $u)
        <div class="carousel-item  @if ($loop->first) active @endif">
            <div class="overlay"></div>
            <img src="{{asset(env('root').env('front').env('slider').$u->image)}}" class="d-block w-100" alt="{{$u->title}}">
        </div>
        @endforeach
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>
