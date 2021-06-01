<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class SiparisController extends Controller
{
    public function siparis_ver(Request $request)
    {
        $adet = $_COOKIE['adet'];
        $package = $_COOKIE['package'];
        if (!isset(Auth::user()->id)) { //eğer kullanıcı oturum açmamışsa
            return redirect()->route('giris');
        } else { //kullanıcı oturum açtıktan sonra
            $fiyat = findGamesPackagesPrice($package) * $adet;
            if (Auth::user()->bakiye < $fiyat) { // eğer bakiyesi yeterli değilse
                return redirect()->route('bakiye_ekle')->with('fiyat', $fiyat);
            }
        }
    }

    public function bakiye_ekle()
    {
        return view('front.pages.bakiye-ekle');
    }
}
