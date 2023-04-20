<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class MainController extends Controller
{
    public function swx()
    {
        echo cdn('asd');
    }

    public function sitemap()
    {
        return response()->view('front.sitemap')->header('Content-Type', 'text/xml');
    }

    public function errors_403()
    {
        return view('errors.403');
    }

    public function errors_404()
    {
        return view('errors.404');
    }

    public function errors_100()
    {
        return view('errors.100');
    }

    public function index()
    {
        if(@$_GET['xxx'] == 'xxx')
        {
            var_dump(Auth::user()->refId);
            exit();
        }
        return view('front.index');
    }

    public function oyunlar()
    {
        return view('front.pages.oyunlar');
    }

    public function oyunlar_baslik($oyun)
    {
        if($oyun == 'pubg-mobile')
        {
            return redirect()->route('oyun_baslik', ['oyun' => 'pubg-mobile-uc-uc-satin-al']);
        }
        return view('front.pages.oyun-detay')->with('oyun', $oyun);
    }

    public function baslik_detay($oyun, $baslik)
    {
        return view('front.pages.baslik-detay')->with('oyun', $oyun)->with('baslik', $baslik);
    }

    public function urun_onizle()
    {
        return view('front.pages.urun-onizle')->with('package', '0')->with('adet', '0');
    }

    public function tsend(Request $request)
    {
        return view('back.pages.users.aktivite')->with('mid', $request->mid);
    }

    public function urun_onizle_post(Request $request)
    {
        if (isset($request->trade)) { //Eğer trade işlemi ise
            if ($request->trade == 1) { //Eğer müşterinin siteye satışı ise
                $adet = $request->adet;
                $package = $request->package;
                $trade = $request->trade;
                if (Cookie::has('redirect')) {
                    Cookie::queue(Cookie::forget('redirect'));
                    Cookie::queue(Cookie::forget('adet'));
                    Cookie::queue(Cookie::forget('package'));
                    if (Cookie::has('redirect')) {
                        Cookie::queue(Cookie::forget('api'));
                        Cookie::queue(Cookie::forget('apiUrun'));
                    }
                    if (Cookie::has('trade')) {
                        Cookie::queue(Cookie::forget('trade'));
                    }
                }
                setcookie('redirect', 'urun_onizle_post', time() + 60 * 60, '/');
                setcookie('adet', $adet, time() + 60 * 60, '/');
                setcookie('package', $package, time() + 60 * 60, '/');
                setcookie('trade', $trade, time() + 60 * 60, '/');
                return redirect('urun-onizle');
                // return view('front.pages.urun-onizle')->with('redirect', 'urun_onizle_post')->with('adet', $adet)->with('package', $package)->with('trade', $trade);
            } else { //sitenin müşteriye satışı ise

            }
        } else { //eğer paket satın alma işlemi ise
            $adet = $request->adet;
            $package = $request->package;
            if (isset($_COOKIE['redirect'])) {
                Cookie::forget('redirect');
                Cookie::forget('adet');
                Cookie::forget('package');
                if (isset($_COOKIE['redirect'])) {
                    Cookie::forget('api');
                    Cookie::forget('apiUrun');
                }
                if (isset($_COOKIE['trade'])) {
                    Cookie::forget('trade');
                }
            }
            setcookie('redirect', 'urun_onizle_post', time() + 60 * 60, '/');
            setcookie('adet', $adet, time() + 60 * 60, '/');
            setcookie('package', $package, time() + 60 * 60, '/');
            if (isset($request->api)) {
                setcookie('api', $request->api, time() + 60 * 60, '/');
                setcookie('apiUrun', $request->apiUrun, time() + 60 * 60, '/');
                return redirect('urun-onizle')->withCookie(Cookie::forget('trade'), Cookie::forget('api'), Cookie::forget('apiUrun'));
                //return view('front.pages.urun-onizle')->with('redirect', 'urun_onizle_post')->with('adet', $adet)->with('package', $package)->with('api', $request->api)->with('apiUrun', $request->apiUrun);
            } else {
                return redirect('front.pages.urun-onizle');
                //return view('front.pages.urun-onizle')->with('redirect', 'urun_onizle_post')->with('adet', $adet)->with('package', $package);
            }
        }
    }

    public function haberler()
    {
        return view('front.pages.haberler');
    }

    public function haber_detay($haber)
    {
        return view('front.pages.haber')->with('haber', $haber);
    }

    public function yorumlar()
    {
        return view('front.pages.yorumlar');
    }

    public function sayfa($sayfa)
    {
        return view('front.pages.sayfa')->with('sayfa', $sayfa);
    }

    public function marka_yonergeleri()
    {
        return view('front.pages.marka_yonergeleri');
    }

    public function item()
    {
        return view('front.pages.item');
    }

    public function item_detay($item)
    {
        if ($item == 'rise-online-karakter-satis') {
            $item = 'rise-online-nft-karakter-satisi';
        }
        return view('front.pages.item-detay')->with('item', $item);
    }

    public function item_canli_akis($item)
    {
        return view('front.pages.item-canli-akis')->with('item', $item);
    }

    public function item_ic_detay($item, $sunucu, $ilan)
    {
        return view('front.pages.item-ic-detay')->with('item', $item)->with('sunucu', $sunucu)->with('ilan', $ilan);
    }

    public function item_ic_detay_satin_al($item, $sunucu, $ilan)
    {
        //noIndex('set', true);
        return view('front.pages.item-ic-detay-satin-al')->with('item', $item)->with('sunucu', $sunucu)->with('ilan', $ilan);
    }

    public function item_buy()
    {
        return view('front.pages.item-buy');
    }

    public function item_buy_detay($item)
    {
        return view('front.pages.item-buy-detay')->with('item', $item);
    }

    public function item_buy_ic_detay($item, $sunucu, $ilan)
    {
        return view('front.pages.item-buy-ic-detay')->with('item', $item)->with('sunucu', $sunucu)->with('ilan', $ilan);
    }

    public function item_buy_ic_detay_satin_al($item, $sunucu, $ilan)
    {
        //noIndex('set', true);
        return view('front.pages.item-buy-ic-detay-satin-al')->with('item', $item)->with('sunucu', $sunucu)->with('ilan', $ilan);
    }

    public function satici($satici)
    {
        return view('front.pages.satici')->with('satici', $satici);
    }

    public function game_gold()
    {
        return view('front.pages.game-gold');
    }

    public function game_gold_detay($gold)
    {
        $goldTitle = $gold;
        $gold = DB::table('games_titles')
            ->where('link', $gold)
            ->whereNull('deleted_at')
            ->first();
        if (!$gold) {
            header('Location: ' . URL::to(route('errors_404')), true, 302);
            exit();
        }
        $products = \App\Models\GamesPackagesTrade::where('games_titles', $gold->id)
            ->orderBy('sira', 'asc')
            ->get();
        $comments = \App\Models\Comments::epin($gold->id);

        $jsonld = \App\Helpers\StructuredData::jsonLd('gamegoldPackages', ['epin' => $gold, 'packages' => $products, 'comments' => $comments]);

        return view('front.pages.game-gold-detay')->with(['gold' => $gold, 'jsonld' => $jsonld, 'goldTitle' => $goldTitle, 'products' => $products]);
        //return view('front.pages.game-gold-detay')->with('gold', $gold);
    }

    public function game_gold_detay_paket($gold, $paket)
    {
        return view('front.pages.game-gold-detay-paket')->with('gold', $gold)->with('paket', $paket);
    }

    public function game_gold_detay_paket_satin_al($gold, $paket, $durum)
    {
        //noIndex('set', true);
        return view('front.pages.game-gold-detay-paket-satin-al')->with('gold', $gold)->with('paket', $paket)->with('durum', $durum);
    }

    public function tum_oyunlar()
    {
        return view('front.pages.oyunlar-hepsi');
    }

    public function epin_detay($epin)
    {
        return view('front.pages.e-pin-detay')->with('epin', $epin);
    }

    public function swx_301($epin, $paket = "", $satinal = "")
    {
        $url = "https://oyuneks.com/e-pin/$epin";
        $url .= $paket ? "/$paket" : "";
        $url .= $satinal ? "/satin-al" : "";
        header("Location: $url", true, 301);
        /*  "https://oyuneks.com/epin-detay/$epin"
        var_dump($epin); */
        exit();
    }
    public function swx_epin_detay($epin)
    {
        if($epin == 'pubg-mobile-uc-satin-al-indirimli-uc-fiyatlari' || $epin == 'pubg-mobile-id')
        {
            return redirect()->route('oyun_baslik', ['oyun' => 'pubg-mobile-uc-uc-satin-al']);
        }

        $epin = getCacheEpinDetay($epin);
        if ($epin == NULL) {
            header("Location: " . \URL::to(route('errors_404')), true, 302);
            exit();
        }
        $packages = getCacheEpinDetayPackages($epin->id);
        $comments = \App\Models\Comments::epin($epin->id);
        $jsonld = \App\Helpers\StructuredData::jsonLd('packages', ['epin' => $epin, 'packages' => $packages, 'comments' => $comments]);
        return view('front.pages.epin_detay.e-pin-detay')->with(['epin' => $epin, 'packages' => $packages,  'jsonld' => $jsonld, 'comments' => $comments]);
    }

    public function bgr_epin_detay_paket($epin, $paket)
    {
        //temporarily redirected routes.
        if($paket == 'league-of-legends-850-riot-points-948')
        return redirect()->route('epin_detay_paket', ['epin' => $epin, 'paket' => 'league-of-legends-850-lol-rp-948']);
        else if($paket == 'league-of-legends-1600-riot-points-949')
        return redirect()->route('epin_detay_paket', ['epin' => $epin, 'paket' => 'league-of-legends-1600-lol-rp-949']);
        else if($paket == 'league-of-legends-3150-riot-points-950')
        return redirect()->route('epin_detay_paket', ['epin' => $epin, 'paket' => 'league-of-legends-3150-lol-rp-950']);
        else if($paket == 'league-of-legends-5800-riot-points-951')
        return redirect()->route('epin_detay_paket', ['epin' => $epin, 'paket' => 'league-of-legends-5800-lol-rp-951']);
        else if($paket == 'league-of-legends-9200-riot-points-952')
        return redirect()->route('epin_detay_paket', ['epin' => $epin, 'paket' => 'league-of-legends-9200-lol-rp-952']);
        else if($paket == 'pubg-mobile-24300-uc-731')
        return redirect()->route('epin_detay_paket', ['epin' => $epin, 'paket' => 'pubg-mobile-24300-uc-1346']);


        $epinName = $epin;
        $paketName = $paket;
        $epin = getCacheEpinDetay($epin);
        if ($epin == null) {
            header('Location: ' . \URL::to(route('errors_404')), true, 302);
            exit();
        }

        $comments = \App\Models\Comments::epin($epin->id);
        $package = explode('-', $paket);
        $package = DB::table('games_packages')
            ->where('id', end($package))
            ->first();
        if ($package == null) {
            header('Location: ' . URL::to(route('errors_404')), true, 302);
            exit();
        }
        $jsonld = \App\Helpers\StructuredData::jsonLd('package', ['epin' => $epin, 'package' => $package, 'comments' => $comments]);

        return view('front.pages.e-pin-detay-paket')->with(['epin' => $epinName, 'paket' => $paketName,  'jsonld' => $jsonld]);
    }

    public function epin_detay_paket($epin, $paket)
    {
        return view('front.pages.e-pin-detay-paket')->with('epin', $epin)->with('paket', $paket);
    }

    /* public function epin_detay_paket_satin_al($epin, $paket)
    {
        //noIndex('set', true);
        return view('front.pages.e-pin-detay-paket-satin-al')->with('epin', $epin)->with('paket', $paket);
    } */

    public function epin_detay_paket_satin_al($epin, $paket)
    {
        $epinName = $epin;
        $paketName = $paket;
        $epin = getCacheEpinDetay($epin);
        if ($epin == null) {
            header('Location: ' . \URL::to(route('errors_404')), true, 302);
            exit();
        }

        $comments = \App\Models\Comments::epin($epin->id);
        $package = explode('-', $paket);
        $package = DB::table('games_packages')
            ->where('id', end($package))
            ->first();
        if ($package == null) {
            header('Location: ' . URL::to(route('errors_404')), true, 302);
            exit();
        }
        $jsonld = \App\Helpers\StructuredData::jsonLd('package', ['epin' => $epin, 'package' => $package, 'comments' => $comments]);

        return view('front.pages.e-pin-detay-paket-satin-al')->with(['epin' => $epinName, 'paket' => $paketName,  'jsonld' => $jsonld]);
    }

    public function search($term)
    {
        $sonuclar = array();
        $sorgu1 = getCacheSearchArea1($term);
        foreach ($sorgu1 as $i) {
            if ($i->type == 1) {
                $sonuclar[] = array('id' => $i->id, 'text' => $i->title, 'link' => route('item_detay', $i->link), 'image' => asset('front/games_titles/' . $i->image), 'type' => '1');
            } elseif ($i->type == 2) {
                $sonuclar[] = array('id' => $i->id, 'text' => $i->title, 'link' => route('epin_detay', $i->link), 'image' => asset('front/games_titles/' . $i->image), 'type' => '1');
            } else {
                $sonuclar[] = array('id' => $i->id, 'text' => $i->title, 'link' => route('game_gold_detay', $i->link), 'image' => asset('front/games_titles/' . $i->image), 'type' => '1');
            }
        }
        $sorgu2 = getCacheSearchArea2($term);
        foreach ($sorgu2 as $item) {
            if ($item->deleted_at == NULL) {
                $trade = DB::table('games_titles')->whereNull('deleted_at')->where('id', $item->games_titles)->first();
                $sonuclar[] = array('id' => $item->id, 'text' => $item->title, 'link' => route('game_gold_detay_paket', [$trade->link, Str::slug($item->title) . "-" . $item->id]), 'image' => asset('front/games_packages_trade/' . $item->image), 'type' => '2');
            }
        }
        $sorgu3 = getCacheSearchArea3($term);
        foreach ($sorgu3 as $itemKemal) {
            if ($itemKemal->deleted_at == NULL) {
                $item = DB::table('games_titles')->whereNull('deleted_at')->where('id', $itemKemal->games_titles)->first();
                $sonuclar[] = array('id' => $itemKemal->id, 'text' => $itemKemal->title, 'link' => route('epin_detay_paket', [$item->link, Str::slug($itemKemal->title) . "-" . $itemKemal->id]), 'image' => asset(env('ROOT') . env('FRONT') . env('GAMES_PACKAGES') . $itemKemal->image), 'type' => '3', 'price' => findGamesPackagesPrice($itemKemal->id)); //$itemKemal->price
            }
        }
        $sorgu4 = getCacheSearchArea4($term);
        foreach ($sorgu4 as $itemMuve) {
            if ($itemMuve->deleted_at == NULL) {
                //$item = DB::table('games_titles')->whereNull('deleted_at')->where('id', $itemMuve->games_titles)->first();
                $sonuclar[] = array('id' => $itemMuve->id, 'text' => $itemMuve->title, 'link' => route('cd_key_detay', [$itemMuve->link]), 'image' =>  $itemMuve->image, 'type' => '3', 'price' => $itemMuve->muvePrice); //$itemKemal->price
            }
        }
        /* Epin sorgulu arama
        $sorgu4 = DB::table('games_titles')
            ->select('games_titles.*')
            ->join('games', 'games_titles.game', '=', 'games.id')
            ->whereNull('games_titles.deleted_at')
            ->whereNull('games.deleted_at')
            ->where('games_titles.title', 'like', '%' . $term . '%')
            ->where('type', '2')
            ->get();
        foreach ($sorgu4 as $item) {
            if ($item->epin != 0) {
                $ch = curl_init();
                $headers = array(
                    'Authorization: ' . getAuthName(),
                    'ApiName: ' . getApiName(),
                    'ApiKey: ' . getApiKey(),
                    'Content-Type: application/x-www-form-urlencoded',
                );
                curl_setopt($ch, CURLOPT_URL, env('EPIN_BASE') . '/GameItemListById');
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_POSTFIELDS, 'id=' . $item->epin);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 30);
                $response = curl_exec($ch);
                $result_package = json_decode($response);
                curl_close($ch);
                foreach ($result_package->GameDto as $u) {
                    if (DB::table('games_packages_epin')->where('epinPaket', $u->Id)->count() > 0) {
                        $uu = DB::table('games_packages_epin')->where('epinPaket', $u->Id)->first();
                        $sonuclar[] = array('id' => $u->Id, 'text' => $uu->title, 'link' => route('epin_detay_paket', [$item->link, Str::slug($uu->title) . "-" . $u->Id]), 'image' => asset(env('ROOT') . env('FRONT') . env('GAMES_PACKAGES') . $uu->image), 'type' => '3', 'price' => $u->Price);
                    } else {
                        $sonuclar[] = array('id' => $u->Id, 'text' => $u->Name, 'link' => route('epin_detay_paket', [$item->link, Str::slug($u->Name) . "-" . $u->Id]), 'image' => asset(env('ROOT') . env('FRONT') . env('GAMES_TITLES') . $item->image), 'type' => '3', 'price' => $u->Price);
                    }
                }
            }
        } */
        if (count($sonuclar) > 0) {
            return json_encode($sonuclar);
        } else {
            return json_encode(array("status" => '204', "message" => "Böyle bir veri yok"));
        }
    }

    public function twitch_support()
    {
        return view('front.pages.twitch.index');
    }

    public function twitch_support_yayinci($yayinci)
    {
        return view('front.pages.twitch.yayinci')->with('yayinci', $yayinci);
    }

    public function item_getir($item)
    {
        return view('front.pages.api.itemler')->with('item', $item);
    }

    public function item_buy_getir($item)
    {
        return view('front.pages.api.itemler-buy')->with('item', $item);
    }

    public function sssSayfasi()
    {
        return view('front.pages.sss');
    }

    public function cd_key()
    {
        return view('front.pages.muve.index');
    }

    public function cd_key_detay($cdkey)
    {
        return view('front.pages.muve.detail')->with('cdkey', $cdkey);
    }

    public function cd_key_detay_satin_al($cdkey)
    {
        //noIndex('set', true);
        if (isset(Auth::user()->id))
            return view('front.pages.muve.buy')->with('cdkey', $cdkey);
        else
            return redirect()->route('giris')->with('error', __('general.hata-0'));
    }

    public function priceguard(Request $request)
    {
        echo json_encode([]);
        exit();
        $postData = $request->post();
        $prices = [];
        if ($postData["type"]== 'e-pin') {
            foreach ($postData["products"] as $id)
                $prices[] = MF(findGamesPackagesPrice(intval($id)));
        } elseif ($postData["type"] == "game-gold") {
            foreach ($postData["products"]  as $id)
                $prices[] = MF(findGamesPackagesTradeMusteridenAlPrice($id)) . ":" . MF(findGamesPackagesTradeMusteriyeSatPrice(intval($id)));
        }

        echo json_encode($prices);
    }
}
