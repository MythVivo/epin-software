<?
if(isset($item->link)){
    $item->link=str_replace(array('&','<','>', '%', '`', '*',"'",'"','|','.'), '', $item->link);
}
if(isset($errors)){
    $errors=str_replace(array('&','<','>', '%', '`', '*',"'",'"','|','.','-'), '', $errors);
}

?>
<section class="breadcrumb-sec header-margin">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav class="nav-breadcrumb" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('homepage')}}">{{getSiteName()}}</a></li>
                        @if(Route::currentRouteName() == 'baslik_detay')
                            @if(is_numeric($oyun))
                                <li class="breadcrumb-item"><a
                                            href="{{route('oyunlarTum')}}">{{getPageTitle('oyunlar', getLang())}}</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    <a href="{{route(Route::currentRouteName(), [$oyun, 'satin-al'])}}">
                                        {{FindApiGame($oyun)}} E-pin
                                    </a>
                                </li>
                            @else
                                <li class="breadcrumb-item">
                                    <a href="{{route('oyun_baslik', $oyun->link)}}">
                                        {{$oyun->title}}
                                    </a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">

                                    <a href="{{route(Route::currentRouteName(), [$oyun->link, $baslik->link])}}">
                                        {{$baslik->title}}
                                    </a>
                                </li>
                            @endif
                        @elseif(Route::currentRouteName() == 'oyun_baslik')
                            <li class="breadcrumb-item active" aria-current="page">
                                <a href="{{route(Route::currentRouteName(), $oyun->link)}}">
                                    {{$oyun->title}}
                                </a>
                            </li>
                        @elseif(Route::currentRouteName() == 'satis_duzenle')
                            <li class="breadcrumb-item active" aria-current="page">
                                <a href="{{route(Route::currentRouteName(), $ilan->id)}}">
                                    {{substr($ilan->title, 0, 50)}} @if(strlen($ilan->title) > 50)...@endif Düzenle
                                </a>
                            </li>
                        @elseif(Route::currentRouteName() == 'item_detay')
                            <li class="breadcrumb-item"><a
                                        href="{{route('itemTum')}}">{{getPageTitle('item', getLang())}}</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                <a href="{{route(Route::currentRouteName(), $item->link)}}">
                                    <?php
                                    $item = DB::table('games_titles')->where('link', $item->link)->first();
                                    echo DB::table('games')->where('id', $item->game)->first()->title . " - " . $item->title;
                                    ?>
                                </a>
                            </li>
                        @elseif(Route::currentRouteName() == 'item_canli_akis')
                            <li class="breadcrumb-item"><a
                                        href="{{route('itemTum')}}">{{getPageTitle('item', getLang())}}</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                <a href="{{route(Route::currentRouteName(), $item->link)}}">
                                    <?php
                                    $item = DB::table('games_titles')->where('link', $item->link)->first();
                                    echo DB::table('games')->where('id', $item->game)->first()->title . " - " . $item->title;
                                    ?> - Canlı Akış
                                </a>
                            </li>
                        @elseif(Route::currentRouteName() == 'item_buy_detay')
                            <li class="breadcrumb-item"><a
                                        href="{{route('item_buy')}}">{{getPageTitle('item-buy', getLang())}}</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                <a href="{{route(Route::currentRouteName(), $item->link)}}">
                                    <?php
                                    $item = DB::table('games_titles')->where('link', $item->link)->first();
                                    echo DB::table('games')->where('id', $item->game)->first()->title . " - " . $item->title;
                                    ?>
                                </a>
                            </li>
                        @elseif(Route::currentRouteName() == 'item_ic_detay')
                            <li class="breadcrumb-item"><a
                                        href="{{route('itemTum')}}">{{getPageTitle('item', getLang())}}</a>
                            </li>
                            <li class="breadcrumb-item"><a
                                        href="{{route('item_detay', [$item->link])}}">
                                    <?php
                                    $item = DB::table('games_titles')->where('link', $item->link)->first();
                                    echo DB::table('games')->where('id', $item->game)->first()->title . " - " . $item->title;
                                    ?>
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{route('item_detay', [$item->link])}}?sunucu={{$sunucu}}">
                                    {{$sunucu}}
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                <a href="{{route(Route::currentRouteName(), [$item->link, $sunucu, Str::slug($u->title)."-".$u->id])}}">
                                    {{$u->title}}
                                </a>
                            </li>
                        @elseif(Route::currentRouteName() == 'item_buy_ic_detay')
                            <li class="breadcrumb-item"><a
                                        href="{{route('item_buy')}}">{{getPageTitle('item-buy', getLang())}}</a>
                            </li>
                            <li class="breadcrumb-item"><a
                                        href="{{route('item_buy_detay', [$item->link])}}">
                                    <?php
                                    $item = DB::table('games_titles')->where('link', $item->link)->first();
                                    echo DB::table('games')->where('id', $item->game)->first()->title . " - " . $item->title;
                                    ?>
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{route('item_buy_detay', [$item->link])}}?sunucu={{$sunucu}}">
                                    {{$sunucu}}
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                <a href="{{route(Route::currentRouteName(), [$item->link, $sunucu, Str::slug($u->title)."-".$u->id])}}">
                                    {{$u->title}}
                                </a>
                            </li>
                        @elseif(Route::currentRouteName() == 'item_ic_detay_satin_al')
                            <li class="breadcrumb-item"><a
                                        href="{{route('itemTum')}}">{{getPageTitle('item', getLang())}}</a>
                            </li>
                            <li class="breadcrumb-item"><a
                                        href="{{route('item_detay', [$item->link])}}">
                                    <?php
                                    $item = DB::table('games_titles')->where('link', $item->link)->first();
                                    echo DB::table('games')->where('id', $item->game)->first()->title . " - " . $item->title;
                                    ?>
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{route('item_detay', [$item->link])}}?sunucu={{$sunucu}}">
                                    {{$sunucu}}
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{route('item_ic_detay', [$item->link, $sunucu, Str::slug($u->title)."-".$u->id])}}">
                                    {{$u->title}}
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                <a href="{{route(Route::currentRouteName(), [$item->link, $sunucu, Str::slug($u->title)."-".$u->id])}}">
                                    Satın Al
                                </a>
                            </li>
                        @elseif(Route::currentRouteName() == 'item_buy_ic_detay_satin_al')
                            <li class="breadcrumb-item"><a
                                        href="{{route('item_buy')}}">{{getPageTitle('item-buy', getLang())}}</a>
                            </li>
                            <li class="breadcrumb-item"><a
                                        href="{{route('item_buy_detay', [$item->link])}}">
                                    <?php
                                    $item = DB::table('games_titles')->where('link', $item->link)->first();
                                    echo DB::table('games')->where('id', $item->game)->first()->title . " - " . $item->title;
                                    ?>
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{route('item_buy_detay', [$item->link])}}?sunucu={{$sunucu}}">
                                    {{$sunucu}}
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{route('item_buy_ic_detay', [$item->link, $sunucu, Str::slug($u->title)."-".$u->id])}}">
                                    {{$u->title}}
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                <a href="{{route(Route::currentRouteName(), [$item->link, $sunucu, Str::slug($u->title)."-".$u->id])}}">
                                    Sat
                                </a>
                            </li>
                        @elseif(Route::currentRouteName() == 'satici')
                            <li class="breadcrumb-item"><a
                                        href="{{route('itemTum')}}">{{getPageTitle('item', getLang())}}</a>
                            </li>
                            <li class="breadcrumb-item"><a
                                        href="#">Satıcı</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                <a href="{{route(Route::currentRouteName(), [$satici->id])}}">
                                    {{substr($satici->name, 0, 2)}}**** ******
                                </a>
                            </li>
                        @elseif(Route::currentRouteName() == 'sayfa')
                            <li class="breadcrumb-item active" aria-current="page">
                                <a href="{{route(Route::currentRouteName(), [$u->link])}}">
                                    {{$u->title}}
                                </a>
                            </li>
                        @elseif(Route::currentRouteName() == 'game_gold_detay')
                            <li class="breadcrumb-item"><a
                                        href="{{route('game_gold')}}">{{getPageTitle('game-gold', getLang())}}</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page"><a
                                        href="{{route('game_gold_detay', [$gold->link])}}">
                                    {{$gold->title}}
                                </a>
                            </li>
                        @elseif(Route::currentRouteName() == 'game_gold_detay_paket')
                            @if(isset($gold))
                                <li class="breadcrumb-item"><a
                                            href="{{route('game_gold')}}">{{getPageTitle('game-gold', getLang())}}</a>
                                </li>
                                <li class="breadcrumb-item"><a
                                            href="{{route('game_gold_detay', [$gold->link])}}">
                                        {{$gold->title}}
                                    </a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    <a href="{{route(Route::currentRouteName(), [$gold->link, Str::slug($paket->title)."-".$paket->id])}}">
                                        {{$paket->title}}
                                    </a>
                                </li>
                            @endif
                        @elseif(Route::currentRouteName() == 'game_gold_detay_paket_satin_al')
                            <li class="breadcrumb-item"><a
                                        href="{{route('game_gold')}}">{{getPageTitle('game-gold', getLang())}}</a>
                            </li>
                            <li class="breadcrumb-item"><a
                                        href="{{route('game_gold_detay', [$gold->link])}}">
                                    {{$gold->title}}
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{route('game_gold_detay_paket', [$gold->link, Str::slug($paket->title)."-".$paket->id])}}">
                                    {{$paket->title}}
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                <a href="{{route(Route::currentRouteName(), [$gold->link, Str::slug($paket->title)."-".$paket->id, $durum])}}">
                                    Satın Al / Sat
                                </a>
                            </li>
                        @elseif(Route::currentRouteName() == 'epin_detay')
                            <li class="breadcrumb-item"><a
                                        href="{{route('oyunlarTum')}}">{{getPageTitle('e-pin', getLang())}}</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                <a href="{{route(Route::currentRouteName(), [$epin->link,])}}">
                                    {{$epin->title}}
                                </a>
                            </li>
                        @elseif(Route::currentRouteName() == 'epin_detay_paket')
                            <li class="breadcrumb-item"><a
                                        href="{{route('oyunlarTum')}}">{{getPageTitle('e-pin', getLang())}}</a>
                            </li>
                            <li class="breadcrumb-item"><a
                                        href="{{route('epin_detay', [$epin->link])}}">
                                    {{$epin->title}}
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                <a href="{{route(Route::currentRouteName(), [$epin->link, Str::slug($paket->title)."-".$paket->id])}}">
                                    {{$paket->title}}
                                </a>
                            </li>
                        @elseif(Route::currentRouteName() == 'epin_detay_paket_satin_al')
                            <li class="breadcrumb-item"><a
                                        href="{{route('oyunlarTum')}}">{{getPageTitle('e-pin', getLang())}}</a>
                            </li>
                            <li class="breadcrumb-item"><a
                                        href="{{route('epin_detay', [$epin->link])}}">
                                    {{$epin->title}}
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{route('epin_detay_paket', [$epin->link, Str::slug($paket->title)."-".$paket->id])}}">
                                    {{$paket->title}}
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                <a href="{{route(Route::currentRouteName(), [$epin->link, Str::slug($paket->title)."-".$paket->id])}}">
                                    Satın Al
                                </a>
                            </li>
                        @elseif(Route::currentRouteName() == 'cd_key_detay_satin_al')
                            <li class="breadcrumb-item"><a
                                        href="{{route('cd_key')}}">{{getPageTitle('cd-key', getLang())}}</a>
                            </li>
                            <li class="breadcrumb-item"><a
                                        href="{{route('cd_key_detay', [$cdkey->link])}}">
                                    {{$cdkey->title}}
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                <a href="{{route(Route::currentRouteName(), [$cdkey->link])}}">
                                    Satın Al
                                </a>
                            </li>
                        @elseif(Route::currentRouteName() == 'haber_detay')
                            <li class="breadcrumb-item"><a
                                        href="{{route('haber')}}">{{getPageTitle('haberler', getLang())}}</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                <a href="{{route(Route::currentRouteName(), [$u->link])}}">
                                    {{$u->title}}
                                </a>
                            </li>
                        @elseif(Route::currentRouteName() == 'twitch_support_yayinci')
                            <li class="breadcrumb-item"><a
                                        href="{{route('twitch_support')}}">{{getPageTitle('twitch-support', getLang())}}</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                <a href="{{route(Route::currentRouteName(), [$yayinciDB->yayin_link])}}">
                                    {{$yayinciDB->title}}
                                </a>
                            </li>
                        @elseif(Route::currentRouteName() == 'cd_key_detay')
                            <li class="breadcrumb-item"><a
                                        href="{{route('cd_key')}}">{{getPageTitle('cd-key', getLang())}}</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                <a href="{{route(Route::currentRouteName(), $cdkey->link)}}">
                                    {{$cdkey->title}}
                                </a>
                            </li>
                        @else
                            <li class="breadcrumb-item active" aria-current="page">
                                <a href="{{route(Route::currentRouteName(), '')}}">
                                    {{getPageTitle(getPage(), getLang())}}
                                </a>
                            </li>
                        @endif
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>
