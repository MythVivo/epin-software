<?php
$datetime = new DateTime(date('Y-m-d H:i:s'));
$datetime = $datetime->modify("-1 hours")->format('Y-m-d\TH:00:00P');
?>
<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{route('homepage')}}</loc>
        <lastmod>{{$datetime}}</lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>{{route('homepage').'/tum-oyunlar'}}</loc>
        <lastmod>{{$datetime}}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>
    <url>
        <loc>{{route('homepage').'/cd-key'}}</loc>
        <lastmod>{{$datetime}}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>

    <?php
    $sorgu = DB::table('muve_games')
        ->whereNull('deleted_at')
        ->where('status', '1')->get();
    foreach ($sorgu as $u) {
        echo '
        <url>
        <loc>' . route('cd_key_detay', [$u->link]) . '</loc>
        <lastmod>' . $datetime . '</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
        </url>';
    }
    ?>

    <?php
    $sorgu = DB::table('games')->whereNull('deleted_at')->where('status', '1')->orderBy('sira', 'asc')->get();
    //    $link = $u->category == 2 ? route('oyun_baslik', $u->link) : route('oyun_baslik', $u->link);
    ?>
    @foreach($sorgu as $u)
    <?php
        $link = route('oyun_baslik', $u->link);
        $kontrol = \App\Models\GamesTitles::whereNull('deleted_at')
        ->where('status', '1')
        ->where('game', $u->id);
        
         if ($kontrol->count() == 1) {
            if ($kontrol->first()->type == 1) {
                $link = route('item_detay', [$kontrol->first()->link]);
            } elseif ($kontrol->first()->type == 2) {
                $link = route('epin_detay', [$kontrol->first()->link]);
            } elseif ($kontrol->first()->type == 3) {
                $link = route('game_gold_detay', [$kontrol->first()->link]);
            }
        }
    /*  $datetime = new DateTime($u->created_at);
        $datetime = $datetime->format('Y-m-d\TH:i:sP'); */
    ?>
    <url>
        <loc>{{$link}}</loc>
        <lastmod>{{$datetime}}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>
    @endforeach
    <?php
    $sorgu = DB::table('games_titles')
        ->select('games_titles.*')
        ->join('games', 'games_titles.game', '=', 'games.id')
        ->where('games_titles.type', 1)
        ->whereNull('games_titles.deleted_at')
        ->whereNull('games.deleted_at')
        ->orderBy('games_titles.sira', 'asc')
        ->get();
    ?>
    @foreach($sorgu as $u)
    <?php
    /* $datetime = new DateTime($u->created_at);
        $datetime = $datetime->format('Y-m-d\TH:i:sP'); */
    ?>
    <url>
        <loc>{{route('item_detay', $u->link)}}</loc>
        <lastmod>{{$datetime}}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>
    @if($u->image_alis != "")
    <url>
        <loc>{{route('item_buy_detay', $u->link)}}</loc>
        <lastmod>{{$datetime}}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>
    @endif
    @endforeach
    <?php
    $sorgu = \App\Models\News::whereNull('deleted_at')->where('lang', getLang())->orderBy('created_at', 'desc')->where('status', '1')->get();
    ?>
    @foreach($sorgu as $u)
    <?php
    /* $datetime = new DateTime($u->created_at);
        $datetime = $datetime->format('Y-m-d\TH:i:sP'); */
    ?>
    <url>
        <loc>{{route('haber_detay', $u->link)}}</loc>
        <lastmod>{{$datetime}}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach
    <?php
    $sorgu = DB::table('games_titles')
        ->select('games_titles.*')
        ->join('games', 'games_titles.game', '=', 'games.id')
        ->where('games_titles.type', '!=', 1)
        ->whereNull('games_titles.deleted_at')
        ->whereNull('games.deleted_at')
        ->orderBy('games_titles.sira', 'asc')
        ->get();
    ?>
    @foreach($sorgu as $u)
    <?php
    /* $datetime = new DateTime($u->created_at);
        $datetime = $datetime->format('Y-m-d\TH:i:sP'); */
    ?>
    @if($u->type == 3)
    <url>
        <loc>{{route('game_gold_detay', $u->link)}}</loc>
        <lastmod>{{$datetime}}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.7</priority>
    </url>
    @else
    <url>
        <loc>{{route('epin_detay', $u->link)}}</loc>
        <lastmod>{{$datetime}}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.7</priority>
    </url>
    @endif
    @endforeach
    <?php
    $sorgu3 = getCacheSearchArea3('');
    ?>
    @foreach($sorgu3 as $u)
    @if($u->deleted_at == NULL)
    <?php
    $item = DB::table('games_titles')->where('id', $u->games_titles)->first();
    /* $datetime = new DateTime($u->created_at);
            $datetime = $datetime->format('Y-m-d\TH:i:sP'); */
    ?>
    <url>
        <loc>{{route('epin_detay_paket', [$item->link, Str::slug($u->title) . "-" . $u->id])}}</loc>
        <lastmod>{{$datetime}}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.6</priority>
    </url>
    @endif
    @endforeach
    <?php
    $sorgu2 = getCacheSearchArea2('');
    ?>
    @foreach($sorgu2 as $u)
    @if($u->deleted_at == NULL)
    <?php
    $trade = DB::table('games_titles')->where('id', $u->games_titles)->first();
    $datetime = new DateTime($u->created_at);
    $datetime = $datetime->format('Y-m-d\TH:i:sP');
    ?>
    <url>
        <loc>{{route('game_gold_detay_paket', [$trade->link, Str::slug($u->title) . "-" . $u->id])}}</loc>
        <lastmod>{{$datetime}}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.6</priority>
    </url>
    @endif
    @endforeach
    <?php
    $item = DB::table('games_titles')->where('type', 1)->whereNull('deleted_at')->where('status', '1')->orderBy('created_at', 'asc')->get();
    ?>
    @foreach($item as $i)
    <?php
    $ilanlar = DB::table('pazar_yeri_ilanlar')
        ->where('pazar', $i->id)
        ->where('pazar_yeri_ilanlar.status', '1')
        ->whereNull('pazar_yeri_ilanlar.deleted_at')
        ->where('userStatus', '1')
        ->orderBy('created_at', 'desc')
        ->select('pazar_yeri_ilanlar.id as ilanId', 'pazar_yeri_ilanlar.*')
        ->get();
    ?>
    @foreach($ilanlar as $u)
    <?php
    /* $datetime = new DateTime($u->created_at);
            $datetime = $datetime->format('Y-m-d\TH:i:sP'); */
    ?>
    <url>
        <loc>{{route('item_ic_detay', [$i->link, $u->sunucu, Str::slug($u->title).'-'.$u->id])}}</loc>
        <lastmod>{{$datetime}}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.6</priority>
    </url>
    @endforeach
    <?php
    $ilanlar2 = DB::table('pazar_yeri_ilanlar_buy')
        ->where('pazar', $i->id)
        ->where('pazar_yeri_ilanlar_buy.status', '1')
        ->where('userStatus', '1')
        ->whereNull('pazar_yeri_ilanlar_buy.deleted_at')
        ->orderBy('created_at', 'asc')
        ->select('pazar_yeri_ilanlar_buy.id as ilanId', 'pazar_yeri_ilanlar_buy.*')
        ->get();
    ?>
    @foreach($ilanlar2 as $u)
    <?php
    /* $datetime = new DateTime($u->created_at);
            $datetime = $datetime->format('Y-m-d\TH:i:sP'); */
    ?>
    <url>
        <loc>{{route('item_buy_ic_detay', [$i->link, $u->sunucu, Str::slug($u->title).'-'.$u->id])}}</loc>
        <lastmod>{{$datetime}}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.6</priority>
    </url>
    @endforeach
    @endforeach
    <?php
    $sorgu = DB::table('twitch_support_streamer')
        ->where('twitch_id', '!=', NULL)
        ->where('status', '1')
        ->orderBy('created_at', 'ASC')
        ->whereNull('deleted_at')
        ->get();
    ?>
    @foreach($sorgu as $u)
    <?php
    /* $datetime = new DateTime($u->created_at);
        $datetime = $datetime->format('Y-m-d\TH:i:sP'); */
    ?>
    <url>
        <loc>{{route('twitch_support_yayinci', $u->yayin_link)}}</loc>
        <lastmod>{{$datetime}}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.6</priority>
    </url>
    @endforeach
    <?php
    $sorgu = \App\Models\Pages::where('lang', 'tr')->whereNull('deleted_at')->where('status', '1')->get();
    ?>
    @foreach($sorgu as $u)
    <?php
    /* $datetime = new DateTime($u->created_at);
        $datetime = $datetime->format('Y-m-d\TH:i:sP'); */
    ?>
    <url>
        <loc>{{route('sayfa', $u->link)}}</loc>
        <lastmod>{{$datetime}}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.6</priority>
    </url>
    @endforeach

</urlset>