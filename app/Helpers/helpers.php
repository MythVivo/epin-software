<?php

use App\Models\Slider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

function getLang(): string
{
    if (isset($_COOKIE['lang'])) {
        App::setLocale($_COOKIE['lang']);
        return $_COOKIE['lang'];
    } else {
        return App::getLocale();
    }
}

function setLang($lang)
{
    setcookie("lang", $lang, time() + (60 * 60 * 24 * 7), '/', '', false, true);
    App::setLocale($lang);
}

function getPage()
{
    return Request::path();
}

function getUrl()
{
    return url()->full();
}

function getLangLongName($lang = 'tr')
{
    if (\App\Models\Language::whereNull('deleted_at')->where('lang', $lang)->count() > 0) {
        return \App\Models\Language::whereNull('deleted_at')->where('lang', $lang)->first()->langName;
    } else {
        return "Hata";
    }
}

function getSiteName()
{
    return \App\Models\Settings::first()->site_name;
}

function getSiteDescription()
{
    return \App\Models\Settings::first()->description;
}

function getAuthorName()
{
    return \App\Models\Settings::first()->author_name;
}

function getFavicon()
{
    return \App\Models\Settings::first()->favicon;
}

function getUserAvatar($id = 0)
{
    if($id == 0) {
        return \App\Models\User::where('id', Auth::user()->id)->first()->avatar;
    } else {
        if(\App\Models\User::where('id', $id)->count() > 0) {
            return \App\Models\User::where('id', $id)->first()->avatar;
        } else {
            return \App\Models\Settings::first()->logo;
        }

    }

}

function getUserName($id = 0)
{
    if($id == 0) {
        return \App\Models\User::where('id', Auth::user()->id)->first()->username;
    } else {
        if(\App\Models\User::where('id', $id)->count() > 0) {
            return \App\Models\User::where('id', $id)->first()->username;
        } else {
            return "Silinmiş Kullanıcı";
        }

    }

}

function getStatistic()
{
    $settings = DB::table('settings')->first();
    date_default_timezone_set('Europe/Istanbul');

    class Detect
    {
        public static function systemInfo()
        {
            $user_agent = $_SERVER['HTTP_USER_AGENT'];
            $os_platform = "Bilinmeyen İşletim sistemi";
            $os_array = array(
                '/windows nt 10/i' => 'Windows 10',
                '/windows nt 6.3/i' => 'Windows 8.1',
                '/windows phone 8/i' => 'Windows Phone 8',
                '/windows phone os 7/i' => 'Windows Phone 7',
                '/windows nt 6.2/i' => 'Windows 8',
                '/windows nt 6.1/i' => 'Windows 7',
                '/windows nt 6.0/i' => 'Windows Vista',
                '/windows nt 5.2/i' => 'Windows Server 2003/XP x64',
                '/windows nt 5.1/i' => 'Windows XP',
                '/windows xp/i' => 'Windows XP',
                '/windows nt 5.0/i' => 'Windows 2000',
                '/windows me/i' => 'Windows ME',
                '/win98/i' => 'Windows 98',
                '/win95/i' => 'Windows 95',
                '/win16/i' => 'Windows 3.11',
                '/macintosh|mac os x/i' => 'Mac OS X',
                '/mac_powerpc/i' => 'Mac OS 9',
                '/linux/i' => 'Linux',
                '/ubuntu/i' => 'Ubuntu',
                '/iphone/i' => 'iPhone',
                '/ipod/i' => 'iPod',
                '/ipad/i' => 'iPad',
                '/android/i' => 'Android',
                '/blackberry/i' => 'BlackBerry',
                '/webos/i' => 'Mobile');
            $found = false;
            $device = '';
            foreach ($os_array as $regex => $value) {
                if ($found)
                    break;
                else if (preg_match($regex, $user_agent)) {
                    $os_platform = $value;
                    $device = !preg_match('/(windows|mac|linux|ubuntu)/i', $os_platform)
                        ? 'MOBILE' : (preg_match('/phone/i', $os_platform) ? 'MOBILE' : 'SYSTEM');
                }
            }
            $device = !$device ? 'SYSTEM' : $device;
            return array('os' => $os_platform, 'device' => $device);
        }

        public static function browser()
        {
            $user_agent = $_SERVER['HTTP_USER_AGENT'];
            $browser = "Bilinmeyen tarayıcı";
            $browser_array = array('/msie/i' => 'Internet Explorer',
                '/firefox/i' => 'Firefox',
                '/safari/i' => 'Apple Safari',
                '/chrome/i' => 'Google Chrome',
                '/opera/i' => 'Opera',
                '/netscape/i' => 'Netscape',
                '/maxthon/i' => 'Maxthon',
                '/konqueror/i' => 'Konqueror',
                '/mobile/i' => 'Cellphone');
            $found = false;
            foreach ($browser_array as $regex => $value) {
                if ($found)
                    break;
                else if (preg_match($regex, $user_agent, $result)) {
                    $browser = $value;
                }
            }
            return $browser;
        }
    }

    $system = Detect::systemInfo();
    $browser = Detect::browser();
    $ip = Request::ip();
    $page = Request::path();
    if ($page == '/') {
        $page = 'anasayfa';
    }
    $kontrol = DB::table('istatistik')->where('ip', $ip)->whereDay('date', date('d'))->count();
    if ($kontrol > 0) {
        DB::table('istatistik')->insert([
            'ip' => $ip,
            'date' => date('YmdHis'),
            'page' => $page,
            'device' => $system['os'],
            'browser' => $browser,
            'ms' => $system['device'],
            'tekil' => 0
        ]);
    } else {
        DB::table('istatistik')->insert([
            'ip' => $ip,
            'date' => date('YmdHis'),
            'page' => $page,
            'device' => $system['os'],
            'browser' => $browser,
            'ms' => $system['device'],
            'tekil' => 1
        ]);
    }
}

