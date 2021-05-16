<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UyeController extends Controller
{
    public function index()
    {
        return view('back.pages.users.index');
    }

    public function add(Request $request)
    {
        request()->validate([
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->slug = Str::slug($request->name);
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->email_token = sha1(time());
        if($user->save()) { //aktivasyon mail gönderimi
            $to_name = $user->name;
            $to_email = $user->email;
            $data = array('name'=>getSiteName(), "body" => __('general.mesaj-1') . " - " . getSiteName(), 'email'=>$user->email, 'token'=>$user->email_token);
            Mail::send('emails.active', $data, function($message) use ($to_name, $to_email) {
                $message->to($to_email, $to_name)->subject(__('general.mesaj-1') . ' - ' .  getSiteName());
                $message->from(getSiteSenderMail(), __('general.mesaj-1') . ' - ' .  getSiteName());
            });
            return back()->with('success', __('admin.basariliMetin'));
        } else {
            return __('admin.hata-2');
        }
    }

    public function detail($email)
    {
        return view('back.pages.users.detay')->with('email', $email);
    }

    public function edit(Request $request)
    {

    }
}
