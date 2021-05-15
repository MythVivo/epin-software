<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function register()
    {
        return view('front.pages.register');
    }

    public function register_post(Request $request)
    {
        request()->validate([
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6'
        ], [
            'email.required' => 'E-posta gereklidir.',
            'email.email' => 'Lütfen geçerli bir e-posta adresi girin',
            'email.exists' => 'Böyle bir e-posta zaten var',
            'password.required' => 'Şifre gereklidir.',
            'password.min' => 'Şifreniz en az 6 karakter olmalıdır.',
        ]);

        $user = new User();
        $user->name = $request->name_1 . " " . $request->name_2;
        $user->slug = Str::slug($request->name_1.$request->name_2);
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->email_token = sha1(time());
        if($user->save()) { //aktivasyon mail gönderimi
            $to_name = "".$user->name." ".$user->last_name;
            $to_email = $user->email;
            $data = array('name'=>getSiteName(), "body" => __('general.mesaj-1') . " - " . getSiteName(), 'email'=>$user->email, 'token'=>$user->email_token);
            $gonderim = Mail::send('emails.active', $data, function($message) use ($to_name, $to_email) {
                $message->to($to_email, $to_name)->subject(__('general.mesaj-1') . ' - ' .  getSiteName());
                $message->from(getSiteSenderMail(), __('general.mesaj-1') . ' - ' .  getSiteName());
            });
        } else {
            return __('general.hata-1');
        }
    }
}