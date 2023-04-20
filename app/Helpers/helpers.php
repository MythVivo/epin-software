<?php
//swx
use App\Models\Slider;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\FaqCategory;

function cdn($link, $w = null, $h = null)
{

    $link = 'https://oyuneks.com/' . $link;
    return $link; // disable cdn
    $resize = [];
    $w && ($resize[] = "w=$w");
    $h && ($resize[] = "h=$h");
    $resize && ($link = $link . '?' . implode("&", $resize));
    return $link;
}

#-----------------------------------------google reg. check
 function post_captcha($user_response,$ip, $olay=1)
{
    if ($olay==1){$key='6LcYPqEkAAAAAFyfewaE_5Z2wmIovD-yqANetGvQ';} else {$key='6LftzlolAAAAACRs2SLg2JMz13k-WgKA65kW4FDU';}
    $fields_string = '';
    $fields = array(
        'remoteip'=> $ip,
        'secret' => $key,
        'response' => $user_response
    );
    foreach($fields as $key=>$value)
        $fields_string .= $key . '=' . $value . '&';
    $fields_string = rtrim($fields_string, '&');
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
    curl_setopt($ch, CURLOPT_POST, count($fields));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, True);
    $result = curl_exec($ch);
    curl_close($ch);
    return json_decode($result, true);
}


function getLang(): string
{
    if (isset($_COOKIE['lang'])) {
        App::setLocale($_COOKIE['lang']);
        return $_COOKIE['lang'];
    } else {
        return App::getLocale();
    }
}