function getPageTitle($page, $lang)
{
    if (DB::table('pages')->where('url', $page)->where('lang', $lang)->count() > 0) {
        return DB::table('pages')->where('url', $page)->where('lang', $lang)->first()->title;
    } else {
        return __('admin.hata-1');
    }
}

function getGameTitle($link, $lang)
{
    if (DB::table('games')->where('link', $link)->where('lang', $lang)->count() > 0) {
        return DB::table('games')->where('link', $link)->where('lang', $lang)->first()->title;
    } else {
        return __('admin.hata-1');
    }
}

function getLiveVisitor()
{
    $date = strtotime(date('Y-m-d H:i:s') . '- 15 minute');
    return \App\Models\Statistic::
    where('date', '>', date('YmdHis', $date))
        ->distinct('ip')
        ->count();
}

function getBrowserStatistic()
{
    return json_encode(\App\Models\Statistic::distinct('browser')->orderBy('browser')->select('browser')->pluck('browser'));
}

function getBrowserStatisticData()
{
    $total = \App\Models\Statistic::count();
    foreach (json_decode(getBrowserStatistic()) as $browserName) {
        $count[] = round((100 / $total) * \App\Models\Statistic::where('browser', $browserName)->count());
    }
    return $count;

}

function findLogsCategory($categoryId)
{
    $response = DB::table('logs_categories')->where('id', $categoryId)->first();
    return $response;
}

function findLogsTime($logsId)
{
    $time = strtotime(\App\Models\Logs::where('id', $logsId)->first()->created_at);
    if ($time > time() - 60) {
        return __('admin.biraz-once');
    } elseif ($time > time() - 60 * 59) {
        return round((time() - $time) / 60) . " " . __('admin.dakika-once');
    } elseif ($time > time() - 60 * 60 * 23) {
        return round((time() - $time) / 60 / 60) . " " . __('admin.saat-once');
    } elseif ($time > time() - 60 * 60 * 24 * 6) {
        return round((time() - $time) / 60 / 60 / 24) . " " . __('admin.gun-once');
    } else {
        return date('Y-m-d H:i:s', $time);
    }

}

function getNewLogs()
{
    $date = strtotime(date('Y-m-d H:i:s') . '- 10 second');
    if (\App\Models\Logs::where('created_at', '>', date('YmdHis', $date))->count() > 0) {
        $data = \App\Models\Logs::where('created_at', '>', date('YmdHis', $date))->get();
        foreach ($data as $data) {
            echo '<div class="activity slideInDown animated" data-lang="' . getLang() . '">
              <div class="activity-info">
                                                    <div class="icon-info-activity">
                                                        <i class="' . findLogsCategory($data->id)->icon . ' bg-soft-' . findLogsCategory($data->id)->type . '"></i>
                                                    </div>
                                                    <div class="activity-info-text">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <h6 class="m-0 w-75">' . findLogsCategory($data->id)->title . date('s') . '</h6>
                                                            <span
                                                                class="text-muted d-block">' . findLogsTime($data->id) . '</span>
                                                        </div>
                                                        <p class="text-muted mt-3">' . $data->text . '</p>
                                                    </div>
                                                </div>
                                            </div><!--end activity-->';
        }
    } else {
        return "1";
    }
}

