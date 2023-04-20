<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Campaigns extends Model
{
    use HasFactory;
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $table = 'campaigns';
    protected $casts = [
        'target_audience' => 'array'
    ];
    public static function GetCampaign($user, $product)
    {
       
        /*         
        SELECT * FROM `campaigns` where state = 1 AND deleted_at is null AND ( JSON_OVERLAPS(JSON_EXTRACT(target_audience, "$.include.users[*]"),'[37067,"all"]') OR JSON_OVERLAPS(JSON_EXTRACT(target_audience, "$.include.referrers[*]"),'[36908,"all"]') ) AND (ISNULL(JSON_EXTRACT(target_audience, "$.exclude.users[*]")) OR NOT JSON_CONTAINS(JSON_EXTRACT(target_audience, "$.exclude.users[*]"), '37067', '$'))AND (ISNULL(JSON_EXTRACT(target_audience, "$.exclude.referrers[*]")) OR NOT JSON_CONTAINS(JSON_EXTRACT(target_audience, "$.exclude.referrers[*]"), '36908', '$')) AND ( ((ISNULL(JSON_EXTRACT(target_audience, "$.include.products[*]")) OR JSON_CONTAINS(JSON_EXTRACT(target_products, "$.include.products[*]"), '1214', '$'))) OR ((ISNULL(JSON_EXTRACT(target_audience, "$.include.categories[*]")) OR JSON_CONTAINS(JSON_EXTRACT(target_products, "$.include.categories[*]"), '379', '$'))) ) AND ((ISNULL(JSON_EXTRACT(target_audience, "$.exclude.products[*]")) OR JSON_CONTAINS(JSON_EXTRACT(target_products, "$.exclude.products[*]"), '1214', '$'))) AND ((ISNULL(JSON_EXTRACT(target_audience, "$.exclude.categories[*]")) OR JSON_CONTAINS(JSON_EXTRACT(target_products, "$.exclude.categories[*]"), '379', '$')));
        */

        $userId = intval($user);
        $productId = intval($product);
        $userData = DB::table('users')->where('id', $userId)->first();

        if ($userData) {
            if ($userData->refId) {
                $refQ = [
                    "OR JSON_OVERLAPS(JSON_EXTRACT(target_audience, \"$.include.referrers[*]\"),'[$userData->refId,\"all\"]')",
                    "AND (ISNULL(JSON_EXTRACT(target_audience, \"$.exclude.referrers[*]\")) OR NOT JSON_CONTAINS(JSON_EXTRACT(target_audience, \"$.exclude.referrers[*]\"), '$userData->refId', '$'))"
                ];
            }
        } else
            return 0;

        $productData = DB::table('games_packages')->select('games_titles.id as categoryId')->where('games_packages.id', $productId)->join('games_titles', 'games_titles.id', '=', 'games_packages.games_titles')->first();
        if ($productData) {
            $productQ = '
                AND 
                (
                    IF(ISNULL(JSON_EXTRACT(target_products, "$.include.products[*]")),0,JSON_CONTAINS(JSON_EXTRACT(target_products, "$.include.products[*]"), \'' . $productId . '\', \'$\'))
                    OR
                    IF(ISNULL(JSON_EXTRACT(target_products, "$.include.categories[*]")),0,JSON_CONTAINS(JSON_EXTRACT(target_products, "$.include.categories[*]"), \'' . $productData->categoryId . '\', \'$\'))
                )
                AND ((ISNULL(JSON_EXTRACT(target_products, "$.exclude.products[*]")) OR JSON_CONTAINS(JSON_EXTRACT(target_products, "$.exclude.products[*]"), \'' . $productId . '\', \'$\')))
                AND ((ISNULL(JSON_EXTRACT(target_products, "$.exclude.categories[*]")) OR JSON_CONTAINS(JSON_EXTRACT(target_products, "$.exclude.categories[*]"), \'' . $productData->categoryId . '\', \'$\')));
            ';
        } else
            return 0;



        $q = '
        state = 1 AND deleted_at is null AND 
        (
            JSON_OVERLAPS(JSON_EXTRACT(target_audience, "$.include.users[*]"),\'[' . $userId . ',"all"]\') ' . @$refQ[0] . '
        )
        AND (ISNULL(JSON_EXTRACT(target_audience, "$.exclude.users[*]")) OR NOT JSON_CONTAINS(JSON_EXTRACT(target_audience, "$.exclude.users[*]"), \'' . $userId . '\', \'$\'))' . @$refQ[1] . $productQ;



        //echo $q;
        $campaigns = DB::table('campaigns')->whereRaw($q)->get();
        $percentage = 0;
        foreach ($campaigns as $campaign) {
            $c = json_decode($campaign->campaign, true);
            $percentage = $c["percentage"] > $percentage ? $c["percentage"] : $percentage;
        }
        return $percentage;
        /* exit();
        $result = self::select('*')->whereJsonContains('target_audience->include->users', intval($user))->get();

        dd($result); */
    }
}
