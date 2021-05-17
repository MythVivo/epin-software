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
}
