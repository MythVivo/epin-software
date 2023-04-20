<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Auth;
use DB;

class RegisterController extends Controller
{
    public function register()
    {
        return view('front.pages.register');
    }
    public function register_asoft()
    {
        return view('front.pages.register_as');
    }




    public function register_post(Request $request)
    {  //return __('general.hata-1');

        if ($request->yayinciRef != 27156) { //Akınsoft cafe kaydı ise pas geç
#-------------------------google cptc  helpers uzerinde fonksiyon tanımladım curl post/resp.  post_captcha()
            if (isset($_POST['g-recaptcha-response'])) {
                $captcha = $_POST['g-recaptcha-response'];
            }
            if (!$captcha) {
                LogCall('0', '1', "Kullanıcı \"" . $request->email . "\" isimli e-posta adresi ile kaydolmaya çalışırken Recaptcha doğrulamasını yanlış gerçekleştirdi.");
                return redirect()->route('kayit')->with('error', __('general.hata-1'));
            }
            $secretKey = "6LcYPqEkAAAAAFyfewaE_5Z2wmIovD-yqANetGvQ";
            $ip = $_SERVER['REMOTE_ADDR'];
            $responseKeys = post_captcha($captcha, $ip);
            if (intval($responseKeys["success"]) !== 1) {
                LogCall('0', '1', "Kullanıcı \"" . $request->email . "\" isimli e-posta adresi ile kaydolmaya çalışırken Recaptcha doğrulamasını yanlış gerçekleştirdi.");
                return redirect()->route('kayit')->with('error', __('general.hata-1'));
            }
        }
#-------------------------google cptc

        if (!filter_var($request->email, FILTER_VALIDATE_EMAIL) || stristr($request->email, "triots")) {
            LogCall('0', '1', "Kullanıcı geçersiz email kullarak kayıt yapmayı denedi.");
            return redirect()->route('giris')->with('error', 'Email geçersiz..');
        }

        $user = DB::table('users')->where('email', $request->email)->where('deleted_at', '!=', NULL);
        if ($user->count() > 0) {
            $user->update(['email' => time()]);
        }

        request()->validate(['email' => 'required|email|unique:users', 'password' => 'required|min:6']);
        $user = new User();
        $user->name = mb_ucfirst($request->name_1) . " " . mb_ucfirst($request->name_2);
        $user->slug = Str::slug($request->name_1 . $request->name_2);
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->email_token = sha1(time());
        $user->notify_email = 1;
        $user->firma_ad = $request->firma_ad;
        if (isset($request->yayinciRef)) {
            $user->refId = $request->yayinciRef;
        }
        $emailDegisken = "2fa_email";
        $user->$emailDegisken = 0;
        if ($user->save()) {
            $kullanici = $user->id;
            foreach (DB::table('bildirim_kategorileri')->get() as $bk) {
                DB::table('bildirim_kullanici')->insert([
                    'user' => $kullanici,
                    'bildirim' => $bk->id,
                    'created_at' => date('YmdHis'),
                ]);
            }
            $to_name = "" . $user->name . " " . $user->last_name;
            $to_email = $user->email;
            $data = array('name' => getSiteName(), "body" => __('general.mesaj-1') . " - " . getSiteName(), 'email' => $user->email, 'token' => $user->email_token);
            Mail::send('emails.active', $data, function ($message) use ($to_name, $to_email) {
                $message->to($to_email, $to_name)->subject(getSiteName() . ' - ' . __('general.mesaj-1')  );
                $message->from(getSiteSenderMail(), __('general.mesaj-1') . ' - ' . getSiteName());
            });
            LogCall($user->id, '1', "Kullanıcı " . $request->firma_ad . "/" . $user->name . " " . $user->last_name . " ismiyle hesabını oluşturdu ve aktivasyon mail'i gönderildi.");

            if ($request->yayinciRef == 27156) {                #------------------------Akınsoft Kafe müşteri kaydı ise il/ilçe bilgilerini biz giriyoruz fatura adresine
                DB::table('fatura_adresleri')->insert([
                    'adres_ismi' => 'İş',
                    'user'       => $kullanici,
                    'il'         => $request->il,
                    'ilce'       => $request->ilce,
                    'created_at' => date('YmdHis')
                ]);
                return redirect()->route('kayit_asoft')->with('success', "Sayın " . $request->firma_ad . ", kaydınız başarıyla yapıldı. E-mail adresinize gönderilen bağlantıya tıklayıp hesabınızı etkinleştirmeniz gerekiyor. Üyelik bilgileriniz AKINSOFT tarafından doğrulandıktan sonra indirimleriniz hesabınıza tanımlanacaktır.");
            } else {
                return redirect()->route('giris')->with(['success'=> __('general.mesaj-2'), 'type'=> 'kayit']);
            }
        } else {
            LogCall('0', '1', "Kullanıcı hesap oluşturmaya çalışırken bir hata meydana geldi.");
            return __('general.hata-1');
        }
    }