#---------------------------------Fatura için GG
function ictoplam($user, $date1, $date2)
{
    $sor = DB::select("
                    SELECT sum(ggs.adet) adet, ggs.paket paket, gp.oran oran
                    FROM game_gold_satis ggs
                    left join gg_paket gp on gp.paket=ggs.paket
                    WHERE isnull(ggs.deleted_at)
                    and date(ggs.created_at) BETWEEN '$date1' and '$date2'
                    and ggs.tur='bize-sat'
                    and user = '$user'
                    and ggs.status=1
                    group by paket
	");
    return $sor;
}



function log_stok($admin, $stok_id, $once, $islem, $sonra, $det)
{
    $tar = date('YmdHis');

    DB::insert("insert into log_stok values(null,'$admin','$stok_id','$once','$islem','$sonra','$det','$tar')");
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
    if ($id == 0) {
        return \App\Models\User::where('id', Auth::user()->id)->first()->avatar;
    } else {
        if (\App\Models\User::where('id', $id)->count() > 0) {
            return \App\Models\User::where('id', $id)->first()->avatar;
        } else {
            return \App\Models\Settings::first()->logo;
        }
    }
}

function getUserName($id = 0)
{
    if ($id == 0) {
        return \App\Models\User::where('id', Auth::user()->id)->first()->username;
    } else {
        if (\App\Models\User::where('id', $id)->count() > 0) {
            return \App\Models\User::where('id', $id)->first()->username;
        } else {
            return "Silinmiş Kullanıcı";
        }
    }
}

function getStatistic()
{
    return;

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
                '/webos/i' => 'Mobile'
            );
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
            $browser_array = array(
                '/msie/i' => 'Internet Explorer',
                '/firefox/i' => 'Firefox',
                '/safari/i' => 'Apple Safari',
                '/chrome/i' => 'Google Chrome',
                '/opera/i' => 'Opera',
                '/netscape/i' => 'Netscape',
                '/maxthon/i' => 'Maxthon',
                '/konqueror/i' => 'Konqueror',
                '/OPR/i' => 'Opera',
                '/Edg/i' => 'Internet Explorer',
                '/mobile/i' => 'Cellphone'
            );
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
    return \App\Models\Statistic::where('date', '>', date('YmdHis', $date))
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

function findLogsUserName($logsId)
{
    $logs = \App\Models\Logs::where('id', $logsId)->first()->user;
    if ($logs != 0) {
        return DB::table('users')->where('id', $logs)->first()->name;
    } else {
        return "Belirsiz Kullanıcı";
    }
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

function findDuzenlemeTime($duzenlemeId)
{
    $time = strtotime(DB::table('panel_duzenlemeler')->where('id', $duzenlemeId)->first()->created_at);
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
                                                        <i class="' . findLogsCategory($data->category)->icon . ' bg-soft-' . findLogsCategory($data->category)->type . '"></i>
                                                    </div>
                                                    <div class="activity-info-text">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <h6 class="m-0 w-75">' . findLogsCategory($data->category)->title . ' ' . findLogsUserName($data->id) . '</h6>
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
    } elseif ($status == '2') {
        return "<span class='badge badge-md badge-soft-danger'>RED</span>";
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

function ykp_yorum($dr, $id)
{
    $data = DB::table('comments')->where('id', $id);
    $data->update(['status' => $dr]);
}


function getLangInput()
{
    return "<input type='hidden' name='lang' value='" . getLang() . "'>";
}

function deleteImage($table, $id)
{
    $data = DB::table($table)->where('id', $id);
    if (Schema::hasColumn($table, 'image')) {
        if ($data->first()->image != '') {
            $table = strtoupper($table);
            $filename = env("ROOT") . env("FRONT") . env($table) . $data->first()->image;
            $fileOriginalName = explode(".", $data->first()->image);
            $filenameResize = env("ROOT") . env("FRONT") . env($table) . $fileOriginalName[0] . "@2x." . $fileOriginalName[1];
            if (file_exists($filename)) {
                unlink($filename);
            }
            if (file_exists($filenameResize)) {
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

function imageResize($extension, $destinationPath, $fileName, $title, $oran, $quality)
{
    $file = $destinationPath . $fileName;
    list($gen, $yuk) = getimagesize($file);
    $yenigen = $gen * $oran;
    $yeniyuk = $yuk * $oran;
    $hedef = imagecreatetruecolor($yenigen, $yeniyuk);

    $infoImage = getimagesize($file);
    $extensionImage = image_type_to_extension($infoImage[2]);
    if ($extensionImage == "png") {
        $kaynak = imagecreatefrompng($file);
    } elseif ($extensionImage == "jpg" or $extensionImage == "jpeg") {
        $kaynak = imagecreatefromjpeg($file);
    } else {
        return false;
    }

    imagecopyresized($hedef, $kaynak, 0, 0, 0, 0, $yenigen, $yeniyuk, $gen, $yuk);
    $fileNameBoyutlanmis = $title . '@2x.' . $extension;
    imagejpeg($hedef, $destinationPath . $fileNameBoyutlanmis, $quality);
}

function categoryFind($category)
{
    return \App\Models\Category::where('id', $category)->first()->title;
}

function findGamesTitleType($type)
{
    if ($type == 1) {
        return __('admin.baslikPazarYeri');
    } elseif ($type == 2) {
        return __('admin.baslikPaketSatisi');
    } elseif ($type == 3) {
        return __('admin.baslikTrade');
    } else {
        return __('admin.hata-3');
    }
}

function findGamesPackagesPrice($gamesPackages)
{
    $gamesPackagesId =  $gamesPackages;
    if (isset(Auth::user()->id) && Auth::user()->id == 21673 && $gamesPackages == 295) {
        return 405;
    } #---------------------Yakup tarafindan eklendi kullanici ya ozel fiyat

    $gamesPackages = \App\Models\GamesPackages::where('id', $gamesPackages)->first();
    if ($gamesPackages == NULL) {
        return 0;
    }
    $finalPrice = 99999;
    if (date('Y-m-d H:i:s') < $gamesPackages->discount_date) {
        if ($gamesPackages->discount_type == 0) {
            $finalPrice = $gamesPackages->price;
        } elseif ($gamesPackages->discount_type == 1) { //yüzde olarak indirim
            $finalPrice = $gamesPackages->price - ($gamesPackages->discount_amount * $gamesPackages->price / 100);
        } elseif ($gamesPackages->discount_type == 2) { // tutar olarak indirim
            $finalPrice = $gamesPackages->price - $gamesPackages->discount_amount;
        }
    } else {
        $finalPrice = $gamesPackages->price;
    }

    if (isset(Auth::user()->id)) {
        $discount = \App\Models\Campaigns::GetCampaign(Auth::user()->id, $gamesPackagesId);
        if ($discount) {
            $discountedPrice = $gamesPackages->price - ($discount * $gamesPackages->price / 100);
            $finalPrice = round($discountedPrice < $finalPrice ? $discountedPrice : $finalPrice, 2);
        }
    }

    return $finalPrice;
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
    if (\App\Models\User::find($id)->telefon_verified_at != NULL) {
        return "<i class='mdi mdi-check text-success'></i>";
    } else {
        return "<i class='mdi mdi-alert-outline text-danger'></i>";
    }
}

function getUserEmailStatus($id)
{
    if (\App\Models\User::find($id)->email_verified_at != NULL) {
        return "<i class='mdi mdi-check text-success'></i>";
    } else {
        return "<i class='mdi mdi-alert-outline text-danger'></i>";
    }
}

function getUserTcnoStatus($id)
{
    if (\App\Models\User::find($id)->tc_verified_at != NULL) {
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
    return env('CONTATC_MAIL');
}

function LogCall($yapan, $kategori, $aciklama)
{
    $log = new \App\Models\Logs();
    $log->user = $yapan;
    $log->category = $kategori;
    $log->text = request()->server('REMOTE_PORT') . ":" . Request::getClientIp() . "- " . $aciklama;
    $log->lang = getLang();
    $log->created_at = date('YmdHis');
    $log->save();
}

function loginsAttempt($user, $failed)
{
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
                '/webos/i' => 'Mobile'
            );
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

function findIl($il)
{
    return DB::table('iller')->where('id', $il)->first()->il_adi;
}

function findIlce($ilce)
{
    return DB::table('ilceler')->where('id', $ilce)->first()->ilce_adi;
}

function getAuthName()
{
    return \App\Models\Settings::first()->epinAuthName;
    //return "testapi auth";
}

function getApiName()
{
    return \App\Models\Settings::first()->epinApiName;
    //return "7ddfa30c7d994901ed41d66506acd27e";
}

function getApiKey()
{
    return \App\Models\Settings::first()->epinApiKey;
    //return "f1e20793f7697c632e5d176a5601f5ee";
}

function findPaymentChannel($channel)
{
    return DB::table('payment_channels')->where('id', $channel)->first()->name;
}

function findPaymentStatus($status)
{
    if ($status == 0) {
        return "<span class='text-info'>Ödemeniz henüz işleniyor</span>";
    } elseif ($status == 1) {
        return "<span class='text-success'>İşleminiz başarıyla gerçekleştirildi</span>";
    } elseif ($status == 2) {
        return "<span class='text-danger'>İşleminiz iptal edildi</span>";
    } else {
        return "<span class='text-warning'>Ödeme işleminiz ile ilgili bir sorun oluştu</span>";
    }
}

function findOrderStatus($status)
{
    if ($status == 0) {
        return "Siparişiniz henüz işleniyor";
    } elseif ($status == 1) {
        return "Siparişiniz başarılı";
    } elseif ($status == 2) {
        return "Siparişiniz iptal edildi";
    } else {
        return "Siparişiniz ile ilgili bir sorun oluştu";
    }
}

function FindApiGame($gameId)
{
    $ch = curl_init();
    $headers = array(
        'Authorization: ' . getAuthName(),
        'ApiName: ' . getApiName(),
        'ApiKey: ' . getApiKey(),
    );
    curl_setopt($ch, CURLOPT_URL, env('EPIN_BASE') . '/GetCategoryList');
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_POSTFIELDS, '');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    $response = curl_exec($ch);
    $result_game = json_decode($response);
    curl_close($ch);
    foreach ($result_game->GameDto->GameViewModel as $item) {
        if ($item->Id == $gameId) {
            return $item->Name;
        }
    }
    return "Bulunamadı";
}

function findApiGameProduct($game, $product)
{
    $epin = DB::table('games_titles')->where('id', $game)->first();
    $ch = curl_init();
    $headers = array(
        'Authorization: ' . getAuthName(),
        'ApiName: ' . getApiName(),
        'ApiKey: ' . getApiKey(),
        'Content-Type: application/x-www-form-urlencoded',
    );
    curl_setopt($ch, CURLOPT_URL, env('EPIN_BASE') . '/GameItemListById');
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'id=' . $epin->epin);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    $response = curl_exec($ch);
    $result_package = json_decode($response);
    curl_close($ch);
    foreach ($result_package->GameDto as $item) {
        if ($item->Id == $product) {
            return $item->Name;
        }
    }
    return "Bulunamadı";
}

function findApiGameProductPrice($game, $product)
{
    $epin = DB::table('games_titles')->where('id', $game)->first();
    $ch = curl_init();
    $headers = array(
        'Authorization: ' . getAuthName(),
        'ApiName: ' . getApiName(),
        'ApiKey: ' . getApiKey(),
        'Content-Type: application/x-www-form-urlencoded',
    );
    curl_setopt($ch, CURLOPT_URL, env('EPIN_BASE') . '/GameItemListById');
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'id=' . $epin->epin);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    $response = curl_exec($ch);
    $result_package = json_decode($response);
    curl_close($ch);
    foreach ($result_package->GameDto as $item) {
        if ($item->Id == $product) {
            return $item->Price;
        }
    }
    return "Bulunamadı";
}

function findApiGamePackage($game)
{
    $epin = DB::table('games_titles')->where('id', $game)->first();
    $ch = curl_init();
    $headers = array(
        'Authorization: ' . getAuthName(),
        'ApiName: ' . getApiName(),
        'ApiKey: ' . getApiKey(),
        'Content-Type: application/x-www-form-urlencoded',
    );
    curl_setopt($ch, CURLOPT_URL, env('EPIN_BASE') . '/GameItemListById');
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'id=' . $epin->epin);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    $response = curl_exec($ch);
    $result_package = json_decode($response);
    curl_close($ch);
    if (isset($result_package->GameDto)) {
        return $result_package->GameDto;
    } else {
        return "Bulunamadı";
    }
}

function getPackageInfo($epin, $stock, $area)
{
    $ch = curl_init();
    $headers = array(
        'Authorization: ' . getAuthName(),
        'ApiName: ' . getApiName(),
        'ApiKey: ' . getApiKey(),
        'Content-Type: application/x-www-form-urlencoded',
    );
    curl_setopt($ch, CURLOPT_URL, env('EPIN_BASE') . '/GameItemListById');
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'id=' . $epin);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    $response = curl_exec($ch);
    $result_package = json_decode($response);
    curl_close($ch);
    foreach ($result_package->GameDto as $item) {
        if ($item->StockCode == $stock) {
            if ($area == 1) {
                return $item->Price;
            } else {
                return $item->Percentage;
            }
        }
    }
    return "Bulunamadı";
}

function findGamesPackagesTradeMusteridenAlPrice($gamesPackages)
{
    $gamesPackages = \App\Models\GamesPackagesTrade::where('id', $gamesPackages)->first();
    $al_fiy= $gamesPackages->alis_fiyat;

    #-------------- üye özel indirim modül enteg.
    if(Auth::check()) { // login var mı ?
        $indirim = DB::table('camp_gg_py')->where('user', Auth::user()->id)->where('aktif', 1)->whereNull('deleted_at');
        if ($indirim->count() > 0) {    // indirim tablosunda aranan üye mevcut ise

            $al = $indirim->first();
            $j = json_decode($al->indirim, true);
            foreach ($j as $key => $value) {
                if ($value['id'] == $gamesPackages->id) { // bu üründe indirimi var mı
                    $mevcut = 'ok';
                    break;
                }
            }

            if (@$mevcut == 'ok') {
                if ($j[$key]['alis'] > 0) {
                    $al_fiy = $j[$key]['alis'];
                }
                if ($j[$key]['satis'] <= 0 && $j[$key]['aoran'] > 0) {
                    $al_fiy = $al_fiy - ($j[$key]['aoran'] * $al_fiy / 100);
                }

            }
        }
    }
    #---------------------------------------------- üye özel indirim modül enteg. sonu


    return $al_fiy;
}


function findGamesPackagesTradeMusteriyeSatPrice($gamesPackages)
{
    $gamesPackages = \App\Models\GamesPackagesTrade::where('id', $gamesPackages)->first();

//    #--------------------------------------------------------------------------------------------------------------------------
//    if (Auth::check() && Auth::user()->refId > 1 && Auth::user()->onay == 1) {  // Akınsoft yada refrans indirim tanımı var mı
//        $refid = Auth::user()->refId;
//        $al = DB::select("select gg from bayi where uid='$refid'")[0];  // epin indirim oranı alalım
//        $satis_fiyati = $gamesPackages->satis_fiyat - ($al->gg * $gamesPackages->satis_fiyat / 100); // indirimli rakam
//    } else {
//        $satis_fiyati = $gamesPackages->satis_fiyat;
//    } // refid yoxa dewam
//    #--------------------------------------------------------------------------------------------------------------------------
//    if (Auth::check() && (Auth::user()->id == 20285 || Auth::user()->id == 7217 || Auth::user()->id == 69325)) {
//        $satis_fiyati = $gamesPackages->satis_fiyat - (1 * $gamesPackages->satis_fiyat / 100);
//    }

    $satis_fiyati = $gamesPackages->satis_fiyat;

    #-------------- üye özel indirim modül enteg.
     if(Auth::check()) { // login var mı ?
         $indirim = DB::table('camp_gg_py')->where('user', Auth::user()->id)->where('aktif', 1)->whereNull('deleted_at');
         if ($indirim->count() > 0) {    // indirim tablosunda aranan üye mevcut ise

             $al = $indirim->first();
             $j = json_decode($al->indirim, true);
             foreach ($j as $key => $value) {
                 if ($value['id'] == $gamesPackages->id) { // bu üründe indirimi var mı
                     $mevcut = 'ok';
                     break;
                 }
             }
                        //      $j[$key]['id']
                        //      $j[$key]['alis']
                        //      $j[$key]['aoran']
                        //      $j[$key]['satis']
                        //      $j[$key]['soran']

             if (@$mevcut == 'ok') {
                 if ($j[$key]['satis'] > 0) {
                     $satis_fiyati = $j[$key]['satis'];
                 }
                 if ($j[$key]['satis'] <= 0 && $j[$key]['soran'] > 0) {
                     $satis_fiyati = $satis_fiyati - ($j[$key]['soran'] * $satis_fiyati / 100);
                 }

             }
         }
     }
     #---------------------------------------------- üye özel indirim modül enteg. sonu
        return $satis_fiyati;
}


function deleteItem($id)
{
    DB::table('games_titles_items_info')->where('id', $id)->update([
        'deleted_at' => date('YmdHis'),
    ]);
    DB::table('games_titles_items')->where('item', $id)->update([
        'deleted_at' => date('YmdHis'),
    ]);
    $data = DB::table('games_titles_items_photos')->where('item', $id);
    if ($data->first()->image != '') {
        $filename = env("ROOT") . env("FRONT") . env("GAMES_ITEMS") . $data->first()->image;
        if (file_exists($filename)) {
            unlink($filename);
        }
    }
    DB::table('games_titles_items_photos')->where('item', $id)->delete();
}

function findIlanStatus($status)
{
    if ($status == 0) {
        return "Onay Bekliyor";
    } elseif ($status == 1) {
        return "Yayında";
    } elseif ($status == 2) {
        return "Red Edildi";
    } elseif ($status == 3) {
        return "Pasif";
    } elseif ($status == 4) {
        return "Site İtemi Bekliyor";
    } elseif ($status == 5) {
        return "İtem Sitede";
    } elseif ($status == 6) {
        return "İtem Satışı Başarılı";
    } else {
        return "Silindi / Yayınlanmıyor";
    }
}

function findUserIlanStatus($status)
{
    if ($status == 0) {
        return "Kullanıcı Tarafından Pasif";
    } elseif ($status == 1) {
        return "Kullanıcı Tarafından Aktif";
    } else {
        return "Silindi / Yayınlanmıyor";
    }
}

function getIlanStatus()
{
    $status = array(
        '0' => 'Onay Bekliyor',
        '1' => 'Yayında',
        '2' => 'Red Edildi',
        '3' => 'Pasif',
        '4' => 'Site İtemi Bekliyor',
        '5' => 'İtem Sitede',
        '6' => 'İtem Satışı Başarılı',
    );
    return $status;
}

function findGameGoldStatus($status, $tur = "bizden-al", $teslim_nick = NULL)
{
    if ($status == 0) {
        if ($tur == "bize-sat") {
            if ($teslim_nick == NULL) {
                return "Lütfen bekleyin, teslim edeceğiniz nick belirleniyor";
            } else {
                echo "Teslim edeceğiniz nick : " . $teslim_nick;
                //$myAudioFile = "https://widget-v4.tidiochat.com//tururu.mp3";
                //echo '<audio class="ses" id="ses" autoplay="false" style="display:none;"><source src="' . $myAudioFile . '" type="audio/wav"></audio>';
            }
        } else {
            return "Site Teslimat İçin Bekleniyor";
        }
    } elseif ($status == 1) {
        return "İşlem Başarılı";
    } elseif ($status == 2) {
        if ($teslim_nick)
            return 'İptal Edildi : ' . $teslim_nick;
        else
            return 'İptal Edildi';
    }
}

function findGameGoldStatusById($id)
{
    if (!isset(Auth::user()->id)) {
        echo "=)";
        exit();
    }
    $siparis = DB::table('game_gold_satis')->where('id', $id)->first();
    if ($siparis->user != Auth::user()->id) {
        echo "==)";
        exit();
    }
    if ($siparis->status == 0) {
        if ($siparis->tur == "bize-sat") {
            if ($siparis->teslim_nick == NULL) {
                echo "Lütfen bekleyin, teslim edeceğiniz nick belirleniyor";
            } else {
                echo "Teslim edeceğiniz nick : " . $siparis->teslim_nick;
                //$myAudioFile = "https://widget-v4.tidiochat.com//tururu.mp3";
                //echo '<audio class="ses" id="ses" autoplay="false" style="display:none;"><source src="' . $myAudioFile . '" type="audio/wav"></audio>';
            }
        } else {
            echo "Site Teslimat İçin Bekleniyor";
        }
    } elseif ($siparis->status == 1) {
        echo "İşlem Başarılı";
    } elseif ($siparis->status == 2) {
        echo "Satış İşlemi İptal Edildi";
    }
}

function getTwitchAccessToken()
{
    $value = Cache::remember('twitchAccessToken', '3600', function () {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://id.twitch.tv/oauth2/token');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, "client_id=" . env("TWITCH_ID") . "&client_secret=" . env("TWITCH_SECRET") . "&grant_type=client_credentials");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $response = curl_exec($ch);
        $result = json_decode($response);
        curl_close($ch);
        return $result->access_token ?? '';
    });
    return $value;
}

function getTwitchLiveStreams($userId)
{
    $ch = curl_init();
    $headers = array(
        'Authorization:' . "Bearer " . getTwitchAccessToken(),
        'Client-ID: ' . env("TWITCH_ID"),
    );
    $link = 'https://api.twitch.tv/helix/users?' . $userId;
    curl_setopt($ch, CURLOPT_URL, $link);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    $response = curl_exec($ch);
    $result = json_decode($response);
    curl_close($ch);
    return $result->data ?? '';
}

function getTwitchUserInfo($userId)
{
    $ch = curl_init();
    $headers = array(
        'Authorization:' . "Bearer " . getTwitchAccessToken(),
        'Client-ID: ' . env("TWITCH_ID"),
    );
    curl_setopt($ch, CURLOPT_URL, 'https://api.twitch.tv/helix/users?id=' . $userId);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    $response = curl_exec($ch);
    $result = json_decode($response);
    curl_close($ch);
    return $result->data[0];
}

function getTwitchGames()
{
    $ch = curl_init();
    $headers = array(
        'Authorization:' . "Bearer " . getTwitchAccessToken(),
        'Client-ID: ' . env("TWITCH_ID"),
    );
    curl_setopt($ch, CURLOPT_URL, 'https://api.twitch.tv/helix/games/top');
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    $response = curl_exec($ch);
    $result = json_decode($response);
    curl_close($ch);
    return $result->data;
}

function userLastSeen($user)
{
    $udata = DB::table('users')->where('id', $user)->first();

    $lastSeen = new DateTime($udata->last_see_at);
    $secondsDifference = (new DateTime())->getTimestamp() - $lastSeen->getTimestamp();
    $hoursDifference = ceil($secondsDifference / 3600) + 1;
    $minutes = [5, 15, 30, 45, 1440];
    $messages = [
        "Online",
        "10 dakika önce",
        "30 dakika önce",
        "45 dakika önce",
        "[x] saat önce",
        "Offline",
    ];

    $message = end($messages);
    $lastIndex = sizeof($messages) - 1;
    foreach ($minutes as $k => $checkMinute) {
        if ($secondsDifference < $checkMinute * 60) {
            $message = $messages[$k];
            $lastIndex = $k;
            break;
        }
    }

    $message = str_replace('[x]', $hoursDifference, $message);

    $lastIndex = max($lastIndex - 1, 0);
    $multiplier = ceil(255 / 4);

    $rColor = $lastIndex * $multiplier;
    $gColor = (sizeof($messages) - 1 - $lastIndex) * $multiplier;

    echo "<span class='user-last-seen' style='color:rgb($rColor,$gColor,0);font-size:16px'>$message</span>";
}

function lastSeeAt()
{
    if (isset(Auth::user()->id)) {
        DB::table('users')->where('id', Auth::user()->id)->update(['last_see_at' => date('YmdHis')]);
    }
}

function getSorgu($min, $user)
{
    return DB::table('users')->where('id', $user)->where('last_see_at', '>=', Carbon::now()->subMinutes($min)->toDateTimeString())->count();
}

function userSeeControl($user)
{

    if (getSorgu(5, $user) > 0) {
        return '<i title="Online" class="fas fa-circle text-success"></i>';
    } elseif (getSorgu(10, $user) > 0) {
        return '<i title="10 dk önce online" class="fas fa-circle text-green-300"></i>';
    } elseif (getSorgu(15, $user) > 0) {
        return '<i title="15 dk önce online" class="fas fa-circle text-yellow-800"></i>';
    } elseif (getSorgu(20, $user) > 0) {
        return '<i title="20 dk önce online" class="fas fa-circle text-orange-900"></i>';
    } else {
        return '<i title="Offline" class="fas fa-circle text-danger"></i>';
    }
}

function findNewsTime($newsId)
{
    $time = strtotime(\App\Models\News::where('id', $newsId)->first()->created_at);
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

function getStreamAccess($code)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://streamlabs.com/api/v1.0/token');
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, "client_id=" . env("STREAM_ID") . "&client_secret=" . env("STREAM_SECRET") . "&redirect_uri=" . env("STREAM_URL") . "&grant_type=authorization_code&code=" . $code);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    $response = curl_exec($ch);
    $result = json_decode($response);
    curl_close($ch);
    return $result;
}

function getStreamUserInfo()
{
    $yayinci = DB::table('twitch_support_streamer')->where('user', Auth::user()->id);
    if ($yayinci->count() > 0 and $yayinci->first()->status == 1) {
        $ch = curl_init();
        $headers = array(
            'Accept: application/json',
        );
        curl_setopt($ch, CURLOPT_URL, 'https://streamlabs.com/api/v1.0/user?access_token=' . $yayinci->first()->access_token);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $response = curl_exec($ch);
        $result = json_decode($response);
        curl_close($ch);
        return $result;
    }
}

function getStreamUserInfoById($id, $access_token = '')
{
    $yayinci = DB::table('twitch_support_streamer')->where('id', $id)->first();
    if ($access_token == '') {
        $access_token = $yayinci->access_token;
    }
    $ch = curl_init();
    $headers = array(
        'Accept: application/json',
    );
    curl_setopt($ch, CURLOPT_URL, 'https://streamlabs.com/api/v1.0/user?access_token=' . $access_token);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    $response = curl_exec($ch);
    $result = json_decode($response);
    curl_close($ch);
    return $result;
}

function setStreamUserDonate($name, $message, $amount, $access_token)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://streamlabs.com/api/v1.0/donations');
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, "name=" . $name . "&message=" . $message . "&identifier=" . Auth::user()->id . "&amount=" . $amount . "&currency=TRY&access_token=" . $access_token);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    $response = curl_exec($ch);
    $result = json_decode($response);
    LogCall(Auth::user()->id, '1', "Twitch bagis response ->" . $response); // bagis bildirim sonrasi response loglara aliniyor..
    curl_close($ch);
    if (isset($result->donation_id)) {
        return $result->donation_id;
    } else {
        return false;
    }
}

