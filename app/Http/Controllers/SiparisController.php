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
        if (!isset($_COOKIE['redirect'])) {
            echo "<meta http-equiv='refresh' content='2;url=" . route('homepage') . "' />";
            die(__('general.yonlendiriliyorsunuz'));
        }
        $adet = $_COOKIE['adet'];
        $package = $_COOKIE['package'];
        if (!isset(Auth::user()->id)) { //eğer kullanıcı oturum açmamışsa
            return redirect()->route('giris');
        } else { //kullanıcı oturum açtıktan sonra
            $fiyat = findGamesPackagesPrice($package) * $adet;
            if (Auth::user()->bakiye < $fiyat) { // eğer bakiyesi yeterli değilse
                return redirect()->route('bakiye_ekle')->with('fiyat', $fiyat);
            } else { //bakiyesi yeterli ise

            }
        }
    }

    public function bakiye_ekle()
    {
        return view('front.pages.bakiye-ekle');
    }

    public function odeme_yap(Request $request)
    {
        if ($request->tur == 1) { //online ödeme
            $tutarName = "options-outlined";
            $tutar = $request->$tutarName;
            if ($tutar == 0) { //manuel girilmiştir
                $tutar = $request->tutar_manuel;
            }
            $name = $request->name;
            $number = $request->number;
            $expiration = $request->expiration;
            $cvv = $request->cvv;

            /*
             * Ödeme Güvenlik Bağlantısı İsteği
             */


            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, "https://secure.snd.payu.com/pl/standard/user/oauth/authorize");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials&client_id=410158&client_secret=8331faafd420af98a1ce7dc5ac95c978");
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Content-Type: application/x-www-form-urlencoded"
            ));
            $response = curl_exec($ch);
            print_r(curl_getinfo($ch));
            curl_close($ch);
            var_dump($response);


            /*$oathCode = json_decode($response, true);
            $accessToken = $oathCode['access_token'];
            $accessType = $oathCode['token_type'];*/

        }
    }
}
