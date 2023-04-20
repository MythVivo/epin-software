<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Auth;

class UyeController extends Controller
{
    public function index()
    {
        return view('back.pages.users.index');
    }

    public function add(Request $request)
    {
        request()->validate([
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->slug = Str::slug($request->name);
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->email_token = sha1(time());
        if ($user->save()) { //aktivasyon mail gönderimi
            $to_name = $user->name;
            $to_email = $user->email;
            $data = array('name' => getSiteName(), "body" => __('general.mesaj-1') . " : " . getSiteName(), 'email' => $user->email, 'token' => $user->email_token);
            Mail::send('emails.active', $data, function ($message) use ($to_name, $to_email) {
                $message->to($to_email, $to_name)->subject(__('general.mesaj-1') . ' : ' . getSiteName());
                $message->from(getSiteSenderMail(), __('general.mesaj-1') . ' : ' . getSiteName());
            });
            LogCall(Auth::user()->id, '4', "Kullanıcı " . $request->name . " isimli yeni bir kullanıcı ekledi.");
            return back()->with('success', __('admin.basariliMetin'));
        } else {
            return __('admin.hata-2');
        }
    }

    public function uye_aktivite()
    {
        return view('back.pages.users.aktivite');
    }

    public function uye_ozel_fiyat()
    {
        return view('back.pages.users.ozel_fiyat');
    }


    public function detail($email)
    {
        return view('back.pages.users.detay')->with('email', $email);
    }

    public function edit(Request $request)
    {
        if ($request->amount != "") { //bakiye ekleme işlemi
            DB::table('odemeler')->insert([
                'user' => $request->id,
                'amount' => $request->amount,
                'channel' => 4,
                'description' => $request->description,
                'status' => 1,
                'created_at' => date('YmdHis'),
                'islemYapan' => Auth::user()->id,
            ]); //ödeme kaydı gerçekleştiriliyor
            $user = DB::table('users')->where('id', $request->id);

            #----------------------------------------------------------------------------------------------------------
            if ($request->amount < 0) { // bakiye çıkarma işlemi ise

                if (abs($request->amount) > $user->first()->bakiye) { # fiyat bakiyeden büyükse
                    $bak_dus = 0;                     # bakiyede ne varsa çek sıfırla
                    $cek_dus = $user->first()->bakiye_cekilebilir - (abs($request->amount) - $user->first()->bakiye); # kalanı cbakiyeden al
                } else {
                    $bak_dus = $user->first()->bakiye - abs($request->amount); # fiyat bakiyeden küçükse bakiyeden fiyatı çıkar
                    $cek_dus = $user->first()->bakiye_cekilebilir; # c.bakiye ye dokunma
                }

                $user->update(['bakiye' => $bak_dus, 'bakiye_cekilebilir' => $cek_dus]);
            }
            #----------------------------------------------------------------------------------------------------------
            else { // Çıkarma işlemi değilse
                $user->update(['bakiye' => $user->first()->bakiye + $request->amount]);
            }

            LogCall(Auth::user()->id, '4', "Kullanıcı " . $request->id . " id'li kullanıcıya manuel olarak bakiye ekledi. ($request->amount,)");
            return back()->with("success", __('general.mesaj-9'));
        }

        if ($request->amount1 != "") {
            DB::table('odemeler')->insert([
                'user' => $request->id,
                'amount' => $request->amount1,
                'channel' => 5,
                'description' => $request->description1,
                'status' => 1,
                'created_at' => date('YmdHis'),
                'islemYapan' => Auth::user()->id,
            ]); //ödeme kaydı gerçekleştiriliyor
            $user = DB::table('users')->where('id', $request->id);
            $user->update([
                'bakiye_cekilebilir' => $user->first()->bakiye_cekilebilir + $request->amount1,
            ]);
            LogCall(Auth::user()->id, '4', "Kullanıcı " . $request->id . " id'li kullanıcıya manuel olarak cekilebilir bakiye ekledi. ($request->amount1)");
            return back()->with("success", __('general.mesaj-9'));
        }
        if (isset($request->notify_sms)) {
            $notify_sms = 1;
        } else {
            $notify_sms = 0;
        }
        if (isset($request->notify_email)) {
            $notify_email = 1;
        } else {
            $notify_email = 0;
        }
        $name = "2fa_sms";
        $name1 = "2fa_email";
        $name2 = "2fa_google";
        if (isset($request->$name)) {
            $fa_sms = 1;
        } else {
            $fa_sms = 0;
        }
        if (isset($request->$name1)) {
            $fa_email = 1;
        } else {
            $fa_email = 0;
        }

        if (isset($request->$name2)) {
            $fa_google = 1;
        } else {
            $fa_google = 0;
        }



        $request->refer = isset($request->refer) ? $request->refer : 0;

        $telef = preg_replace("/\D/", '', $request->telefon);
        $telef = $telef == '' ? NULL : $telef;

        $updateVars = [
            'name' => $request->name,
            'email' => $request->email,
            'telefon' => $telef, //$request->telefon,
            'tcno' => $request->tcno,
            'dogum_tarihi' => $request->dogum_tarihi,
            'cinsiyet' => $request->cinsiyet,
            'notify_sms' => $notify_sms,
            'notify_email' => $notify_email,
            '2fa_email' => $fa_email,
            '2fa_sms' => $fa_sms,
            '2fa_google' => $fa_google,
            'role' => $request->role,
            'pazar_komisyon' => $request->pazar_komisyon,
            'alisIzin' => $request->alisIzin,
            'para_cek_kom' => $request->pcekkom,
            'refId' => $request->refer,
            'ilan_izin'=>$request->satis_izin
        ];

        if (isset($request->group)) {
            $updateVars['groupId'] = $request->group;
        }
        DB::table('users')->where('id', $request->id)->update($updateVars);
        if ($request->password != '') {
            if ($request->password == $request->password_rewrite) {
                DB::table('users')->where("id", $request->id)->update([
                    "password" => bcrypt($request->password),
                ]);
            } else {
                return back()->with("error", __('general.hata-11'));
            }
        }
        if (isset($request->kullanici_grubu) and $request->kullanici_grubu != 0) {
            if (DB::table('user_group_users')->where('user', $request->id)->count() > 0) {
                DB::table('user_group_users')->where('user', $request->id)->delete();
            }
            DB::table('user_group_users')->insert([
                'user_group' => $request->kullanici_grubu,
                'user' => $request->id,
                'created_at' => date('YmdHis'),
            ]);
        }
        LogCall(Auth::user()->id, '4', "Yönetici " . $request->id . " id'li kullanıcıyı düzenledi.");
        return back()->with("success", __('general.mesaj-9'));
    }

    public
    function kullanici_gruplari()
    {
        return view('back.pages.kullanici-gruplari.index');
    }

    public
    function kullanici_gruplari_add(Request $request)
    {
        DB::table('user_group')->insert([
            'title' => $request->title,
            'text' => $request->text,
            'created_at' => date('YmdHis'),
        ]);
        $lastId = DB::getPdo()->lastInsertId();
        foreach ($request->pages as $page) {
            DB::table('user_group_pages')->insert([
                'user_group' => $lastId,
                'page' => $page,
                'created_at' => date('YmdHis'),
            ]);
        }
        LogCall(Auth::user()->id, '4', "Kullanıcı " . $request->title . " isimli yeni bir kullanıcı grubu oluşturdu.");
        return back()->with("success", __('general.mesaj-9'));
    }

    public
    function kullanici_gruplari_edit(Request $request)
    {
        DB::table('user_group')->where('id', $request->id)->update([
            'title' => $request->title,
            'text' => $request->text,
        ]);
        DB::table('user_group_pages')->where('user_group', $request->id)->delete();
        foreach ($request->pages as $page) {
            DB::table('user_group_pages')->insert([
                'user_group' => $request->id,
                'page' => $page,
                'created_at' => date('YmdHis'),
            ]);
        }
        LogCall(Auth::user()->id, '4', "Kullanıcı " . $request->title . " isimli kullanıcı grubunu düzenledi.");
        return back()->with("success", __('general.mesaj-9'));
    }
}