function sayfaIzinKontrol($sayfa)
{
    $kullaniciGrubu = DB::table('user_group_users')->where('user', Auth::user()->id);
    if ($kullaniciGrubu->count() > 0) {
        $kullanicininGrubu = $kullaniciGrubu->first()->user_group;
        $grupSayfalari = DB::table('user_group_pages')->where('user_group', $kullanicininGrubu);
        if ($grupSayfalari->count() > 0) {
            if (strpos($sayfa, 'panel/oyunlar') !== false) {
                $sayfa = 'panel/oyunlar';
            }
            if (strpos($sayfa, 'panel/uye-detay') !== false) {
                $sayfa = 'panel/uye-detay';
            }
            if (strpos($sayfa, 'panel/oyun-paket-kod-goruntule') !== false) {
                $sayfa = 'panel/oyun-paket-kod-goruntule';
            }
            if (strpos($sayfa, 'panel/oyun-paket-kod-duzenle') !== false) {
                $sayfa = 'panel/oyun-paket-kod-duzenle';
            }
            if ($sayfa != 0) {
                $findPage = DB::table('pages')->where('url', $sayfa);
                if ($findPage->count() > 0) {
                    if ($grupSayfalari->where('page', $findPage->first()->id)->count() > 0) {
                        return true;
                    }
                }
            } else {
                if ($sayfa == 0) {
                    if ($grupSayfalari->where('page', '0')->count() > 0) {
                        return true;
                    }
                }
            }
        }
    }
    return false;
}

function kullaniciBildirimKategorisi($user, $category)
{
    if (DB::table('bildirim_kullanici')->where('user', $user)->where('bildirim', $category)->count() > 0) {
        return true;
    } else {
        return false;
    }
}

function setBildirim($user, $category, $title, $text, $link)
{
    if (DB::table('bildirim_kullanici')->where('user', $user)->where('bildirim', $category)->count() > 0) { //eğer kullanıcı bu bildirimleri almak istiyorsa
        DB::table('bildirim')->insert([
            'user' => $user,
            'title' => $title,
            'text' => $text,
            'category' => $category,
            'link' => $link,
            'created_at' => date('YmdHis'),
        ]);
    }
}

function findBildirimTime($bildirim)
{
    $time = strtotime(DB::table('bildirim')->where('id', $bildirim)->first()->created_at);
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

function MF($value)
{
    return number_format($value, 2, ',', '.');
}

function getGoogleAuth()
{
    $ch = curl_init();
    $headers = array(
        "Content-Type: application/x-www-form-urlencoded",
    );
    $link = 'https://authenticatorapi.com/api.asmx/Pair';
    curl_setopt($ch, CURLOPT_URL, $link);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, "appName=oyuneks&appInfo=oyuneks&secretCode=oyuneks-" . Auth::user()->id);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    $response = curl_exec($ch);
    curl_close($ch);
    $xml = simplexml_load_string($response);
    $json = json_encode($xml);
    $array = json_decode($json, TRUE);
    return $array;
}

function getGoogleAuthControl($code, $id)
{
    $ch = curl_init();
    $headers = array(
        "Content-Type: application/x-www-form-urlencoded",
    );
    $link = 'https://authenticatorapi.com/api.asmx/ValidatePin';
    curl_setopt($ch, CURLOPT_URL, $link);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, "pin=" . $code . "&secretCode=oyuneks-" . $id);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    $response = curl_exec($ch);
    curl_close($ch);
    $xml = simplexml_load_string($response);
    $json = json_encode($xml);
    $array = json_decode($json, TRUE);
    return $array;
}

function getUserVerifyStatus($version)
{
    if ($version == 1) {
        if (Auth::user()->email_verified_at != NULL) {
            return "bg-success";
        } else {
            return "bg-danger";
        }
    }
    if ($version == 2) {
        if (Auth::user()->telefon_verified_at != NULL) {
            return "bg-success";
        } else {
            return "bg-danger";
        }
    }
    if ($version == 3) {
        if (Auth::user()->tc_verified_at != NULL) {
            return "bg-success";
        }
        if (Auth::user()->tc_verified_at_first != NULL) {
            return "bg-warning";
        }
        return "bg-danger";
    }
}

function getUserVerifyText($version)
{
    if ($version == 1) {
        if (Auth::user()->email_verified_at == NULL) {
            return "E-postanı Onayla";
        } else {
            return "E-postan Onaylı!";
        }
    }
    if ($version == 2) {
        if (Auth::user()->telefon_verified_at == NULL) {
            return "Telefonunu Onayla";
        } else {
            return "Telefonun Onaylı!";
        }
    }
    if ($version == 3) {
        if (Auth::user()->tc_verified_at != NULL) {
            return "Kimliğin Onaylı";
        }
        if (Auth::user()->tc_verified_at_first != NULL) {
            return "Kimliğin 2. Onay Aşamasında";
        }
        return "Kimliğini Onayla";
    }
}

function sendSmsRequest($site_name, $send_xml, $header_type)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $site_name);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $send_xml);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header_type);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 120);
    $result = curl_exec($ch);
    return $result;
}

function karakterDuzelt($yazi)
{
    $ara = array("ç", "i", "ı", "ğ", "ö", "ş", "ü");
    $degistir = array("Ç", "İ", "I", "Ğ", "Ö", "Ş", "Ü");
    $yazi = str_replace($ara, $degistir, $yazi);
    $yazi = strtoupper($yazi);
    return $yazi;
}

