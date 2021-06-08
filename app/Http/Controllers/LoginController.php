<?php

namespace App\Http\Controllers;

use App\Models\LoginsAttempt;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Mail;

class LoginController extends Controller
{
    public function login()
    {
        return view('front.pages.login');
    }

    public function login_post(Request $request)
    {
        request()->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required|min:6'
        ], [
            'email.required' => 'E-posta gereklidir.',
            'email.email' => 'Lütfen geçerli bir e-posta adresi girin.',
            'email.exists' => 'Böyle bir e-posta bulunamadı.',
            'password.required' => 'Şifre gereklidir.',
            'password.min' => 'Şifreniz en az 6 karakter olmalıdır.',
        ]);
        $user = User::where('email', $request->email)->first();
        $smsDegisken = "2fa_sms";
        $emailDegisken = "2fa_email";
        if ($user->email_verified_at == NULL) { // kullanıcı hesabını onay kontrol
            return back()->with('error', __('general.hata-3'));
        }

        if ($user->$smsDegisken == 1 or $user->$emailDegisken == 1) { //sms veya email ile giriş açık mı

        } else {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
                if (LoginsAttempt::where('user', $user->id)->where('failed', '0')->count() < 1) { //ilk kez giriş yapıyor
                    $to_name = $user->name;
                    $to_email = $user->email;
                    $data = array('name' => getSiteName(), "body" => __('general.mesaj-4') . " - " . getSiteName(), 'email' => $user->email, 'user' => $user->name);
                    Mail::send('emails.welcome', $data, function ($message) use ($to_name, $to_email) {
                        $message->to($to_email, $to_name)->subject(__('general.mesaj-4') . ' - ' . getSiteName());
                        $message->from(getSiteSenderMail(), __('general.mesaj-4') . ' - ' . getSiteName());
                    });
                    LogCall($user->id, '1', "Kullanıcıya sisteme ilk kez girdiği için hoşgeldiniz e-postası gönderildi.");
                }
                loginsAttempt($user->id, '0');
                LogCall($user->id, '1', "Kullanıcı sisteme giriş yaptı.");
                if(isset($request->redirect)) {
                    return redirect()->route($request->redirect);
                } else {
                    return redirect()->route('hesabim');
                }

            } else {
                loginsAttempt($user->id, '1');
                return back()->with('error', __('general.hata-4'))->with('email', $request->email);
            }
        }


    }

    public function logout()
    {
        LogCall(Auth::user()->id, '1', "Kullanıcı sistemden güvenli bir şekilde çıkış yaptı.");
        if(Cookie::has('redirect')) {
            Cookie::queue(Cookie::forget('redirect'));
        }
        Auth::logout();
        return redirect()->route('homepage');
    }

    public function sifremi_unuttum()
    {
        return view('front.pages.sifremi-unuttum');
    }
}
