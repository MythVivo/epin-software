@extends('front.layouts.app')
@section('body')
    <section class="bg-gray pb-40">
        <div class="container">
            <div class="row">
                <h1 class="d-none">Oyuneks Türkiye'nin En Uygun Fiyatlı Digital Kod Epin Satış Sitesi</h1>
                <div class="col-12 text-center pb-5">
                    <form method="get">
                        <div class="row">
                            <div class="col-8">
                                <div class="form-group">
                                    <input placeholder="Epinler İçinde Ara" class="form-control style-input" name="q"
                                           @if(isset($_GET['q'])) value="{{$_GET['q']}} @endif">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <button class="btn-inline color-darkgreen w-100">Ara</button>
                                </div>
                            </div>
                        </div>


                    </form>
                </div>
                @if(isset($_GET['q']))
                    @php $title = $_GET['q']; @endphp
                @else
                    @php $title = ""; @endphp
                @endif

                @php $sorgu = \App\Models\GamesTitles::whereNull('deleted_at')->where('title', 'like', '%'.$title.'%')->where('status', '1')->orderBy('sira', 'asc')->get(); @endphp


                <div class="col-md-12">

                        <div class="row">
                            @foreach($sorgu as $u)
                                @if($u->type == 2 and DB::table('games')->where('id', $u->game)->whereNull('deleted_at')->count() > 0)
                                    <div class="colflex">
                                        <div class="col_cell">
                                            <a href="{{route('epin_detay', [$u->link])}}">
                                                <figure>
                                                    <img src="{{asset(env('ROOT').env('FRONT').env('GAMES_TITLES').$u->image)}}" alt="{{$u->title}}">
                                                </figure>
                                                <div class="text-container flex">
                                                    <h2>{{$u->title}}</h2>
                                                </div>

                                            </a>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>

                </div>
            </div>
        </div>
    </section>
@endsection