function sendSms($telefon, $smsText)
{
    $smsText=str_replace(array('&','<','>', '%', '`', '*',"'",'"','/','\\'), '', $smsText);

    $user = DB::table('users')->where('telefon', $telefon)->first();
    if ($user->telefon_country == "90") { //eğer kişi türkiyede ise
        $settings = DB::table('settings')->first();
        $smsUsername = $settings->smsUsername;
        $smsUserPass = $settings->smsUserpass;
        $orgin_name = $settings->smsSendTitle;
        $date = date('d/m/Y H:i');
        $xml = <<<EOS
   		 <request>
   			 <authentication>
   				 <username>{$smsUsername}</username>
   				 <password>{$smsUserPass}</password>
   			 </authentication>

   			 <order>
   	    		 <sender>{$orgin_name}</sender>
   	    		 <sendDateTime>{$date}</sendDateTime>
   	    		 <message>
   	        		 <text>{$smsText}</text>
   	        		 <receipents>
   	            		 <number>{$telefon}</number>
   	        		 </receipents>
   	    		 </message>
   			 </order>
   		 </request>
EOS;
        $result = sendSmsRequest('http://api.iletimerkezi.com/v1/send-sms', $xml, array('Content-Type: text/xml'));
        $xml = simplexml_load_string($result);
        $json = json_encode($xml);
        $array = json_decode($json, TRUE);
        if (isset($array['status'])) {

            DB::table('sms_log')->insert(['user_id' => $user->id, 'telefon' => $telefon, 'text' => gzcompress($smsText, 9), 'created_at' => date('YmdHis'), 'sonuc' => $array['status']['code']]);

            if ($array['status']['code'] == '200') { //sms başarıyla gönderildi
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    } else {
        return true;
    }
}

function registerOrLoginUser($data, $type)
{
    if ($type == 1 or $type == 2) {
        $user = User::where('email', '=', $data->email)->first();
        if ($type == 1) { //google işlemleri
            if (DB::table('users')->where('email', $data->email)->count() > 0 and $user->googleSecret == NULL) {
                DB::table('users')->where('id', $user->id)->update([
                    'googleSecret' => $data->id,
                ]);
            }
        }
        if ($type == 2) { //twitch işlemleri
            if (DB::table('users')->where('email', $data->email)->count() > 0 and $user->twitchSecret == NULL) {
                DB::table('users')->where('id', $user->id)->update([
                    'twitchSecret' => $data->id,
                ]);
            }
        }
    } elseif ($type == 3) {
        $user = User::where('steamSecret', '=', $data->steamSecret)->first();
    }
    if (!$user) {
        $user = new User();
        $user->name = $data->name;
        if ($type == 1) {
            $user->email = $data->email;
            $user->googleSecret = $data->id;
        } elseif ($type == 2) {
            $user->email = $data->email;
            $user->twitchSecret = $data->id;
        } elseif ($type == 3) {
            $user->email = "";
            $user->steamSecret = $data->id;
        }
        $user->password = "";
        $user->slug = Str::slug($data->name);
        $user->email_verified_at = date('YmdHis');
        $user->save();
    }
    if ($user->deleted_at != NULL) {
        LogCall($user->id, '1', "Hesabı silinmiş olan kullanıcı giriş yapmaya çalıştı.");
        return back()->with('error', 'Hesabınız silindiği için giriş yapamazsınız!');
    }
    $smsDegisken = "2fa_sms";
    $emailDegisken = "2fa_email";
    $googleDegisken = "2fa_google";
    if ($user->email_verified_at == NULL) { // kullanıcı hesabını onay kontrol
        LogCall($user->id, '1', "Kullanıcı hesabını onaylamadan giriş yapmaya çalıştı.");
        return redirect()->route('giris')->with('error', __('general.hata-3'));
    }
    if ($user->$smsDegisken == 1 or $user->$emailDegisken == 1 or $user->$googleDegisken) { //sms veya email veya google ile giriş açık mı
        $login_token = md5(uniqid(time(), true));
        DB::table('users')->where('id', $user->id)->update(['login_token' => $login_token]);
        if ($user->$emailDegisken == 1) { //email açık ise
            $min_3 = now()->addMinute(3);
            $code = rand(1000, 9999);
            DB::table('users')->where('id', $user->id)->update(['2fa_code' => $code, '2fa_code_expired' => $min_3]);
            $to_name = $user->name;
            $to_email = $user->email;
            $data = array('name' => getSiteName(), "body" => __('general.mesaj-8') . " - " . getSiteName(), 'email' => $user->email, 'user' => $user->name, 'code' => $code, 'time' => '3');
            Mail::send('emails.code', $data, function ($message) use ($to_name, $to_email) {
                $message->to($to_email, $to_name)->subject(__('general.mesaj-8') . ' - ' . getSiteName());
                $message->from(getSiteSenderMail(), __('general.mesaj-8') . ' - ' . getSiteName());
            });
            LogCall($user->id, '1', "Kullanıcıya oturum açabilmesi için bir kod gönderildi.");
            return redirect()->route('fa_auth', $login_token)->with('success', 'Lütfen E-postanıza gönderdiğimiz kodu girin.');
            //return view('front.pages.login-fa')->with('email', $user->email)->with('success', 'Lütfen size gönderdiğimiz kodu girin.');
        }
        if ($user->$googleDegisken == 1) {
            LogCall($user->id, '1', "Kullanıcı google auth uygulamasındaki kodu girmek için oturum açtı.");
            return redirect()->route('fa_auth_google', $login_token)->with('email', $user->email)->with('success', 'Lütfen Google OTP üstünde yer alan kodu girin.');
            //return view('front.pages.login-google')->with('email', $user->email)->with('success', 'Lütfen uygulama üstünde yer alan kodu girin.');
        }
        if ($user->$smsDegisken == 1) { //sms açık ise
            $min_3 = now()->addMinute(3);
            $code = rand(111111, 999999);
            DB::table('users')->where('id', $user->id)->update(['2fa_code' => $code, '2fa_code_expired' => $min_3]);
            $smsText = "Sayın " . $user->name . ",  oyuneks'e giriş için kodunuz : " . $code . " . Kod geçerlilik süresi 3 dakikadır.";
            if (sendSms($user->telefon, $smsText)) { //sms başarıyla gönderildi
                LogCall($user->id, '1', "Kullanıcı oturum açabilmek için sms talep etti.");
                return redirect()->route('fa_auth_sms', $login_token)->with('email', $user->email)->with('success', 'Lütfen size gönderilen sms içerisinde yer alan kodu girin.');
                //return view('front.pages.login-sms')->with('email', $user->email)->with('success', 'Lütfen size gönderilen sms içerisinde yer alan kodu girin.');
            } else {
                LogCall($user->id, '1', "Kullanıcı oturum açabilmek için sms talep etti fakat gönderilemedi.");
                return redirect()->route('giris')->with("error", "Telefonunuza sms gönderimi sırasında bir hata meydana geldi.");
                //return back()->with("error", "Telefonunuza sms gönderimi sırasında bir hata meydana geldi.");
            }
        }
    } else {
        Auth::loginUsingId($user->id, false);
        LogCall($user->id, '1', "Kullanıcı sosyal medya hesaplarını kullanarak giriş yaptı.");
        if ($user && $user->role == 0) { // kullanici yeni kayil olmamis ise ve kullanıcı yönetici ise direkt panele atalım. 01/08/2022
            return redirect()->route('hesabim');
        } else {
            return redirect()->route('hesabim');
        }
    }
}

function getUserVerifiyStep($user = 0)
{
    if (isset(Auth::user()->id)) {
        if ($user == 0) {
            $user = Auth::user()->id;
        }
        $userInfo = DB::table('users')->where('id', $user)->first();
        if ($userInfo->tc_verified_at != NULL and $userInfo->telefon_verified_at != NULL and $userInfo->email_verified_at != NULL) {
            return 3;
        }
        if ($userInfo->telefon_verified_at != NULL and $userInfo->email_verified_at != NULL) {
            return 2;
        }
        if ($userInfo->email_verified_at != NULL) {
            return 1;
        }
    } else {
    }

    return 0;
}

function deleteImageManually($file)
{
    if (file_exists($file)) {
        unlink($file);
        return true;
    } else {
        return false;
    }
}

function getGameGold()
{
    foreach (DB::table('game_gold_satis')->whereNull('deleted_at')->get() as $u) {
        if ($u->tur == 'bizden-al') {
            $tur = "Müşteriye Satış";
        } else {
            $tur = "Müşteriden Alış";
        }
        if ($u->status == 0) {
            $status = " Müşteri teslimat bekliyor";
            if ($u->teslim_nick == NULL and $u->tur == "bize-sat") {
                $status .= ' <span class="text-primary">Nick Verilmemiş</span>';
            } elseif ($u->teslim_nick != NULL and $u->tur == "bize-sat") {
                $status .= ' <span class="text-danger" >' . $u->teslim_nick . '</span >';
            }
        } elseif ($u->status == 1) {
            $status = "Satış Tamamlandı";
        } elseif ($u->status == 2) {
            $status = "Satış İptal Edildi";
        } else {
            $status = "Bir Sorun Oluştu";
        }
        $tekTirnak = "'";
        $buttons = '';
        if ($u->status != 1) {
            if ($u->tur == "bize-sat") {
                $buttons .= '<button data-toggle="modal" data-target="#nickGir' . $u->id . '"
                                type="button"
                                class="btn btn-lg btn-success waves-effect waves-light">
                            <i class="fas fa-pen"></i>
                        </button>
                        <div class="modal fade" id="nickGir' . $u->id . '" tabindex="-1" aria-labelledby="exampleModalLabel"
                             aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Nick Girin</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                    </div>
                                    <form method="post"
                                          action="' . route('game_gold_yonetim_onayla_post', [0, $u->id]) . '">
                                        <input type="hidden" name="_token" value="' . csrf_token() . '" />
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label class="col-form-label">Teslim Edecek Nick</label><br>
                                                <input placeholder="Teslim edecek nick" type="text" name="teslim_nick"
                                                       class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                                            <button type="submit" class="btn btn-primary">Onayla</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        ';
                if ($u->teslim_nick != NULL) {
                    $buttons .= '<button onclick="location.href= ' . $tekTirnak . route('game_gold_yonetim_onayla', [1, $u->id]) . $tekTirnak . ' "
                                    type="button"
                                    class="btn btn-lg btn-success waves-effect waves-light">
                                <i class="fas fa-check"></i>
                            </button>';
                }
            } else {
                $buttons .= '<button onclick="location.href=' . $tekTirnak . route('game_gold_yonetim_onayla', [1, $u->id]) . $tekTirnak . '"
                                type="button"
                                class="btn btn-lg btn-success waves-effect waves-light">
                            <i class="fas fa-check"></i>
                        </button>';
            }
        }
        if ($u->status != 2) {
            $buttons .= '<button onclick = "location.href=' . $tekTirnak . route('game_gold_yonetim_onayla', [2, $u->id]) . $tekTirnak . '" type = "button"
                            class="btn btn-lg btn-warning waves-effect waves-light" >
                        <i class="fas fa-times" ></i >
                    </button >';
        }
        $buttons .= ' <button onclick = "deleteContent(' . $tekTirnak . "game_gold_satis" . $tekTirnak . ', ' . $u->id . ')" type = "button"
                        class="btn btn-lg btn-danger waves-effect waves-light" >
                    <i class="far fa-trash-alt" ></i >
                </button >';
        $data['data'][] = [
            $u->id,
            DB::table('users')->where('id', $u->user)->first()->name,
            DB::table('games_packages_trade')->where('id', $u->paket)->first()->title,
            $u->note,
            $u->adet,
            $tur,
            $u->price,
            $status,
            $u->created_at,
            $buttons,
        ];
    }
    $data = json_encode($data);
    return $data;
}

function setPushNotify($user, $title, $body, $route)
{
    $url = 'https://fcm.googleapis.com/fcm/send';
    $FcmToken = User::whereNotNull('device_key')->where('id', $user)->pluck('device_key')->all();

    $serverKey = 'AAAAegbgdGw:APA91bFsxPFExsAfk3G0OfrHs1s6gTtIkYWlrhg2eQBEBejiHKN9GLQFd5Zh47KozfnthvOH1I8lb1-pFIBcet2XW7gJ5STikqtrpx4_4OVzW78p1h4zeSIGjD_-Arbeq22tTn0FjmIG';
    $data = [
        "registration_ids" => $FcmToken,
        "notification" => [
            "title" => $title,
            "body" => $body,
            "click_action" => route($route),
            "icon" => "https://oyuneks.com/brand/brandicon.png",
        ]
    ];
    $encodedData = json_encode($data);
    $headers = [
        'Authorization:key=' . $serverKey,
        'Content-Type: application/json',
    ];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
    $result = curl_exec($ch);
    if ($result === FALSE) {
        die('Curl failed: ' . curl_error($ch));
    }
    curl_close($ch);
    return true;
}


function getGameGoldCont()
{
    echo 0;
    exit();
    $onSaniyeOnce = Carbon::now()->subSeconds(10);
    $sayim = DB::table('game_gold_satis')->whereNull('deleted_at')->where('created_at', '>=', $onSaniyeOnce)->count();
    if ($sayim > 0) {
        setPushNotify(Auth::user()->id, "Yeni Oyun Parası Siparişi", $sayim . " adet yeni oyun parası siparişi bulunuyor", "game_gold_yonetim");
        echo 1;
    } else {
        echo 0;
    }
}

function getIlanCont()
{
    echo 0;
    exit();
    $onSaniyeOnce = Carbon::now()->subSeconds(10);
    $sayim = DB::table('pazar_yeri_ilanlar')->whereNull('deleted_at')->where('created_at', '>=', $onSaniyeOnce)->count();
    if ($sayim > 0) {
        setPushNotify(Auth::user()->id, "Yeni ilan siparişi", $sayim . " adet yeni ilan siparişi bulunuyor", "ilanlar_yonetim");
        echo 1;
    } else {
        echo 0;
    }
}

function hashHesapla($price, $id)
{
    $merchant_ok_url = "https://oyuneks.com/siparis/basarili";
    $merchant_fail_url = "https://oyuneks.com/siparis/basarisiz";
    $soapUrl = "https://test-dmz.param.com.tr:4443/turkpos.ws/service_turkpos_test.asmx?wsdl";
    $taksit = 1;
    $PARAM_CLIENT_CODE = (int)env('PARAM_CLIENT_CODE');
    $Islem_Guvenlik_Str = $PARAM_CLIENT_CODE . env('PARAM_GUID') . $taksit . $price . $price . $id . $merchant_fail_url . $merchant_ok_url;
    $xml_data = '<?xml version="1.0" encoding="utf-8"?> <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/"> <soap:Body>
                    <SHA2B64 xmlns="https://turkpos.com.tr/">
                    <Data>' . $Islem_Guvenlik_Str . '</Data>
                    </SHA2B64>
                    </soap:Body>
                </soap:Envelope> ';


    $ch = curl_init($soapUrl);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml_data");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);
    $clean_xml = str_ireplace(['SOAP-ENV:', 'SOAP:'], '', $output);
    $xml = simplexml_load_string($clean_xml);
    return $xml->Body->SHA2B64Response->SHA2B64Result;
}

function hashReturnHesapla($dekont, $tutar, $sipId, $servisId)
{
    $soapUrl = "https://test-dmz.param.com.tr:4443/turkpos.ws/service_turkpos_test.asmx?wsdl";
    $PARAM_CLIENT_CODE = (int)env('PARAM_CLIENT_CODE');
    $Islem_Guvenlik_Str = $PARAM_CLIENT_CODE . env('PARAM_GUID') . $dekont . $tutar . $sipId . $servisId;
    $xml_data = '<?xml version="1.0" encoding="utf-8"?> <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/"> <soap:Body>
                    <SHA2B64 xmlns="https://turkpos.com.tr/">
                    <Data>' . $Islem_Guvenlik_Str . '</Data>
                    </SHA2B64>
                    </soap:Body>
                </soap:Envelope> ';


    $ch = curl_init($soapUrl);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml_data");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);
    $clean_xml = str_ireplace(['SOAP-ENV:', 'SOAP:'], '', $output);
    $xml = simplexml_load_string($clean_xml);
    return $xml->Body->SHA2B64Response->SHA2B64Result;
}


function getNewBildirim($seconds = 5)
{
    $xSaniyeOnce = Carbon::now()->subSeconds($seconds);
    if (isset(Auth::user()->id)) {
        $bildirim = DB::table('bildirim')->where('user', Auth::user()->id)->where('created_at', '>=', $xSaniyeOnce)->whereNull('deleted_at')->orderBy('created_at', 'desc');
        if ($bildirim->count() > 0) {
            $bildirim = $bildirim->get();
            $bildirims = array();
            foreach ($bildirim as $item) {
                $bildirims[] = array('id' => $item->id, 'title' => $item->title, 'text' => $item->text, 'time' => findBildirimTime($item->id), 'link' => $item->link);
            }
            return json_encode($bildirims);
        } else {
            return json_encode('false');
        }
    } else {
        return json_encode('false');
    }
}

function findGamesPackagesBonus($gamesPackages)
{
    $gamesPackages = \App\Models\GamesPackages::where('id', $gamesPackages)->first();
    if (date('Y-m-d H:i:s') < $gamesPackages->bonus_date) {
        if ($gamesPackages->bonus_type == 0) {
            return 0;
        } elseif ($gamesPackages->bonus_type == 1) { //yüzde olarak indirim
            return $gamesPackages->bonus_amount * $gamesPackages->price / 100;
        } elseif ($gamesPackages->bonus_type == 2) { // tutar olarak indirim
            return $gamesPackages->bonus_amount;
        }
    } else {
        return 0;
    }
}

function findGamesPackagesTradeBonus($gamesPackages)
{
    $gamesPackages = \App\Models\GamesPackagesTrade::where('id', $gamesPackages)->first();
    if (date('Y-m-d H:i:s') < $gamesPackages->bonus_date) {
        if ($gamesPackages->bonus_type == 0) {
            return 0;
        } elseif ($gamesPackages->bonus_type == 1) { //yüzde olarak indirim
            return $gamesPackages->bonus_amount * $gamesPackages->price / 100;
        } elseif ($gamesPackages->bonus_type == 2) { // tutar olarak indirim
            return $gamesPackages->bonus_amount;
        }
    } else {
        return 0;
    }
}

function deviceTokenRegister($token)
{
    auth()->user()->update(['device_key' => $token]);
    return response()->json(['Bildirim izni başarıylaa oluşturuldu.']);
}

function getPaytrMerchantId($foreign = false)
{
    return $foreign ? '304883' : \App\Models\Settings::first()->paytrMerchantId;
}

function getPaytrMerchantKey($foreign = false)
{
    return $foreign ? 'rzK5LsKLGWB5sxuT' : \App\Models\Settings::first()->paytrMerchantKey;
}

function getPaytrMerchantSalt($foreign = false)
{
    return $foreign ? 'eJ4Q3b1xtutTouzP' : \App\Models\Settings::first()->paytrMerchantSalt;
}

function getPaparaKey()
{
    return \App\Models\Settings::first()->paparaKey;
}

function getPaparaSecretKey()
{
    return \App\Models\Settings::first()->paparaSecretKey;
}

function getPaparaNotifyUrl()
{
    return env('PAPARA_NOTIFY_URL');
}

function getPaparaRedirectUrl()
{
    return 'https://oyuneks.com/siparis/basarili';
}

function getGpayUsername()
{
    return \App\Models\Settings::first()->gpayUsername;
}

function getGpayKey()
{
    return \App\Models\Settings::first()->gpayKey;
}

function findSuccessSellItem($date, $id)
{
    $findIlanlar = DB::table('pazar_yeri_ilan_icerik')->where('item', $id)->get();
    $fiyat = 0;
    foreach ($findIlanlar as $item) {
        $ilan = DB::table('pazar_yeri_ilanlar')->where('id', $item->ilan)->where('status', '6')->whereDate('updated_at', $date);
        if ($ilan->count() > 0) {
            $fiyat += $ilan->first()->price;
        }
    }
    return $fiyat;
}

function getSaticiPuani($satici)
{
    if (DB::table('satici_yorumlar')->where('satici', $satici)->whereNull('deleted_at')->where('status', '1')->count() > 0) {
        $yorumlar = DB::table('satici_yorumlar')->where('satici', $satici)->whereNull('deleted_at')->where('status', '1')->get();
        $toplamPuan = 0;
        $toplamYorum = 0;
        foreach ($yorumlar as $item) {
            $toplamPuan += $item->puan;
            $toplamYorum += 1;
        }
        $oran = $toplamPuan / $toplamYorum;
        return $oran;
    } else {
        return "-";
    }
}

function getDataItem($table, $id)
{
    $veri = DB::table($table)->where('id', $id)->first();
    $indis = 0;
    $indis2 = 0;
    $bilgiler[$indis] = array("title" => $veri->title, "description" => $veri->description, "id" => $id);
    foreach (DB::table('games_titles_features')->whereNull('deleted_at')->where('game_title', $veri->game_title)->get() as $f) {
        $bilgiler[$indis]['ozellikler'][$indis2] = array("id" => $f->id, "title" => $f->title, "type" => $f->type);
        if ($f->type == 1) {
            foreach (json_decode($f->value) as $deger) {
                $kontrol = DB::table('games_titles_items')->where('item', $id)->where('feature', $f->id)->whereNull('deleted_at');
                if ($kontrol->count() > 0 and $kontrol->first()->value == Str::slug($deger)) {
                    $bilgiler[$indis]['ozellikler'][$indis2]['degerler'][] = array("value" => Str::slug($deger), "selected" => "1", "deger" => $deger);
                } else {
                    $bilgiler[$indis]['ozellikler'][$indis2]['degerler'][] = array("value" => Str::slug($deger), "selected" => "0", "deger" => $deger);
                }
            }
        } elseif ($f->type == 2) {
            foreach (json_decode($f->value) as $deger) {
                $kontrol = DB::table('games_titles_items')->where('item', $id)->where('feature', $f->id)->whereNull('deleted_at');
                if (DB::table('games_titles_items')->where('item', $id)->where('feature', $f->id)->whereNull('deleted_at')->first()->value == Str::slug($deger)) {
                    $bilgiler[$indis]['ozellikler'][$indis2]['degerler'][] = array("value" => Str::slug($deger), "selected" => "1", "deger" => $deger);
                } else {
                    $bilgiler[$indis]['ozellikler'][$indis2]['degerler'][] = array("value" => Str::slug($deger), "selected" => "0", "deger" => $deger);
                }
            }
        }
        $indis2 += 1;
    }
    if (DB::table('games_titles_items_photos')->where('item', $id)->count() > 0) {
        $bilgiler[$indis]["media"] = array("image" => asset('front/games_items/' . DB::table('games_titles_items_photos')->where('item', $id)->first()->image));
    } else {
        $bilgiler[$indis]["media"] = array("image" => null);
    }

    echo json_encode($bilgiler);
}

function setEnv($name, $value)
{
    $path = base_path('.env');
    if (file_exists($path)) {
        file_put_contents($path, str_replace(
            $name . '=' . env($name),
            $name . '=' . $value,
            file_get_contents($path)
        ));
    }
}

function getWaitIlanSiparisler()
{
    return DB::table('pazar_yeri_ilanlar')->where('status', '!=', '1')->where('status', '!=', '6')->where('status', '!=', '2')->where('status', '!=', '3')->whereNull('deleted_at')->count();
}

function getWaitAlisIlanlari()
{
    return DB::table('pazar_yeri_ilanlar_buy')->where('status', '!=', '1')->where('status', '!=', '6')->where('status', '!=', '2')->where('status', '!=', '3')->where('status', '!=', '99')->whereNull('deleted_at')->count();
}

function getWaitOyunParasiSiparisleri()
{
    return DB::table('game_gold_satis')->whereNull('deleted_at')->where('status', '0')->count();
}

function getWaitParaCekimTalepleri()
{
    return DB::table('para_cek')->whereNull('deleted_at')->where('status', '0')->count();
}

function getWaitTwitchParaCekim()
{
    return DB::table('twitch_support_cevirmeler')->where('tur', '2')->whereNull('deleted_at')->where('status', '0')->count();
}

function getWaitTwitchKesintisiz()
{
    return DB::table('twitch_kesintisiz_yayinci')->whereNull('deleted_at')->where('status', '0')->count();
}

function getWaitKimlikOnaylari()
{
    return DB::table('users')->where('tc_verified_at_first', '!=', NULL)->whereNull('tc_verified_at')->whereNull('deleted_at')->count();
}

function getWaitIlanYorumlari()
{
    return DB::table('ilan_yorumlar')->where('status', '0')->whereNull('deleted_at')->count();
}

function getWaitYorumlar()
{
    return DB::table('comments')->where('status', '0')->whereNull('deleted_at')->count();
}

function getWaitSiparisler()
{
    $say=DB::select("
        select count(es.id) say
        from epin_siparisler es
        left JOIN games_packages gp on es.oyun=gp.id
        where es.durum='Onay Bekliyor' and
        es.deleted_at is null and
        gp.api_info is null");

    return $say[0]->say;
}

function getWaitRazer(){  ///  Esk'den razer idi steam icin kullaniyorum
    $say=DB::select("
        select count(es.id) say
        from epin_siparisler es
        left JOIN games_packages gp on es.oyun=gp.id
        where es.durum='Onay Bekliyor' and
        es.deleted_at is null and
        gp.api_info is not null");
    return $say[0]->say;
}


function getWaitodeme_onay()
{
    return DB::table('odeme_limit')->where('durum', '1')->count();
}


function findUserKomisyon($user)
{

    if (DB::table('users')->where('id', $user)->first()->pazar_komisyon === NULL) {
        return DB::table('settings')->first()->pazar_komisyon;
    } else {
        return DB::table('users')->where('id', $user)->first()->pazar_komisyon;
    }
}

function findUserKomisyonAlis($user)
{

    if (DB::table('users')->where('id', $user)->first()->pazar_komisyon === NULL) {
        return DB::table('settings')->first()->pazar_komisyon_alis;
    } else {
        return DB::table('users')->where('id', $user)->first()->pazar_komisyon;
    }
}

function findRemainingStock($package)
{
    $kontrol = DB::table('games_packages_codes')->where('package_id', $package);
    if ($kontrol->count() > 0) { // oyun kodu tabloda bulunuyor
        return $kontrol->where('is_used', '0')->count();
    } else {
        return 0;
    }
}

function findValueOfBuyingStock($package)
{
    $kontrol = DB::table('games_packages_codes')->where('package_id', $package);
    if ($kontrol->count() > 0) { // oyun kodu tabloda bulunuyor
        return $kontrol->where('is_used', '0')->sum('alis_fiyati');
    } else {
        return 0;
    }
}

function findCodeSupplier($code)
{
    $code = DB::table('games_packages_codes')->where('id', $code);
    if ($code->count() > 0) { // oyun kodu tabloda bulunuyor
        $tedarikci = DB::table('games_packages_codes_suppliers')->where('id', $code->first()->tedarikci);
        if ($tedarikci->count() > 0) {
            return $tedarikci->first()->title;
        } else {
            return "0";
        }
    } else {
        return "0";
    }
}

function mb_ucfirst($str)
{
    $tmp = preg_split("//u", $str, 2, PREG_SPLIT_NO_EMPTY);
    return mb_convert_case(
        str_replace("i", "İ", $tmp[0]),
        MB_CASE_TITLE,
        "UTF-8"
    ) .
        $tmp[1];
}

function tutarOkuyucu($sayi)
{
    $o = array(
        'birlik' => array('Bir', 'İki', 'Üç', 'Dört', 'Beş', 'Altı', 'Yedi', 'Sekiz', 'Dokuz'),
        'onluk' => array('On', 'Yirmi', 'Otuz', 'Kırk', 'Elli', 'Altmış', 'Yetmiş', 'Seksen', 'Doksan'),
        'basamak' => array('Yüz', 'Bin', 'Milyon', 'Milyar', 'Trilyon', 'Katrilyon')
    );
    $basamak = array_reverse(str_split(implode('', array_reverse(str_split($sayi))), 3));
    $basamak_sayisi = count($basamak);
    for ($i = 0; $i < $basamak_sayisi; ++$i) {
        $basamak[$i] = implode(array_reverse(str_split($basamak[$i])));
        if (strlen($basamak[$i]) == 1)
            $basamak[$i] = '00' . $basamak[$i];
        elseif (strlen($basamak[$i]) == 2)
            $basamak[$i] = '0' . $basamak[$i];
    }
    $yenisayi = array();
    foreach ($basamak as $k => $b) {
        if ($b[0] > 0) {
            $yenisayi[] = ($b[0] > 1 ? $o['birlik'][$b[0] - 1] . '' : '') . $o['basamak'][0];
        }
        if ($b[1] > 0) {
            $yenisayi[] = $o['onluk'][$b[1] - 1];
        }
        if ($b[2] > 0) {
            if ($b[0] == 0 && $b[1] == 0) {
                if ($basamak_sayisi >= 3) {
                    $yenisayi[] = $o['birlik'][$b[2] - 1];
                } else if ($basamak_sayisi == 2) {
                    $yenisayi[] = ($b[2] > 1 ? $o['birlik'][$b[2] - 1] . '' : '');
                } else {
                    $yenisayi[] = ($b[2] >= 1 ? $o['birlik'][$b[2] - 1] . '' : '');
                }
            } else {
                $yenisayi[] = $o['birlik'][$b[2] - 1];
            }
        }
        if ($basamak_sayisi > 1) {
            $yenisayi[] = $o['basamak'][$basamak_sayisi - 1];
        }
        --$basamak_sayisi;
    }
    return implode('', $yenisayi);
}

function getGUID()
{
    if (function_exists('com_create_guid')) {
        return com_create_guid();
    } else {
        mt_srand((float)microtime() * 10000); //optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45); // "-"
        $uuid = substr($charid, 0, 8) . $hyphen
            . substr($charid, 8, 4) . $hyphen
            . substr($charid, 12, 4) . $hyphen
            . substr($charid, 16, 4) . $hyphen
            . substr($charid, 20, 12); // "}"
        return $uuid;
    }
}

function findPackageKdvEpin($baslik)
{
    $baslik = DB::table('games_titles')->where('id', $baslik)->first();
    if ($baslik) {
        return $baslik->kdv;
    }
    return 18;
}


function faturaKes($user, $tutar, $sipId, $kdv = 18, $kdvDahil = 1, $adet = 1, $urun = 0, $type = 1)
{
    /*
     * Eşsiz Id Oluşturma
     */
    $idFatura = "ABC2009123456789";
    $uuid = getGUID();

    if ($type == 1) { //epin siparişi ise
        $urun = DB::table('games_packages')->where('id', $urun)->first();
        $siparis = DB::table('epin_satis')->where('id', $sipId)->first(); //siparişin ana bilgisi
        $kodlar = DB::table('epin_satis_kodlar')->where('epin_satis', $sipId); //siparişin içindeki kodlar
        $kodSayisi = $kodlar->count();
        $kdvliSayisi = 0;
        $alisFiyatiTotal = 0;
        foreach ($kodlar->get() as $kod) {
            $kodOrigin = DB::table('games_packages_codes')->where('code', $kod->code);
            if ($kodOrigin->first()->kdv > 0) {
                $kdvliSayisi += 1;
            } else {
                $alisFiyatiTotal += $kodOrigin->first()->alis_fiyati;
            }
        }

        if ($kodSayisi == $kdvliSayisi) { //hepsi kdvli
            $lineCount = 1;

            /*
             * Kdv hesaplamaları
             */
            $kdvsizTutar = number_format((float)$tutar / ($kdv / 100 + 1), 2, '.', ''); //ürünün kdv'siz fiyat değerinin bulunabilmesi için aradaki kdv'yi hesapladık
            $kdvTutari = $tutar - $kdvsizTutar; //kdv miktarı
            $kdvTutari = number_format((float)$kdvTutari, 2, '.', ''); //kdv miktarı satı formatına uygun hale getirdik
            /*
             * Tek satır için bilgiler
             */
            $line1Name = $urun->title;
            $line1Id = $urun->id;
            $line1Adet = $adet;
            $line1KdvOrani = $kdv;
            $line1KdvTutari = $kdvTutari;
            $line1KdvsizTutar = $kdvsizTutar;

            /*
             * Total fatura sonu bilgileri
             */
            $totalKdvTutari = $kdvTutari;
            $totalKdvsizTutar = $kdvsizTutar;
            $totalKdvOrani = $kdv;
            $totalHerseyDahilTutar = $tutar;
            $totalTekilFiyat = number_format((float)$totalKdvsizTutar / $line1Adet, 2, '.', '');


            $num = explode('.', number_format($tutar, 2, '.', ''));
            $num[0] > 0 ? $first = tutarOkuyucu($num[0]) . 'Lira' : $first = '';
            $num[1] > 0 ? $second = tutarOkuyucu($num[1]) . 'Kuruş' : $second = '';
        } elseif ($kdvliSayisi == 0) { //hiç birinde  kdv yok

            $lineCount = 2;

            /*
             * ikinci satır için bilgiler
             */
            $line2Name = "Oyuneks komisyon hizmet bedeli";
            $line2Id = "1";
            $line2Adet = "1";
            $line2KdvOrani = 18;
            $line2ToplamTutar = $tutar - $alisFiyatiTotal;
            if ($alisFiyatiTotal >= $tutar) {
                $faturaKesme = 1;
            }
            /*
             * Line2 için kdv tutarı hesaplamaları
             */
            $kdvsizTutar2 = number_format((float)$line2ToplamTutar / (18 / 100 + 1), 2, '.', ''); //komisyonun kdv'siz fiyat değerinin bulunabilmesi için aradaki kdv'yi hesapladık
            $kdvTutari2 = $line2ToplamTutar - $kdvsizTutar2; //kdv miktarı
            $kdvTutari2 = number_format((float)$kdvTutari2, 2, '.', ''); //kdv miktarı satı formatına uygun hale getirdik
            $line2KdvTutari = $kdvTutari2;
            $line2KdvsizTutar = $kdvsizTutar2;

            /*
             * Kdv hesaplamaları
             */
            $kdvsizTutar = $tutar - $line2ToplamTutar; //ürünün kdv'siz fiyat değerinin bulunabilmesi için aradaki kdv'yi hesapladık
            $kdvTutari = ($tutar - $line2ToplamTutar) - $kdvsizTutar; //kdv miktarı
            $kdvTutari = number_format((float)$kdvTutari, 2, '.', ''); //kdv miktarı satı formatına uygun hale getirdik
            /*
             * ilk satır için bilgiler
             */
            $line1Name = $urun->title;
            $line1Id = $urun->id;
            $line1Adet = $adet;
            $line1KdvOrani = 0;
            $line1KdvTutari = $kdvTutari;
            $line1KdvsizTutar = $kdvsizTutar;

            /*
             * Total fatura sonu bilgileri
             */
            $totalKdvTutari = $kdvTutari2;
            $totalKdvsizTutar = $kdvsizTutar + $kdvsizTutar2;
            $totalKdvOrani = 18;
            $totalHerseyDahilTutar = $tutar;
            $totalTekilFiyat = number_format((float)$line1KdvsizTutar / $line1Adet, 2, '.', '');
            $totalTekilFiyat2 = number_format((float)$line2KdvsizTutar / $line2Adet, 2, '.', '');


            $num = explode('.', number_format($tutar, 2, '.', ''));
            $num[0] > 0 ? $first = tutarOkuyucu($num[0]) . 'Lira' : $first = '';
            $num[1] > 0 ? $second = tutarOkuyucu($num[1]) . 'Kuruş' : $second = '';
        } else { //bir kısmı kdv'li bir kısmı kdv'siz

            $lineCount = 3;
            $kdvsizSayisi = $adet - $kdvliSayisi;

            /*
             * Kdv hesaplamaları
             */
            $kdvsizTutar = number_format((float)(($tutar / $adet) * $kdvliSayisi) / (18 / 100 + 1), 2, '.', ''); //ürünün kdv'siz fiyat değerinin bulunabilmesi için aradaki kdv'yi hesapladık
            $kdvTutari = (($tutar / $adet) * $kdvliSayisi) - $kdvsizTutar; //kdv miktarı
            $kdvTutari = number_format((float)$kdvTutari, 2, '.', ''); //kdv miktarı satı formatına uygun hale getirdik

            /*
             * ilk satır için bilgiler
             */
            $line1Name = $urun->title;
            $line1Id = $urun->id;
            $line1Adet = $kdvliSayisi;
            $line1KdvOrani = $kdv;
            $line1KdvTutari = $kdvTutari;
            $line1KdvsizTutar = $kdvsizTutar;


            /*
             * Kdv hesaplamaları 2
             */
            if ($alisFiyatiTotal >= $tutar) {
                $faturaKesme = 1;
            }
            $line3ToplamTutar = (($tutar / $adet) * $kdvsizSayisi) - $alisFiyatiTotal; //satır 3'ün hesaplanacağı rakam
            $kdvsizTutar2 = number_format((float)(($tutar / $adet) * $kdvsizSayisi) - $line3ToplamTutar, 2, '.', ''); //ürünün kdv'siz fiyat değerinin bulunabilmesi için aradaki kdv'yi hesapladık
            $kdvTutari2 = ((($tutar / $adet) * $kdvsizSayisi) - $line3ToplamTutar) - $kdvsizTutar2; //kdv miktarı
            $kdvTutari2 = number_format((float)$kdvTutari2, 2, '.', ''); //kdv miktarı satı formatına uygun hale getirdik

            /*
             * ikinci satır için bilgiler
             */
            $line2Name = $urun->title;
            $line2Id = $urun->id;
            $line2Adet = $kdvsizSayisi;
            $line2KdvOrani = 0;
            $line2KdvTutari = $kdvTutari2;
            $line2KdvsizTutar = (($tutar / $adet) * $kdvsizSayisi) - $line3ToplamTutar;

            /*
             * Kdv hesaplamaları 3
             */
            $kdvsizTutar3 = number_format((float)$line3ToplamTutar / (18 / 100 + 1), 2, '.', ''); //komisyonun kdv'siz fiyat değerinin bulunabilmesi için aradaki kdv'yi hesapladık
            $kdvTutari3 = $line3ToplamTutar - $kdvsizTutar3; //kdv miktarı
            $kdvTutari3 = number_format((float)$kdvTutari3, 2, '.', ''); //kdv miktarı satı formatına uygun hale getirdik


            /*
             * üçüncü satır için bilgiler
             */
            $line3Name = "Oyuneks komisyon hizmet bedeli";
            $line3Id = "1";
            $line3Adet = "1";
            $line3KdvOrani = 18;
            $line3KdvTutari = $kdvTutari3;
            $line3KdvsizTutar = $kdvsizTutar3;

            /*
             * Total fatura sonu bilgileri
             */
            $totalKdvTutari = $kdvTutari + $kdvTutari2 + $kdvTutari3;
            $totalKdvsizTutar = $kdvsizTutar + $kdvsizTutar2 + $kdvsizTutar3;
            $totalKdvOrani = 18;
            $totalHerseyDahilTutar = $tutar;
            $totalTekilFiyat = number_format((float)$line1KdvsizTutar / $line1Adet, 2, '.', '');
            $totalTekilFiyat2 = number_format((float)$line2KdvsizTutar / $line2Adet, 2, '.', '');
            $totalTekilFiyat3 = number_format((float)$line3KdvsizTutar / $line3Adet, 2, '.', '');


            $num = explode('.', number_format($tutar, 2, '.', ''));
            $num[0] > 0 ? $first = tutarOkuyucu($num[0]) . 'Lira' : $first = '';
            $num[1] > 0 ? $second = tutarOkuyucu($num[1]) . 'Kuruş' : $second = '';
        }
    }

    /*
     * Ürün Bilgileri
     */
    /*
    if ($type == 1) { //epin faturası
        $urun = DB::table('games_packages')->where('id', $urun)->first();
        $title = $urun->title;
        $urunId = $urun->id;
    } elseif ($type == 2) { //game gold faturası
        $urun = DB::table('games_packages_trade')->where('id', $urun)->first();
        $title = $urun->title . " Komisyon";
        $urunId = $urun->id;
    } elseif ($type == 3) { //item faturası
        $title = "Oyuneks Item Satış Komisyonu";
        $urunId = "1";
    } */

    if ($type == 2) {
        $urun = DB::table('games_packages_trade')->where('id', $urun)->first();
        $lineCount = 1;
        /*
         * Kdv hesaplamaları
         */
        $kdvsizTutar = number_format((float)$tutar / ($kdv / 100 + 1), 2, '.', ''); //ürünün kdv'siz fiyat değerinin bulunabilmesi için aradaki kdv'yi hesapladık
        $kdvTutari = $tutar - $kdvsizTutar; //kdv miktarı
        $kdvTutari = number_format((float)$kdvTutari, 2, '.', ''); //kdv miktarı satı formatına uygun hale getirdik
        /*
         * Tek satır için bilgiler
         */
        $line1Name = $urun->title . " komisyon";
        $line1Id = $urun->id;
        $line1Adet = $adet;
        $line1KdvOrani = $kdv;
        $line1KdvTutari = $kdvTutari;
        $line1KdvsizTutar = $kdvsizTutar;

        /*
         * Total fatura sonu bilgileri
         */
        $totalKdvTutari = $kdvTutari;
        $totalKdvsizTutar = $kdvsizTutar;
        $totalKdvOrani = $kdv;
        $totalHerseyDahilTutar = $tutar;
        $totalTekilFiyat = number_format((float)$totalKdvsizTutar / $line1Adet, 2, '.', '');


        $num = explode('.', number_format($tutar, 2, '.', ''));
        $num[0] > 0 ? $first = tutarOkuyucu($num[0]) . 'Lira' : $first = '';
        $num[1] > 0 ? $second = tutarOkuyucu($num[1]) . 'Kuruş' : $second = '';
    }


    /*
     * Kullanıcı Bilgileri
     */
    $user = DB::table('users')->where('id', $user)->first();
    $name = $user->name;
    $parts = explode(" ", $name);
    if (count($parts) > 1) {
        $lastname = array_pop($parts);
        $firstname = implode(" ", $parts);
    } else {
        $firstname = $name;
        $lastname = " ";
    }
    if ($user->tc_verified_at == NULL) {
        $tcno = "11111111111";
    } else {
        $tcno = $user->tcno;
    }


    $xml = '<Invoice xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:xades="http://uri.etsi.org/01903/v1.3.2#" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xsi:schemaLocation="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2 UBL-Invoice-2.1.xsd" xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2">
	<cbc:UBLVersionID>2.1</cbc:UBLVersionID>
	<cbc:CustomizationID>TR1.2</cbc:CustomizationID>';
    if ($line1KdvTutari == 0) {
        $xml .= '<cbc:ProfileID>OZELMATRAH</cbc:ProfileID>';
    } else {
        $xml .= '<cbc:ProfileID>EARSIVFATURA</cbc:ProfileID>';
    }
    $xml .= '
	<cbc:ID>' . $idFatura . '</cbc:ID>
	<cbc:CopyIndicator>false</cbc:CopyIndicator>
	<cbc:UUID>' . $uuid . '</cbc:UUID>
	<cbc:IssueDate>' . date('Y-m-d') . '</cbc:IssueDate>
	<cbc:IssueTime>' . date('H:i:s') . '</cbc:IssueTime>
	<cbc:InvoiceTypeCode>SATIS</cbc:InvoiceTypeCode>
	<cbc:Note>Yazı ile: #' . $first . $second . '#</cbc:Note>
	<cbc:DocumentCurrencyCode>TRY</cbc:DocumentCurrencyCode>
	<cbc:LineCountNumeric>' . $lineCount . '</cbc:LineCountNumeric>
	<cac:OrderReference>
		<cbc:ID>' . $sipId . '</cbc:ID>
		<cbc:IssueDate>' . date('Y-m-d') . '</cbc:IssueDate>
	</cac:OrderReference>
	<cac:AccountingSupplierParty>
		<cac:Party>
			<cac:PartyIdentification>
				<cbc:ID schemeID="VKN">' . env('BILNEX_TEDARIKCI') . '</cbc:ID>
			</cac:PartyIdentification>
			<cac:PartyName>
				<cbc:Name>' . env('FIRMA_UNVANI') . '</cbc:Name>
			</cac:PartyName>
			<cac:PostalAddress>
				<cbc:BuildingName>' . env('FIRMA_ADRES') . '</cbc:BuildingName>
				<cbc:CitySubdivisionName>' . env('FIRMA_ILCE') . '</cbc:CitySubdivisionName>
				<cbc:CityName>' . env('FIRMA_IL') . '</cbc:CityName>
				<cac:Country>
					<cbc:IdentificationCode>TR</cbc:IdentificationCode>
					<cbc:Name>TÜRKİYE</cbc:Name>
				</cac:Country>
			</cac:PostalAddress>
			<cac:PartyTaxScheme>
				<cac:TaxScheme>
					<cbc:Name>' . env('FIRMA_VERGI_DAIRESI') . '</cbc:Name>
				</cac:TaxScheme>
			</cac:PartyTaxScheme>
			<cac:Contact>
				<cbc:Telephone>' . env('FIRMA_SORUMLU_TEL') . '</cbc:Telephone>
				<cbc:ElectronicMail>' . env('FIRMA_SORUMLU_POSTA') . '</cbc:ElectronicMail>
			</cac:Contact>
		</cac:Party>
	</cac:AccountingSupplierParty>
	<cac:AccountingCustomerParty>
		<cac:Party>
			<cac:PartyIdentification>
				<cbc:ID schemeID="TCKN">' . $tcno . '</cbc:ID>
			</cac:PartyIdentification>
			<cac:PostalAddress>
				<cbc:BuildingName>-</cbc:BuildingName>
				<cbc:CitySubdivisionName>Muratpaşa</cbc:CitySubdivisionName>
				<cbc:CityName>Antalya</cbc:CityName>
				<cac:Country>
					<cbc:IdentificationCode>TR</cbc:IdentificationCode>
					<cbc:Name>TÜRKİYE</cbc:Name>
				</cac:Country>
			</cac:PostalAddress>
			<cac:PartyTaxScheme>
				<cac:TaxScheme>
					<cbc:Name></cbc:Name>
				</cac:TaxScheme>
			</cac:PartyTaxScheme>
			<cac:Person>
				<cbc:FirstName>' . $firstname . '</cbc:FirstName>
				<cbc:FamilyName>' . $lastname . '</cbc:FamilyName>
			</cac:Person>
		</cac:Party>
	</cac:AccountingCustomerParty>
	<cac:AllowanceCharge>
		<cbc:ChargeIndicator>false</cbc:ChargeIndicator>
		<cbc:Amount currencyID="TRY">0</cbc:Amount>
	</cac:AllowanceCharge>
	<cac:TaxTotal>
		<cbc:TaxAmount currencyID="TRY">' . $totalKdvTutari . '</cbc:TaxAmount>
		<cac:TaxSubtotal>
			<cbc:TaxableAmount currencyID="TRY">' . $totalKdvsizTutar . '</cbc:TaxableAmount>
			<cbc:TaxAmount currencyID="TRY">' . $totalKdvTutari . '</cbc:TaxAmount>
			<cbc:CalculationSequenceNumeric>1</cbc:CalculationSequenceNumeric>
			<cbc:Percent>' . $totalKdvOrani . '</cbc:Percent>
			<cac:TaxCategory>
				<cac:TaxScheme>
					<cbc:Name>KDV</cbc:Name>
					<cbc:TaxTypeCode>0015</cbc:TaxTypeCode>
				</cac:TaxScheme>
			</cac:TaxCategory>';
    if ($line1KdvTutari == 0) {
        $xml .= '<cac:TaxCategory>
				<cbc:TaxExemptionReasonCode>810</cbc:TaxExemptionReasonCode>
				<cbc:TaxExemptionReason>810 - Telefon kartı ve jeton satışları</cbc:TaxExemptionReason>
				<cac:TaxScheme>
					<cbc:Name>KDV</cbc:Name>
					<cbc:TaxTypeCode>0015</cbc:TaxTypeCode>
				</cac:TaxScheme>
			</cac:TaxCategory>';
    }
    $xml .= '
		</cac:TaxSubtotal>
	</cac:TaxTotal>
	<cac:LegalMonetaryTotal>
		<cbc:LineExtensionAmount currencyID="TRY">' . $totalKdvsizTutar . '</cbc:LineExtensionAmount>
		<cbc:TaxExclusiveAmount currencyID="TRY">' . $totalKdvsizTutar . '</cbc:TaxExclusiveAmount>
		<cbc:TaxInclusiveAmount currencyID="TRY">' . $totalHerseyDahilTutar . '</cbc:TaxInclusiveAmount>
		<cbc:AllowanceTotalAmount currencyID="TRY">0</cbc:AllowanceTotalAmount>
		<cbc:PayableAmount currencyID="TRY">' . $totalHerseyDahilTutar . '</cbc:PayableAmount>
	</cac:LegalMonetaryTotal>
	<cac:InvoiceLine>
		<cbc:ID>1</cbc:ID>
		<cbc:InvoicedQuantity unitCode="C62">' . $line1Adet . '</cbc:InvoicedQuantity>
		<cbc:LineExtensionAmount currencyID="TRY">' . $line1KdvsizTutar . '</cbc:LineExtensionAmount>
		<cac:TaxTotal>
			<cbc:TaxAmount currencyID="TRY">' . $line1KdvTutari . '</cbc:TaxAmount>
			<cac:TaxSubtotal>
				<cbc:TaxableAmount currencyID="TRY">' . $line1KdvsizTutar . '</cbc:TaxableAmount>
				<cbc:TaxAmount currencyID="TRY">' . $line1KdvTutari . '</cbc:TaxAmount>
				<cbc:CalculationSequenceNumeric>1</cbc:CalculationSequenceNumeric>
				<cbc:Percent>' . $line1KdvOrani . '</cbc:Percent>
				<cac:TaxCategory>
					<cac:TaxScheme>
						<cbc:Name>GERÇEK USULDE KATMA DEĞER VERGİSİ</cbc:Name>
						<cbc:TaxTypeCode>0015</cbc:TaxTypeCode>
					</cac:TaxScheme>
				</cac:TaxCategory>
			</cac:TaxSubtotal>
		</cac:TaxTotal>
		<cac:Item>
			<cbc:Name>' . $line1Name . '</cbc:Name>
			<cac:SellersItemIdentification>
				<cbc:ID>' . $line1Id . '</cbc:ID>
			</cac:SellersItemIdentification>
		</cac:Item>
		<cac:Price>
			<cbc:PriceAmount currencyID="TRY">' . $totalTekilFiyat . '</cbc:PriceAmount>
		</cac:Price>
	</cac:InvoiceLine>';
    if ($lineCount > 1) {
        $xml .= '<cac:InvoiceLine>
		<cbc:ID>2</cbc:ID>
		<cbc:InvoicedQuantity unitCode="C62">' . $line2Adet . '</cbc:InvoicedQuantity>
		<cbc:LineExtensionAmount currencyID="TRY">' . $line2KdvsizTutar . '</cbc:LineExtensionAmount>
		<cac:TaxTotal>
			<cbc:TaxAmount currencyID="TRY">' . $line2KdvTutari . '</cbc:TaxAmount>
			<cac:TaxSubtotal>
				<cbc:TaxableAmount currencyID="TRY">' . $line2KdvsizTutar . '</cbc:TaxableAmount>
				<cbc:TaxAmount currencyID="TRY">' . $line2KdvTutari . '</cbc:TaxAmount>
				<cbc:CalculationSequenceNumeric>1</cbc:CalculationSequenceNumeric>
				<cbc:Percent>' . $line2KdvOrani . '</cbc:Percent>
				<cac:TaxCategory>
					<cac:TaxScheme>
						<cbc:Name>GERÇEK USULDE KATMA DEĞER VERGİSİ</cbc:Name>
						<cbc:TaxTypeCode>0015</cbc:TaxTypeCode>
					</cac:TaxScheme>
				</cac:TaxCategory>
			</cac:TaxSubtotal>
		</cac:TaxTotal>
		<cac:Item>
			<cbc:Name>' . $line2Name . '</cbc:Name>
			<cac:SellersItemIdentification>
				<cbc:ID>' . $line2Id . '</cbc:ID>
			</cac:SellersItemIdentification>
		</cac:Item>
		<cac:Price>
			<cbc:PriceAmount currencyID="TRY">' . $totalTekilFiyat2 . '</cbc:PriceAmount>
		</cac:Price>
	</cac:InvoiceLine>';
    }
    if ($lineCount > 2) {
        $xml .= '<cac:InvoiceLine>
		<cbc:ID>3</cbc:ID>
		<cbc:InvoicedQuantity unitCode="C62">' . $line3Adet . '</cbc:InvoicedQuantity>
		<cbc:LineExtensionAmount currencyID="TRY">' . $line3KdvsizTutar . '</cbc:LineExtensionAmount>
		<cac:TaxTotal>
			<cbc:TaxAmount currencyID="TRY">' . $line3KdvTutari . '</cbc:TaxAmount>
			<cac:TaxSubtotal>
				<cbc:TaxableAmount currencyID="TRY">' . $line3KdvsizTutar . '</cbc:TaxableAmount>
				<cbc:TaxAmount currencyID="TRY">' . $line3KdvTutari . '</cbc:TaxAmount>
				<cbc:CalculationSequenceNumeric>1</cbc:CalculationSequenceNumeric>
				<cbc:Percent>' . $line3KdvOrani . '</cbc:Percent>
				<cac:TaxCategory>
					<cac:TaxScheme>
						<cbc:Name>GERÇEK USULDE KATMA DEĞER VERGİSİ</cbc:Name>
						<cbc:TaxTypeCode>0015</cbc:TaxTypeCode>
					</cac:TaxScheme>
				</cac:TaxCategory>
			</cac:TaxSubtotal>
		</cac:TaxTotal>
		<cac:Item>
			<cbc:Name>' . $line3Name . '</cbc:Name>
			<cac:SellersItemIdentification>
				<cbc:ID>' . $line3Id . '</cbc:ID>
			</cac:SellersItemIdentification>
		</cac:Item>
		<cac:Price>
			<cbc:PriceAmount currencyID="TRY">' . $totalTekilFiyat3 . '</cbc:PriceAmount>
		</cac:Price>
	</cac:InvoiceLine>';
    }
    $xml .= '</Invoice>';
    //header("Content-type: text/xml");
    $soapUrl = "https://www.e-bilnex.com/WebService/Invoices.asmx?WSDL";
    $options = array(
        'encoding' => 'UTF-8',
        'exceptions' => TRUE,
    );
    if (isset($faturaKesme)) {
        return false;
    } else {
        try {
            $baglantix = new SoapClient($soapUrl, $options);
            $gonderx = new stdClass();
            $gonderx->Token = env('BILNEX_TOKEN');
            $gonderx->SupplierTaxNumber = env('BILNEX_TEDARIKCI');
            $gonderx->BranchCode = env('BILNEX_BRANCH');
            $gonderx->Xml = (string)$xml;
            $gonderx->Ubl = "1";
            $gonderx->CustomerPK = $user->email;
            $gonderx->CustomerCode = $tcno;
            $nsorgux = $baglantix->addInvoices($gonderx);
            if ($nsorgux->addInvoicesResult->StatusCode == 200) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }
}

function getSuperUser()
{
    return 3;
}

function userRoleIsAdmin($user = 0)
{
    if ($user != 0) { //eğer kullanıcı id'si gönderilmişse
        $user = DB::table('users')->where('id', $user)->first();
        if ($user) { // eğer kullanıcı varsa
            if ($user->role == 0) { //kullanıcı bir yönetici mi
                $yetki = DB::table('user_group_users')->where('user', $user->id)->where('user_group', getSuperUser())->whereNull('deleted_at')->first();
                if ($yetki) { //eğer kullanıcının yetkisi var ise
                    return true;
                }
            }
        }
    }
    return false;
}

function findUserReference($user = 0)
{
    if ($user != 0) {
        $user = DB::table('users')->where('id', $user)->first();
        if ($user) {
            if ($user->refId != 0) {
                $referrer = DB::table('users')->where('id', $user->refId)->first();
                if ($referrer) {
                    return $referrer->name;
                }
            }
        }
    }
    return "Yok";
}

function findUserBloke($user = 0)
{
    $toplam = 0;
    $alis_ilanlari = DB::table('pazar_yeri_ilanlar_buy')->where('user', $user)->whereNull('deleted_at')->where('status', '<', '2')->get();
    if ($alis_ilanlari) {
        foreach ($alis_ilanlari as $item) {
            $toplam += $item->price * 0.1;
        }
    }
    return $toplam;
}

function muveAuth()
{
    $value = Cache::remember('muveAuth', '3600', function () {
        $info = DB::table('settings')->first();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, env('MUVE_BASE_URL') . '/oauth/v2/token');
        //curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials&client_id=' . $info->muve_client_id . '&client_secret=' . $info->muve_client_secret);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $response = curl_exec($ch);
        $result_package = json_decode($response);
        curl_close($ch);
        if ($result_package->access_token) {
            return $result_package->access_token;
        }
    });
    return $value;
}

function muveGetCategories()
{
    $value = Cache::remember('muveGamesCategories', '86400', function () {
        $ch = curl_init();
        $headers = array(
            'Authorization: Bearer ' . muveAuth(),
        );
        curl_setopt($ch, CURLOPT_URL, env('MUVE_BASE_URL') . '/api/dictionaries/categories');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_POSTFIELDS, '');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $response = curl_exec($ch);
        $result_game = json_decode($response);
        curl_close($ch);
        if (isset($result_game->code)) {
            if ($result_game->code == 200) {
                return $result_game->data;
            }
        } else {
            return false;
        }
    });
    return $value;
}

function muveGetProducts()
{
    $value = Cache::remember('muveGames', '3600', function () {
        $ch = curl_init();
        $headers = array(
            'Authorization: Bearer ' . muveAuth(),
        );
        curl_setopt($ch, CURLOPT_URL, env('MUVE_BASE_URL') . '/api/products');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_POSTFIELDS, '');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $response = curl_exec($ch);
        $result_game = json_decode($response);
        curl_close($ch);
        if (isset($result_game->code)) {
            if ($result_game->code == 200) {
                return $result_game->data;
            }
        } else {
            return false;
        }
    });
    return $value;
}

function muveGetProductsDetail($id)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://store.steampowered.com/api/appdetails?l=turkish&appids=' . $id);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    $response = curl_exec($ch);
    $data = json_decode($response);
    if (isset($data->$id)) {
        if (isset($data->$id->data)) {
            $data = $data->$id->data;
            return $data;
        }
    }
    curl_close($ch);
    /*
    $value = Cache::remember('muveGamesDetail'.$id, '3600', function () use ($id) {
        $ch = curl_init();
        $headers = array(
            'Authorization: Bearer ' . muveAuth(),
        );
        curl_setopt($ch, CURLOPT_URL, env('MUVE_BASE_URL') . '/api/products/'.$id);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_POSTFIELDS, '');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $response = curl_exec($ch);
        $result_game = json_decode($response);
        curl_close($ch);
        if($result_game->code == 200) {
            return $result_game->data;
        }
    });
    return $value;*/
}

function muveStatusList()
{
    $data = [
        [
            'id' => '2',
            'type' => 'Kutu',
            'description' => 'Mevcut, 24 saat içinde teslimat',
        ],
        [
            'id' => '3',
            'type' => 'Kutu, Dijital',
            'description' => 'Mevcut, ön sipariş',
        ],
        [
            'id' => '4',
            'type' => 'Kutu',
            'description' => 'Mevcut, 3-5 gün içinde teslimat',
        ],
        [
            'id' => '5',
            'type' => 'Kutu',
            'description' => 'Mevcut, 7-14 gün içinde teslimat',
        ],
        [
            'id' => '8',
            'type' => 'Dijital',
            'description' => 'Kullanılabilir, hemen indirilebilir',
        ],
        [
            'id' => '9',
            'type' => 'Kutu',
            'description' => 'Mevcut, 7 gün içinde teslimat',
        ],
        [
            'id' => '10',
            'type' => 'Kutu',
            'description' => 'Mevcut, muhtelif sürelerde teslimat',
        ],
    ];
    return $data;
}

/*
 * Cache Areas, plesea write other functioon upper this area.
 */

function getCacheHeaderMenu()
{
    $value = Cache::rememberForever('header_menu_status_1', function () {
        return DB::table('header_menu')->whereNull('deleted_at')->where('status', '1')->get();
    });
    return $value;
}

function getCacheHeaderMenuSub1Count($id)
{
    $value = Cache::rememberForever('header_menu_sub_1_count' . $id, function () use ($id) {
        return DB::table('header_menu')->where('sub_menu', $id)->whereNull('deleted_at')->where('status', '1')->count();
    });
    return $value;
}

function getCacheHeaderMenuSub1Get($id)
{
    $value = Cache::rememberForever('header_menu_sub_1_get' . $id, function () use ($id) {
        return DB::table('header_menu')->where('sub_menu', $id)->whereNull('deleted_at')->where('status', '1')->get();
    });
    return $value;
}

function getCacheHeaderMenuSub2Count($id)
{
    $value = Cache::rememberForever('header_menu_sub_2_count' . $id, function () use ($id) {
        return DB::table('header_menu')->where('sub_menu', $id)->whereNull('deleted_at')->where('status', '1')->count();
    });
    return $value;
}

function getCacheHeaderMenuSub3Count($id)
{
    $value = Cache::rememberForever('header_menu_sub_3_count' . $id, function () use ($id) {
        return DB::table('header_menu')->where('sub_menu', $id)->whereNull('deleted_at')->where('status', '1')->count();
    });
    return $value;
}

function getCacheHeaderMenuSub3Get($id)
{
    $value = Cache::rememberForever('header_menu_sub_3_get' . $id, function () use ($id) {
        return DB::table('header_menu')->where('sub_menu', $id)->whereNull('deleted_at')->where('status', '1')->get();
    });
    return $value;
}

function getCacheEpinPopular()
{

    $value = Cache::remember('epin_satis_header', '3600', function () {
        return DB::table('epin_satis')
            ->selectRaw("count(id) as satis, paketId, game_title")
            ->where('created_at', '>=', DB::raw('DATE_SUB(NOW(), INTERVAL 7 DAY)'))
            ->groupBy('paketId', 'game_title')
            ->orderBy('satis', 'DESC')
            ->limit('5')
            ->distinct()
            ->get();
    });
    return $value;
}

function getCacheGameGoldPopular()
{

    $value = Cache::remember('game_gold_satis_header', '3600', function () {
        return DB::table('game_gold_satis')
            ->selectRaw("paket,count(id) as satis")
            ->where('created_at', '>=', DB::raw('DATE_SUB(NOW(), INTERVAL 7 DAY)'))
            ->groupBy('paket')
            ->orderBy('satis', 'DESC')
            ->limit('5')
            ->distinct()
            ->get();
    });
    return $value;
}

function getCacheEpinPopularSingle($game_title)
{
    $value = Cache::remember('games_titles_header' . $game_title, '3600', function () use ($game_title) {
        return DB::table('games_titles')->where('id', $game_title)->first();
    });
    return $value;
}

function getCacheEpinPopularSinglePackage($paketId)
{
    $value = Cache::remember('games_packages_header' . $paketId, '3600', function () use ($paketId) {
        return DB::table('games_packages')->where('id', $paketId)->first();
    });
    return $value;
}

function getCacheGameGoldPopularSingle($paket)
{
    $value = Cache::remember('games_packages_trade_header' . $paket, '3600', function () use ($paket) {
        return DB::table('games_packages_trade')->where('id', $paket)->first();
    });
    return $value;
}

function getCacheGameGoldPopularSinglePackage($games_titles)
{
    $value = Cache::remember('games_titles_header_gold' . $games_titles, '3600', function () use ($games_titles) {
        return DB::table('games_titles')->where('id', $games_titles)->first();
    });
    return $value;
}

function getCacheHomeSlider()
{
    $value = Cache::remember('SliderHome', '3600', function () {
        return \App\Models\Slider::whereNull('deleted_at')->where('status', '1')->orderBy('created_at', 'DESC')->get();
    });
    return $value;
}

function getCacheHomeSliderMini1()
{
    $value = Cache::remember('SliderHomeMini1', '3600', function () {
        return DB::table('slider_mini')->where('id', '1')->first();
    });
    return $value;
}

function getCacheHomeSliderMini2()
{
    $value = Cache::remember('SliderHomeMini2', '3600', function () {
        return DB::table('slider_mini')->where('id', '2')->first();
    });
    return $value;
}

function getCacheHomeGameIcons()
{
    $value = Cache::remember('HomeGameIcons', '3600', function () {
        return DB::table('games')->whereNull('deleted_at')->where('status', '1')->where('icon', '!=', 'NULL')->orderBy('sira', 'asc')->take(12)->get();
    });
    return $value;
}

function getCacheHomeGamesPopular()
{
    $value = Cache::remember('HomeGamesPopular', '3600', function () {
        return DB::table('games')->whereNull('deleted_at')->orderBy('sira', 'asc')->where('status', '1')->take(12)->get();
    });
    return $value;
}

function getCacheHomeTwitchMarlen()
{
    $value = Cache::remember('TwitchMarlen', '3600', function () {
        return getTwitchLiveStreams('id=430176703');
    });
    return $value;
}

function getCacheHomeTwitch()
{
    $sorgu = DB::table('twitch_support_streamer')->where('favori', '1')->where('status', '1')->where('twitch_id', '!=', '430176703')->whereNull('deleted_at')->inRandomOrder()->limit(13)->get();
    $twitchIds = null;
    $i = 0;
    foreach ($sorgu as $idler) {
        if ($i != 0) {
            $twitchIds .= '&';
        }
        $twitchIds .= 'id=' . $idler->twitch_id;
        $i += 1;
    }
    $value = Cache::remember('Twitch', '3600', function () use ($twitchIds) {
        return getTwitchLiveStreams($twitchIds);
    });
    return $value;
}

function getCacheHomeNews()
{
    $value = Cache::remember('NewsHome', '3600', function () {
        return \App\Models\News::whereNull('deleted_at')->where('lang', getLang())->orderBy('created_at', 'desc')->where('status', '1')->take(5)->get();
    });
    return $value;
}

function getCacheHomeFaqCategory()
{
    $value = Cache::remember('FaqCategoryHome', '3600', function () {
        return FaqCategory::whereNull('deleted_at')->where('status', '1')->get();
    });
    return $value;
}

function getCacheHomeFaqCategoryById($id)
{
    $value = Cache::remember('FaqCategoryHomeById' . $id, '3600', function () use ($id) {
        return DB::table('faq')->where('category', $id)->where('status', '1')->where('one_cikan', '1')->whereNull('deleted_at')->get();
    });
    return $value;
}

function noIndex($op = 'get', $state = true)
{
    static $noIndex;
    if ($op != 'get')
        $noIndex = $state;
    return !!$noIndex;
}
function getCacheSetings()
{
    $value = Cache::rememberForever('settings', function () {
        return \App\Models\Settings::first();
    });
    return $value;
}

function getCacheEpinDetay($epin)
{
    $value = Cache::rememberForever('EpinDetay' . $epin, function () use ($epin) {
        return DB::table('games_titles')
            ->select('games_titles.*')
            ->join('games', 'games_titles.game', '=', 'games.id')
            ->where('games_titles.link', $epin)
            ->whereNull('games_titles.deleted_at')
            ->whereNull('games.deleted_at')
            ->first();
    });
    return $value;
}

function getCacheEpinDetayPackages($packagesEpin)
{
    $value = Cache::rememberForever('EpinDetayPackages' . $packagesEpin, function () use ($packagesEpin) {
        return DB::table('games_packages')->where('games_titles', $packagesEpin)->whereNull('deleted_at')->orderBy('sira', 'asc')->get();
    });
    return $value;
}

function getCacheSearchArea1($term)
{
    $value = Cache::rememberForever('SearchArea1' . $term, function () use ($term) {
        $term = $term;
        return DB::table('games_titles')
            ->select('games_titles.*')
            ->join('games', 'games_titles.game', '=', 'games.id')
            ->whereNull('games_titles.deleted_at')
            ->whereNull('games.deleted_at')
            ->where(function ($titles) use ($term) {
                $titles->where('games_titles.title', 'like', '%' . $term . '%')
                    ->orWhere('games_titles.etiket', 'like', '%' . $term . '%');
            })
            ->where('games.status', '1')
            ->where('games_titles.status', '1')
            ->limit(10)
            ->get();
    });
    return $value;
}

function getCacheSearchArea2($term)
{
    $value = Cache::rememberForever('SearchArea2' . $term, function () use ($term) {
        $term = $term;
        return DB::table('games_packages_trade')
            ->select('games_packages_trade.*')
            ->join('games_titles', 'games_packages_trade.games_titles', '=', 'games_titles.id')
            ->whereNull('games_packages_trade.deleted_at')
            ->whereNull('games_titles.deleted_at')
            ->where('games_titles.status', '1')
            ->where(function ($titles) use ($term) {
                $titles->where('games_packages_trade.title', 'like', '%' . $term . '%')
                    ->orWhere('games_packages_trade.etiket', 'like', '%' . $term . '%');
            })
            ->limit(10)
            ->get();
    });
    return $value;
}

function getCacheSearchArea3($term)
{
    $value = Cache::rememberForever('SearchArea3' . $term, function () use ($term) {
        $term = $term;
        return DB::table('games_packages')
            ->select('games_packages.*')
            ->join('games_titles', 'games_packages.games_titles', '=', 'games_titles.id')
            ->join('games', 'games_titles.game', '=', 'games.id')
            ->where(function ($titles) use ($term) {
                $titles->where('games_packages.title', 'like', '%' . $term . '%')
                    ->orWhere('games_packages.etiket', 'like', '%' . $term . '%');
            })
            ->where('games_titles.status', '1')
            ->where('games.status', '1')
            ->whereNull('games_packages.deleted_at')
            ->whereNull('games_titles.deleted_at')
            ->whereNull('games.deleted_at')
            ->limit(10)
            ->get();
    });
    return $value;
}

function getCacheSearchArea4($term)
{
    $value = Cache::rememberForever('SearchArea4' . $term, function () use ($term) {
        $term = $term;
        return DB::table('muve_games')
            ->select('muve_games.*')
            ->where(function ($titles) use ($term) {
                $titles->where('muve_games.title', 'like', '%' . $term . '%');
            })
            ->whereNull('muve_games.deleted_at')
            ->limit(10)
            ->get();
    });
    return $value;
}


/*
 * End of cache area
 */

function currencyConverter($price, $mainCurrency, $convertCurrency)
{
    //$mainCurrency : fiyatın gönderildiği para birimi
    //$convertCurrency : fiyatın çevirileceği para birimi
    $value = Cache::remember('currency' . $mainCurrency . $convertCurrency, '900', function () use ($mainCurrency, $convertCurrency) {
        if ($mainCurrency == 'TRY') {
            $searchCurrency = $convertCurrency;
        } else {
            $searchCurrency = $mainCurrency;
        }
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://www.nosyapi.com/apiv2/economy/getCurrencyDetails?code=' . $searchCurrency,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer H5NMWP2VsXTGl3iu56OCvQmQ1SacRRiFcswfoyqMs2wt5ffEx8WMGzkXLxtd'
            ),
        ));
        $response = curl_exec($curl);
        $data = json_decode($response);
        curl_close($curl);
        if (isset($data->data[0]->selling)) {
            return $data->data[0]->selling;
        } else {
            return 1;
        }
    });
    if ($mainCurrency == 'TRY') {
        return round($price / $value, 2);
    } else {
        return round($price * $value, 2);
    }
}