function getUniqueVisitor()
{
    return \App\Models\Statistic::where('tekil', '1')->count();
}

function getPluralVisitor()
{
    return \App\Models\Statistic::where('tekil', '0')->count();
}

function getPluralVisitorLast7Day()
{
    $string = "";
    for ($i = 0; $i < 7; $i++) {
        $date = date('Y-m-d', strtotime(date('Y-m-d') . '-' . $i . ' day'));
        $string .= " " . \App\Models\Statistic::whereDate('date', $date)->where('tekil', '0')->count() . ",";
    }
    return $string;
}

function getUniqueVisitorLast7Day()
{
    $string = "";
    for ($i = 0; $i < 7; $i++) {
        $date = date('Y-m-d', strtotime(date('Y-m-d') . '-' . $i . ' day'));
        $string .= " " . \App\Models\Statistic::whereDate('date', $date)->where('tekil', '1')->count() . ",";
    }
    return $string;
}

function getDeviceUnique()
{
    return \App\Models\Statistic::distinct('device')->orderBy('device')->select('device')->pluck('device');
}

function getDeviceUniqueLabel()
{
    return json_encode(\App\Models\Statistic::distinct('device')->orderBy('device')->select('device')->pluck('device'));
}

function findSessionByDevice($device)
{
    return \App\Models\Statistic::where('device', $device)->count();
}

function createRandomColor($adet)
{
    $string = array();
    for ($i = 1; $i <= $adet; $i++) {
        $string[] = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
    }
    return json_encode($string);
}

function findSessionByDeviceDaily($device)
{
    $date = date('Y-m-d', strtotime(date('Y-m-d') . '-1 day'));
    $dunZiyaretci = \App\Models\Statistic::whereDate('date', $date)->where('device', $device)->count();
    $bugunZiyaretci = \App\Models\Statistic::whereDate('date', date('Y-m-d'))->where('device', $device)->count();
    if ($dunZiyaretci == 0) {
        return round($bugunZiyaretci * 100);
    } else {
        $deger = round(($bugunZiyaretci - $dunZiyaretci) / $dunZiyaretci * 100);
        if ($deger > 0) {
            return "%" . $deger;
        } else {
            return "-%" . -1 * $deger;
        }
    }


}

function getDataStatus($status)
{
    if ($status == '0') {
        return "<span class='badge badge-md badge-soft-warning'>" . __('admin.pasif') . "</span>";
    } elseif ($status == '1') {
        return "<span class='badge badge-md badge-soft-success'>" . __('admin.aktif') . "</span>";
    }
}

function setStatus($table, $id)
{
    $data = DB::table($table)->where('id', $id);
    if ($data->first()->status == '1') {
        $data->update(['status' => '0']);
    } else {
        $data->update(['status' => '1']);
    }
}

function getLangInput()
{
        return "<input type='hidden' name='lang' value='".getLang()."'>";
}

function deleteImage($table, $id)
{
    $data = DB::table($table)->where('id', $id);
    if(Schema::hasColumn($table, 'image')) {
        if($data->first()->image != '') {
            $filename = env("root") . env("front") . env($table) . $data->first()->image;
            $fileOriginalName = explode(".", $data->first()->image);
            $filenameResize = env("root") . env("front") . env($table) . $fileOriginalName[0]."@2x.".$fileOriginalName[1];
            if (file_exists($filename)) {
                unlink($filename);
            }
            if(file_exists($filenameResize)) {
                unlink($filenameResize);
            }
        }
    }
}

function deleteContent($table, $id)
{
    $data = DB::table($table)->where('id', $id);
    deleteImage($table, $id);
    $data->update(['deleted_at' => date('YmdHis')]);
}

function getData($table, $id)
{
    echo json_encode(DB::table($table)->where('id', $id)->first());
}

function imageResize($extension, $destinationPath, $fileName, $title, $oran, $quality) {
    $file = $destinationPath.$fileName;
    list($gen, $yuk) = getimagesize($file);
    $yenigen = $gen * $oran;
    $yeniyuk = $yuk * $oran;
    $hedef = imagecreatetruecolor($yenigen, $yeniyuk);
    $kaynak = imagecreatefromjpeg($file);
    imagecopyresized($hedef, $kaynak, 0, 0, 0, 0, $yenigen, $yeniyuk, $gen, $yuk);
    $fileNameBoyutlanmis = $title . '@2x.' . $extension;
    imagejpeg($hedef, $destinationPath.$fileNameBoyutlanmis, $quality) ;
}

function categoryFind($category)
{
    return \App\Models\Category::where('id', $category)->first()->title;
}

