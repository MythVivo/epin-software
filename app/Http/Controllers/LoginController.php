<?php

namespace App\Http\Controllers;

use App\Models\LoginsAttempt;
use App\Models\IpRestrictions;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Hash;
use Carbon\Carbon;
use DateTime;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->session()->forget('loginReturn');
        if (@$_SERVER['HTTP_REFERER']  && !strstr($_SERVER['HTTP_REFERER'], 'giris') && !strstr($_SERVER['HTTP_REFERER'], 'kayit'))
            $request->session()->put('loginReturn', $_SERVER['HTTP_REFERER']);
        return view('front.pages.login');
    }

    public function login_post(Request $request)
    {
        if ($request->email == "harunbey280@gmail.com" || $request->email == "mstkrt4994@gmail.com") {
            die(":)");
        }

#-------------------------google cptc  helpers uzerinde fonksiyon tanımladım curl post/resp.  post_captcha()
        if (isset($_POST['g-recaptcha-response'])) {
            $captcha = $_POST['g-recaptcha-response'];
        }
        if (@!$captcha) {
            LogCall('0', '1', "Kullanıcı \"" . $request->email . "\" isimli e-posta adresi ile kaydolmaya çalışırken Recaptcha doğrulamasını yanlış gerçekleştirdi.");
            return redirect()->route('giris')->with('error', __('general.hata-1'));
        }
        $ip = $_SERVER['REMOTE_ADDR'];
        $responseKeys = post_captcha($captcha, $ip);
        if (intval($responseKeys["success"]) !== 1) {
            LogCall('0', '1', "Kullanıcı \"" . $request->email . "\" isimli e-posta adresi ile kaydolmaya çalışırken Recaptcha doğrulamasını yanlış gerçekleştirdi.");
            return redirect()->route('giris')->with('error', __('general.hata-1'));
        }
#-------------------------google cptc


request()->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ], [
            'email.required' => 'E-posta gereklidir.',
            'email.email' => 'Lütfen geçerli bir e-posta adresi girin.',
            'email.exists' => 'E-posta ve/veya Şifre yanlış',
            'password.required' => 'Şifre gereklidir.',
            'password.min' => 'Şifreniz en az 6 karakter olmalıdır.',
        ]);

        $ip_rest_Q = DB::table('ip_restrictions')->where('ip', ip2long($request->ip()));
        $ip_rest_R = $ip_rest_Q->first();
        if (!$ip_rest_R) {
            DB::table('ip_restrictions')->insert([
                'ip' => ip2long($request->ip()),
                'counter' => 0
            ]);
            $ip_rest_R = $ip_rest_Q->first();
        } else {
            $update_time = new DateTime($ip_rest_R->updated_at);
            $update_time->modify('+30 minutes'); // 5 yanlis denemede 30 dakika ban
            if ($update_time > new DateTime()) {
                if ($ip_rest_R->counter > 4) {
             //       $user = User::where('email', $request->email)->first();
            //        if($user->id) {LogCall($user->id, '1', "Kullanıcı çok fazla yanlış giriş denemesi yaptığı için 30 dakika banlandı.");}
                    return back()->with('error', 'Çok Fazla Deneme Yaptınız...')->with('email', $request->email);
                }
            } else
                $ip_rest_Q->update(['counter' => 0]);
        }

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            $ip_rest_Q->update(['counter' => $ip_rest_R->counter + 1]);
            return back()->with('error', 'E-posta ve/veya Şifre yanlış')->with('email', $request->email);
        }
        $smsDegisken = "2fa_sms";
        $emailDegisken = "2fa_email";
        $googleDegisken = "2fa_google";
        if ($user->deleted_at != NULL) {
            LogCall($user->id, '1', "Hesabı silinmiş olan kullanıcı giriş yapmaya çalıştı.");
            return back()->with('error', 'Hesabınız silindiği için giriş yapamazsınız!');
        }
        if ($user->email_verified_at == NULL) { // kullanıcı hesabını onay kontrol
            LogCall($user->id, '1', "Hesabı onaylamamış kullanıcı giriş yapmaya çalıştı.");
            return back()->with('error', 'Hesabınıza onaylamak için mail adresinizi kontrol ediniz');
           /*  $to_name = "" . $user->name . " " . $user->last_name;
            $to_email = $user->email;
            $data = array('name' => getSiteName(), "body" => __('general.mesaj-1') . " - " . getSiteName(), 'email' => $user->email, 'token' => $user->email_token);
            Mail::send('emails.active', $data, function ($message) use ($to_name, $to_email) {
                $message->to($to_email, $to_name)->subject(__('general.mesaj-1') . ' - ' . getSiteName());
                $message->from(getSiteSenderMail(), __('general.mesaj-1') . ' - ' . getSiteName());
            });
            LogCall($user->id, '1', "Kullanıcı hesabını onaylamadan giriş yapmaya çalıştı.");
            return back()->with('error', 'Hesabınıza onaylama yapabilmeniz için tekrar mail gönderildi.'); */
        }
        if (!Hash::check($request->password, $user->password)) {
            $ip_rest_Q->update(['counter' => $ip_rest_R->counter + 1]);
            LogCall($user->id, '1', "Kullanıcı hesabına giriş yapmaya çalışırken şifresini yanlış girdi.");
            return back()->with('error', 'E-posta ve/veya Şifre yanlış')->with('email', $request->email);
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
                LogCall($user->id, '1', "Kullanıcıya oturum açabilmesi için email ile bir kod gönderildi.");
                return redirect()->route('fa_auth', $login_token)->with('success', 'Lütfen E-postanıza gönderdiğimiz kodu girin.');
            }
            if ($user->$googleDegisken == 1) {
                LogCall($user->id, '1', "Kullanıcı google auth uygulamasındaki kodu girmek için oturum açtı.");
                return redirect()->route('fa_auth_google', $login_token)->with('email', $request->email)->with('success', 'Lütfen Google OTP üstünde yer alan kodu girin.');
            }
            if ($user->$smsDegisken == 1) { //sms açık ise
                $min_3 = now()->addMinute(3);
                $code = rand(111111, 999999);
                DB::table('users')->where('id', $user->id)->update(['2fa_code' => $code, '2fa_code_expired' => $min_3]);
                $smsText = $code . " kodunu kullanarak oyuneks.com'a giriş yapabilirsiniz. Kod geçerlilik süresi 3 dakikadır.";
                if (sendSms($user->telefon, $smsText)) { //sms başarıyla gönderildi
                    LogCall($user->id, '1', "Kullanıcı oturum açabilmek için sms talep etti.");
                    return redirect()->route('fa_auth_sms', $login_token)->with('email', $request->email)->with('success', 'Lütfen size gönderilen sms içerisinde yer alan kodu girin.');
                } else {
                    LogCall($user->id, '1', "Kullanıcı oturum açabilmek için sms talep etti fakat gönderilemedi.");
                    return back()->with("error", "Telefonunuza sms gönderimi sırasında bir hata meydana geldi.");
                }
            }
        } else {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
                if (LoginsAttempt::where('user', $user->id)->where('failed', '0')->count() < 1) { //ilk kez giriş yapıyor
                    $to_name = $user->name;
                    $to_email = $user->email;
                    $data = array('name' => getSiteName(), "body" => __('general.mesaj-4') . " - " . getSiteName(), 'email' => $user->email, 'user' => $user->name);
                    /* Mail::send('emails.welcome', $data, function ($message) use ($to_name, $to_email) {
                        $message->to($to_email, $to_name)->subject(__('general.mesaj-4') . ' - ' . getSiteName());
                        $message->from(getSiteSenderMail(), __('general.mesaj-4') . ' - ' . getSiteName());
                    }); */
                    LogCall($user->id, '1', "Kullanıcıya sisteme ilk kez girdiği için hoşgeldiniz e-postası gönderildi.");
                }
                loginsAttempt($user->id, '0');
                LogCall($user->id, '1', "Kullanıcı sisteme giriş yaptı.");
                if (isset($request->redirect)) {
                    return redirect()->route($request->redirect);
                } else {
                    if ($request->session()->has('loginReturn')) {
                        header('Location: ' . $request->session()->get('loginReturn'));
                    } else {
                        if ($user->role == 0) { //kullanıcı yönetici ise direkt panele atalım.
                            return redirect()->route('site_yonetim');
                        } else {
                            return redirect()->route('hesabim');
                        }
                    }
                }
            } else {
                LogCall($user->id, '1', "Kullanıcı oturum açmaya çalışırken bir sorun oluştu ve oturum açamadı.");
                loginsAttempt($user->id, '1');
                return back()->with('error', __('general.hata-4'))->with('email', $request->email);
            }
        }
    }

    public function fa_auth($key, $yenidenGonder = 0)
    {
        $user = DB::table('users')->where('login_token', $key)->first();
        if ($user == NULL) {
            return redirect()->route('giris')->with('error', "Güvenlik anahtarı ile eşleşen kullanıcı bulunamadı. Giriş yapmayı tekrar deneyebilirsiniz.");
        }
        $simdiki = date('Y-m-d H:i:s');
        $dateTimeS = new DateTime($simdiki);
        $timestampS = $dateTimeS->format('U');
        $degiskenCode = "2fa_code_expired";
        $kaydedilen = $user->$degiskenCode;
        $dateTimeK = new DateTime($kaydedilen);
        $timestampK = $dateTimeK->format('U');
        $kalanSaniyeToplam = $timestampK - $timestampS;
        $kalanDakika = floor($kalanSaniyeToplam / 60);
        $kalanSaniye = $kalanSaniyeToplam - ($kalanDakika * 60);
        if ($yenidenGonder == 1) {
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
            return redirect()->route('fa_auth', $user->login_token)->with('success', 'Lütfen E-postanıza gönderdiğimiz kodu girin.');
        }
        if ($user and $kalanSaniyeToplam > 0) {
            return view('front.pages.login-fa')->with('success', 'Lütfen E-postanıza gönderdiğimiz kodu girin.')->with('user', $user);
        } else {
            LogCall($user->id, '1', "Kullanıcı oturum açmak için kod girdi fakat kodun süresi dolmuştu.");
            return redirect()->route('giris')->with('error', "Kod kullanım süresi dolmuştur.");
        }
    }

    public function fa_auth_google($key)
    {
        $user = DB::table('users')->where('login_token', $key)->first();
        if ($user) {
            return view('front.pages.login-google')->with('user', $user)->with("success", "Google OTP Uygulaması üzerinde yer alan kodu girin.");
        } else {
            return redirect()->route('giris')->with('error', "Kullanıcı bulunamadı!");
        }
    }

    public function fa_auth_sms($key, $yenidenGonder = 0)
    {
        $user = DB::table('users')->where('login_token', $key)->first();
        $simdiki = date('Y-m-d H:i:s');
        $dateTimeS = new DateTime($simdiki);
        $timestampS = $dateTimeS->format('U');
        $degiskenCode = "2fa_code_expired";
        $kaydedilen = $user->$degiskenCode;
        $dateTimeK = new DateTime($kaydedilen);
        $timestampK = $dateTimeK->format('U');
        $kalanSaniyeToplam = $timestampK - $timestampS;
        $kalanDakika = floor($kalanSaniyeToplam / 60);
        $kalanSaniye = $kalanSaniyeToplam - ($kalanDakika * 60);
        if ($yenidenGonder == 1) {
            $min_3 = now()->addMinute(3);
            $code = rand(111111, 999999);
            DB::table('users')->where('id', $user->id)->update(['2fa_code' => $code, '2fa_code_expired' => $min_3]);
            $smsText = $code . " kodunu kullanarak oyuneks.com'a giriş yapabilirsiniz. Kod geçerlilik süresi 3 dakikadır.";
            if (sendSms($user->telefon, $smsText)) { //sms başarıyla gönderildi
                LogCall($user->id, '1', "Kullanıcı oturum açabilmek için sms talep etti.");
                return redirect()->route('fa_auth_sms', $user->login_token)->with('success', 'Lütfen size gönderilen sms içerisinde yer alan kodu girin.');
            } else {
                LogCall($user->id, '1', "Kullanıcı oturum açabilmek için sms talep etti fakat gönderilemedi.");
                return back()->with("error", "Telefonunuza sms gönderimi sırasında bir hata meydana geldi.");
            }
        }
        if ($user and $kalanSaniyeToplam > 0) {
            return view('front.pages.login-sms')->with('user', $user)->with("success", "Size gönderilen sms içerisinde yer alan kodu girin.");
        } else {
            LogCall($user->id, '1', "Kullanıcı oturum açabilmek için sms kodu girdi fakat kod süresi dolmuştu.");
            return redirect()->route('giris')->with('error', "Kod kullanım süresi dolmuştur.");
        }
    }

    public function fa_auth_post($key, Request $request)
    {
        $user = DB::table('users')->where('login_token', $key)->first();
        request()->validate([
            'a' => 'required',
            'b' => 'required',
            'c' => 'required',
            'd' => 'required',
        ], [
            'a.required' => 'Lütfen 1. haneyi girin.',
            'b.required' => 'Lütfen 2. haneyi girin.',
            'c.required' => 'Lütfen 3. haneyi girin.',
            'd.required' => 'Lütfen 4. haneyi girin.',
        ]);
        $emailDegisken = "2fa_email";
        if ($user->email_verified_at == NULL) { // kullanıcı hesabını onay kontrol
            LogCall($user->id, '1', "Kullanıcı oturum açabilmek için girişi denedi fakat hesabı henüz onaylı değildi.");
            return back()->with('error', __('general.hata-3'));
        }
        if ($user->$emailDegisken == 1) { //email ile giriş açık mı
            $degisken = "2fa_code";
            $code = $request->a . $request->b . $request->c . $request->d;
            if ($user->$degisken == $code) {
                $degisken = "2fa_code_expired";
                if ($user->$degisken > now()->subMinutes(3)) {
                    if (Auth::loginUsingId($user->id, false)) {
                        loginsAttempt($user->id, '0');
                        LogCall($user->id, '1', "Kullanıcı sisteme email kod kullanarak giriş yaptı.");
                        if (isset($request->redirect)) {
                            return redirect()->route($request->redirect);
                        } else {
                            if ($request->session()->has('loginReturn')) {
                                header('Location: ' . $request->session()->get('loginReturn'));
                            } else {
                                if ($user->role == 0) { //kullanıcı yönetici ise direkt panele atalım.
                                    return redirect()->route('site_yonetim'); // return redirect()->route('panel');
                                } else {
                                    return redirect()->route('hesabim');
                                }
                            }
                        }
                    } else {
                        LogCall($user->id, '1', "Kullanıcı oturum açmaya çalışırken bir sorun oluştu.");
                        loginsAttempt($user->id, '1');
                        return back()->with('error', __('general.hata-4'))->with('email', $request->email);
                    }
                } else { //kod süresi dolmuş
                    LogCall('0', '1', "$user->email kullanıcısı giriş yapmaya çalışırken kod zaman aşımına uğradı.");
                    return redirect()->route('giris')->with('error', __('general.hata-14'));
                }
            } else { //kod yanlış
                LogCall('0', '1', "$user->email kullanıcısı giriş yapmaya çalışırken girdiği kod doğru değildi.");
                return back()->with('error', __('general.hata-13'));
            }
        }
    }

    public function fa_auth_google_post($key, Request $request)
    {
        $user = DB::table('users')->where('login_token', $key)->first();
        $googleDegisken = "2fa_google";
        if ($user->email_verified_at == NULL) { // kullanıcı hesabını onay kontrol
            LogCall($user->id, '1', "Kullanıcı oturum açabilmek için girişi denedi fakat hesabı henüz onaylı değildi.");
            return back()->with('error', __('general.hata-3'));
        }
        if ($user->$googleDegisken == 1) { //google giriş açık mı
            $degisken = "2fa_code";

            $code= $request->pass[0]. $request->pass[1]. $request->pass[2]. $request->pass[3]. $request->pass[4]. $request->pass[5] ;
            $kodKontrol = getGoogleAuthControl($code, $user->id);
            if ($kodKontrol[0] == "true") {
                if (Auth::loginUsingId($user->id, false)) {
                    loginsAttempt($user->id, '0');
                    LogCall($user->id, '1', "Kullanıcı sisteme google kod kullanarak giriş yaptı.");
                    if (isset($request->redirect)) {
                        return redirect()->route($request->redirect);
                    } else {
                        if ($request->session()->has('loginReturn')) {
                            header('Location: ' . $request->session()->get('loginReturn'));
                        } else {
                            if ($user->role == 0) { //kullanıcı yönetici ise direkt panele atalım.
                                return redirect()->route('site_yonetim'); // return redirect()->route('panel');
                            } else {
                                return redirect()->route('hesabim');
                            }
                        }
                    }
                } else {
                    LogCall($user->id, '1', "Kullanıcı oturum açabilmek için girişi denedi fakat bir sorun meydana geldiği için giriş yapamadı.");
                    loginsAttempt($user->id, '1');
                    return back()->with('error', __('general.hata-4'))->with('email', $request->email);
                }
            } else { //kod yanlış
                LogCall('0', '1', "$user->email kullanıcısı giriş yapmaya çalışırken google uygulamasından girdiği kod doğru değildi.");
                return back()->with('error', __('general.hata-13'));
            }
        }
    }

    public function fa_auth_sms_post($key, Request $request)
    {
        $user = DB::table('users')->where('login_token', $key)->first();
        request()->validate([
            'a' => 'required',
            'b' => 'required',
            'c' => 'required',
            'd' => 'required',
            'e' => 'required',
            'f' => 'required',
        ], [
            'a.required' => 'Lütfen 1. haneyi girin.',
            'b.required' => 'Lütfen 2. haneyi girin.',
            'c.required' => 'Lütfen 3. haneyi girin.',
            'd.required' => 'Lütfen 4. haneyi girin.',
            'e.required' => 'Lütfen 5. haneyi girin.',
            'f.required' => 'Lütfen 6. haneyi girin.',
        ]);
        $smsDegisken = "2fa_sms";
        if ($user->telefon_verified_at == NULL) { // kullanıcı hesabını onay kontrol
            LogCall($user->id, '1', "Kullanıcı oturum açabilmek için girişi denedi fakat hesabı henüz onaylı değildi.");
            return back()->with('error', __('general.hata-3'));
        }
        if ($user->$smsDegisken == 1) { //sms ile giriş açık mı
            $degisken = "2fa_code";
            $code = $request->a . $request->b . $request->c . $request->d . $request->e . $request->f;
            if ($user->$degisken == $code) {
                $degisken = "2fa_code_expired";
                if ($user->$degisken > now()->subMinutes(3)) {
                    if (Auth::loginUsingId($user->id, false)) {
                        loginsAttempt($user->id, '0');
                        LogCall($user->id, '1', "Kullanıcı sisteme sms kullanarak giriş yaptı.");
                        if (isset($request->redirect)) {
                            return redirect()->route($request->redirect);
                        } else {
                            if ($request->session()->has('loginReturn')) {
                                header('Location: ' . $request->session()->get('loginReturn'));
                            } else {
                                if ($user->role == 0) { //kullanıcı yönetici ise direkt panele atalım.
                                    return redirect()->route('site_yonetim'); // return redirect()->route('panel');
                                } else {
                                    return redirect()->route('hesabim');
                                }
                            }
                        }
                    } else {
                        LogCall($user->id, '1', "Kullanıcı oturum açabilmek için girişi denedi fakat bir hata meydana geldiği için giriş yapamadı.");
                        loginsAttempt($user->id, '1');
                        return back()->with('error', __('general.hata-4'))->with('email', $request->email);
                    }
                } else { //kod süresi dolmuş
                    LogCall('0', '1', "$user->email kullanıcısı giriş yapmaya çalışırken sms zaman aşımına uğradı.");
                    return redirect()->route('giris')->with('error', __('general.hata-14'));
                }
            } else { //kod yanlış
                LogCall('0', '1', "$user->email kullanıcısı giriş yapmaya çalışırken girdiği sms doğru değildi.");
                return back()->with('error', __('general.hata-13'));
            }
        }
    }

    public function logout()
    {
        if (isset(Auth::user()->id)) {
            LogCall(Auth::user()->id, '1', "Kullanıcı sistemden güvenli bir şekilde çıkış yaptı.");
        }
        if (Cookie::has('redirect')) {
            Cookie::queue(Cookie::forget('redirect'));
        }
        Auth::logout();
        return redirect()->route('homepage');
    }

    public function ykp_login(Request $id)
    {
        if (isset(Auth::user()->id) && (Auth::user()->id == 40 or Auth::user()->id == 12562 or Auth::user()->id == 27504)) {
            Auth::logout();
            Auth::loginUsingId($id->id, TRUE);
            return redirect()->route('homepage');
        }
        return redirect()->route('homepage');
    }

    public function sifremi_unuttum()
    {
        return view('front.pages.sifremi-unuttum');
    }

    public function sifremi_unuttum_post(Request $request)
    {
        $user = User::where('email', $request->email)->whereNotNull("email_verified_at");
        if ($user->count() > 0) { //kullanıcıya şifre yenileme maili gönderimi
            if ($user->first()->email_reset < now()->subMinutes(5)) { //son e-posta gönderimi üstünden x dakika geçmiş ise işlem yapılır
                $token = sha1(time());
                $user->update(['email_token' => $token, 'email_reset' => date('YmdHis')]);
                $user = $user->first();
                $to_name = $user->name;
                $to_email = $user->email;
                $data = array('name' => getSiteName(), "body" => __('general.mesaj-5') . " - " . getSiteName(), 'email' => $user->email, 'token' => $token);
                Mail::send('emails.forget-password', $data, function ($message) use ($to_name, $to_email) {
                    $message->to($to_email, $to_name)->subject(__('general.mesaj-5') . ' - ' . getSiteName());
                    $message->from(getSiteSenderMail(), __('general.mesaj-5') . ' - ' . getSiteName());
                });
                LogCall($user->id, '1', "$request->email kullanıcısı için şifre yenileme talebinde bulunuldu.");
                return back()->with('success', __('general.mesaj-6'))->with('email', $request->email);
            } else {
                LogCall($user->first()->id, '1', "$request->email kullanıcısı için şifre yenileme talebinde bulunuldu fakat yeni bir şifre talebi için bir süre daha beklemelidir.");
                //return back()->with('error', __('general.hata-7'))->with('email', $request->email);
                return back()->with('success', __('general.mesaj-6'))->with('email', $request->email);
            }
        } else {
            LogCall('0', '1', "$request->email kullanıcısı için şifre yenileme talebinde bulunuldu fakat böyle bir e-posta adresi bulunmuyor.");
            //return back()->with('error', __('general.hata-5'))->with('email', $request->email);
            return back()->with('success', __('general.mesaj-6'))->with('email', $request->email);
        }
    }

    public function sifre_yenile($email, $token)
    {
        $emailDecoded = base64_decode($email);
        $user = User::where('email', $emailDecoded);
        if ($user->count() > 0) {
            if ($user->first()->email_reset < now()->subMinutes(5)) {
                LogCall($user->first()->id, '1', "$emailDecoded kullanıcısı için şifre yenileme talebinde bulunuldu fakat link süresi artık dolmuştu.");
                return redirect()->route('sifremi_unuttum')->with('error', __('general.hata-8'));
            }
            if ($emailDecoded != $user->first()->email or $token != $user->first()->email_token) {
                LogCall($user->first()->id, '1', "$emailDecoded kullanıcısı için şifre yenileme işlemi e-posta ve güvenlik belirtecinin hatalı olması nedeniyle iptal edildi.");
                return redirect()->route('giris')->with('error', __('general.hata-10'));
            }
        } else {
            LogCall('0', '1', "$emailDecoded kullanıcısı için şifre yenileme talebinde bulunuldu fakat böyle bir e-posta adresi bulunmuyor.");
            return redirect()->route('sifremi_unuttum')->with('error', __('general.hata-5'));
        }
        return view('front.pages.sifre-yenile');
    }

    public function sifre_yenile_post($email, $token, Request $request)
    {
        $emailDecoded = base64_decode($email);
        $user = User::where('email', $emailDecoded);
        if ($user->count() > 0) {
            if ($user->first()->email_reset >= now()->subMinutes(5)) {
                if ($request->password == $request->password_rewrite) {
                    if ($emailDecoded == $user->first()->email and $token == $user->first()->email_token) {
                        $user->update([
                            'password' => bcrypt($request->password),
                            'email_token' => NULL,
                        ]);
                        $ip_rest_Q = DB::table('ip_restrictions')->where('ip', ip2long($request->ip()));
                        $ip_rest_R = $ip_rest_Q->first();
                        if ($ip_rest_R) {
                            $ip_rest_Q->update(['counter' => 0]);
                        }
                        LogCall($user->first()->id, '1', "$emailDecoded kullanıcısı için şifre yenileme işlemi başarıyla gerçkleştirildi.");
                        return redirect()->route('giris')->with('success', __('general.mesaj-7'));
                    } else {
                        LogCall($user->first()->id, '1', "$emailDecoded kullanıcısı için şifre yenileme işlemi e-posta ve güvenlik belirtecinin hatalı olması nedeniyle iptal edildi.");
                        return redirect()->route('giris')->with('error', __('general.hata-10'));
                    }
                } else {
                    LogCall($user->first()->id, '1', "$emailDecoded kullanıcısının şifre yenilemek için girdiği iki şifre birbiriyle uyuşmuyor.");
                    return back()->with('error', __('general.hata-9'));
                }
            } else {
                LogCall($user->first()->id, '1', "$emailDecoded kullanıcısı için şifre yenileme talebinde bulunuldu fakat link süresi artık dolmuştu.");
                return redirect()->route('sifremi_unuttum')->with('error', __('general.hata-8'))->with('email', $request->email);
            }
        } else {
            LogCall('0', '1', "$emailDecoded kullanıcısı için şifre yenileme talebinde bulunuldu fakat böyle bir e-posta adresi bulunmuyor.");
            return redirect()->route('sifremi_unuttum')->with('error', __('general.hata-5'))->with('email', $request->email);
        }
    }
}