function getMuveGamesPrice($muveGameId)
{
    $muveGame = DB::table('muve_games')->where('id', $muveGameId)->first();
    if ($muveGame == NULL) {
        return 0;
    }
    if (date('Y-m-d H:i:s') < $muveGame->discount_date) {
        if ($muveGame->discount_type == 0) {
            return currencyConverter($muveGame->muvePrice, $muveGame->muveCurrency, 'TRY');
        } elseif ($muveGame->discount_type == 1) { //yüzde olarak indirim
            return currencyConverter($muveGame->muvePrice - ($muveGame->discount_amount * $muveGame->muvePrice / 100), $muveGame->muveCurrency, 'TRY');
        } elseif ($muveGame->discount_type == 2) { // tutar olarak indirim
            return currencyConverter($muveGame->muvePrice - $muveGame->discount_amount, $muveGame->muveCurrency, 'TRY');
        }
    } else {
        return currencyConverter($muveGame->muvePrice, $muveGame->muveCurrency, 'TRY');
    }
}

function getMuveGamesCategories()
{
    $categories = DB::table('muve_games')->select('categories')->distinct()->get();
    $uniqueCat = array();
    foreach ($categories as $cat) {
        $catz = explode("\n", $cat->categories);
        foreach ($catz as $u) {
            if (!in_array(trim($u), $uniqueCat)) {
                $uniqueCat[] = rtrim(trim($u));
            }
        }
    }
    return $uniqueCat;
}

