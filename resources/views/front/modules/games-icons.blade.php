<div class="row">
    <div class="game-icons">
        <div class="icon-wrapper owl-carousel owl-theme">
            <?php
            $icons = getCacheHomeGameIcons();
            ?>
            @foreach($icons as $u)
            <a class="icon-items" href="{{route('oyun_baslik', [$u->link])}}">
                <img src="{!!cdn(env('ROOT').env('FRONT').env('GAMES_ICON').$u->icon,80,80)!!}" alt="{{$u->title}} ikon" width="80" height="80">

            </a>
            @endforeach
        </div>
    </div>
</div>