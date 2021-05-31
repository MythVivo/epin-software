<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


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
        return view('front.pages.urun-onizle')->with('package', $request->package)->with('adet', $request->adet);
    }
}