function getMuveGamesCurrency()
{
    $value = Cache::rememberForever('currencies', function () {
        return array(
            ["code" => "TRY", "ShortName" => "Türk Lirası"],
            ["code" => "USD", "ShortName" => "Dolar"],
            ["code" => "EUR", "ShortName" => "Euro"],
            ["code" => "GBP", "ShortName" => "İngiliz Sterlini"],
            ["code" => "CAD", "ShortName" => "Kanada Doları"],
            ["code" => "DKK", "ShortName" => "Danimarka Kronu"],
            ["code" => "SEK", "ShortName" => "İsveç Kronu"],
            ["code" => "AUD", "ShortName" => "Avustralya Doları"],
            ["code" => "RUB", "ShortName" => "Rus Rublesi"],
            ["code" => "ILS", "ShortName" => "İsrail Şekeli"],
            ["code" => "PLN", "ShortName" => "Polonya Zlotisi"],
            ["code" => "BGN", "ShortName" => "BULGAR LEVI"],
            ["code" => "RON", "ShortName" => "Romanya Leyi"],
            ["code" => "AZN", "ShortName" => "Azerbaycan Manatı"]
        );
    });
    return $value;
}

function getMuveErrorsDesc($errorId)
{
    $value = Cache::rememberForever('muveErrorDesc', function () use ($errorId) {
        if ($errorId == 0) {
            return "Tanımlanmamış hata oluştu.";
        } elseif ($errorId == 50015) {
            return "Oyun henüz satışa sunulmadığı için satın alma işlemi gerçekleştiremezisinz";
        } else {
            return "Teknik Hata, hata koduyla birlikte canlı desteğe başvurunuz. Hata Kodu : " . $errorId;
        }
    });
    return $value;
}

