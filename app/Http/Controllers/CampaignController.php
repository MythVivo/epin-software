<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Campaigns;

class CampaignController extends Controller
{
    public function test()
    {
        Campaigns::GetCampaign(37067, 1214);
        exit();
    }
}
