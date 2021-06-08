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
            $expiration_month = explode("/", $expiration)[0];
            $expiration_year = explode("/", $expiration)[1];
            $number = str_replace(' ', '', $number);



            /*
             * PAYTR DIRECT API STEP 1
             */
            $merchant_id = '239375';
            $merchant_key = '6qRZZa9skJw1Xuju';
            $merchant_salt = '5BQ5XdNsRmgpUAhF';
            $merchant_ok_url = "http://oyuneks.ifeelcodev.com/siparis/basarili";
            $merchant_fail_url = "http://oyuneks.ifeelcodev.com/siparis/basarisiz";
            $user_basket = htmlentities(json_encode(array(
                array("Oyuneks bakiye yükleme işlemi", $tutar, 1)
            )));
            $merchant_oid = Auth::user()->id . time();
            $test_mode = "0";
            $non_3d = "0"; //3d'siz işlem için 0
            $client_lang = getLang();
            $non3d_test_failed = "0"; //non3d işlemde, başarısız işlemi test etmek için 1 gönderilir (test_mode ve non_3d değerleri 1 ise dikkate alınır!)
            if (isset($_SERVER["HTTP_CLIENT_IP"])) {
                $ip = $_SERVER["HTTP_CLIENT_IP"];
            } elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
                $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
            } else {
                $ip = $_SERVER["REMOTE_ADDR"];
            }
            $user_ip = $ip;
            $email = Auth::user()->email;
            $payment_amount = $tutar;
            $currency = "TL";
            $payment_type = "card";
            $installment_count = "0";
            $post_url = "https://www.paytr.com/odeme";
            $hash_str = $merchant_id . $user_ip . $merchant_oid . $email . $payment_amount . $payment_type . $installment_count . $currency . $test_mode . $non_3d;
            $token = base64_encode(hash_hmac('sha256', $hash_str . $merchant_salt, $merchant_key, true));

            /*
             * Ödeme isteği
             */
            $postRequest = array(
                'merchant_id' => $merchant_id,
                'paytr_token' => $token,
                'user_ip' => $user_ip,
                'merchant_oid' => $merchant_oid,
                'email' => $email,
                'payment_type' => $payment_type,
                'payment_amount' => $payment_amount,
                'installment_count' => $installment_count,
                'currency' => $currency,
                'client_lang' => $client_lang,
                'test_mode' => $test_mode,
                'non_3d' => $non_3d,
                'non3d_test_failed' => $non3d_test_failed,
                'cc_owner' => $name,
                'card_number' => $number,
                'expiry_month' => $expiration_month,
                'expiry_year' => $expiration_year,
                'cvv' => $cvv,
                'merchant_ok_url' => $merchant_ok_url,
                'merchant_fail_url' => $merchant_fail_url,
                'user_name' => Auth::user()->name,
                'user_address' => "NonPhysical",
                'user_phone' => Auth::user()->telefon,
                'user_basket' => $user_basket,
                'debug_on' => "1",
            );
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $post_url);
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postRequest);
            $response = curl_exec($ch);
            curl_close($ch);

        }
    }

    public function odeme_notify()
    {


        ## 2. ADIM için örnek kodlar ##

        ## ÖNEMLİ UYARILAR ##
        ## 1) Bu sayfaya oturum (SESSION) ile veri taşıyamazsınız. Çünkü bu sayfa müşterilerin yönlendirildiği bir sayfa değildir.
        ## 2) Entegrasyonun 1. ADIM'ında gönderdiğniz merchant_oid değeri bu sayfaya POST ile gelir. Bu değeri kullanarak
        ## veri tabanınızdan ilgili siparişi tespit edip onaylamalı veya iptal etmelisiniz.
        ## 3) Aynı sipariş için birden fazla bildirim ulaşabilir (Ağ bağlantı sorunları vb. nedeniyle). Bu nedenle öncelikle
        ## siparişin durumunu veri tabanınızdan kontrol edin, eğer onaylandıysa tekrar işlem yapmayın. Örneği aşağıda bulunmaktadır.

        $post = $_POST;

        ####################### DÜZENLEMESİ ZORUNLU ALANLAR #######################
        #
        ## API Entegrasyon Bilgileri - Mağaza paneline giriş yaparak BİLGİ sayfasından alabilirsiniz.
        $merchant_key = 'YYYYYYYYYYYYYY';
        $merchant_salt = 'ZZZZZZZZZZZZZZ';
        ###########################################################################

        ####### Bu kısımda herhangi bir değişiklik yapmanıza gerek yoktur. #######
        #
        ## POST değerleri ile hash oluştur.
        $hash = base64_encode(hash_hmac('sha256', $post['merchant_oid'] . $merchant_salt . $post['status'] . $post['total_amount'], $merchant_key, true));
        #
        ## Oluşturulan hash'i, paytr'dan gelen post içindeki hash ile karşılaştır (isteğin paytr'dan geldiğine ve değişmediğine emin olmak için)
        ## Bu işlemi yapmazsanız maddi zarara uğramanız olasıdır.
        if ($hash != $post['hash'])
            die('PAYTR notification failed: bad hash');
        ###########################################################################

        ## BURADA YAPILMASI GEREKENLER
        ## 1) Siparişin durumunu $post['merchant_oid'] değerini kullanarak veri tabanınızdan sorgulayın.
        ## 2) Eğer sipariş zaten daha önceden onaylandıysa veya iptal edildiyse  echo "OK"; exit; yaparak sonlandırın.

        /* Sipariş durum sorgulama örnek
            $durum = SQL
           if($durum == "onay" || $durum == "iptal"){
                echo "OK";
                exit;
            }
         */

        if ($post['status'] == 'success') { ## Ödeme Onaylandı

            ## BURADA YAPILMASI GEREKENLER
            ## 1) Siparişi onaylayın.
            ## 2) Eğer müşterinize mesaj / SMS / e-posta gibi bilgilendirme yapacaksanız bu aşamada yapmalısınız.
            ## 3) 1. ADIM'da gönderilen payment_amount sipariş tutarı taksitli alışveriş yapılması durumunda
            ## değişebilir. Güncel tutarı $post['total_amount'] değerinden alarak muhasebe işlemlerinizde kullanabilirsiniz.

        } else { ## Ödemeye Onay Verilmedi

            ## BURADA YAPILMASI GEREKENLER
            ## 1) Siparişi iptal edin.
            ## 2) Eğer ödemenin onaylanmama sebebini kayıt edecekseniz aşağıdaki değerleri kullanabilirsiniz.
            ## $post['failed_reason_code'] - başarısız hata kodu
            ## $post['failed_reason_msg'] - başarısız hata mesajı

        }

        ## Bildirimin alındığını PayTR sistemine bildir.
        echo "OK";
        exit;

    }
}