function getMeta($page, $type = 0)
{
    $seoPage = DB::table('pages')->where('url', $page)->where('seo_page', '1')->where('lang', 'tr')->first();
    if ($seoPage) {
        $et = DB::table('pages')->where('url', $page)->where('lang', 'tr')->first();
        if ($et) {
            if ($type == 0) { //meta description
                return $et->description;
            } else { //meta keywords
                return $et->keywords;
            }
        } else {
            return getCacheSetings()->description;
        }
    } else {
        return getCacheSetings()->description;
    }
}

function getMetaSpecial($routeName, $id, $type = 0)
{
    if ($routeName == 'oyun_baslik') {
        $game = DB::table('games')->where('id', $id)->first();
        if ($game) {
            if ($type == 0) {
                return $game->description;
            } else {
                return $game->keywords;
            }
        }
    } elseif ($routeName == 'epin_detay_paket') {

        if ($type == 0) {
            return 'desc';
        } else {
            return 'keyw';
        }
    } elseif ($routeName == 'game_gold_detay' or $routeName == 'epin_detay' or $routeName == 'item_detay') {

        $baslik = DB::table('games_titles')->where('id', $id)->first();

        if (@$_GET['swx']) {
            var_dump($baslik);
            exit();
        }
        if ($baslik) {
            if ($type == 0) {
                return $baslik->description;
            } else {
                return $baslik->keywords;
            }
        }
    } elseif ($routeName == 'cd_key_detay') {
        $baslik = DB::table('muve_games')->where('id', $id)->first();
        if ($baslik) {
            if ($type == 0) {
                return $baslik->title . ', ' . $baslik->metaDescription;
            } else {
                return $baslik->keywords;
            }
        }
    } else {
        return getCacheSetings()->description;
    }
}

