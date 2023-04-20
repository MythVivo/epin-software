<div class="row title-area" data-lang="{{ getLang() }}">
    <div class="col-sm-12 col-md-12 title s-title">
        <h1 class="heading-primary style-2"><span>Oyuneks'te Popüler</span></h1>
    </div>

</div>
<div class="popular_games">
    <div class="p_games_table">
        <div class="p_games_right_items">
            <style>
                .popflex {
                    width: 16.66% !important;
                }

                @media only screen and (max-width: 800px) {
                    .popflex {
                        width: 33.33% !important;
                    }
                }
            </style>
            <div class="row">
                <?php
                $games = getCacheHomeGamesPopular();
                ?>
                @foreach ($games as $u)
                <div class="colflex popflex">
                    <?php
                    $image = explode('.', $u->image);
                    $image = $image[0] . '@2x.' . $image[1];
                    ?>
                    <div class="col_cell">
                        <a class="overlay" href="{{ route('oyun_baslik', [$u->link]) }}">
                            <figure class="cover">
                                <img srcx="data:image/gif;base64,R0lGODdhAQABAPAAAMPDwwAAACwAAAAAAQABAAACAkQBADs=" src="{{ cdn(env('ROOT') . env('FRONT') . env('GAMES') . $u->image, 286, 286) }}" alt="{{ $u->title }} görseli" width="286" height="286">

                            </figure>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <?php /*
    
        <div class="p_games_table">
            <div class="p_games_left_banner">
                <img src="{{asset('public/front/images/populer-kategoriler.jpg')}}">
            </div>
            <div class="p_games_right_items">
    
                @foreach(DB::table('games')->whereNull('deleted_at')->orderBy('created_at', 'asc')->take(10)->get() as $u)
                    <?php
                    $image = explode(".", $u->image);
                    $image = $image[0] . "@2x." . $image[1];
                    ?>
    ?>
    <div class="pop_game_cell">
        <figure class="pop_game_figure">
            <a href="{{ route('oyun_baslik', [$u->link]) }}">
                <img src="{{ asset(env('ROOT') . env('FRONT') . env('GAMES') . $image) }}">


                <h5>{{ $u->title }}</h5>
            </a>

        </figure>
    </div>
    @endforeach

    <div class="mask">

        <img src="{{ asset('public/front/images/populer-kategoriler.jpg') }}">
    </div>
</div>


</div> */ ?>


    <?php /*@foreach(\App\Models\Games::whereNull('deleted_at')->where('lang', getLang())->get() as $u)
        <div class="card">
            <img src="{{asset(env('ROOT').env('FRONT').env('GAMES').$u->image)}}" class="card-img" alt="{{$u->title}}">
                <h5 class="card-title text-center">{{$u->title}}</h5>
        </div>
    @endforeach
 */
    ?>
</div>