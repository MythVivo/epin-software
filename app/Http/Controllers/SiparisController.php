<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;

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
            $token = $request->token;


            /*
             * Ödeme Güvenlik Bağlantısı İsteği
             */
            $ch = curl_init();
            $post = "grant_type=client_credentials&client_id=410199&client_secret=f17486a0d74810148080e8887f37ad7f";
            $certificate_location = 'C:\wamp64\bin\php\php7.4.9\extras\ssl\openssl.cnf'; //silinecek
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, $certificate_location); //silinecek
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $certificate_location); //silinecek
            curl_setopt($ch, CURLOPT_URL, "https://secure.snd.payu.com/pl/standard/user/oauth/authorize");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Content-Type: application/x-www-form-urlencoded"
            ));
            $response = curl_exec($ch);
            curl_close($ch);
            $oathCode = json_decode($response, true);
            $accessToken = $oathCode['access_token'];
            $accessType = $oathCode['token_type'];
            /*
             * ödeme işlemi başlangıç
             */
            $ip = $_SERVER["REMOTE_ADDR"];
            $ch = curl_init();
            $notifyUrl = "http://localhost.com/oyuneks/api/odeme-al";
            $continueUrl = "http://localhost.com/oyuneks/odemelerim";
            $email = Auth::user()->email;
            $ad = Auth::user()->name;
            $id = time()."-".Auth::user()->id;
            $description = $ad . " isimli kullanıcı için " . $tutar . " değerinde ödeme";
            $certificate_location = 'C:\wamp64\bin\php\php7.4.9\extras\ssl\openssl.cnf'; //silinecek
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, $certificate_location); //silinecek
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $certificate_location); //silinecek
            curl_setopt($ch, CURLOPT_URL, "https://secure.snd.payu.com/api/v2_1/orders/");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, "{
            \"notifyUrl\": \"$notifyUrl\",
            \"continueUrl\": \"$continueUrl\",
            \"extOrderId\": \"$id\",
            \"customerIp\": \"$ip\",
            \"merchantPosId\": \"410199\",
            \"description\": \"$description\",
            \"currencyCode\": \"PLN\",
            \"cardOnFile\": \"FIRST\",
            \"totalAmount\": \"$tutar\",
            \"buyer\": {
            \"email\": \"$email\",
            \"firstName\": \"$ad\",
            \"lastName\": \" \",
            \"language\": \"hu\"
            },
            \"payMethods\": {
                \"payMethod\": {
                    \"value\": \"$token\",
                    \"type\":  \"CARD_TOKEN\"
                }
            },
            \"products\": [
            {
            \"name\": \"Oyuneks Bakiye Yükleme\",
            \"unitPrice\": \"$tutar\",
            \"quantity\": \"1\"
            }
            ]
            }");
            $header = ucfirst($accessType) . " " . $accessToken;
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Content-Type: application/json",
                "Authorization: " . $header
            ));

            $response = curl_exec($ch);
            curl_close($ch);
            $json_decoded = json_decode($response, true);
            if ($json_decoded['status']['statusCode'] == 'SUCCESS') { //ödeme işlemi başarılı
                header('Location: /');
                die();
            } else {
                var_dump($json_decoded);
                die("İşlem başarılı değil!");
            }


        }
    }

    public function odeme_notify()
    {
        DB::table('users')->where('id', '2')->update(['bakiye' => '10']);

        die();
        $inputJSON = file_get_contents('php://input');
        $input = json_decode( $inputJSON, true);
        if($input['order']['status'] == 'COMPLETED') {
            $bilgiler = explode("-", $input['order']['extOrderId']);
            $id = $bilgiler[1];
            DB::table('users')->where('id', $id)->update(['bakiye' => $input['order']['totalAmount']]);
        }
    }
}