#-----------------------------------------EPIN crypt decrypt module 13/09/22
class epin
{   #------------------> !!!!!!!  BU SINIF ICERISINDE DEGISKENLERE MUDAHALE EDILIRSE EPIN KRIPTO SISTEMI HATALI CALISACAKTIR (VAR LENGTH = 35) !!!!!!!
    #------------------>BU FONKSIYONUN TERSI ALINAMAZ.. KEY DEGISIMINDE TUM KAYITLI EPINLERINIZI KAYBETME RISKINIZ MEVCUT. NE YAPTIGINIZIN FARKINDA ISENIZ OYLE DEVAM EDIN!!
    private static $secret1  = '1-Natasha-Morozova-Prince-Igor---:)'; //secret ve public
    private static $secret2  = '2-Emma-Shapplin-Spente-le-stelle-:)';
    private static $method   = 'AES-256-CBC'; // Algo
    public static function ENC($data)
    {
        return base64_encode(openssl_encrypt($data, self::$method, hash('sha256', self::$secret1), 0, substr(hash('sha256', self::$secret2), 0, 16)));
    }
    public static function DEC($data)
    {
        return openssl_decrypt(base64_decode($data), self::$method, hash('sha256', self::$secret1), 0, substr(hash('sha256', self::$secret2), 0, 16));
    }
}
#-----------------------------------------EPIN crypt decrypt module END

function bloke_kontrol($uid)
{
    return DB::table('bakiye_bloke')->where('aktif', 1)->where('user', $uid)->count() > 0 ? true : false;
}

function odeme_kontrol()
{
    $tar = date('Y-m-d H:i:') . '00';
    $odm = DB::select("select * from odemeler where created_at >= '$tar' and  deleted_at is null and status=1");
    $lmt = DB::select("select kanal,tutar from bakiye_bloke_odeme");

    foreach ($lmt as $arr) {
        $limitler[$arr->kanal] = $arr->tutar;
    }
    foreach ($odm as $al) {
        if ($al->amount >= $limitler[$al->channel] && $limitler[$al->channel] != 0) {
            if (DB::table("odeme_limit")->where('oid', $al->id)->where('created_at', $al->created_at)->count() == 0) {
                DB::select("insert into odeme_limit values(null,'$al->id',1,'$al->created_at')");
                DB::select("update users set bakiye=bakiye-$al->amount where id='$al->user'");
                LogCall($al->user, '1',  $al->amount . " TL tutarındaki ödeme tanımlanan güvenlik limitini aştığı için onaya düştü.");
            }
        }
    }
}
function isEpinSepeti()
{
    $epinsepeti = [40559, 41809, 41808, 41810, 41811, 40597, 40583, 40596, 41806, 40579, 40598, 40586, 40585, 40600, 40601, 27504, 44251, 49381, 48419, 73108,80862, 152972, 35859];
    return (isset(Auth::user()->id) && in_array(Auth::user()->id, $epinsepeti));
}
function isAkinsoftClient()
{

    if (@$_GET['akinsoftt'] == 'akinsoftt' || isEpinSepeti())
        return true;
    return (in_array(@$_SERVER['HTTP_USER_AGENT'], ["Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/45.0.2454.101 Safari/537.36", "Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/45.0.2454.0 Safari/537.36"]));
}
function canOzanPay()
{
    $id = 0;
    if (isset(Auth::user()->id))
        $id = Auth::user()->id;
    if ($id == 0)
        return false;
    if (DB::table("odemeler")->where('user', $id)->where('status', 1)->whereIn('channel', [1, 9, 10, 11, 12, 13, 14, 16])->whereNull('deleted_at')->count())
        return true;
    else
        return false;
}
class paparaCurl
{
    public static function send($acc, $tutar)
    {
        $id =  md5(mktime(date('YmdHis')));
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
}