function findGamesTitleType($type)
{
    if($type == 1) {
        return __('admin.baslikPazarYeri');
    } elseif($type == 2) {
        return __('admin.baslikPaketSatisi');
    } elseif($type == 3) {
        return __('admin.baslikTrade');
    } else {
        return __('admin.hata-3');
    }
}

function findGamesPackagesPrice($gamesPackages)
{
    $gamesPackages = \App\Models\GamesPackages::where('id', $gamesPackages)->first();
    if($gamesPackages->discount_type == 0) {
        return $gamesPackages->price;
    } elseif($gamesPackages->discount_type == 1) { //yüzde olarak indirim
        return $gamesPackages->price - ($gamesPackages->discount_amount * $gamesPackages->price / 100);
    } elseif($gamesPackages->discount_type == 2) { // tutar olarak indirim
        return $gamesPackages->price - $gamesPackages->discount_amount;
    }
}

function findFaqCategory($faq)
{
    return DB::table('faq_categories')->where('id', $faq)->first()->title;
}

function getSiteSenderMail()
{
    return env('MAIL_FROM_ADDRESS');
}

function getUserPhoneStatus($id)
{
    if(\App\Models\User::find($id)->telefon_verified_at != NULL) {
        return "<i class='mdi mdi-check text-success'></i>";
    } else {
        return "<i class='mdi mdi-alert-outline text-danger'></i>";
    }
}

function getUserEmailStatus($id)
{
    if(\App\Models\User::find($id)->email_verified_at != NULL) {
        return "<i class='mdi mdi-check text-success'></i>";
    } else {
        return "<i class='mdi mdi-alert-outline text-danger'></i>";
    }
}

function getSiteUrl()
{
    return env("APP_URL");
}

function getSiteContactEmail()
{
    return env('contatc_mail');
}

function LogCall($yapan, $kategori, $aciklama) {
    $log = new \App\Models\Logs();
    $log->user = $yapan;
    $log->category = $kategori;
    $log->text = $aciklama;
    $log->lang = getLang();
    $log->created_at = date('YmdHis');
    $log->save();
}

function loginsAttempt($user, $failed) {
    class Detect
    {
        public static function systemInfo()
        {
            $user_agent = $_SERVER['HTTP_USER_AGENT'];
            $os_platform = "Bilinmeyen İşletim sistemi";
            $os_array = array(
                '/windows nt 10/i' => 'Windows 10',
                '/windows nt 6.3/i' => 'Windows 8.1',
                '/windows phone 8/i' => 'Windows Phone 8',
                '/windows phone os 7/i' => 'Windows Phone 7',
                '/windows nt 6.2/i' => 'Windows 8',
                '/windows nt 6.1/i' => 'Windows 7',
                '/windows nt 6.0/i' => 'Windows Vista',
                '/windows nt 5.2/i' => 'Windows Server 2003/XP x64',
                '/windows nt 5.1/i' => 'Windows XP',
                '/windows xp/i' => 'Windows XP',
                '/windows nt 5.0/i' => 'Windows 2000',
                '/windows me/i' => 'Windows ME',
                '/win98/i' => 'Windows 98',
                '/win95/i' => 'Windows 95',
                '/win16/i' => 'Windows 3.11',
                '/macintosh|mac os x/i' => 'Mac OS X',
                '/mac_powerpc/i' => 'Mac OS 9',
                '/linux/i' => 'Linux',
                '/ubuntu/i' => 'Ubuntu',
                '/iphone/i' => 'iPhone',
                '/ipod/i' => 'iPod',
                '/ipad/i' => 'iPad',
                '/android/i' => 'Android',
                '/blackberry/i' => 'BlackBerry',
                '/webos/i' => 'Mobile');
            $found = false;
            $device = '';
            foreach ($os_array as $regex => $value) {
                if ($found)
                    break;
                else if (preg_match($regex, $user_agent)) {
                    $os_platform = $value;
                    $device = !preg_match('/(windows|mac|linux|ubuntu)/i', $os_platform)
                        ? 'MOBILE' : (preg_match('/phone/i', $os_platform) ? 'MOBILE' : 'SYSTEM');
                }
            }
            $device = !$device ? 'SYSTEM' : $device;
            return array('os' => $os_platform, 'device' => $device);
        }
    }
    $system = Detect::systemInfo();
    $attempt = new \App\Models\LoginsAttempt();
    $attempt->user = $user;
    $attempt->chanel = $system['os'];
    $attempt->failed = $failed;
    $attempt->created_at = date('YmdHis');
    $attempt->save();
}

function findBakiye()
{
    return Auth::user()->bakiye;
}