    public function active($email, $token)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            LogCall('0', '1', "Kullanıcı kendisine gönderilen linki manipüle ederek sistemi kandırmaya çalıştı.");
            return redirect()->route('giris')->with('error', 'Link beklenen formatta değildi. Aktivasyon başarısız..');
        }


        $userFind = User::where('email', $email);
        if ($userFind->count() > 0) {
            if ($userFind->first()->email == $email and $userFind->first()->email_token == $token) {
                $userFind->update(['email_verified_at' => date('YmdHis')]);
                LogCall($userFind->first()->id, '1', "Kullanıcı hesabını onayladı.");
                return redirect()->route('giris')->with('success', __('general.mesaj-3'));
            } else {
                LogCall($userFind->first()->id, '1', "Kullanıcı hesabını onaylamaya çalışırken hata aldı.");
                return redirect()->route('giris')->with('error', __('general.hata-6'));
            }
        } else {
            LogCall('0', '1', "Kullanıcı hesabını onaylamak istedi fakat böyle bir hesap bulunamadı.");
            return redirect()->route('giris')->with('error', __('general.hata-5'));
        }
    }

    public function google_kayit_ol()
    {
        return Socialite::driver('google')->scopes('email', 'openid')->redirect();
    }

    public function google_kayit_ol_donus(Request $request)
    {
        try {
            $user = Socialite::driver('google')->stateless()->user();
            return registerOrLoginUser($user, 1);
            //return redirect()->route('hesabim');
            exit;
        } catch (\Exception $e) {
            return redirect()->route('giris')->with("error", "Sosyal medya üzerinden giriş esnasında bir sorun meydana geldi, lütfen tekrar deneyin.");
        }
    }

    public function twitch_kayit_ol()
    {
        return Socialite::driver('twitch')->redirect();
    }

    public function twitch_kayit_ol_donus(Request $request)
    {
        try {
            $user = Socialite::driver('twitch')->user();
            return registerOrLoginUser($user, 2);
            //return redirect()->route('hesabim');
            exit;
        } catch (\Exception $e) {
            return redirect()->route('giris')->with("error", "Sosyal medya üzerinden giriş esnasında bir sorun meydana geldi, lütfen tekrar deneyin.");
        }
    }

    public function steam_kayit_ol()
    {
        return Socialite::driver('steam')->redirect();
    }

    public function steam_kayit_ol_donus(Request $request)
    {
        try {
            $user = Socialite::driver('steam')->stateless()->user();
            return registerOrLoginUser($user, 3);
            return redirect()->route('hesabim');
            exit;
        } catch (\Exception $e) {
            return redirect()->route('giris')->with("error", "Sosyal medya üzerinden giriş esnasında bir sorun meydana geldi, lütfen tekrar deneyin.");
        }
    }
}
