@extends('front.layouts.app')
@section('body')
    <?php
    $sef = $oyun;
    $oyun = \App\Models\Games::where('link', $oyun)->first();
    if ($oyun == null) {
        header('Location: ' . URL::to(route('errors_404')), true, 302);
        exit();
    }
    $kontrol = \App\Models\GamesTitles::whereNull('deleted_at')
        ->where('status', '1')
        ->where('game', $oyun->id);
    if ($kontrol->count() == 1) {
        if ($kontrol->first()->type == 1) {
            header('Location: ' . route('item_detay', [$kontrol->first()->link]));
            die();
        } elseif ($kontrol->first()->type == 2) {
            header('Location: ' . route('epin_detay', [$kontrol->first()->link]));
            die();
        } elseif ($kontrol->first()->type == 3) {
            header('Location: ' . route('game_gold_detay', [$kontrol->first()->link]));
            die();
        }
    }
    ?>
    <style>
        @media only screen and (max-width: 600px) {
            .game-info-text {
                display: none;
            }
        }
    </style>
    <section class="bg-gray pb-40">
        @if ($sef == 'knight-online')
            <style>
                @media only screen and (max-width: 1600px) {
                    .gbbar {
                        display: none !important;
                    }
                }
            </style>
            <div class="gbbar container mb-2" style="text-align: center;">
                <a href="{{ route('item_detay', ['item-satis']) }}">
                    <img style="border-radius:10px" src="https://oyuneks.com/public/front/images/topbar/gbbar.gif" alt="">
                </a>
            </div>
        @endif
        <div class="container">
            <div class="row">
                @if ($oyun->link != 'hediye-kartlari')
                    <div class="col-md-4">
                        <div class="game-info">
                            <figure>
                                <img class="api" src="{{ asset('public/front/games/' . $oyun->image) }}"
                                    alt="{{ $oyun->alt }}">
                            </figure>
                            <div class="game-info-text">
                                {!! $oyun->text !!}
                            </div>

                        </div>

                    </div>
                @endif
                @if ($oyun->link == 'hediye-kartlari')
                    @php $col=12; @endphp
                @else
                    @php $col=8; @endphp
                @endif
                <div class="col-md-{{ $col }}">
                    <div class="row">
                        @if ($oyun->link == 'hediye-kartlari')
                            <div class="col-12 text-center pb-5">
                                <form method="get">
                                    <div class="row">
                                        <div class="col-8">
                                            <div class="form-group">
                                                <input placeholder="{{ $oyun->title }} İçinde Ara"
                                                    class="form-control style-input" name="q"
                                                    @if (isset($_GET['q'])) value="{{ $_GET['q'] }} @endif">
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
                        @endif
                        @if (isset($_GET['q']))
                            @php $title = $_GET['q']; @endphp
                        @else
                            @php $title = ""; @endphp
                        @endif
                        @php
                            if (isAkinsoftClient()) {
                                $sorgu = \App\Models\GamesTitles::whereNull('deleted_at')
                                    ->where('game', $oyun->id)
                                    ->where(function ($titles) use ($title) {
                                        $titles->where('title', 'like', '%' . $title . '%')->orWhere('etiket', 'like', '%' . $title . '%');
                                    })
                                    ->orderBy('sira', 'asc')
                                    ->get();
                            } else {
                                $sorgu = \App\Models\GamesTitles::whereNull('deleted_at')
                                    ->where('status', '1')
                                    ->where('game', $oyun->id)
                                    ->where(function ($titles) use ($title) {
                                        $titles->where('title', 'like', '%' . $title . '%')->orWhere('etiket', 'like', '%' . $title . '%');
                                    })
                                    ->orderBy('sira', 'asc')
                                    ->get();
                            }
                            
                        @endphp
                        @foreach ($sorgu as $u)
                            @if ($u->type == 1)
                                <div class="colflex mode-4">
                                    <div class="col_cell">
                                        <a href="{{ route('item_detay', [$u->link]) }}">
                                            <figure>
                                                <img src="{{ asset(env('ROOT') . env('FRONT') . env('GAMES_TITLES') . $u->image) }}"
                                                    alt="{{ $u->alt }}">

                                            </figure>
                                            <div class="text-container">
                                                <h5>{{ $u->title }}</h5>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                @if ($u->image_alis != '')
                                    <div class="colflex mode-4">
                                        <div class="col_cell">
                                            <a href="{{ route('item_buy_detay', [$u->link]) }}">
                                                <figure>
                                                    <img src="{{ asset(env('ROOT') . env('FRONT') . env('GAMES_TITLES') . $u->image_alis) }}"
                                                        alt="{{ $u->alt }}">
                                                </figure>
                                                <div class="text-container">
                                                    <h5>{{ $u->title }}</h5>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            @elseif($u->type == 3)
                                <div class="colflex mode-4">
                                    <div class="col_cell">
                                        <a href="{{ route('game_gold_detay', [$u->link]) }}">
                                            <figure>
                                                <img src="{{ asset(env('ROOT') . env('FRONT') . env('GAMES_TITLES') . $u->image) }}"
                                                    alt="{{ $u->alt }}">
                                            </figure>
                                            <div class="text-container">
                                                <h5>{{ $u->title }}</h5>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            @else
                                <div class="colflex @if ($oyun->link != 'hediye-kartlari') mode-4 @endif">
                                    <div class="col_cell">
                                        <a href="{{ route('epin_detay', [$u->link]) }}">
                                            <figure>

                                                <img src="{{ asset(env('ROOT') . env('FRONT') . env('GAMES_TITLES') . $u->image) }}"
                                                    alt="{{ $u->alt }}">

                                            </figure>
                                            <div class="text-container">
                                                <h5>{{ $u->title }}</h5>
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

    <section class="">
        <div class="container">
            <div class="row">
                <div class="col-md-12">


                </div>
            </div>
        </div>
    </section>
@endsection
