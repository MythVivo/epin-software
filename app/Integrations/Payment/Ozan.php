<?php

namespace App\Integrations\Payment;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class Ozan extends AbstractPayment
{
    //public static $apiKey = "2e57272630d33a4406a7b327616315fc";//test
    public static $apiKey = "7b1e31f46bce4c0e34c288030ea500c0";
    //public static $url = "https://checkout-api-sandbox.ozan.com/pw/v3/initializePayment"; //test
    public static $url = "https://checkout-api.ozan.com/pw/v3/initializePayment";
    public function send($amount)
    {
        if(!canOzanPay())
            echo "NONONO";
        $userData = \App\Models\User::where('id', Auth::user()->id)->first();
        $name_arr = explode(" ", $userData->name);
        list($name, $surName) = [$name_arr[0], end($name_arr)];
        $sendData = [
            "apiKey" => self::$apiKey,
            "email" => $userData->email,
            "amount" => $amount,
            "currency" => "TRY",
            "showInstallmentList" => null,
            "returnUrl" => "https://oyuneks.com/ozan/return",
            "referenceNo" => '',
            "language" => "tr",
            "billingFirstName" => $name,
            "billingLastName" => $surName,
            "billingAddress1" => "34",
            "billingCity" => "Istanbul",
            "billingPostcode" => "07050",
            "billingCountry" => "TR",
            "billingPhone" => $userData->telefon_country . $userData->telefon,
            /*  "basketItems" => [
                "name" => "Oyuneks Bakiye",
                "description" => "Site Bakiyesi",
                "category" => "Online",
                "extraField" => "",
                "quantity" => 1,
                "unitPrice" => $amount
            ] */
            "basketItems" => [
                [
                    "name" => "Oyuneks Bakiye",
                    "description" => "Site Bakiyesi",
                    "category" => "Online",
                    "extraField" => "",
                    "quantity" => 1,
                    "unitPrice" => $amount
                ]
            ]
        ];


        DB::table('integrations_payment_ozan_send')->insert([
            "user_id" => $userData->id,
            "ip" => \Request::ip(),
            "data" => json_encode($sendData)
        ]);
        $lastId = DB::getPdo()->lastInsertId();
        $sendData['referenceNo'] = $lastId;
        $sendJson = json_encode($sendData);

        $ch = curl_init(self::$url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $sendJson);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $responseJson = curl_exec($ch);
        curl_close($ch);



        DB::table('integrations_payment_ozan_send')->where('id', $lastId)->update(['data' => $sendJson, 'response' => $responseJson]);

        $responseArr = json_decode($responseJson, true);
        if (@$responseArr['purchaseUrl']) {
            header('Location: ' . $responseArr['purchaseUrl']);
        } else {
            header('Location: https://oyuneks.com');
        }
        //echo Auth::user()->id;

    }
    public function return()
    {
        $referenceNo = @$_POST['referenceNo'];
        $amountProcessed = @$_POST['amountProcessed'];
        $status = @$_POST['status'];
        $message = @$_POST['message'];
        $transactionId = @$_POST['transactionId'];


        if (\Request::ip() != '185.187.184.140') {
            if ($status == 'APPROVED')
                return redirect()->route('odemelerim')->with('success', 'Ödeme işleminiz başarıyla kaydedilmiştir.');
            else
                return redirect()->route('odemelerim')->with('error', 'Ödeme işleminiz sırasında bir hata meydana gelmiştir.HATA: ' . $message);
        }
        DB::table('integrations_payment_ozan_return')->insert([
            "ip" => \Request::ip(),
            "data" => json_encode(@$_POST)
        ]);

        $ozan_req = DB::table('integrations_payment_ozan_send')->where('id', $referenceNo)->where('state', 0)->first();

        if (!$ozan_req)
            die("Not Found!");

        $user_id = $ozan_req->user_id;
        if ($status == "DECLINED" || $status == "ERROR") {
            DB::table('integrations_payment_ozan_send')->where('id', $referenceNo)->update(['state' => 2]);
        } else if ($status == "WAITING") {
            //DB::table('integrations_payment_ozan_send')->where('id', $referenceNo)->update(['state' => 99]);
        } else if ($status == "APPROVED") {
            DB::table('integrations_payment_ozan_send')->where('id', $referenceNo)->update(['state' => 1]);
            $balanceToAdd = round(($amountProcessed * (1 - (getCacheSetings()->ozanKomisyon / 100))) / 100, 2);
            DB::table('odemeler')->insert([
                'transId' => $transactionId,
                'user' => $user_id,
                'amount' => $balanceToAdd,
                'channel' => 16,
                'status' => '1',
                'description' => 'Kullanıcı ödeme işlemi başarılı',
                'created_at' => date('YmdHis')
            ]);
            $user = DB::table('users')->where('id', $user_id)->first();
            $bakiye = $user->bakiye;
            $totalUserWallet = $bakiye + $balanceToAdd;
            DB::table('users')->where('id', $user_id)->update([
                'bakiye' => $totalUserWallet,
            ]);
            odeme_kontrol(); // Ödeme limit kontrol

            #------------------------------------------------------  Cari tabloya para girisi
            DB::table('cariy_fisler')->insert([
                'kaynak_cari' => 0,
                'hedef_cari' => 22,
                'cikan_tutar' => $balanceToAdd,
                'aktarilan_tutar' => $balanceToAdd,
                'aciklama' => $user->name . ' tarafından Ozan Bakiye yüklemesi UID:'.$user->id,
                'turu' =>10,
                'created_at' => date('YmdHis')
            ]);
            DB::select("update cariy_hesaplar set bakiye=bakiye+$balanceToAdd where id=22"); // Papara hesap bakiyesi upt.
#-----------------------------------------------------


            LogCall($user->id, '3', "Kullanıcı " . $balanceToAdd . " TL tutarındaki ödeme talebi onaylandı.");
            setBildirim($user->id, '4', 'Ödeme Talebiniz Onaylandı', $balanceToAdd . 'TL değerindeki ödeme işleminiz onaylanmıştır. Keyifli alışverişler dileriz.', route('odemelerim'));
            sendSms($user->telefon, $balanceToAdd . " TL değerindeki ödeme işleminiz onaylanmıştır. Keyifli alışverişler dileriz.");
        }
    }
}
