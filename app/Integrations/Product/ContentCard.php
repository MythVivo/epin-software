<?php

namespace App\Integrations\Product;

use Illuminate\Support\Facades\DB;

class ContentCard extends AbstractProduct
{
    //denom => EAN
    static $denoms = [
        '20' => '4059629052460',
        '50' => '4059629052477',
        '100' => '4059629052484',
        '200' => '4059629052491',
        '250' => '4059629052507',
        '300' => '4059629052514',
        '500' => '4059629064050',
        '1000' => '4059629064067'
    ];
    public static function CreateOrder($product, $count)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://212.175.23.123:6799/status/1?key=oeks2022secretccc');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $resp = curl_exec($ch);
        echo $resp;
    }

    public static function FetchResult($jobid, &$count)
    {
        $jobid = intval($jobid);
        if (!$jobid)
            return false;

        $jobRes = DB::table('integrations_product_contentcard_jobs')->where('job_id', $jobid)->first();
        if (!$jobRes)
            return false;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://212.175.23.123:6799/status/$jobid?key=oeks2022secretccc");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $resp = curl_exec($ch);
        if ($resp) {
            $resp = json_decode($resp, true);
            $count = $resp['data']['count'] . '/' . $resp['data']['data']['count'];
            if ($resp['status'] == 1 && $resp['data']['response']) {
                DB::table('integrations_product_contentcard_jobs')->where('job_id', $jobid)->update(['result' => json_encode($resp['data']['response'])]);
                return $resp['data']['response'];
            } else
                return false;
        }
    }
    public static function GetJob($order_id)
    {
        $order_id = intval($order_id);
        if (!$order_id)
            return false;

        return DB::table('integrations_product_contentcard_jobs')->where('order_id', $order_id)->first();
    }
    public static function CreateJob($order_id, $count, $ean)
    {
        $job = self::GetJob($order_id);

        if (!$job) {

            $queryResult = DB::table('integrations_product_contentcard_jobs')->insert([
                'order_id' => $order_id
            ]);
            if ($queryResult)
                $job = self::GetJob($order_id);
        }

        if ($job->job_id) {
            return $job->job_id;
        } else {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'http://212.175.23.123:6799/add?key=oeks2022secretccc');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, 1);
            $payload = json_encode(["ean" => $ean, "count" => $count]);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

            $resp = curl_exec($ch);
            if ($resp) {
                $resp = json_decode($resp, true);
                if ($resp['status'] == 1) {
                    DB::table('integrations_product_contentcard_jobs')->where('order_id', $order_id)->update(['job_id' => $resp['id']]);
                    return $resp['id'];
                } else
                    return false;
            } else
                return false;
        }
    }
    public static function ResetJob($order_id)
    {
        $job = self::GetJob($order_id);

        if (!$job) {
            return "Gorev Bulunamadi";
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://212.175.23.123:6799/reset/?key=oeks2022secretccc');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $resp = curl_exec($ch);
        if ($resp) {
            return $resp;
        } else
            return false;
    }
    function test()
    {
        /* DB::table('integrations_product_contentcard_jobs')->insert([
            "order_id" => 333,
            "job_id" => 1
        ]); */

        //var_dump(self::FetchResult(1));
    }
    function router()
    {
        $ean = (string)intval(@$_GET['ean']);
        $count = intval(@$_GET['count']);
        $order_id = intval(@$_GET['order_id']);
        if ($ean && $count && $order_id) {
        } else {
            echo json_encode(['status' => 0, 'msg' => 'Parameter Error']);
            exit();
        }
        $jobId = self::CreateJob($order_id, $count, $ean);
        if ($jobId) {
            $job = self::GetJob($order_id);
            if (!$job->result) {
                $count = 0;
                $fetch = self::FetchResult($job->job_id, $count);
                if ($fetch)
                    echo json_encode(['status' => 1, 'data' => $fetch]);
                else
                    echo json_encode(['status' => -1, 'msg' => 'Job Created ID: ' . $jobId, 'count' => $count]);
            } else {
                echo json_encode(['status' => 1, 'data' => json_decode($job->result, true)]);
            }
        } else {
            echo json_encode(['status' => 0, 'msg' => 'API Error']);
        }
        //echo "aaaa";
    }
}
