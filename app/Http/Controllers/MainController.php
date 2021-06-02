<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;


class MainController extends Controller
{
    public function index()
    {
        return view('front.index');
    }

    public function oyunlar()
    {
        return view('front.pages.oyunlar');
    }

    public function oyunlar_baslik($oyun)
    {
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

    public function urun_onizle_post(Request $request)
    {
        $adet = $request->adet;
        $package = $request->package;
        setcookie('redirect', 'urun_onizle_post', time() + 60 * 60, '/');
        setcookie('adet', $adet, time() + 60 * 60, '/');
        setcookie('package', $package, time() + 60 * 60, '/');
        return view('front.pages.urun-onizle')->with('redirect', 'urun_onizle_post')->with('adet', $adet)->with('package', $package);
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
}
