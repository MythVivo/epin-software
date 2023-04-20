<?php

namespace App\Integrations\Finance;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class Papara
{
    public static function request($acc, $tutar, $id)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://merchant-api.papara.com/masspayment',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{ "accountNumber":"' . $acc . '", "amount":' . $tutar . ', "massPaymentId":"' . $id . '", "description": "Oyuneks Ödemesi" }',
            CURLOPT_HTTPHEADER => array(
                'ApiKey: jpB+ovOkx9rwzC1Xp1Y3+m18h+djfbXmMFQ5NQev/898wvZPVUhpBxBJlZF8W1f9qdLwjayEniBNu/Y+V45INw==',
                'Content-Type: application/json',
                'Cookie: __cf_bm=CHo2xS8lQg33D.oU4GiqSDTLvBZDnZEe30NrDe5_WuQ-1672994341-0-ASlLN/ZSVdHRo7hvroractmhpURdehJADA0KNhP001LXkFipfwKceyenKhAEuOsCzNxYCW8y1INZXsDH7XGDsxncbjcP8de8Em+IPS62RU1m; __cflb=02DiuDp3VShvLUP6f7rfBDyTxHJATk2mu6XVp765BaWsN'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
    public static function GeneratePayouts()
    {
        $options = DB::table('_options')->where('name', 'papara')->first();
        $options = json_decode($options->value);

        if (!$options->active) {
            return -1;
        }

        //$talepler = DB::select("SELECT id,user,amount,kesinti,text, amount-(amount*kesinti/100) ode FROM `para_cek` where text like 'PAPARA%' and status=0 and deleted_at is null and amount<'$options->limit'");

        $requests = DB::table('para_cek')
            ->select(DB::Raw('para_cek.id,para_cek.user,odeme_kanallari.alici,odeme_kanallari.iban,para_cek.amount,para_cek.kesinti,users.tcno,users.name'))
            ->join('odeme_kanallari', 'odeme_kanallari.id', '=', 'para_cek.odeme_kanali')
            ->join('users', 'users.id', '=', 'para_cek.user')
            ->where('para_cek.status', 0)
            ->where('para_cek.amount', '<', $options->limit)
            ->where('para_cek.text', 'LIKE', 'PAPARA%')
            ->whereNull('para_cek.deleted_at')
            ->get();
        var_dump($requests);
        foreach ($requests as $req) {
            $data = [
                'user' => $req->user,
                'tutar' => (floor($req->amount * 100 / 1.015) / 100),
                'hesap' => $req->iban
            ];


            /* echo $req->iban;
            echo "<br>";
            echo $req->amount;
            echo "<br>"; */


            if (strlen($data['hesap']) == 10 /* || strlen($data['hesap']) == 11 */) {
                if (DB::table('integrations_papara')->where('para_cek_id', $req->id)->count() < 1) {
                    try {
                        DB::table('integrations_papara')->insert([
                            'para_cek_id' => $req->id,
                            'request' => json_encode($data, JSON_UNESCAPED_UNICODE)
                        ]);
                        DB::table('para_cek')->where('id', $req->id)->update(['status' => -1]);
                    } catch (\Exception $e) {
                        //var_dump($e);
                    }
                }
            }
        }
        //var_dump($requests);

        return;
        foreach ($talepler as $req) {

            preg_match('/\((.*?)\)/s', $req->text, $r);  // hes. noyu ayır al arr. r1 de olacak

            $data = [
                "hesap" => $r[1],
                "tutar" => $req->amount - $req->ode
            ];

            if (DB::table('integrations_papara')->where('para_cek_id', $req->id)->count() < 1) {
                try {
                    DB::table('integrations_papara')->insert([
                        'para_cek_id' => $req->id,
                        'request' => json_encode($data, JSON_UNESCAPED_UNICODE)
                    ]);
                    DB::table('para_cek')->where('id', $req->id)->update(['status' => -1]);
                } catch (\Exception $e) {
                    //var_dump($e);
                }
            }
        }
        return sizeof($talepler);
    }

    public function client(string $endpoint, ?array $body = null, string $method = "GET", ?array $headers = null)
    {
    }
    public function SenderIBANList()
    {
    }
    public function test(Request $request)
    {
        //if($request->ip() != '31.223.124.98') {echo "xxx";exit();}  Almış olduğunuz ödeme bildiriminin Papara IP'si 213.74.240.34’den geldiğini teyit etmelisiniz.
        echo json_encode($this->GeneratePayouts(), JSON_UNESCAPED_UNICODE);
    }
    public function list(Request $request)
    {
        //if($request->ip() != '31.223.124.98') {echo "xxx";exit();}

        $data = DB::table('integrations_papara')->whereIn('state', array(-1, 0))->get();
        foreach ($data as $item) {
            $item->request = json_decode($item->request, true);
            $item->response = json_decode($item->response, true);
        }

        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    public function send(Request $request)
    {
        echo "Boom";
        $this->GeneratePayouts();
        echo "asdasd";
        $data = DB::table('integrations_papara')->whereIn('state', array(0))->get();
        var_dump($data);
        foreach ($data as $item) {
            $item->request = json_decode($item->request, true);
            DB::table('integrations_papara')->where('id', $item->id)->update(['state' => -1]);
            $apiResponse = self::request($item->request['hesap'], $item->request['tutar'], $item->id . "_" . $item->para_cek_id);
            $apiResponseArr = json_decode($apiResponse);
            echo $apiResponse . "<br>";
            //1490471989 - real
            //1101733754 - fake

            if (@$apiResponseArr->error->message) {
                $durum = -2;
            } else {
                $durum = 1;

#------------------------------------------------------  Cari tabloya para girisi
                try {
                    $user=json_decode(DB::table('integrations_papara')->where('id',$item->id)->first()->request)->user;
                    $son=$item->request['tutar'];
                    DB::table('cariy_fisler')->insert([
                        'kaynak_cari' => 21,
                        'hedef_cari' => 33,
                        'cikan_tutar' => $son,
                        'aktarilan_tutar' => $son,
                        'aciklama' => $item->request['hesap'] . ' hesabına gönderim. Kalan bakiye: ' .  $apiResponseArr->data->resultingBalance ,
                        'turu' => 16,
                        'created_at' => date('YmdHis')
                    ]);
#------Cari loglar
                    $last_id = DB::getPdo()->lastInsertId();

                    $bak_once = DB::table('cariy_hesaplar')->where('id', 33)->first()->bakiye;
                    $bak_sonra = $son + $bak_once;
                    DB::table('cariy_log')->insert([
                        'hesap_id' => 33,
                        'fis_id' => $last_id,
                        'onceki_bak' => $bak_once,
                        'sonraki_bak' => $bak_sonra,
                        'aciklama' => 'Kullanıcı Para Çekim',
                        'user' => $user,
                        'created_at' => date('YmdHis')
                    ]);

                    DB::select("update cariy_hesaplar set bakiye=bakiye+$son where id=33"); // Papara hesap bakiyesi upt.

                    $bak_once = DB::table('cariy_hesaplar')->where('id', 21)->first()->bakiye;
                    $bak_sonra = $bak_once - $son;
                    DB::table('cariy_log')->insert([
                        'hesap_id' => 21,
                        'fis_id' => $last_id,
                        'onceki_bak' => $bak_once,
                        'sonraki_bak' => $bak_sonra,
                        'aciklama' => 'Kullanıcı Para Çekim',
                        'user' => $user,
                        'created_at' => date('YmdHis')
                    ]);

                    DB::select("update cariy_hesaplar set bakiye=bakiye-$son where id=21"); // Papara hesap bakiyesi upt.
                }
                catch (\Exception $e){

                }
#------------------------------------------------------  Cari tabloya para girisi son

            }
            $pReq = DB::table('integrations_papara')->where('id', $item->id)->first();
            DB::table('integrations_papara')->where('id', $item->id)->update(["state" => $durum, "response" => $apiResponse]);
            DB::table('para_cek')->where('id', $pReq->para_cek_id)->update(['status' => $durum]);
            /*$this->request($item->request['hesap'], $item->request['tutar']);*/
        }
    }
    public function update(Request $request)
    {
        // if($request->ip() != '31.223.124.98') {echo "xxx";exit();}

        $post = $request->post();
        $id = $post['id'];
        unset($post['id']);

        DB::table('integrations_papara')->where('id', $id)->update($post);

        if (@$post['state'] == 1) {
            $bulutReq = DB::table('integrations_papara')->where('id', $id)->first();
            DB::table('para_cek')->where('id', $bulutReq->para_cek_id)->update(['status' => 1]);
        } elseif (@$post['state'] == -2) {
            $bulutReq = DB::table('integrations_papara')->where('id', $id)->first();
            DB::table('para_cek')->where('id', $bulutReq->para_cek_id)->update(['status' => 0]);
        }


        //var_dump($request->post());
    }
}
