<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Classes\Cari;
use App\Classes\Cari\CariGroup;
use App\Classes\Cari\CariHesap;
use ReflectionClass;
use SoapClient;

class TestController  extends Controller
{

    public function cari()
    {

        $x = CariGroup::Create(["name" => "zaaa"]);
        var_dump($x);
        exit();
        CariGroup::All();
        //$result = Cari::FindById(1)->Send(Cari::FindById(2),50);
        //var_dump($result);
        //echo "xx";
    }

    public function form()
    {


        $merchant_id = getPaytrMerchantId();
        $merchant_key = getPaytrMerchantKey();
        $merchant_salt = getPaytrMerchantSalt();


        if (isset($_SERVER["HTTP_CLIENT_IP"])) {
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        } elseif (isset($_SERVER["HTTP_CF_PSEUDO_IPV4"])) {
            $ip = $_SERVER["HTTP_CF_PSEUDO_IPV4"];
        } elseif (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
            $ip = $_SERVER["HTTP_CF_CONNECTING_IP"];
        } elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } else {
            $ip = $_SERVER["REMOTE_ADDR"];
        }



        echo '<pre>';
        print_r([
            'HEADER' => [
                'HTTP_CF_CONNECTING_IP' => $_SERVER["HTTP_CF_CONNECTING_IP"],
                'HTTP_CLIENT_IP' => $_SERVER["HTTP_CLIENT_IP"] ?? '-',
                'HTTP_CF_PSEUDO_IPV4' => $_SERVER["HTTP_CF_PSEUDO_IPV4"] ?? '-',
                'HTTP_X_FORWARDED_FOR' => $_SERVER["HTTP_X_FORWARDED_FOR"] ?? '-',
                'REMOTE_ADDR' => $_SERVER["REMOTE_ADDR"] ?? '-',
            ],
            'REQUEST PAYTR API IP' => $ip,
            'REQUEST ALL' => request()->all(),
        ]);
        echo '</pre>';


        $user_basket = base64_encode(json_encode(array(
            array("Test İşlem", 1, 1), // 1. ürün (Ürün Ad - Birim Fiyat - Adet )
        )));

        $merchant_ok_url = "https://oyuneks.com/testodeme";
        $merchant_fail_url = "https://oyuneks.com/testodeme";


        $test_mode = 0;
        $merchant_oid = 'TEST' . time();
        $user_ip = $ip;
        $email = 'onurtasciweb@gmail.com';
        $user_name = 'Test Test';
        $user_address = "-";
        $user_phone = '00000000000';
        $payment_amount = 1 * 100;
        $currency = "TL";
        $timeout_limit = "30";
        $debug_on = 1;
        $no_installment = 0;
        $max_installment = 0;
        $post_url = "https://www.paytr.com/odeme/api/get-token";
        $hash_str = $merchant_id . $user_ip . $merchant_oid . $email . $payment_amount . $user_basket . $no_installment . $max_installment . $currency . $test_mode;
        $paytr_token = base64_encode(hash_hmac('sha256', $hash_str . $merchant_salt, $merchant_key, true));
        $post_vals = array(
            'merchant_id' => $merchant_id,
            'user_ip' => $user_ip,
            'merchant_oid' => $merchant_oid,
            'email' => $email,
            'payment_amount' => $payment_amount,
            'paytr_token' => $paytr_token,
            'user_basket' => $user_basket,
            'debug_on' => $debug_on,
            'no_installment' => $no_installment,
            'max_installment' => $max_installment,
            'user_name' => $user_name,
            'user_address' => $user_address,
            'user_phone' => $user_phone,
            'merchant_ok_url' => $merchant_ok_url,
            'merchant_fail_url' => $merchant_fail_url,
            'timeout_limit' => $timeout_limit,
            'currency' => $currency,
            'test_mode' => $test_mode
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $post_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_vals);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        $result = @curl_exec($ch);
        if (curl_errno($ch))
            return back()->with("error", "Ödeme servislerinde bir hata meydana geldi!");
        curl_close($ch);
        $result = json_decode($result, 1);


        if ($result['status'] == 'success') {
            $token = $result['token'];
            echo '<script src="https://www.paytr.com/js/iframeResizer.min.js"></script>';
            echo '<iframe src="https://www.paytr.com/odeme/guvenli/' . $token . '" id="paytriframe" frameborder="0" scrolling="no" style="width: 100%;"></iframe>';
            echo "<script>iFrameResize({},'#paytriframe');</script>";
        }
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


        return view('front.pages.test.game-gold-detay')->with(['gold' => $gold, 'jsonld' => $jsonld, 'goldTitle' => $goldTitle, 'products' => $products, 'success'=> __('general.mesaj-2'), 'type'=> 'kayit']);
    }
}
