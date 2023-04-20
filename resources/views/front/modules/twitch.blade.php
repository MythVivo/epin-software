<div class="row title-area" data-lang="{{ getLang() }}">
    <div class="col-sm-12 col-md-12 title s-title">
        <h1 class="heading-primary style-2"><span>Yayıncılar</span></h1>
    </div>
</div>
<div class="row">
    <?php
    $marlen = getCacheHomeTwitchMarlen();
    ?>
    @if ($marlen)
    <!-- Marlen -->
    <?php $u = $marlen; ?>
    @if (is_array($marlen))
    @foreach ($marlen as $u)
    <?php
    $yayinci = DB::table('twitch_support_streamer')
        ->where('twitch_id', $u->id)
        ->first();
    ?>
    <div class="colflex mode-7 @if ($yayinci->favori == 1) favori_yayinci @endif">
        <div class="col_cell">
            <a href="{{ route('twitch_support_yayinci', $yayinci->yayin_link) }}">
                <figure>
                    <img class="lazyload" src="data:image/gif;base64,R0lGODdhAQABAPAAAMPDwwAAACwAAAAAAQABAAACAkQBADs=" data-src="{{ $u->profile_image_url }}" alt="{{ $yayinci->title }} görseli" width="195" height="195">
                </figure>
                <div class="text-container flex">
                    <h5>{{ $yayinci->title }}</h5>
                </div>
            </a>
        </div>
    </div>
    @endforeach
    @endif
    <!-- Marlen -->
    @endif

    <?php
    $twitch = getCacheHomeTwitch();
    ?>
    @if (is_array($twitch))
    @foreach ($twitch as $u)
    <?php
    $yayinci = DB::table('twitch_support_streamer')
        ->where('twitch_id', $u->id)
        ->first();
    ?>
    @if (isset($yayinci->twitch_id))
    <div class="colflex mode-7 @if ($yayinci->favori == 1) favori_yayinci @endif">
        <div class="col_cell">
            <a href="{{ route('twitch_support_yayinci', $yayinci->yayin_link) }}">
                <figure>
                    <img class="lazyload" src="data:image/gif;base64,R0lGODdhAQABAPAAAMPDwwAAACwAAAAAAQABAAACAkQBADs=" data-src="{{ $u->profile_image_url }}" alt="{{ $yayinci->title }} görseli" width="195" height="195">
                </figure>
                <div class="text-container flex">
                    <h5>{{ $yayinci->title }}</h5>
                </div>
            </a>
        </div>
    </div>
    @endif
    @endforeach
    @endif
</div>