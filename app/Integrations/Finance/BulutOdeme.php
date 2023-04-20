<?php

namespace App\Integrations\Finance;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use mysql_xdevapi\Exception;

class BulutOdeme
{
    public static function GeneratePayouts()
    {
        // SELECT para_cek.id,odeme_kanallari.alici,odeme_kanallari.iban,para_cek.amount,para_cek.kesinti FROM para_cek inner join odeme_kanallari on odeme_kanallari.id = para_cek.odeme_kanali WHERE para_cek.id = 27564 and para_cek.status = 0
        $options = DB::table('_options')->where('name', 'bulut')->first();
        $options = json_decode($options->value);

        if (!$options->active)
            return -1;

        $requests = DB::table('para_cek')
            ->select(DB::Raw('para_cek.id,odeme_kanallari.alici,odeme_kanallari.iban,para_cek.amount,para_cek.kesinti,users.tcno,users.name'))
            ->join('odeme_kanallari', 'odeme_kanallari.id', '=', 'para_cek.odeme_kanali')
            ->join('users', 'users.id', '=', 'para_cek.user')
            ->where('para_cek.status', 0)
            ->where('para_cek.amount', '<', $options->limit)
            ->whereNull('para_cek.deleted_at')
            ->get();


        foreach ($requests as $req) {

            $data = [
                "name" => $req->alici,
                "iban" => $req->iban,
                "amount" => $req->amount - $req->kesinti,
                "taxnumber" => $req->tcno
            ];
            echo $req->id . " : " . strlen($data['iban']) . " : " . strlen($data['taxnumber']) . " : " . levenshtein(strtolower($req->alici), strtolower($req->name)) . " : " . $req->alici . " : " . $req->name . "<br>";
            if (strlen($data['iban']) == 24 && strlen($data['taxnumber']) == 11 && levenshtein(strtolower($req->alici), strtolower($req->name)) < 4) {
                if (DB::table('integrations_finance_bulut')->where('para_cek_id', $req->id)->count() < 1) {
                    try {
                        DB::table('integrations_finance_bulut')->insert([
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
        return sizeof($requests);
    }

    public function client(string $endpoint, ?array $body = null, string $method = "GET", ?array $headers = null)
    {
    }
    public function SenderIBANList()
    {
    }
    public function test(Request $request)
    {
        if ($request->ip() != '212.175.23.123' && $request->ip() != '212.174.16.130') {
            echo "xxx";
            exit();
        }
        echo json_encode($this->GeneratePayouts(), JSON_UNESCAPED_UNICODE);
    }
    public function list(Request $request)
    {
        if ($request->ip() != '212.175.23.123' && $request->ip() != '212.174.16.130') {
            echo "xxx";
            exit();
        }
        if (@$_GET['type'] == 'result')
            $data = DB::table('integrations_finance_bulut')->whereIn('state', array(1))->whereNull('result')->orWhere('result', 'like', '%BEKLE%')->limit(1000)->get();
        else
            $data = DB::table('integrations_finance_bulut')->whereIn('state', array(-1, 0))->limit(10)->get();
        foreach ($data as $item) {
            $item->request = json_decode($item->request, true);
            $item->response = json_decode($item->response, true);
        }

        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    public function update(Request $request)
    {
        if ($request->ip() != '212.175.23.123' && $request->ip() != '212.174.16.130') {
            echo "xxx";
            exit();
        }
        $post = $request->post();

        try {
            DB::table('aa_sil')->insert(['sil' => implode($post)    ]);
        } catch (\Exception $e) { }


        $id = $post['id'];
        unset($post['id']);

        DB::table('integrations_finance_bulut')->where('id', $id)->update($post);

        if (@$post['state'] == 1) {
            $bulutReq = DB::table('integrations_finance_bulut')->where('id', $id)->first();
            DB::table('para_cek')->where('id', $bulutReq->para_cek_id)->update(['status' => 1]);

#------------------------------------------------------  Cari tabloya para girisi
            try {
                $al=DB::table('para_cek')->where('id',$bulutReq->para_cek_id)->first();
                $user=$al->user;
                $son=$al->amount;
                DB::table('cariy_fisler')->insert([
                    'kaynak_cari' => 28,
                    'hedef_cari' => 34,
                    'cikan_tutar' => $son,
                    'aktarilan_tutar' => $son,
                    'aciklama' => json_decode($bulutReq->request)->iban . ' IBAN nolu hesaba gönderim. UID: '.$user,
                    'turu' => 17,
                    'created_at' => date('YmdHis')
                ]);
#------Cari loglar
                $last_id = DB::getPdo()->lastInsertId();

                $bak_once = DB::table('cariy_hesaplar')->where('id', 34)->first()->bakiye;
                $bak_sonra = $son + $bak_once;
                DB::table('cariy_log')->insert([
                    'hesap_id' => 34,
                    'fis_id' => $last_id,
                    'onceki_bak' => $bak_once,
                    'sonraki_bak' => $bak_sonra,
                    'aciklama' => 'Kullanıcı Para Çekim',
                    'user' => $user,
                    'created_at' => date('YmdHis')
                ]);

                DB::select("update cariy_hesaplar set bakiye=bakiye+$son where id=34"); // Ziraat giden hesap bakiyesi upt.

                $bak_once = DB::table('cariy_hesaplar')->where('id', 28)->first()->bakiye;
                $bak_sonra = $bak_once - $son;
                DB::table('cariy_log')->insert([
                    'hesap_id' => 28,
                    'fis_id' => $last_id,
                    'onceki_bak' => $bak_once,
                    'sonraki_bak' => $bak_sonra,
                    'aciklama' => 'Kullanıcı Para Çekim',
                    'user' => $user,
                    'created_at' => date('YmdHis')
                ]);

                DB::select("update cariy_hesaplar set bakiye=bakiye-$son where id=28"); // ziraat gelen hesap bakiyesi upt.
            }
            catch (\Exception $e){

            }
#------------------------------------------------------  Cari tabloya para girisi son





        } elseif (@$post['state'] == -2) {
            $bulutReq = DB::table('integrations_finance_bulut')->where('id', $id)->first();
            DB::table('para_cek')->where('id', $bulutReq->para_cek_id)->update(['status' => 0]);
        }


        //var_dump($request->post());
    }
}
