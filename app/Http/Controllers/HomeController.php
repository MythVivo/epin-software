<?php


namespace App\Http\Controllers;

error_reporting(E_PARSE);

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use \Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use function PHPUnit\Framework\fileExists;


class HomeController extends Controller
{
    public function hesabim()
    {
        return view('front.pages.hesabim');
    }

    public function hesap_onayla()
    {
        return view('front.pages.hesap-onayla');
    }

    public function email_onayla(Request $request)
    {
        if (Auth::user()->email_verified_at == NULL) {
            $token = sha1(time());
            DB::table('users')->where('id', Auth::user()->id)->update(['email' => $request->email, 'email_token' => $token]);
            $to_name = Auth::user()->name;
            $to_email = $request->email;
            $data = array('name' => getSiteName(), "body" => "E-postanızı doğrulayın" . " - " . getSiteName(), 'email' => $request->email, 'token' => $token);
            Mail::send('emails.active', $data, function ($message) use ($to_name, $to_email) {
                $message->to($to_email, $to_name)->subject("E-postanızı doğrulayın" . ' - ' . getSiteName());
                $message->from(getSiteSenderMail(), "E-postanızı doğrulayın" . ' - ' . getSiteName());
            });
            LogCall(Auth::user()->id, '1', "Kullanıcıya hesabını onaylaması için bir e-posta gönderildi.");
            return back()->with("success", "E-postanızı onaylamak için girmiş olduğunuz e-posta adresine bir mail gönderilmiştir.");
        } else {
            LogCall(Auth::user()->id, '1', "Kullanıcıya hesabını onaylaması için bir e-posta gönderilemedi çünkü hesabı zaten onaylı.");
            return back()->with("error", "E-postanız zaten onaylı!");
        }
    }

    public function telefon_onayla(Request $request)
    {
        if (Auth::user()->telefon_verified_at == NULL) {
            $regex = $request->telefon_country == 90 ? '/^[1-9][0-9]{7,}$/' : '/^[0-9]{8,}$/';
            if (!preg_match($regex, $request->telefon, $matches)) {
                LogCall(Auth::user()->id, '1', "Kullanıcı uygunsuz telefon numarasi onaylamya calisti");
                return back()->with("error", "Telefon Numarası Yanlış! (Başında 0 olmadan giriniz)");
            }
            $findUser = DB::table('users')->where('telefon', $request->telefon)->where('id', '!=', Auth::user()->id);
            if ($findUser->count()) {
                LogCall(Auth::user()->id, '1', "Kullanıcı varolan telefon numarası onaylamaya calıştı");
                return back()->with("error", "Telefon Numarası sistemde kayıtlı!");
            }

            $res = DB::table('users')->where('id', Auth::user()->id)->update(['telefon' => $request->telefon, 'telefon_country' => $request->telefon_country]);

            if ($request->telefon_country == "90") { //eğer türkiye ise otomatik doğrulama çalışır
                $verifyNumber = rand(111111, 999999);
                DB::table('users')->where('id', Auth::user()->id)->update(['telefon_code' => $verifyNumber, 'telefon_code_expired_at' => date('YmdHis')]);
                $smsText = "Sayın " . Auth::user()->name . ",  telefon numaranızı onaylamak için kodunuz : " . $verifyNumber . " . Kod geçerlilik süresi 5 dakikadır.";
                if (sendSms($request->telefon, $smsText)) {
                    LogCall(Auth::user()->id, '1', "Kullanıcıya hesabını onaylaması için bir sms gönderildi.");
                    return back()->with("success", "Telefonunuza bir kod gönderilmiştir.")->with('smsKod', '1');
                } else {
                    LogCall(Auth::user()->id, '1', "Kullanıcıya hesabını onaylaması için bir sms gönderilmek istendi fakat sms  servisinde bir hata meydana geldi.");
                    return back()->with("error", "Telefonunuza sms gönderimi sırasında bir hata meydana geldi.");
                }
            } else { //yaabancı numara ise manuel doğrulama kaydı yapılır
                $verifyNumber = rand(111111, 999999);
                $kayit = DB::table('users')->where('id', Auth::user()->id)->update(['telefon_country' => $request->telefon_country, 'telefon_verified_at_first' => date('Ymdhis'), 'telefon_code' => $verifyNumber, 'telefon_code_expired_at' => date('YmdHis')]);
                if ($kayit) {
                    LogCall(Auth::user()->id, '1', "Kullanıcıya hesabını onaylaması için bir kod verildi ve manuel onaylamaya yönlendirildi.");
                    return back()->with("success", "'" . $verifyNumber . "' kodunu '+90 (850) 308 00-07' numarasına whatsapp üzerinden ad soyad bilginiz ile ileterek manuel onaylama talep ediniz.");
                } else {
                    LogCall(Auth::user()->id, '1', "Kullanıcıya hesabını onaylaması için bir kod verilmek istendi fakat bir hata meydana geldi.");
                    return back()->with("error", "Manuel onaylama servisinde bir hata meydana geldi.");
                }
            }
        } else {
            LogCall(Auth::user()->id, '1', "Kullanıcıya hesabını onaylaması için bir sms gönderilmek istendi fakat hesabın telefon onayı zaten vardı.");
            return back()->with("error", "Telefonunuz zaten onaylı!");
        }
    }

    public function telefon_onayla_kod(Request $request)
    {
        $kod = $request->kod;
        $date = strtotime(date('Y-m-d H:i:s') . '- 5 minute');

        if (strtotime(Auth::user()->telefon_code_expired_at) > $date) {
            if (Auth::user()->telefon_code == $kod) {
                DB::table('users')->where('id', Auth::user()->id)->update(['telefon_verified_at' => date('YmdHis')]);
                LogCall(Auth::user()->id, '1', "Kullanıcı hesabın telefon onayını başarıyla gerçekleştirdi.");
                if (Auth::user()->refId == 32282) {
                    $prev = DB::table('odemeler')->where('user', Auth::user()->id)->whereNull('deleted_at');
                    if ($prev->count() == 0 || true) {
                        $chance = rand(0, 100);
                        $tutar = $chance < 3 ? rand(80, 100) : ($chance < 6 ? rand(60, 79) : ($chance < 12 ? rand(40, 79) : rand(10, 20))); // Dagılım Kullanrak 5k userda
                        DB::table('odemeler')->insert([
                            'user' => Auth::user()->id,
                            'amount' => $tutar,
                            'channel' => 3,
                            'description' => "GIST2022 kodu kullanılarak yükleme yapıldı.",
                            'status' => 0,
                            'created_at' => date('YmdHis')
                        ]); //ödeme kaydı gerçekleştiriliyor
                        return back()->with("success", "Telefon onaylama işleminiz başarılı. GIST Bonusu ($tutar TL) Operatör onayından sonra hesabınıza eklenecek.");
                    } else {
                        //echo 'dup';
                    }
                }
                return back()->with("success", "Telefon onaylama işleminiz başarılı.");
            } else {
                LogCall(Auth::user()->id, '1', "Kullanıcı hesabın telefon onaylaması için girdiği sms kodu yanlıştı.");
                return back()->with("error", "Onaylama işlemi için girdiğiniz kod yanlıştır.")->with('smsKod', '1');
            }
        } else {
            LogCall(Auth::user()->id, '1', "Kullanıcı hesabın telefon onaylaması için girdiği sms kodunun süresi dolmuştu.");
            return back()->with("error", "Onaylama işlemini size ayrılan süre içerisinde gerçekleştirin.");
        }
    }

    public function kimlik_onayla(Request $request)
    {
        if (Auth::user()->tc_verified_at == NULL) {
            if (isset($request->notTr)) { //yabancı kimlik onayı ise
                $dgun = (int)$request->dgun;
                $day = (int)$request->day;
                if ($day < 10) {
                    $day = "0" . $day;
                }
                $dyil = (int)$request->dyil;
                if ($dgun < 10) {
                    $dgun = "0" . $dgun;
                }
                $dTarih = $dyil . $day . $dgun;
                if ($request->hasFile('image')) {
                    $destinationPath = "secret/kimlik";
                    $file = $request->image;
                    $title = Str::slug($request->ad) . "-" . Str::slug($request->soyad) . "-" . Auth::user()->id;
                    $fileName = $title . "-" . time() . '.' . $file->clientExtension();
                    $file->move($destinationPath, $fileName);
                } else {
                    $fileName = "";
                }
                $ad = karakterDuzelt(trim($request->ad));
                $soyad = karakterDuzelt(trim($request->soyad));
                DB::table('users')->where('id', Auth::user()->id)->update([
                    'tc_image' => $fileName,
                    'tc_verified_at_first' => date('YmdHis'),
                    'tcno' => "11111111111",
                    'yabanci' => '1',
                    'dogum_tarihi' => $dTarih,
                    'name' => $ad . " " . $soyad
                ]);
                LogCall(Auth::user()->id, '1', "Kullanıcı kimlik bilgilerini devlet sistemi üstünden başarıyla onaylattı ve yönetici onayına gönderdi.");
                return back()->with("success", "Tebrikler, kimlik bilgileriniz başarıyla kaydedildi ve yönetici onayına gönderildi.");
            }

            ini_set('default_socket_timeout', 600);
            $tc = (int)$request->tcno;
            $ad = karakterDuzelt(trim($request->ad));
            $soyad = karakterDuzelt(trim($request->soyad));
            $dgun = (int)$request->dgun;
            $day = (int)$request->day;
            if ($day < 10) {
                $day = "0" . $day;
            }
            $dyil = (int)$request->dyil;
            $dTarih = $dyil . $day . $dgun;
            $veriler = array(
                'TCKimlikNo' => $tc,
                'Ad' => $ad,
                'Soyad' => $soyad,
                'SoyadYok' => false,
                'DogumGun' => $dgun,
                'DogumGunYok' => false,
                'DogumAy' => $day,
                'DogumAyYok' => false,
                'DogumYil' => $dyil,
            );
            if ($request->tip == 0) { //yeni kimlik
                $serino = $request->serino;
                $veriler["TCKKSeriNo"] = $serino;
            } else { //eski kimlik
                $CuzdanSeri = $request->CuzdanSeri;
                $CuzdanNo = $request->CuzdanNo;
                $veriler["CuzdanSeri"] = $CuzdanSeri;
                $veriler["CuzdanNo"] = $CuzdanNo;
            }


            $baglan = new \SoapClient("https://tckimlik.nvi.gov.tr/Service/KPSPublicV2.asmx?WSDL");
            $sonuc = $baglan->KisiVeCuzdanDogrula($veriler);
            if ($sonuc->KisiVeCuzdanDogrulaResult) {
                if ($dgun < 10) {
                    $dgun = "0" . $dgun;
                }
                $dTarih = $dyil . $day . $dgun;
                if ($request->hasFile('image')) {
                    $destinationPath = "secret/kimlik";
                    $file = $request->image;
                    $title = Str::slug($request->ad) . "-" . Str::slug($request->soyad) . "-" . Auth::user()->id;
                    $fileName = $title . "-" . time() . '.' . $file->clientExtension();
                    $file->move($destinationPath, $fileName);
                } else {
                    $fileName = "";
                }
                DB::table('users')->where('id', Auth::user()->id)->update([
                    'tc_image' => $fileName,
                    'tc_verified_at' => date('YmdHis'),
                    'tcno' => $request->tcno,
                    'dogum_tarihi' => $dTarih,
                    'name' => $ad . " " . $soyad
                ]);
                LogCall(Auth::user()->id, '1', "Kullanıcı kimlik bilgilerini devlet sistemi üstünden başarıyla onaylattı ve yönetici onayına gönderdi.");
                return back()->with("success", "Tebrikler, kimlik bilgileriniz doğrulandı ve başarıyla kaydedildi. Keyifli alışverişler dileriz.");
            } else {
                LogCall(Auth::user()->id, '1', "Kullanıcı kimlik bilgilerini onaylatırken kimlik bilgilerini yanlış girdi.");
                return back()->with("error", "Girmiş olduğunuz kimlik bilgileri yanlıştır!")->with('tc', '1');
            }
        } else {
            LogCall(Auth::user()->id, '1', "Kullanıcı kimlik bilgilerini onaylamaya çalıştı fakat kimliği zaten onaylı.");
            return back()->with("error", "Kimliğiniz zaten onaylı!");
        }
    }

    public function siparislerim()
    {
        return view('front.pages.siparislerim');
    }

    public function siparislerim_()
    {
        return view('front.pages.siparislerim_');
    }


    public function siparislerim_ilan()
    {
        return view('front.pages.siparislerim-ilan');
    }

    public function odemelerim()
    {
        return view('front.pages.odemelerim');
    }

    public function fatura_adreslerim()
    {
        return view('front.pages.fatura-adreslerim');
    }

    public function fatura_adreslerim_post(Request $request)
    {
        $rules = [
            "adres_ismi" => "required",
            "ad_soyad" => "required",
            "ulke" => "required",
            "il" => "required",
            "ilce" => "required",
            "adres" => "required",
            "posta_kodu" => "required",
            "telefon" => "required",
        ];
        $messages = [
            'adres_ismi.required' => 'Adres ismi alanını boş bırakmayın.',
            'ad_soyad.required' => 'Lütfen ad soyad (kurumsal fatura ise firma adı) alanını boş bırakmayın.',
            "ulke.required" => "Bir ülke seçimi yapmalısınız.",
            "il.required" => "Bir il seçimi yapmalısınız.",
            "ilce.required" => "Bir ilçe seçimi yapmalısınız.",
            "adres.required" => "Adres alanını boş bırakmayın.",
            "posta_kodu.required" => "Posta kodu alanını boş bırakmayın.",
            "telefon.required" => "Telefon numarası alanını boş bırakmayın."
        ];
        $data = $request->all();
        $validator = Validator::make($data, $rules, $messages);
        if (!isset($request->id)) { //eğer yeni bir kayıt ise
            if ($request->bireysel_kurumsal == 'on') {
                $vergi_dairesi = $request->vergi_dairesi;
                $vergi_no = $request->vergi_no;
                $tc_no = $request->vergi_no;
                $bk = 2;
            } else {
                $vergi_dairesi = "";
                $vergi_no = "";
                $tc_no = $request->tc_no;
                $bk = 1;
            }
            DB::table('fatura_adresleri')->insert([
                'user' => Auth::user()->id,
                'adres_ismi' => $request->adres_ismi,
                'bireysel_kurumsal' => $bk,
                'ad_soyad' => $request->ad_soyad,
                'il' => $request->il,
                'ilce' => $request->ilce,
                'ulke' => $request->ulke,
                'posta_kodu' => $request->posta_kodu,
                'adres' => $request->adres,
                'telefon' => $request->telefon,
                'tc_no' => $tc_no,
                'vergi_dairesi' => $vergi_dairesi,
                'vergi_no' => $vergi_no,
                'updated_at' => date('YmdHis'),
                'created_at' => date('YmdHis'),
            ]);
            LogCall(Auth::user()->id, '1', "Kullanıcı hesabına " . $request->adres_ismi . " ismiyle bir fatura adresi ekledi.");
            return redirect()->route('fatura_adreslerim')->with("success", __('general.mesaj-9'));
        } else { //düzenleme modu
            if ($request->bireysel_kurumsal == 'on') {
                $vergi_dairesi = $request->vergi_dairesi;
                $vergi_no = $request->vergi_no;
                $tc_no = $request->vergi_no;
                $bk = 2;
            } else {
                $vergi_dairesi = "";
                $vergi_no = "";
                $tc_no = $request->tc_no;
                $bk = 1;
            }
            DB::table('fatura_adresleri')->where('id', $request->id)->update([
                'adres_ismi' => $request->adres_ismi,
                'bireysel_kurumsal' => $bk,
                'ad_soyad' => $request->ad_soyad,
                'il' => $request->il,
                'ilce' => $request->ilce,
                'ulke' => $request->ulke,
                'posta_kodu' => $request->posta_kodu,
                'adres' => $request->adres,
                'telefon' => $request->telefon,
                'tc_no' => $tc_no,
                'vergi_dairesi' => $vergi_dairesi,
                'vergi_no' => $vergi_no,
                'updated_at' => date('YmdHis'),
            ]);
            LogCall(Auth::user()->id, '1', "Kullanıcı " . $request->adres_ismi . " isimli fatura adresini güncelledi.");
            return redirect()->route('fatura_adreslerim')->with("success", __('general.mesaj-9'));
        }
    }

    public function bildirimlerim()
    {
        return view('front.pages.bildirimlerim');
    }

    public function bildirimlerim_post(Request $request)
    {
        foreach (DB::table('bildirim_kategorileri')->get() as $bk) {
            $inputName = Str::slug($bk->title);
            if (isset($request->$inputName)) {
                if (DB::table('bildirim_kullanici')->where('user', Auth::user()->id)->where('bildirim', $bk->id)->count() < 1) {
                    DB::table('bildirim_kullanici')->insert([
                        'user' => Auth::user()->id,
                        'bildirim' => $bk->id,
                        'created_at' => date('YmdHis'),
                    ]);
                    LogCall(Auth::user()->id, '1', "Kullanıcı " . $bk->id . " id'li bildirimleri almak için izin verdi.");
                }
            } else {
                if (DB::table('bildirim_kullanici')->where('user', Auth::user()->id)->where('bildirim', $bk->id)->count() > 0) {
                    DB::table('bildirim_kullanici')->where('user', Auth::user()->id)->where('bildirim', $bk->id)->delete();
                    LogCall(Auth::user()->id, '1', "Kullanıcı " . $bk->id . " id'li bildirimleri almak için verdiği izni kapattı.");
                }
            }
        }
        return back()->with("success", __('general.mesaj-9'));
    }

    public function bildirim_oku(Request $request)
    {
        $id = $request->id;
        if ($id == 0) {
            DB::table('bildirim')->where('user', Auth::user()->id)->update(['isRead' => '1']);
            return true;
        } else {
            DB::table('bildirim')->where('user', Auth::user()->id)->where('id', $id)->update(['isRead' => '1']);
            return true;
        }
    }

    public function ayarlarim()
    {
        return view('front.pages.ayarlarim');
    }

    public function ayarlarim_post(Request $request)
    {
        $user = Auth::user()->id;
        $rules = [
            "name" => "required", "telefon" => "required", "tcno" => "required|min:11", "dogum_tarihi" => "required",
        ];
        $messages = [
            'name.required' => ':attribute Gereklidir !',
            'telefon.required' => ':attribute alanı gereklidir',
            "tcno.min" => ":attribute alanı min 11 karakter olmalıdır !",
            "tcno.required" => ":attribute alanı gereklidir !",
            "dogum_tarihi.required" => ":attribute alanı gereklidir"
        ];
        $data = $request->all();

        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails()) {
            return back()->with("error", "Alanları Doldurunuz");
        } else {
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
                $googleSecret = $request->googleSecret;
            } else {
                $fa_google = 0;
                $googleSecret = "";
            }
            if ($request->telefon != Auth::user()->telefon) {
                DB::table('users')->where('id', Auth::user()->id)->update(['telefon_verified_at' => NULL]);
            }
            if ($request->tcno != Auth::user()->tcno) {
                DB::table('users')->where('id', Auth::user()->id)->update(['tc_verified_at' => NULL]);
            }
            $ad = preg_replace('/[^A-Za-z0-9\-_ÇçĞğıİÖöŞşÜü.]/', ' ', $request->name);
            $telef = preg_replace("/\D/", '', $request->telefon);
            DB::table('users')->where('id', $user)->update([
                'name' => $ad,
                'telefon' => $telef, //$request->telefon,
                'telefon_country' => $request->telefon_country,
                'tcno' => $request->tcno,
                'dogum_tarihi' => $request->dogum_tarihi,
                'cinsiyet' => $request->cinsiyet,
                'notify_sms' => $notify_sms,
                'notify_email' => $notify_email,
                '2fa_email' => $fa_email,
                '2fa_sms' => $fa_sms,
                '2fa_google' => $fa_google,
            ]);
            if ($request->password != '') {
                if (Hash::check($request->password_old, DB::table('users')->where("id", $user)->first()->password)) {
                    if ($request->password == $request->password_rewrite) {
                        DB::table('users')->where("id", $user)->update([
                            "password" => bcrypt($request->password),
                        ]);
                        LogCall(Auth::user()->id, '1', "Kullanıcı profil şifresi ve ayarları güncellendi.");
                    } else {
                        LogCall(Auth::user()->id, '1', "Kullanıcı profil şifresini ve ayarlarını güncellerken yeni şifre ve yeni şifre tekrarını yanlış yazdı.");
                        return back()->with("error", __('general.hata-11'));
                    }
                } else {
                    LogCall(Auth::user()->id, '1', "Kullanıcı profil şifresini ve ayarlarını güncellerken eski şifresini yanlış yazdı.");
                    return back()->with("error", __('general.hata-12'));
                }
            } else {
                LogCall(Auth::user()->id, '1', "Kullanıcı profil bilgilerini güncelledi.");
            }
            return back()->with("success", __('general.mesaj-9'));
        }
    }

    public function avatar_sec($avatar, $name)
    {
        DB::table('users')->where('id', Auth::user()->id)->update([
            'avatar' => $avatar . "/" . $name
        ]);
        return back()->with("success", __('general.mesaj-9'));
    }

    public function satici_panelim()
    {
        return view('front.pages.satici-panelim');
    }

    public function satici_panelim_post(Request $request)
    {
if(DB::table('users')->where('id',Auth::user()->id)->first()->ilan_izin==-1) {return back()->with("error", "İlan oluşturma izniniz bulunmamaktadır. Canlı destek ile iletişime geçebilirsiniz.");}

        if (getUserVerifiyStep() >= 2) {
            if (DB::table('games_titles_special')->where('games_titles', $request->pazar)->count() > 0) { //eğer resmi satıcı yüklüyor ise
                if (!isset($request->title) or !isset($request->price) or !isset($request->image) or $request->price <= 0 or $request->sunucu=='') {
                    return back()->with("error", __('general.hata-15'));
                }
                if ($request->pazar == 397) {
                    if (Auth::user()->id == '18683' || Auth::user()->id == '2644' || Auth::user()->id == '14879' || Auth::user()->id == '233') {
                        $komisyon = 3;
                    } else {
                        $komisyon = 5;
                    }   // bu kullanıcıya rise için imtiyaz
                } elseif ($request->pazar == 413) {
                    if (Auth::user()->id == '18683' || Auth::user()->id == '2644' || Auth::user()->id == '14879' || Auth::user()->id == '233') {
                        $komisyon = 3;
                    } else {
                        $komisyon = 5;
                    }   // bu kullanıcıya rise için imtiyaz
                }  // rise NFT
                else {
                    $komisyon = findUserKomisyon(Auth::user()->id);
                }




                $kazanc = $request->price - ($request->price * $komisyon / 100);
                if ($request->hasFile('image')) {
                    $destinationPath = "front/ilanlar";
                    $file = $request->image;
                    $title = Str::slug($request->title);
                    $fileName = $title . "-" . time() . '.' . $file->clientExtension();
                    $file->move($destinationPath, $fileName);
                } else {
                    $fileName = NULL;
                }
                DB::table('pazar_yeri_ilanlar')->insert([
                    'user' => Auth::user()->id,
                    'pazar' => $request->pazar,
                    'price' => $request->price,
                    'moment_komisyon' => $kazanc,
                    'title' => $request->title,
                    'text' => $request->text,
                    'sunucu' => $request->sunucu,
                    'type' => 1,
                    'tl' => $request->sure,
                    'image' => $fileName,
                    'teslimat' => $request->teslimat,
                    'updated_at' => date('YmdHis'),
                    'created_at' => date('YmdHis'),
                ]);
                $lastId = DB::getPdo()->lastInsertId();
                foreach (DB::table('games_titles_features')->where('game_title', $request->pazar)->whereNull('deleted_at')->get() as $p) {
                    $name = Str::slug($p->title);
                    DB::table('pazar_yeri_ilan_features')->insert([
                        'ilan' => $lastId,
                        'feature' => $p->id,
                        'value' => $request->$name,
                    ]);
                }
                LogCall(Auth::user()->id, '1', "Kullanıcı " . $request->title . " isimli yeni bir ilan oluşturdu.");
                return redirect()->route('satici_panelim')->with("success", "İlanınız başarıyla kaydedilmiştir. Onaylandıktan sonra yayına girecektir.");
            } else { //hazır itemlerden seçiyor ise


                $say = 0;
                foreach ($request->item as $item) {
                    $say += 1;
                    $price = "price" . $item;
                    $text = "text" . $item;
                    $teslimat = "teslimat" . $item;

                    if (!isset($request->$teslimat) or !isset($request->$price) or $request->$price <= 0 or $request->sunucu=='') {
                        return back()->with("error", __('general.hata-15'));
                    }
                    if(!isset($request->$text)) {$request->$text='';}

                    $komisyon = findUserKomisyon(Auth::user()->id);
                    $kazanc = $request->$price - ($request->$price * $komisyon / 100);
                    $itemTitle = DB::table('games_titles_items_info')->where('id', $item)->first();
                    DB::table('pazar_yeri_ilanlar')->insert([
                        'user' => Auth::user()->id,
                        'pazar' => $request->pazar,
                        'price' => $request->$price,
                        'moment_komisyon' => $kazanc,
                        'title' => $itemTitle->title,
                        'text' => $request->$text,
                        'sunucu' => $request->sunucu,
                        'tl' => $request->sure,
                        'grup' => $request->grup,
                        'teslimat' => $request->$teslimat,
                        'updated_at' => date('YmdHis'),
                        'created_at' => date('YmdHis'),
                    ]);
                    $lastId = DB::getPdo()->lastInsertId();
                    DB::table('pazar_yeri_ilan_icerik')->insert([
                        'ilan' => $lastId,
                        'item' => $item,
                    ]);
                }
                LogCall(Auth::user()->id, '1', "Kullanıcı " . $itemTitle->title . " itemiyle bir ilan oluşturdu.");
                if ($say > 1) {
                    return redirect()->route('satici_panelim')->with("success", "İlanlarınız başarıyla kaydedilmiştir. Onaylandıktan sonra yayına girecektir.");
                } else {
                    return redirect()->route('satici_panelim')->with("success", "İlanınız başarıyla kaydedilmiştir. Onaylandıktan sonra yayına girecektir.");
                }
            }
        } else {
            LogCall(Auth::user()->id, '1', "Kullanıcı bir ilan oluşturmak istedi fakat telefon numarası onaylı değildi.");
            return redirect()->route('hesap_onayla')->with('error', 'İlan ekleyebilmek için lütfen telefonunuzu onaylayın.');
        }
    }

    public function satici_panelim_birlestir_post(Request $request)
    {
        $ilanlar = explode(",", $request->ilanlar_id[0]);
        $ilan = DB::table('pazar_yeri_ilanlar')->where('id', $ilanlar[0])->first();

        if ($request->pazar == 397) {
            if (Auth::user()->id == '18683' || Auth::user()->id == '2644' || Auth::user()->id == '14879' || Auth::user()->id == '233') {
                $komisyon = 3;
            } else {
                $komisyon = 5;
            }   // bu kullanıcıya rise için imtiyaz
        } elseif ($request->pazar == 413) {
            if (Auth::user()->id == '18683' || Auth::user()->id == '2644' || Auth::user()->id == '14879' || Auth::user()->id == '233') {
                $komisyon = 3;
            } else {
                $komisyon = 5;
            }   // bu kullanıcıya rise için imtiyaz
        }  // rise NFT
        else {
            $komisyon = findUserKomisyon(Auth::user()->id);
        }


        $kazanc = $request->toplu_fiyat - ($request->toplu_fiyat * $komisyon / 100);
        $title = ""; // toplu ilan başınaa gelecek metin
        foreach ($ilanlar as $ilans) {
            $title .= " " . DB::table('pazar_yeri_ilanlar')->where('id', $ilans)->first()->title;
        }
        DB::table('pazar_yeri_ilanlar')->insert([
            'pazar' => $ilan->pazar,
            'user' => Auth::user()->id,
            'price' => $request->toplu_fiyat,
            'moment_komisyon' => $kazanc,
            'title' => $title,
            'text' => $request->toplu_aciklama,
            'sunucu' => $ilan->sunucu,
            'type' => $ilan->type,
            'status' => '0',
            'toplu' => '1',
            'updated_at' => date('YmdHis'),
            'created_at' => date('YmdHis'),
        ]);
        $lastId = DB::getPdo()->lastInsertId();
        foreach ($ilanlar as $ilans) {
            DB::table('pazar_yeri_ilan_toplu')->insert([
                'toplu' => $lastId,
                'ilan' => $ilans,
            ]);
        }
        LogCall(Auth::user()->id, '1', "Kullanıcı " . $title . " ismiyle bir toplu ilan oluşturdu.");
        return redirect()->route('satici_panelim')->with("success", "Toplu ilanınız başarıyla kaydedilmiştir. Onaylandıktan sonra yayına girecektir.");
    }

    public function alici_panelim()
    {
        return view('front.pages.alici-panelim');
    }

    public function yeni_satis_buy()
    {
        return view('front.pages.yeni-satis-buy');
    }

    public function alici_panelim_post(Request $request)
    {
        if (getUserVerifiyStep() >= 2) {
            if (!isset($request->item) or !isset($request->pazar) or !isset($request->sunucu)) {
                return back()->with("error", __('general.hata-15'));
            }
            if (DB::table('games_titles_special')->where('games_titles', $request->pazar)->count() > 0) { //eğer resmi satıcı yüklüyor ise
                /*
                if (!isset($request->title) or !isset($request->price) or !isset($request->image)) {
                    return back()->with("error", __('general.hata-15'));
                }
                $komisyon = findUserKomisyon(Auth::user()->id);
                $kazanc = $request->price - ($request->price * $komisyon / 100);
                if ($request->hasFile('image')) {
                    $destinationPath = "front/ilanlar";
                    $file = $request->image;
                    $title = Str::slug($request->title);
                    $fileName = $title . "-" . time() . '.' . $file->clientExtension();
                    $file->move($destinationPath, $fileName);
                } else {
                    $fileName = NULL;
                }
                DB::table('pazar_yeri_ilanlar')->insert([
                    'user' => Auth::user()->id,
                    'pazar' => $request->pazar,
                    'price' => $request->price,
                    'moment_komisyon' => $kazanc,
                    'title' => $request->title,
                    'text' => $request->text,
                    'sunucu' => $request->sunucu,
                    'type' => 1,
                    'image' => $fileName,
                    'updated_at' => date('YmdHis'),
                    'created_at' => date('YmdHis'),
                ]);
                $lastId = DB::getPdo()->lastInsertId();
                foreach (DB::table('games_titles_features')->where('game_title', $request->pazar)->whereNull('deleted_at')->get() as $p) {
                    $name = Str::slug($p->title);
                    DB::table('pazar_yeri_ilan_features')->insert([
                        'ilan' => $lastId,
                        'feature' => $p->id,
                        'value' => $request->$name,
                    ]);
                }

                return redirect()->route('satici_panelim')->with("success", "İlanınız başarıyla kaydedilmiştir. Onaylandıktan sonra yayına girecektir.");
                */
            } else { //hazır itemlerden seçiyor ise
                if (!isset($request->item) or !isset($request->pazar) or !isset($request->sunucu)) {
                    return back()->with("error", __('general.hata-15'));
                }
                if (bloke_kontrol(Auth::user()->id)) {
                    return back()->with('error', 'Bakiyeniz blokeli, Canlı Destek ile irtibata geçin.');
                }
                $say = 0;
                $kazancToplam = 0;
                foreach ($request->item as $item) {
                    $price = "price" . $item;
                    $komisyon = findUserKomisyonAlis(Auth::user()->id);
                    $bloke = 10; // %10;
                    $kazanc = $request->$price * $komisyon / 100; //kişiden düşürülecek bakiye
                    $kazancToplam += $kazanc;
                    if ($request->$price <= 0)
                        return back()->with('error', 'Hata F');
                }
                $baki = DB::table('users')->where('id', Auth::user()->id)->first(); #------------------------------------------------------------------

                if ($baki->bakiye + $baki->bakiye_cekilebilir < $kazancToplam) {
                    LogCall(Auth::user()->id, '3', "Kullanıcının alış ilanı oluşturmak için bakiyesi yeterli değil.");
                    return redirect()->route('bakiye_ekle')->with('error', 'Alış ilanı oluşturmak için bakiyeniz yeterli değil. Lütfen bu ilan için hesabınızda en az ' . $kazancToplam . "₺ olduğuna emin olun.");
                }

                $bakiye = Auth::user()->bakiye;
                foreach ($request->item as $item) {
                    $say += 1;
                    $price = "price" . $item;
                    $text = "text" . $item;
                    $teslimat = "teslimat" . $item;
                    $komisyon = findUserKomisyonAlis(Auth::user()->id);
                    $kazanc = $request->$price * $komisyon / 100; //kazanc bakiye
                    $blokeTutar = $request->$price * $bloke / 100;
                    $itemTitle = DB::table('games_titles_items_info')->where('id', $item)->first();
                    if ($baki->bakiye + $baki->bakiye_cekilebilir < $blokeTutar) {
                        LogCall(Auth::user()->id, '3', "Kullanıcının alış ilanı oluşturmak için bakiyesi yeterli değil.");
                        return redirect()->route('alici_panelim')->with('error', 'Alış ilanı oluşturmak için bakiyeniz yeterli değil. Lütfen bu ilan için hesabınızda en az ' . $blokeTutar . "₺ olduğuna emin olun.");
                    }


                    #------------------------------------------------------------------------------------------------------------------------------------------
                    if ($blokeTutar > Auth::user()->bakiye) { # fiyat bakiyeden büyükse
                        $bak_dus = 0;                     # bakiyede ne varsa çek sıfırla
                        $cek_dus = $baki->bakiye_cekilebilir - ($blokeTutar - $baki->bakiye); # kalanı cbakiyeden al
                        $iadeb = $baki->bakiye;
                        $iadec = $blokeTutar - $baki->bakiye;
                    } else {
                        $bak_dus = $baki->bakiye - $blokeTutar; # fiyat bakiyeden küçükse bakiyeden fiyatı çıkar
                        $cek_dus = $baki->bakiye_cekilebilir; # c.bakiye ye dokunma
                        $iadeb = $blokeTutar;
                        $iadec = 0;
                    }



                    #------------------------------------------------------------------------------------------------------------------------------------------

                    // $bakiye = $bakiye - $kazanc;
                    DB::table('users')->where('id', Auth::user()->id)->update(['bakiye' => $bak_dus, 'bakiye_cekilebilir' => $cek_dus]);
                    DB::table('pazar_yeri_ilanlar_buy')->insert([
                        'user' => Auth::user()->id,
                        'pazar' => $request->pazar,
                        'price' => $request->$price,
                        'moment_komisyon' => $kazanc,
                        'title' => $itemTitle->title,
                        'text' => $request->$text,
                        'sunucu' => $request->sunucu,
                        'teslimat' => $request->$teslimat,
                        'updated_at' => date('YmdHis'),
                        'created_at' => date('YmdHis'),
                    ]);
                    $lastId = DB::getPdo()->lastInsertId();


                    # burada iade olayında ne kadar bakiye ne kadar cbakiye tarafına iade olacağını bilmek için kayıt alıyoruz

                    DB::table('iade_bakiye')->insert([
                        'iid'           => $lastId,
                        'uid'           => Auth::user()->id,
                        'bakiye'        => $iadeb,
                        'cbakiye'       => $iadec,
                        'tutar'         => $blokeTutar,
                        'created_at'    => date("YmdHis"),
                    ]);
                    # burada iade olayında ne kadar bakiye ne kadar cbakiye tarafına iade olacağını bilmek için kayıt alıyoruz
                    $lastiid = DB::getPdo()->lastInsertId();

                    DB::table('pazar_yeri_ilanlar_buy')->where('id',$lastId)->update(['iade_id' => $lastiid]); // iade id alış pazarı için geçerli ortalık karışmasın

                    DB::table('pazar_yeri_ilan_icerik_buy')->insert(['ilan' => $lastId,'item' => $item]);
                }
                LogCall(Auth::user()->id, '1', "Kullanıcı " . $itemTitle->title . " ismiyle bir alış ilanı oluşturdu.");
                if ($say > 1) {
                    return redirect()->route('alici_panelim')->with("success", "İlanlarınız başarıyla kaydedilmiştir. Onaylandıktan sonra yayına girecektir.");
                } else {
                    return redirect()->route('alici_panelim')->with("success", "İlanınız başarıyla kaydedilmiştir. Onaylandıktan sonra yayına girecektir.");
                }
            }
        } else {
            LogCall(Auth::user()->id, '1', "Kullanıcı bir alış ilanı oluşturmak istedi fakat telefon numarası onaylı değildi.");
            return redirect()->route('hesap_onayla')->with('error', 'İlan ekleyebilmek için lütfen telefonunuzu onaylayın.');
        }
    }

    public function alis_duzenle_post(Request $request)
    {


        if (bloke_kontrol(Auth::user()->id)) {
            return back()->with('error', 'Bakiyeniz blokeli, Canlı Destek ile irtibata geçin.');
        }
        $ilan = DB::table('pazar_yeri_ilanlar_buy')->where('id', $request->ilan)->first();
        $price = "price" . $request->ilan; //price23
        $text = "text" . $request->ilan; //text23

        if($ilan->status > 3) {return back()->with('error', 'Bu ilanın durumu değiştiği için düzenleme yapılamadı ');}

        if ($request->$price <= 0) {
            return redirect()->route('alici_panelim')->with("error", "Girilen fiyat geçersiz..");
        }

        if ($request->pazar == 397) {
            if (Auth::user()->id == '18683' || Auth::user()->id == '2644' || Auth::user()->id == '14879' || Auth::user()->id == '233') {
                $komisyon = 3;
            } else {
                $komisyon = 5;
            }   // bu kullanıcıya rise için imtiyaz
        } elseif ($request->pazar == 413) {
            if (Auth::user()->id == '18683' || Auth::user()->id == '2644' || Auth::user()->id == '14879' || Auth::user()->id == '233') {
                $komisyon = 3;
            } else {
                $komisyon = 5;
            }   // bu kullanıcıya rise için imtiyaz
        }  // rise NFT
        else {
            $komisyon = findUserKomisyon(Auth::user()->id);
        }

         if($ilan->text == $request->$text)  {$status=1;$ek='';} else {$status=0;$ek="Onaylandıktan sonra yayına girecektir.";} // Gelen açıklama mevcut ile karşılaştırılıyor aynı ise onaya gerek yok yayına al

        $kazanc = $request->$price * $komisyon / 100; //7.5
        $blokeFark = $request->$price * 0.1 - $ilan->price * 0.1; // -1.5

#------------------- YAkup tarafından eklendi bakiye ve cekilebilir toplamı kontrol ediliyor
        $kisi=DB::table('users')->where('id', Auth::user()->id)->first();
        if ($kisi->bakiye+$kisi->bakiye_cekilebilir < $blokeFark && $blokeFark >= 0 ) {
            return redirect()->route('bakiye_ekle')->with('error', 'Alış ilanı oluşturmak için bakiyeniz yeterli değil. Lütfen bu ilan için hesabınızda en az ' . $blokeFark . "₺ olduğuna emin olun.");
        }
#------------------- Hangisinde bakiye varsa oradan düşülüyor
        if($kisi->bakiye>=$blokeFark) {
            DB::table('users')->where('id', Auth::user()->id)->update(['bakiye' => Auth::user()->bakiye - $blokeFark]);
        }
        else { DB::table('users')->where('id', Auth::user()->id)->update(['bakiye_cekilebilir' => Auth::user()->bakiye_cekilebilir - $blokeFark]);}


        DB::table('pazar_yeri_ilanlar_buy')->where('user',Auth::user()->id)->where('id', $request->ilan)->update([
            'price' => $request->$price,
            'moment_komisyon' => $kazanc,
            'text' => $request->$text,
            'status' => $status,
            'updated_at' => date('YmdHis'),
        ]);

        DB::table('iade_bakiye')->where('iid',$ilan->id)->where('id',$ilan->iade_id)->update(['tutar' => $kazanc*10]); //  düzenlemeden sonra iptal olursa iade olacak tutarı güncelle

        LogCall(Auth::user()->id, '1', "Kullanıcı " . $request->ilan . " id'li alış ilanında düzenleme yaptı.");
        return redirect()->route('alici_panelim')->with("success", "İlanınızda yaptığınız değişiklikler başarıyla kaydedilmiştir. ". $ek);
    }

    public function yeni_satis_sozlesme_post(Request $request)
    {
        DB::table('alis_ilani_sozlesme')->insert([
            'user' => Auth::user()->id,
            'kabul' => 1,
            'created_at' => date('YmdHis'),
        ]);
        LogCall(Auth::user()->id, '1', "Kullanıcı alış ilanı sözleşmesini onayladı.");
        return back()->with("success", "Sözleşmeyi onayladınız, artık alış pazarında ilan açabilirsiniz.");
    }

    public function ilan_yorum_yap(Request $request)
    {
        if (getUserVerifiyStep() >= 2) {
            if (isset($request->buy)) {
                $buy = 1;
                $ilan = DB::table('pazar_yeri_ilanlar_buy')->where('id', $request->ilan)->first();
            } else {
                $buy = 0;
                $ilan = DB::table('pazar_yeri_ilanlar')->where('id', $request->ilan)->first();
            }
            //$alici = DB::table('users')->where('id', $ilan->user)->first();
            $id=Auth::user()->id;

            $son_yorum=DB::select("SELECT created_at FROM `ilan_yorumlar` where user='$id' and ilan='$request->ilan' order by created_at desc limit 1");
            if(count($son_yorum)>0) {
                $sure=DB::table('settings')->first()->yorum_sure;
                $date1 = date_create($son_yorum[0]->created_at);
                $date2 = date_create(date("YmdHis"));
                $diff = date_diff($date1, $date2);
                if ($diff->y<1 && $diff->m<1 && $diff->d<1 && $diff->h<1 && $diff->i < $sure ) {
                    return back()->with("error", "Bu ilan için çok kısa bir süre önce yorum yaptınız, Lütfen biraz bekleyin..");
                }
            }

            DB::table('ilan_yorumlar')->insert([
                'ilan' => $request->ilan,
                'buy' => $buy,
                'user' => $id,
                'text' => $request->text,
                'rate' => '0',
                'status' => '0',
                'created_at' => date('YmdHis'),
            ]);
            LogCall(Auth::user()->id, '1', "Kullanıcı " . $request->ilan . " id'li ilana yorum yaptı.");
            return back()->with("success", "Yorumunuz başarıyla kaydedilmiştir. Onaylandıktan sonra yayına alınacaktır.");
        } else {
            LogCall(Auth::user()->id, '1', "Kullanıcı bir ilana yorum yapmak istedi fakat telefon numarası onaylı değildi.");
            return redirect()->route('hesap_onayla')->with('error', 'Yorum yapabilmek için lütfen telefon numaranızını doğrulayın.');
        }
    }

    public function ilan_satin_al(Request $request)
    {
        if (bloke_kontrol(Auth::user()->id)) {
            return back()->with('error', 'Bakiyeniz blokeli, Canlı Destek ile irtibata geçin.');
        }
        if (getUserVerifiyStep() >= 2) {
            $ilan = DB::table('pazar_yeri_ilanlar')->where('id', $request->ilan)->first();
            if (!is_null($ilan->deleted_at)) {
                LogCall(Auth::user()->id, '1', "Kullanıcı bir ilan satın almak istedi fakat ilan silinmiş.");
                return back()->with('error', 'Bu ilan sahibi tarafından az önce yayından kaldırılmış :(');
            }

            if($ilan->status!=1){return back()->with('error', 'Bu ilan için başka bir kullanıcı alım sürecini başlatmış.');}

            if (Auth::user()->bakiye + Auth::user()->bakiye_cekilebilir >= $ilan->price) {
                #---------------------------------------------------------------------------------------------------------------------
                if ($ilan->price > Auth::user()->bakiye) { # fiyat bakiyeden büyükse
                    $bak_dus = 0;                     # bakiyede ne varsa çek sıfırla
                    $cek_dus = Auth::user()->bakiye_cekilebilir - ($ilan->price - Auth::user()->bakiye); # kalanı cbakiyeden al
                    $iadeb = Auth::user()->bakiye;
                    $iadec = $ilan->price - Auth::user()->bakiye;
                } else {
                    $bak_dus = Auth::user()->bakiye - $ilan->price; # fiyat bakiyeden küçükse bakiyeden fiyatı çıkar
                    $cek_dus = Auth::user()->bakiye_cekilebilir; # c.bakiye ye dokunma
                    $iadeb = $ilan->price;
                    $iadec = 0;
                }
                #---------------------------------------------------------------------------------------------------------------------
                $bakiye_dusurme = DB::table('users')->where('id', Auth::user()->id)->update(['bakiye' => $bak_dus, 'bakiye_cekilebilir' => $cek_dus]);
                LogCall(Auth::user()->id, '1', "Kullanıcının bakiye (".Auth::user()->bakiye.") ve çekilebilir bak. (".Auth::user()->bakiye_cekilebilir.") toplamından " . $ilan->id . " id'li ilanın bedeli olan (".$ilan->price.") TL düşüldü.");


                if ($bakiye_dusurme) {
                    DB::table('pazar_yeri_ilan_satis')->insert([
                        'ilan' => $ilan->id,
                        'satin_alan' => Auth::user()->id,
                        'note' => $request->note,
                        'price' => $ilan->price,
                        'created_at' => date('YmdHis'),
                    ]);

                    # burada iade olayında ne kadar bakiye ne kadar cbakiye tarafına iade olacağını bilmek için kayıt alıyoruz
                    $lastId = DB::getPdo()->lastInsertId();
                    DB::table('iade_bakiye')->insert([
                        'iid'           => $lastId,
                        'uid'           => Auth::user()->id,
                        'bakiye'        => $iadeb,
                        'cbakiye'       => $iadec,
                        'tutar'         => $ilan->price,
                        'created_at'    => date("YmdHis"),
                    ]);
                    # burada iade olayında ne kadar bakiye ne kadar cbakiye tarafına iade olacağını bilmek için kayıt alıyoruz



                    /*
                     * Satıcıya Mail Gönderme
                     */
                    $satici = DB::table('users')->where('id', $ilan->user)->first();
                    $to_name = $satici->name;
                    $to_email = $satici->email;
                    $data = array('name' => getSiteName(), "body" => "Satışta olan ilanınız satılmıştır.", 'email' => $satici->email, 'title' => $ilan->title, 'price' => $ilan->moment_komisyon);
                    Mail::send('emails.satildi', $data, function ($message) use ($to_name, $to_email) {
                        $message->to($to_email, $to_name)->subject("Satışta olan ilanınız satılmıştır.");
                        $message->from(getSiteSenderMail(), "Satışta olan ilanınız satılmıştır.");
                    });
                    /*
                     * Satıcıya sms gönderme
                     */
                    $smsText = "Satışta olan " . $ilan->title . " isimli ilanınız ".number_format($ilan->price,2)." TL ye satılmıştır. Teslimat için lütfen oturum açın.";
                    sendSms($satici->telefon, $smsText);

                    DB::table('pazar_yeri_ilanlar')->where('id', $request->ilan)->update([
                        'status' => '4',
                        'updated_at' => date('YmdHis'),
                    ]);
                    $ilan = DB::table('pazar_yeri_ilanlar')->where('id', $request->ilan)->first();
                    $favoriler = DB::table('favoriler')->where('type', '1')->where('favoriId', $request->ilan)->whereNull('deleted_at');
                    if ($favoriler->count() > 0) {
                        foreach ($favoriler->get() as $fg) {
                            setBildirim($fg->user, '2', 'Favori İlanı Satıldı', 'Favorilerinizde ekle olan ' . $ilan->title . ' başlıklı ilan satılmıştır.', route('favorilerim'));
                        }
                    }
                    if (DB::table('pazar_yeri_ilan_toplu')->where('ilan', $ilan->id)->count() > 0) { //bu ilan toplu satış içidedir ve toplu satışı bozar
                        $toplu = DB::table('pazar_yeri_ilan_toplu')->where('ilan', $ilan->id)->first();
                        DB::table('pazar_yeri_ilanlar')->where('id', $toplu->toplu)->update([
                            'deleted_at' => date('YmdHis'),
                        ]);
                        setBildirim($ilan->user, '2', 'Toplu Satış Bozuldu', 'Toplu satış ilanınız bozulmuştur. Yeniden oluşturmak için tıklayın..', route('satici_panelim'));
                    }
                    if ($ilan->toplu == 1) { //toplu ilan içinde ki diğer ilanların durumunu silindi yapıyoruz
                        foreach (DB::table('pazar_yeri_ilan_toplu')->where('toplu', $ilan->id)->get() as $t) {
                            DB::table('pazar_yeri_ilanlar')->where('id', $t->ilan)->update([
                                'deleted_at' => date('YmdHis'),
                            ]);
                        }
                        setBildirim($ilan->user, '2', 'Tekli Satışlarınız Bozuldu', 'Toplu ilan içerisinde yer alan ilanlarınız toplu ilanınız satıldığı için bozulmuştur.', route('satici_panelim'));
                    }
                    setBildirim($ilan->user, '2', 'İlanınız satıldı', 'Yayında olan ilanınız satılmıştır, lütfen ilan içeriğini siteye teslim ederek satışı tamamlayın.', route('satici_panelim'));
                    LogCall(Auth::user()->id, '1', "Kullanıcı " . $ilan->id . " id'li ilanı satın aldı.");
                    return redirect()->route('siparislerim_ilan')->with("success", 'Satın alma işleminiz başarıyla kaydedildi, ilanı alabilmek için lütfen satıcının itemi siteye teslim etmesini bekleyin.');
                } else {
                    LogCall(Auth::user()->id, '1', "Kullanıcı bir ilan satın almak istedi fakat yeterli bakiyesi yoktu.");
                    return back()->with('error', 'Bu işlem için yeterli bakiyeniz yok.');
                }
            } else {
                LogCall(Auth::user()->id, '1', "Kullanıcı bir ilan satın almak istedi fakat yeterli bakiyesi yoktu.");
                return back()->with('error', 'Bu işlem için yeterli bakiyeniz yok.');
            }
        } else {
            LogCall(Auth::user()->id, '1', "Kullanıcı bir ilan almak istedi fakat telefon numarası onaylı değildi.");
            return redirect()->route('hesap_onayla')->with('error', 'İlan satın alabilmek için lütfen telefon numaranızını onaylayın.');
        }
    }

    public function ilan_buy_satin_al(Request $request)
    {
        if (bloke_kontrol(Auth::user()->id)) {
            return back()->with('error', 'Bakiyeniz blokeli, Canlı Destek ile irtibata geçin.');
        }
        if (getUserVerifiyStep() >= 2) {
            $ilan = DB::table('pazar_yeri_ilanlar_buy')->where('id', $request->ilan)->first();
            DB::table('pazar_yeri_ilan_satis_buy')->insert([
                'ilan' => $ilan->id,
                'satin_alan' => Auth::user()->id,
                'note' => $request->note,
                'price' => $ilan->price,
                'created_at' => date('YmdHis'),
            ]);
            /*
             * Alıcıya Mail Gönderme
             */
            $alici = DB::table('users')->where('id', $ilan->user)->first();
            $to_name = $alici->name;
            $to_email = $alici->email;
            $data = array('name' => getSiteName(), "body" => "Alış ilanınız için satıcı bulunmuştur.", 'email' => $alici->email, 'title' => $ilan->title);
            Mail::send('emails.alindi', $data, function ($message) use ($to_name, $to_email) {
                $message->to($to_email, $to_name)->subject("Alış ilanınız için satıcı bulunmuştur.");
                $message->from(getSiteSenderMail(), "Alış ilanınız için satıcı bulunmuştur.");
            });
            /*
             * Alıcıya sms gönderme
             */
            $smsText = "Sayın " . substr($alici->name, 0, 2) . "**** ******, " . $ilan->id . " ID'li alış ilanınız için satış işlemi başlatılmıştır. Üye girişi yaparak Canlı Destek üzerinden bizimle iletişime geçiniz. Canlı Destek harici hiçbir yerden işlem yapmayınız.";
            sendSms($alici->telefon, $smsText);

            /*
             * Satıcıya sms gönderme
             */
            $smsText1 = "Sayın " . substr(Auth::user()->name, 0, 2) . "**** ******, " . $ilan->id . " ID'li alış ilanı için satış işlemi talebiniz alınmıştır. Üye girişi yaparak Canlı Destek üzerinden bizimle iletişime geçiniz. Canlı Destek harici hiçbir yerden işlem yapmayınız.";
            sendSms(Auth::user()->telefon, $smsText1);

            DB::table('pazar_yeri_ilanlar_buy')->where('id', $request->ilan)->update([
                'status' => '4',
                'updated_at' => date('YmdHis'),
            ]);
            $ilan = DB::table('pazar_yeri_ilanlar_buy')->where('id', $request->ilan)->first();
            $favoriler = DB::table('favoriler')->where('type', '2')->where('favoriId', $request->ilan)->whereNull('deleted_at');
            if ($favoriler->count() > 0) {
                foreach ($favoriler->get() as $fg) {
                    setBildirim($fg->user, '2', 'Favori Alış İlanı Satıcı Buldu', 'Favorilerinizde ekli olan ' . $ilan->title . ' başlıklı ilan için alıcı bulunmuştur.', route('favorilerim'));
                }
            }
            LogCall(Auth::user()->id, '1', "Kullanıcı " . $ilan->id . " id'li alış ilanına bir satış işlemi gerçekleştirdi.");
            return redirect()->route('alici_panelim')->with('panel', '3')->with("success", 'Satış işleminiz başarıyla kaydedildi, ilanı satabilmek için lütfen itemi siteye teslim edin.');
        } else {
            LogCall(Auth::user()->id, '1', "Kullanıcı alış ilanına satış yapmak istedi fakat telefon numarası onaylı değildi.");
            return redirect()->route('hesap_onayla')->with('error', 'İlan satışı yapabilmek için lütfen telefon numaranızını onaylayın.');
        }
    }

    public function game_gold_satin_al(Request $request)
    {
        if (bloke_kontrol(Auth::user()->id)) {
            return back()->with('error', 'Bakiyeniz blokeli, Canlı Destek ile irtibata geçin.');
        }

        $id = Auth::user()->id;
        if ($request->adet < 1) {
            LogCall('0', '1', "$id id li kullanıcı eksi bakiye kullanarak ile sistemi kandırmaya çalıştı.");
            return back()->with('error', 'Adet 1 ve üzeri olmalı.');
        }



        $check = DB::select("select status from game_gold_satis where user='$id' and status='0' and  isnull(deleted_at)");
        if ($request->tur == "bize-sat" && $id != 20285 && count($check) != 0) {
            return back()->with('error', 'Henüz tamamlanmamış işlemleriniz bulunmakta. Bekleyen işleminiz varken yeni bir işlem yapamazsınız!');
        }

        $paket = DB::table('games_packages_trade')->where('id', $request->paket)->first();

        if ($request->tur == 'bizden-al') {

            $talep=DB::select("select sum(adet) adet from game_gold_satis where paket='$request->paket' and tur='bizden-al' and status=0 and deleted_at is null");

            if(($paket->stok - $talep[0]->adet) - $request->adet < 0 ) {
                return back()->with('error', 'Yetersiz stok nedeniyle işleminiz devam edemiyor!');
            }

            $fiyat = findGamesPackagesTradeMusteriyeSatPrice($paket->id) * $request->adet;

            #-----------Yakup
            //$indirim= "200/2:500/5:1000/10";
            //$adet=510;
            //$fiyat=20*$adet;

            $eder = $fiyat;

            if (strlen($paket->indirim) > 4 && strpos($paket->indirim, '/') > 0) { //indirim tanımlıysa
                $indirim = explode(':', $paket->indirim); // tanımlı oranları diziye al  adet/oran şeklinde
                $no = 0;
                foreach ($indirim as $f)  // kaç tane indirim oranı tanımlı bilmiyoruz
                {
                    $no++;                    // say
                    $ade = explode('/', $f);
                    $ad[$no] = $ade[0];   # adeti ayır
                    $or[$no] = $ade[1];  // oranı ayır
                }
                for ($x = 1; $x < count($ad) + 1; $x++) {  #----- Alınan adet indirim eslesmesi aranıyor
                    if ($request->adet >= $ad[$x]) {
                        $son = (int)$fiyat - ((int)$fiyat * (int)$or[$x] / 100);
                    } # bulunursa oran kadar inidirim uygula
                    $eder = isset($son) ? $son : $fiyat;
                }
                $fiyat = $eder;
            }
            #-----------Yakup

        } else {
            $fiyat = findGamesPackagesTradeMusteridenAlPrice($paket->id) * $request->adet;
        }

        if (Auth::user()->bakiye + Auth::user()->bakiye_cekilebilir >= $fiyat or $request->tur == "bize-sat") {



            if ($request->tur == "bize-sat") {
                //$bakiye_dusurme = DB::table('users')->where('id', Auth::user()->id)->update(['bakiye' => Auth::user()->bakiye + $fiyat]);
                if ($paket->alis_stok < $request->adet) {
                    LogCall(Auth::user()->id, '1', "Kullanıcı game gold siteye satmak istedi fakat yeterli alış stok yoktu.");
                    return back()->with('error', 'Satın alma için yeterli alış stoğu bulunmuyor, lütfen daha sonra tekrar deneyin veya stokların güncellenmesi için bizimle iletişime geçin.');
                }
                $bakiye_dusurme = true;
            } else {
                if ($paket->stok < $request->adet) {
                    LogCall(Auth::user()->id, '1', "Kullanıcı game gold satın almak istedi fakat yeterli stok yoktu.");
                    return back()->with('error', 'Satın alma için yeterli stok bulunmuyor, lütfen daha sonra tekrar deneyin veya stokların güncellenmesi için bizimle iletişime geçin.');
                }

                #------------------------------------------------------------------------------------------------------------------------------------------------------------------------

                if ($fiyat > Auth::user()->bakiye) { # fiyat bakiyeden büyükse
                    $bak_dus = 0;                     # bakiyede ne varsa çek sıfırla
                    $cek_dus = Auth::user()->bakiye_cekilebilir - ($fiyat - Auth::user()->bakiye); # kalanı cbakiyeden al
                    $iadeb = Auth::user()->bakiye;
                    $iadec = $fiyat - Auth::user()->bakiye;
                } else {
                    $bak_dus = Auth::user()->bakiye - $fiyat; # fiyat bakiyeden küçükse bakiyeden fiyatı çıkar
                    $cek_dus = Auth::user()->bakiye_cekilebilir; # c.bakiye ye dokunma
                    $iadeb = $fiyat;
                    $iadec = 0;
                }
                #------------------------------------------------------------------------------------------------------------------------------------------------------------------------

                $bakiye_dusurme = DB::table('users')->where('id', Auth::user()->id)->update(['bakiye' => $bak_dus, 'bakiye_cekilebilir' => $cek_dus]);
            }
            if ($bakiye_dusurme) {
                DB::table('game_gold_satis')->insert([
                    'paket' => $paket->id,
                    'user' => Auth::user()->id,
                    'note' => $request->note,
                    'price' => $fiyat,
                    'adet' => $request->adet,
                    'tur' => $request->tur,
                    'status' => '0',
                    'created_at' => date('YmdHis'),
                ]);


                #------------------------------------------------------------------------------------------------------------------------------------------------------------------------
                # burada iade olayında ne kadar bakiye ne kadar cbakiye tarafına iade olacağını bilmek için kayıt alıyoruz
                $lastId = DB::getPdo()->lastInsertId();
                DB::table('iade_bakiye')->insert([
                    'iid' => $lastId,
                    'uid' => Auth::user()->id,
                    'bakiye' => $iadeb,
                    'cbakiye' => $iadec,
                    'tutar' => $fiyat,
                    'created_at' => date("YmdHis"),
                ]);
                # burada iade olayında ne kadar bakiye ne kadar cbakiye tarafına iade olacağını bilmek için kayıt alıyoruz

                #------------------------------------------------------------------------------------------------------------------------------------------------------------------------

                LogCall(Auth::user()->id, '1', "Kullanıcı game gold satın alma işlemi kaydedildi.");
                if ($request->tur == "bize-sat") {
                    return redirect()->route('siparislerim_game_gold')->with("success", 'Satın alma işleminiz başarıyla kaydedildi, Teslim için lütfen canlı desteğe bağlanın.')->with('durum', '1');
                } else {
                    return redirect()->route('siparislerim_game_gold')->with("success", 'Satın alma işleminiz başarıyla kaydedildi, Teslim için lütfen canlı desteğe bağlanın.')->with('durum', '2');
                }
            } else {
                LogCall(Auth::user()->id, '1', "Kullanıcı game gold satın alma işlemi yetersiz bakiye nedeniyle iptal edildi.");
                return back()->with('error', 'Bu işlem için yeterli bakiyeniz yok.');
            }
        } else {
            LogCall(Auth::user()->id, '1', "Kullanıcı game gold satın alma işlemi yetersiz bakiye nedeniyle iptal edildi.");
            return back()->with('error', 'Bu işlem için yeterli bakiyeniz yok.');
        }
    }

    public function siparislerim_game_gold()
    {
        return view('front.pages.siparislerim-game-gold');
    }

    public function epin_satin_al(Request $request)
    {
        if (bloke_kontrol(Auth::user()->id)) {
            LogCall(Auth::user()->id, '2', "E-pin satın almak isteyen kullanıcının bakiyesi blokeli olduğu canlı destek ile irtibata geçmesi bildirildi");
            return back()->with('error', 'Bakiyeniz blokeli, Canlı Destek ile irtibata geçin.');
        }

        //                return back()->with('error', 'Epin satış modülü üzerinde bakım çalışması yapılmaktadır. Lütfen daha sonra tekrar deneyin     :(');
        //                die;

        #---------------------------------------------------------------------------------------------------------------------------------
        $u = \App\Models\GamesPackages::where('id', $request->paket)->first();
        if ($u->api_info) {
            /* if ($request->adet > 1 && !isEpinSepeti())
                return back()->with('error', '1 adetten fazla siparis veremezsiniz...'); */

           /*  if ((Auth::user()->refId != 40 || Auth::user()->refId != 27156) && !isEpinSepeti())
                return back()->with('error', '=)'); */
        }
        if (Auth::check() && Auth::user()->refId > 1 && Auth::user()->onay == 1) {  // Akınsoft yada refrans indirim tanımı var mı
            $refid = Auth::user()->refId;
            $al = DB::select("select epin from bayi where uid='$refid'")[0];  // epin indirim oranı alalım
            $indirimli = $u->price - ($al->epin * $u->price / 100); // indirimli rakam
            $kam_fiy = findGamesPackagesPrice($u->id); // kampanyalı rakam ne ?
            $satis_fiyati = $kam_fiy > $indirimli ? $indirimli : $kam_fiy; // hangisi uygunsa onu yazıyoruz
            $oran = $al->epin;
        } else {
            $satis_fiyati = findGamesPackagesPrice($u->id);
            $oran = $u->discount_amount;
        } // refid yoxa dewam
        #---------------------------------------------------------------------------------------------------------------------------------


        $price = $request->adet * $satis_fiyati; //findGamesPackagesPrice($request->paket);

        #---Yakup sipariş üzerine alım yapılıyorsa önce tedarik onay aşaması
        if ($request->siparis == 316) {

            if (Auth::user()->bakiye + Auth::user()->bakiye_cekilebilir >= $price and $price > 0) {    # <----------------------------------------------------------------------------------

                if ($price > Auth::user()->bakiye) { # fiyat bakiyeden büyükse
                    $bak_dus = 0;                     # bakiyede ne varsa çek sıfırla
                    $cek_dus = Auth::user()->bakiye_cekilebilir - ($price - Auth::user()->bakiye); # kalanı cbakiyeden al
                    $iadeb = Auth::user()->bakiye;
                    $iadec = $price - Auth::user()->bakiye;
                } else {
                    $bak_dus = Auth::user()->bakiye - $price; # fiyat bakiyeden küçükse bakiyeden fiyatı çıkar
                    $cek_dus = Auth::user()->bakiye_cekilebilir; # c.bakiye ye dokunma
                    $iadeb = $price;
                    $iadec = 0;
                }

                $bakiye_dusurme = DB::table('users')->where('id', Auth::user()->id)->update(['bakiye' => $bak_dus, 'bakiye_cekilebilir' => $cek_dus]);

                DB::table('epin_siparisler')->insert([

                    'user'  => Auth::user()->id,
                    'oyun'  => $request->paket,
                    'tutar' => $price,
                    'adet'  => $request->adet,
                    'durum' => 'Onay Bekliyor',
                    'created_at'    => date('YmdHis'),
                ]);

                $lastId = DB::getPdo()->lastInsertId();

                #Teslimat bilgileri yazılıyor
                if(!isset($request->cvp1)) {$request->cvp1='Yok';}
                if(!isset($request->cvp2)) {$request->cvp2='Yok';}

                $soru=DB::select("select * from epin_soru where game_id = (SELECT gt.game
                                from epin_siparisler es
                                left JOIN games_packages gp on gp.id=es.oyun
                                left JOIN games_titles gt on gp.games_titles=gt.id
                                where es.id='$lastId')");

                if(count($soru)>0){$soru1=$soru[0]->soru1;$soru2=$soru[0]->soru2;} else {$soru1='-';$soru2='-';}
                if(count($soru)>0) {
                    DB::table('epin_siparisler')->where('id', $lastId)->update(['tbilgi' => $soru1 . ' = ' . $request->cvp1 . ' / ' . $soru2 . ' = ' . $request->cvp2]);
                }


                # burada iade olayında ne kadar bakiye ne kadar cbakiye tarafına iade olacağını bilmek için kayıt alıyoruz
                DB::table('iade_bakiye')->insert([
                    'iid'           => $lastId,
                    'uid'           => Auth::user()->id,
                    'bakiye'        => $iadeb,
                    'cbakiye'       => $iadec,
                    'tutar'         => $price,
                    'created_at'    => date("YmdHis"),
                ]);
                # burada iade olayında ne kadar bakiye ne kadar cbakiye tarafına iade olacağını bilmek için kayıt alıyoruz
            }
            LogCall(Auth::user()->id, '2', "Kullanıcının epin satın alma işlemi başarıyla gerçekleşti. İşlemi onaylandıktan sonra SMS ile bilgi verilecektir. Ürün Id: " . $request->paket . " Adet: " . $request->adet . " Birim Fiyat: " . $satis_fiyati );
            return redirect()->route('siparislerim')->with("success", 'Satın alma işleminiz başarıyla kaydedildi, işleminiz onaylandıktan sonra size SMS ile bilgi vereceğiz.');
        }
        /*
         * Stok kontrol
         */
        $stok = DB::table('games_packages_codes')->where('package_id', $request->paket)->where('is_used', '0')->count();
        if ($stok < $request->adet) {
            LogCall(Auth::user()->id, '2', "Kullanıcının satın almak istediği miktarda ürün stokta bulunmamaktadır.Bizimle irtibata geçmesi bildirilmiştir. Ürün Id: " . $request->paket . " Adet: " . $request->adet . " Stokta bulunan miktar: " . $stok . " Birim Fiyat: " . $satis_fiyati );
            return back()->with('error', 'Satın almak istediğiniz miktarda stok bulunmamaktadır. Lütfen adedi düşürün veya stoklar eklenmesi için bizimle iletişime geçin.');
            die();
        }
        if (Auth::user()->bakiye + Auth::user()->bakiye_cekilebilir  >= $price and $price > 0) {

            if ($price > Auth::user()->bakiye) { # fiyat bakiyeden büyükse
                $bak_dus = 0;                     # bakiyede ne varsa çek sıfırla
                $cek_dus = Auth::user()->bakiye_cekilebilir - ($price - Auth::user()->bakiye); # kalanı cbakiyeden al
                $iadeb = Auth::user()->bakiye;
                $iadec = $price - Auth::user()->bakiye;
            } else {
                $bak_dus = Auth::user()->bakiye - $price; # fiyat bakiyeden küçükse bakiyeden fiyatı çıkar
                $cek_dus = Auth::user()->bakiye_cekilebilir; # c.bakiye ye dokunma
                $iadeb = $price;
                $iadec = 0;
            }

            $bakiye_dusurme = DB::table('users')->where('id', Auth::user()->id)->update(['bakiye' => $bak_dus, 'bakiye_cekilebilir' => $cek_dus]);


            if ($bakiye_dusurme) {
                function unique_code($limit)
                {
                    return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
                }

                if ($request->stokKodu == 0) {
                    $transId = "0";
                } else {
                    $transId = rand(1, 9999) . time();
                }

                $kayit = DB::table('epin_satis')->insert([
                    'transId' => $transId,
                    'game_title' => $request->baslik,
                    'paketId' => $request->paket,
                    'user' => Auth::user()->id,
                    'adet' => $request->adet,
                    'price' => $price,
                    'status' => '0',
                    'created_at' => date('YmdHis'),
                ]);
                $lastId = DB::getPdo()->lastInsertId();



                # burada iade olayında ne kadar bakiye ne kadar cbakiye tarafına iade olacağını bilmek için kayıt alıyoruz

                DB::table('iade_bakiye')->insert([
                    'iid'           => $lastId,
                    'uid'           => Auth::user()->id,
                    'bakiye'        => $iadeb,
                    'cbakiye'       => $iadec,
                    'tutar'         => $price,
                    'created_at'    => date("YmdHis"),
                ]);
                # burada iade olayında ne kadar bakiye ne kadar cbakiye tarafına iade olacağını bilmek için kayıt alıyoruz



                if (Auth::user()->telefon != '') {
                    $telefon = Auth::user()->telefon;
                } else {
                    $telefon = "00000000000";
                }
                if ($kayit) {

                    DB::raw('LOCK TABLES games_packages_codes write, epin_satis write, epin_satis_kodlar write');

                    if ($request->stokKodu == 0) {
                        $bonus = findGamesPackagesBonus($request->paket);
                        DB::table('users')->where('id', Auth::user()->id)->update(['bakiye' => $bak_dus + ($bonus * $request->adet)]); #--------------------------------BONUS OLAYI
                        DB::table('epin_satis')->where('id', $lastId)->update(['status' => '1',]);
                        foreach (DB::table('games_packages_codes')->where('package_id', $request->paket)->where('is_used', '0')->orderBy('id', 'asc')->take($request->adet)->get() as $item) {
                            DB::table('games_packages_codes')->where('id', $item->id)->update(['is_used' => '1']);
                            DB::table('epin_satis')->where('id', $lastId)->update(['alis' => $item->alis_fiyati, 'kdv' => $item->kdv,]);    #-------------Yakup
                            DB::table('epin_satis_kodlar')->insert([
                                'epin_satis' => $lastId,
                                'code' => $item->code,
                                'code_id' => $item->id,  #--------------Yakup
                            ]);
                        }

                        /*
                         * Fatura Epin

                        $baslikFaturaBilgisi = DB::table('games_titles')->where('id', $request->baslik)->first()->fatura_kes;
                        if ($baslikFaturaBilgisi == 1) {
                            try {
                                if (faturaKes(Auth::user()->id, $price, $lastId, findPackageKdvEpin($request->baslik), 1, $request->adet, $request->paket, 1)) {
                                    LogCall(Auth::user()->id, '1', "Kullanıcıya epin alışveriş faturası ₺" . $price . " üzerinden " . findPackageKdvEpin($request->baslik) . "% ile başarıyla kesildi ve sisteme gönderildi.");
                                } else {
                                    LogCall(Auth::user()->id, '1', "Kullanıcıya epin komisyon faturası ₺" . $price . " üzerinden " . findPackageKdvEpin($request->baslik) . "% kesilmek istendi fakat fatura sisteminde bir hata meydana geldi.");
                                }
                            } catch (Exception $e) {
                                LogCall(Auth::user()->id, '1', "Kullanıcıya epin komisyon faturası ₺" . $price . " üzerinden " . findPackageKdvEpin($request->baslik) . "% kesilmek istendi fakat bizim tarafımızda bir hata meydana geldi.");
                            }
                        } else {
                            LogCall(Auth::user()->id, '1', "Kullanıcıya epin komisyon faturası ₺" . $price . " üzerinden " . findPackageKdvEpin($request->baslik) . "% kesilmek istendi fakat satın aldığı başlık türü için fatura kesme özelliği kapalıydı.");
                        } */

                        LogCall(Auth::user()->id, '2', "Kullanıcının epin satın alma işlemi kaydedildi. E-pin tesliminin görüntülenmesi için siparişinin yanında yer alan detay butonuna basması bildirildi. Ürün Id: " . $request->paket . " Adet: " . $request->adet . " Birim Fiyat: " . $satis_fiyati );

                        DB::raw('UNLOCK TABLES');

                        return redirect()->route('siparislerim')->with("success", 'Satın alma işleminiz başarıyla kaydedildi, E-pin tesliminizi görüntülemek için siparişinizin yanında yer alan detay butonuna basın.')->with('show', '1');
                    } else { //eğer epin  alışverisi ise
                        $sipJson = json_encode(array(
                            "TransactionId" => (int)$transId,
                            "StockCode" => $request->stokKodu,
                            "PhoneNumber" => $telefon,
                            "Email" => Auth::user()->email,
                            "Quantity" => (int)$request->adet,
                        ));
                        $ch = curl_init();
                        $headers = array(
                            'Authorization: ' . getAuthName(),
                            'ApiName: ' . getApiName(),
                            'ApiKey: ' . getApiKey(),
                            'Content-Type: application/json',
                        );
                        curl_setopt($ch, CURLOPT_URL, env('EPIN_BASE') . '/SaveOrder');
                        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                        curl_setopt($ch, CURLOPT_HEADER, 0);
                        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $sipJson);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
                        $response = curl_exec($ch);
                        $sipSorgu = json_decode($response);
                        curl_close($ch);
                        if ($sipSorgu->ResultCode == 100) {
                            DB::table('epin_satis')->where('id', $lastId)->update([
                                'status' => '1',
                            ]);
                            foreach ($sipSorgu->PinCode as $item) {
                                DB::table('epin_satis_kodlar')->insert([
                                    'epin_satis' => $lastId,
                                    'code' => $item,
                                ]);
                            }
                            LogCall(Auth::user()->id, '2', "Kullanıcının epin satın alma işlemi kaydedildi. E-pin tesliminin görüntülenmesi için siparişinin yanında yer alan detay butonuna basması bildirildi. Ürün Id: " . $request->paket . " Adet: " . $request->adet . " Birim Fiyat: " . $satis_fiyati );
                            /* LogCall(Auth::user()->id, '1', "Kullanıcı epin satın alma işlemi kaydedildi."); */
                            return redirect()->route('siparislerim')->with("success", 'Satın alma işleminiz başarıyla kaydedildi, E-pin tesliminizi görüntülemek için siparişinizin yanında yer alan detay butonuna basın.')->with('show', '1');
                        } else {
                            LogCall(Auth::user()->id, '2', "Kullanıcının epin satın alma işlemi kaydedildi fakat kodlar servisteki gecikme nedeniyle henüz teslim edilmedi. Ürün Id: " . $request->paket . " Adet: " . $request->adet . " Birim Fiyat: " . $satis_fiyati );
                            /* LogCall(Auth::user()->id, '1', "Kullanıcı epin satın alma işlemi kaydedildi fakat kodlar servisteki gecikme nedeniyle henüz teslim edilmedi."); */
                            return redirect()->route('siparislerim')->with("success", 'Satın alma işleminiz başarıyla kaydedildi, E-pin tesliminiz birazdan yapılacaktır.');
                        }
                    }
                    LogCall(Auth::user()->id, '2', "Kullanıcı epin satın alma işlemi kayıt edilemedi.");
                    return back()->with('error', 'Sipariş kaydı açılamadı!');
                }
            } else {
                LogCall(Auth::user()->id, '1', "Kullanıcı epin satın alma işlemi yetersiz bakiye nedeniyle iptal edildi. Kullanıcının toplam bakiyesi: " . (Auth::user()->bakiye + Auth::user()->bakiye_cekilebilir) . " Satın almak istediği ürünün satış fiyatı: " . ($request->adet * $satis_fiyati));
                return back()->with('error', 'Bu işlem için yeterli bakiyeniz yok.');
            }
        } else {
            LogCall(Auth::user()->id, '1', "Kullanıcı epin satın alma işlemi yetersiz bakiye nedeniyle iptal edildi. Kullanıcının toplam bakiyesi: " . (Auth::user()->bakiye + Auth::user()->bakiye_cekilebilir) . " Satın almak istediği ürünün satış fiyatı: " . ($request->adet * $satis_fiyati));
            /* LogCall(Auth::user()->id, '1', "Kullanıcı epin satın alma işlemi yetersiz  bakiye nedeniyle iptal edildi."); */
            return back()->with('error', 'Bu işlem için yeterli bakiyeniz yok.');
        }
    }

    public function yeni_satis()
    {
        return view('front.pages.yeni-satis');
    }

    public function hizli_menu()
    {
        return view('front.pages.hizli-menu');
    }

    public function hizli_menu_post(Request $request)
    {
        if (isset($request->id)) {
            DB::table('hizli_menu')->where('id', $request->id)->update([
                'title' => $request->title,
                'link' => $request->link,
                'icon' => $request->icon,
            ]);
        } else {
            $hizli_menu = DB::table('hizli_menu')->where('user', Auth::user()->id)->orderBy('id', 'desc');
            if ($hizli_menu->count() >= 7) {
                return back()->with("error", "7 adet üstünde hızlı menü oluşturamazsınız.");
            }
            DB::table('hizli_menu')->insert([
                'user' => Auth::user()->id,
                'title' => $request->title,
                'link' => $request->link,
                'icon' => $request->icon,
            ]);
        }
        return back()->with("success", __('general.mesaj-9'));
    }

    public function satis_duzenle($ilan)
    {
        return view('front.pages.satis-duzenle')->with('ilan', $ilan);
    }

    public function satis_duzenle_post(Request $request)
    {            #Farklı ilan düzenleme veya işlem gören ilan düzenleme kontrolü yakup
        if (DB::table('pazar_yeri_ilanlar')->where('user', Auth::user()->id)->where('id', $request->ilan)->count() < 1 || DB::table('pazar_yeri_ilan_satis')->where('ilan', $request->ilan)->count() > 0 || $request->price <= 0) {
            return redirect()->route('satici_panelim')->with("error", "Düzenleme yapamazsınız..");
        }

       // if($request->sunucu==''){return redirect()->route('satici_panelim')->with("error", "Sunucu seçilmemiş..");}

        if (bloke_kontrol(Auth::user()->id)) {
            return back()->with('error', 'Bakiyeniz blokeli, Canlı Destek ile irtibata geçin.');
        }

        $ilan = DB::table('pazar_yeri_ilanlar')->where('id', $request->ilan)->first();

        if($ilan->status > 3) {return back()->with('error', 'Bu ilanın durumu kısa bir süre önce değiştiği için düzenleme yapılamadı ');}

        $request->pazar = $ilan->pazar;

        if ($ilan->toplu == 1) {

            if ($request->pazar == 397) {
                if (Auth::user()->id == '18683' || Auth::user()->id == '2644' || Auth::user()->id == '14879' || Auth::user()->id == '233') {
                    $komisyon = 3;
                } else {
                    $komisyon = 5;
                }   // bu kullanıcıya rise için imtiyaz
            } elseif ($request->pazar == 413) {
                if (Auth::user()->id == '18683' || Auth::user()->id == '2644' || Auth::user()->id == '14879' || Auth::user()->id == '233') {
                    $komisyon = 3;
                } else {
                    $komisyon = 5;
                }   // bu kullanıcıya rise için imtiyaz
            }  // rise NFT
            else {
                $komisyon = findUserKomisyon(Auth::user()->id);
            }


            $kazanc = $request->price - ($request->price * $komisyon / 100);
            DB::table('pazar_yeri_ilanlar')->where('id', $request->ilan)->update([
                'price' => $request->price,
                'moment_komisyon' => $kazanc,
                'text' => $request->text,
                'tl' => $request->sure,
                'status' => 0,
                'updated_at' => date('YmdHis'),
            ]);
            LogCall(Auth::user()->id, '1', "Kullanıcı " . $request->ilan . " id'li ilanda düzenleme yaptı.");
            return redirect()->route('satici_panelim')->with("success", "İlanınızda yaptığınız değişiklikler başarıyla kaydedilmiştir. Onaylandıktan sonra yayına girecektir.");
        } else {
            if (DB::table('games_titles_special')->where('games_titles', $ilan->pazar)->count() > 0) { //eğer resmi satıcı yüklüyor ise


                if ($request->pazar == 397) {
                    if (Auth::user()->id == '18683' || Auth::user()->id == '2644' || Auth::user()->id == '14879' || Auth::user()->id == '233') {
                        $komisyon = 3;
                    } else {
                        $komisyon = 5;
                    }   // bu kullanıcıya rise için imtiyaz
                } elseif ($request->pazar == 413) {
                    $komisyon = 5;
                }  // rise NFT
                else {
                    $komisyon = findUserKomisyon(Auth::user()->id);
                }


                $kazanc = $request->price - ($request->price * $komisyon / 100);
                if ($request->hasFile('image')) {
                    $destinationPath = "front/ilanlar";
                    $file = $request->image;
                    $title = Str::slug($request->title);
                    $fileName = $title . "-" . time() . '.' . $file->clientExtension();
                    deleteImageManually('front/ilanlar/' . $ilan->image);
                    $file->move($destinationPath, $fileName);
                    DB::table('pazar_yeri_ilanlar')->where('id', $request->ilan)->update([
                        'image' => $fileName,
                    ]);
                }

                #İlan bilgileri aynı sadece fiyat değişti ise operatör onayına düşmüyor ilan
                $ayni_mi=DB::table('pazar_yeri_ilanlar')->where('id', $request->ilan)->where('title',$request->title)->where('text',$request->text)->count();
                if($ayni_mi==1) {$status=1;} else {$status=0;}

                DB::table('pazar_yeri_ilanlar')->where('id', $request->ilan)->update([
                    'price' => $request->price,
                    'moment_komisyon' => $kazanc,
                    'title' => $request->title,
                    'text' => $request->text,
                   # 'sunucu' => $request->sunucu,
                    'tl' => $request->sure,
                    'type' => 1,
                    'grup' => isset($request->grup)?$request->grup:0,
                    'status' => $status,
                    'updated_at' => date('YmdHis'),
                ]);
                foreach (DB::table('games_titles_features')->where('game_title', $ilan->pazar)->whereNull('deleted_at')->get() as $p) {
                    $name = Str::slug($p->title);
                    DB::table('pazar_yeri_ilan_features')->where('ilan', $ilan->id)->where('feature', $p->id)->update([
                        'value' => $request->$name,
                    ]);
                }
                LogCall(Auth::user()->id, '1', "Kullanıcı " . $request->ilan . " id'li ilanda düzenleme yaptı.");
                return back()->with("success", "İlanınız başarıyla kaydedilmiştir. Onaylandıktan sonra yayına girecektir.");
            } else { //hazır itemlerden seçiyor ise
                $komisyon = findUserKomisyon(Auth::user()->id);
                $kazanc = $request->price - ($request->price * $komisyon / 100);

                #İlan bilgileri aynı sadece fiyat değişti ise operatör onayına düşmüyor ilan
                $ayni_mi=DB::table('pazar_yeri_ilanlar')->where('id', $request->ilan)->where('text',$request->text)->count();
                if($ayni_mi==1) {$status=1;$ek='';} else {$status=0;$ek="Onaylandıktan sonra yayına girecektir.";}

                DB::table('pazar_yeri_ilanlar')->where('id', $request->ilan)->update([
                    'price' => $request->price,
                    'moment_komisyon' => $kazanc,
                    'text' => $request->text,
                    'tl' => $request->sure,
                    'grup' => isset($request->grup)?$request->grup:0,
                    'status' => $status,
                    'updated_at' => date('YmdHis'),
                ]);
                LogCall(Auth::user()->id, '1', "Kullanıcı " . $request->ilan . " id'li ilanında düzenleme yaptı.");
                return redirect()->route('satici_panelim')->with("success", "İlanınızda yaptığınız değişiklikler başarıyla kaydedilmiştir. ".$ek);
            }
        }
    }

    public function twitch_support_yayinci_ol()
    {
        return view('front.pages.twitch.yayinci-ol');
    }

    public function twitch_support_yayinci_ol_post(Request $request)
    {
        if (getUserVerifiyStep() >= 2) {
            if (!isset($request->title) or !isset($request->min_bagis)) {
                return back()->with("error", "Alanları Doldurunuz");
            } else {
                $streamerKayit = DB::table('twitch_support_streamer')->insert([
                    'user' => Auth::user()->id,
                    'title' => $request->title,
                    'min_bagis' => $request->min_bagis,
                    'created_at' => date('YmdHis'),
                ]);
                if ($streamerKayit) {
                    $link = 'https://streamlabs.com/api/v1.0/authorize?client_id=' . env("STREAM_ID") . '&redirect_uri=' . env("STREAM_URL") . '&response_type=code&scope=donations.read+donations.create';
                    LogCall(Auth::user()->id, '1', "Kullanıcı twitch yayıncısı olmak için başvurdu.");
                    return redirect($link);
                } else {
                    LogCall(Auth::user()->id, '1', "Kullanıcı twitch yayıncısı kaydı oluşturulurken bir hata meydana geldi.");
                    return back()->with("error", "Bir hata meydana geldiği için yayıncı kaydınız açılamadı.");
                }
            }
        } else {
            LogCall(Auth::user()->id, '1', "Kullanıcı twitch yayıncısı kaydı açmak istedi fakat telefonu onaylı değildi.");
            return redirect()->route('hesap_onayla')->with('error', 'Twitch başvurusu için lütfen telefon numaranızı onaylayın');
        }
    }

    public function stream_notify()
    {
        if (isset($_GET['error'])) {
            if ($_GET['error'] = "access_denied") {
                DB::table('twitch_support_streamer')->where('user', Auth::user()->id)->update(['status' => 2]);
                LogCall(Auth::user()->id, '1', "Kullanıcı twitch kayıt işlemini iptal etti.");
                return redirect()->route('twitch_support_yayinci_ol')->with("error", "Kayıt işlemini iptal ettiniz.");
            }
        }
        if (isset($_GET['code'])) {
            $access_token = getStreamAccess($_GET['code']);
            if (isset($access_token->access_token)) {
                DB::table('twitch_support_streamer')->where('user', Auth::user()->id)->update([
                    'status' => 1,
                    'access_token' => $access_token->access_token,
                ]);
                $kullanici = getStreamUserInfoById(Auth::user()->id, $access_token->access_token);
                if (isset($kullanici->twitch)) {
                    DB::table('twitch_support_streamer')->where('user', Auth::user()->id)->update([
                        'yayin_link' => Str::slug($kullanici->twitch->name),
                        'image' => $kullanici->twitch->icon_url,
                        'twitch_id' => $kullanici->twitch->id,
                    ]);
                }
            }
            LogCall(Auth::user()->id, '1', "Kullanıcı twitch kayıt işlemi başarıyla gerçekleştirildi.");
            return redirect()->route('twitch_support_yayinci_ol')->with("success", "Kayıt işleminiz başarıyla tamamlandı.");
        }
    }

    public function twitch_support_yayinci_support(Request $request)
    {
        if (bloke_kontrol(Auth::user()->id)) {
            return false;
        }

        if (isset($request->yayinci) and isset($request->amount)) {
            if ($request->amount < 1) {
                LogCall(Auth::user()->id, '1', "Eksi bakiye kullanarak TW destek sistemini kandırmaya çalıştı.");
                return false;
            }

            $access_token = DB::table('twitch_support_streamer')->where('id', $request->yayinci)->first()->access_token;
            $yayinci = DB::table('twitch_support_streamer')->where('id', $request->yayinci)->first();
            if ($yayinci->min_bagis > $request->amount) {
                LogCall(Auth::user()->id, '1', "Kullanıcı twitch donate işlemi yayıncının belirlediği tutarın (" . $yayinci->min_bagis . ") altında (" . $request->amount . ") olduğu için kabul edilmedi.");
                return false;
            }


            #-------------------------------------------------------------
            $bak = DB::table('users')->where('id', Auth::user()->id)->first();
            if ($bak->bakiye + $bak->bakiye_cekilebilir >= $request->amount and $request->amount > 0) {    # <----------------------------------------------------------------------------------

                if ($request->amount > $bak->bakiye) { # fiyat bakiyeden büyükse
                    $bak_dus = 0;                     # bakiyede ne varsa çek sıfırla
                    $cek_dus = $bak->bakiye_cekilebilir - ($request->amount - $bak->bakiye); # kalanı cbakiyeden al

                } else {
                    $bak_dus = $bak->bakiye - $request->amount; # fiyat bakiyeden küçükse bakiyeden fiyatı çıkar
                    $cek_dus = $bak->bakiye_cekilebilir; # c.bakiye ye dokunma

                }
            }

            #-------------------------------------------------------------

            if ($bak->bakiye + $bak->bakiye_cekilebilir >= $request->amount and $request->amount > 0) {
                if (DB::table('users')->where('id', Auth::user()->id)->update(['bakiye' => $bak_dus, 'bakiye_cekilebilir' => $cek_dus])) {
                    $bagis = setStreamUserDonate($request->name, $request->message, $request->amount, $access_token);
                    if (isset($bagis)) {
                        if ($request->amount > 0) {
                            $request->message = preg_replace('/[^A-Za-z0-9\-]/', ' ', $request->message);
                            DB::table('twitch_support_donates')->insert([
                                'streamer' => $request->yayinci,
                                'user' => Auth::user()->id,
                                'amount' => $request->amount,
                                'title' => $request->name,
                                'text' => $request->message,
                                'donate_id' => $bagis,
                                'created_at' => date('YmdHis'),
                            ]);
                            $yayinci = DB::table('twitch_support_streamer')->where('id', $request->yayinci)->first()->user;
                            $yayinci = DB::table('users')->where('id', $yayinci)->first();
                            DB::table('users')->where('id', $yayinci->id)->update(['bagis_bakiye' => $yayinci->bagis_bakiye + $request->amount]);
                            setBildirim($yayinci->id, '3', 'Bağış Aldınız', $request->amount . ' TL tutarında bir bağış aldınız, detaylar için tıklayın.', route('twitch_support_yayinci_panelim'));
                            LogCall(Auth::user()->id, '1', "Kullanıcı " . $yayinci->id . " id'li yayıncıya bağış (" . $request->amount . ") gönderdi.");
                            return true;
                            //return back()->with("success", "Gönderdiğiniz donate başarıyla alınmıştır, teşekkürler!");
                        }
                        LogCall(Auth::user()->id, '1', "Kullanıcı twitch donate işlemini gerçekleştiremedi çünkü eksi bir değer (" . $request->amount . ")girdi.");
                        return false;
                    } else {
                        LogCall(Auth::user()->id, '1', "Kullanıcı twitch donate işlemini gerçekleştiremedi.");
                        return false;
                        //return back()->with("error", "Donate gönderilemedi");
                    }
                } else {
                    LogCall(Auth::user()->id, '1', "Kullanıcı twitch donate işlemini yetersiz bakiye nedeniyle gerçekleştiremedi.");
                    return false;
                    //return back()->with("error", "Bakiyeniz bu işlemi yapabilmek için yeterli değil.");
                }
            } else {
                LogCall(Auth::user()->id, '1', "Kullanıcı twitch donate işlemini yetersiz bakiye nedeniyle gerçekleştiremedi.");
                return false;
                //return back()->with("error", "Bakiyeniz bu işlemi yapabilmek için yeterli değil.");
            }
        } else {
            LogCall(Auth::user()->id, '1', "Kullanıcı twitch donate işlemi sırasında bir hata meydana geldi.");
            return false;
        }
    }

    public function twitch_support_yayinci_panelim()
    {
        return view('front.pages.twitch.yayinci-panelim');
    }

    public function twitch_support_yayinci_ayarlarim()
    {
        return view('front.pages.twitch.yayinci-ayarlarim');
    }

    public function twitch_support_yayinci_ayarlarim_post(Request $request)
    {
        $link = Str::slug($request->title);
        DB::table('twitch_support_streamer')->where('user', Auth::user()->id)->update([
            'title' => $request->title,
            'min_bagis' => $request->min_bagis,
            'yayin_link' => $link,
        ]);

        foreach (DB::table('bildirim_kategorileri')->where('title', 'like', '%twitch%')->get() as $bk) {
            $inputName = Str::slug($bk->title);
            if (isset($request->$inputName)) {
                if (DB::table('bildirim_kullanici')->where('user', Auth::user()->id)->where('bildirim', $bk->id)->count() < 1) {
                    DB::table('bildirim_kullanici')->insert([
                        'user' => Auth::user()->id,
                        'bildirim' => $bk->id,
                        'created_at' => date('YmdHis'),
                    ]);
                }
            } else {
                if (DB::table('bildirim_kullanici')->where('user', Auth::user()->id)->where('bildirim', $bk->id)->count() > 0) {
                    DB::table('bildirim_kullanici')->where('user', Auth::user()->id)->where('bildirim', $bk->id)->delete();
                }
            }
        }
        LogCall(Auth::user()->id, '1', "Kullanıcı twitch yayıncı ayarlarını güncelledi.");
        return back()->with("success", __('general.mesaj-9'));
    }

    public function twitch_support_yayinci_bakiye_cevir()
    {
        return view('front.pages.twitch.yayinci-bakiye-cevir');
    }

    public function twitch_support_yayinci_bakiye_cevir_post(Request $request)
    {
        if (bloke_kontrol(Auth::user()->id)) {
            return back()->with('error', 'Bakiyeniz blokeli, Canlı Destek ile irtibata geçin.');
        }
        if (getUserVerifiyStep() >= 2) {
            if ($request->site_bakiye == 1) { //eğer site bakiyesine dönüştürme işlemi ise
                if (Auth::user()->bagis_bakiye >= $request->amount) {
                    if (DB::table('users')->where('id', Auth::user()->id)->update(['bagis_bakiye' => Auth::user()->bagis_bakiye - $request->amount])) {
                        if (DB::table('users')->where('id', Auth::user()->id)->update(['bakiye' => Auth::user()->bakiye + $request->amount])) {
                            DB::table('twitch_support_cevirmeler')->insert([
                                'user' => Auth::user()->id,
                                'amount' => $request->amount,
                                'kesinti' => '0',
                                'tur' => '1',
                                'status' => '1',
                                'created_at' => date('YmdHis'),
                            ]);
                            LogCall(Auth::user()->id, '1', "Kullanıcı twitch bakiye dönüştürme işlemi başarıyla gerçekleştirildi.");
                            return back()->with("success", "Site bakiyesine çevirme işleminiz başarıyla gerçekleşti.");
                        } else {
                            LogCall(Auth::user()->id, '1', "Kullanıcı twitch bakiye dönüştürme işlemini yaparken bir sorun oluştu.");
                            return back()->with("error", "Bakiyeniz eklenirken bir sorun oluştu. Lütfen canlı destek ile iletişime geçin.");
                        }
                    } else {
                        LogCall(Auth::user()->id, '1', "Kullanıcı twitch bakiye dönüştürme işlemini yapması için bakiyesi yeterli değildi.");
                        return back()->with("error", "Bakiyeniz bu işlemi yapabilmek için yeterli değil.");
                    }
                } else {
                    LogCall(Auth::user()->id, '1', "Kullanıcı twitch bakiye dönüştürme işlemini yapması için bakiyesi yeterli değildi.");
                    return back()->with("error", "Bakiyeniz bu işlemi yapabilmek için yeterli değil.");
                }
            } else {
                if (Auth::user()->bagis_bakiye >= $request->amount) {
                    if (DB::table('users')->where('id', Auth::user()->id)->update(['bagis_bakiye' => Auth::user()->bagis_bakiye - $request->amount])) {
                        if (DB::table('twitch_kesintisiz_yayinci')->where('streamer', Auth::user()->id)->count() > 0) {
                            if (DB::table('twitch_kesintisiz_yayinci')->where('streamer', Auth::user()->id)->first()->status == 1) {
                                $kesinti = 0;
                            } else {
                                $kesinti = DB::table('settings')->first()->yayin_komisyon;
                            }
                        } else {
                            $kesinti = DB::table('settings')->first()->yayin_komisyon;
                        }
                        DB::table('twitch_support_cevirmeler')->insert([
                            'user' => Auth::user()->id,
                            'amount' => $request->amount,
                            'kesinti' => $kesinti,
                            'tur' => '2',
                            'odeme_kanali' => $request->odeme_kanali,
                            'status' => '0',
                            'created_at' => date('YmdHis'),
                        ]);
                        LogCall(Auth::user()->id, '1', "Kullanıcı twitch ödeme çekim talebi oluşturdu.");
                        setBildirim(Auth::user()->id, '4', 'Ödeme Çekim Talebiniz Oluşturuldu', 'Ödeme çekim talebiniz oluşturuldu, detaylar için tıklayın.', route('bakiye_cek'));
                        return back()->with("success", "Para çekim talebiniz başarıyla alındı. Onaydan sonra ödemeniz gerçekleştirilecektir.");
                    } else {
                        LogCall(Auth::user()->id, '1', "Kullanıcı twitch ödeme çekim talebi yetersiz bakiye nedeniyle oluşturulamadı.");
                        setBildirim(Auth::user()->id, '4', 'Ödeme Çekim Talebiniz Oluşturulamadı', 'Ödeme çekim talebiniz oluşturulamadı, detaylar için tıklayın.', route('bakiye_cek'));
                        return back()->with("error", "Bakiyeniz bu işlemi yapabilmek için yeterli değil.");
                    }
                } else {
                    LogCall(Auth::user()->id, '1', "Kullanıcı twitch ödeme çekim talebi yetersiz bakiye nedeniyle oluşturulamadı.");
                    setBildirim(Auth::user()->id, '4', 'Ödeme Çekim Talebiniz Oluşturulamadı', 'Ödeme çekim talebiniz oluşturulamadı, detaylar için tıklayın.', route('bakiye_cek'));
                    return back()->with("error", "Bakiyeniz bu işlemi yapabilmek için yeterli değil.");
                }
            }
        } else {
            LogCall(Auth::user()->id, '1', "Kullanıcı twitch ödeme çekim talebi oluşturmak istedi fakat telefon numarası onaylı değildi.");
            return redirect()->route('hesap_onayla')->with('error', 'Twitch bakiye çevirme işlemleriniz için lütfen telefon numaranızı onaylayın.');
        }
    }

    public function twitch_support_yayinci_kesintisiz()
    {
        return view('front.pages.twitch.yayinci-kesintisiz');
    }

    public function twitch_support_yayinci_kesintisiz_post(Request $request)
    {
        if (DB::table('twitch_kesintisiz_yayinci')->where('streamer', Auth::user()->id)->count() > 0) {
            DB::table('twitch_kesintisiz_yayinci')->where('streamer', Auth::user()->id)->update([
                'twitch_url' => $request->twitch_url,
                'twitch_clip_link' => $request->twitch_clip_link,
                'text' => $request->text,
                'status' => 0,
            ]);
            LogCall(Auth::user()->id, '1', "Kullanıcı twitch kesintisiz başvuru talebi tekrar alındı.");
            return back()->with("success", "Kesintisiz yayıncı başvurunuz tekrardan alınmıştır, başvuru durumunuzu bu ekrandan görebilirsiniz.");
        } else {
            DB::table('twitch_kesintisiz_yayinci')->insert([
                'streamer' => Auth::user()->id,
                'twitch_url' => $request->twitch_url,
                'twitch_clip_link' => $request->twitch_clip_link,
                'text' => $request->text,
                'status' => 0,
                'created_at' => date('YmdHis'),
            ]);
            LogCall(Auth::user()->id, '1', "Kullanıcı twitch kesintisiz başvuru talebi oluşturuldu.");
            return back()->with("success", "Kesintisiz yayıncı başvurunuz alınmıştır, başvuru durumunuzu bu ekrandan görebilirsiniz.");
        }
    }

    public function favorilerim()
    {
        return view('front.pages.favorilerim');
    }

    public function favori_ekle($type, $id)
    {
        if (isset(Auth::user()->id)) {
            DB::table('favoriler')->insert([
                'user' => Auth::user()->id,
                'favoriId' => $id,
                'type' => $type,
                'created_at' => date('YmdHis'),
            ]);
            return back()->with("success", "Favorilere eklendi.");
        } else {
            return back()->with("error", "Favorilere ekleyebilmek için lütfen öncelikle oturum açın.");
        }
    }

    public function favori_kaldir($type, $id)
    {
        DB::table('favoriler')->where('user', Auth::user()->id)->where('type', $type)->where('favoriId', $id)->update([
            'deleted_at' => date('YmdHis'),
        ]);
        return back()->with("success", "Favorilerden kaldırıldı.");
    }

    public function yorum_yap(Request $request)
    {
        DB::table('comments')->insert([
            'user' => Auth::user()->id,
            'text' => $request->text,
            'rate' => $request->rate,
            'lang' => 'tr',
            'status' => 0,
            'oyun' => $request->oyun,
            'updated_at' => date('YmdHis'),
            'created_at' => date('YmdHis'),
        ]);
        LogCall(Auth::user()->id, '1', "Kullanıcı yorumu başarıyla kaydedildi.");
        return back()->with('success', 'Yorumunuz başarıyla kaydedilmiştir. Onaylandıktan sonra yayına girecektir.');
    }

    public function satici_yorum_yap(Request $request)
    {
        if (getUserVerifiyStep() >= 2) {
            DB::table('satici_yorumlar')->insert([
                'satici' => $request->satici,
                'yapan' => Auth::user()->id,
                'text' => $request->text,
                'puan' => $request->rate,
                'status' => '0',
                'created_at' => date('YmdHis'),
            ]);
            LogCall(Auth::user()->id, '1', "Kullanıcı satıcıya yaptığı yorum başarıyla kaydedildi.");
            return back()->with("success", "Yorumunuz başarıyla kaydedilmiştir. Onaylandıktan sonra yayına alınacaktır.");
        } else {
            LogCall(Auth::user()->id, '1', "Kullanıcı satıcıya yaptığı yorum telefon onayından dolayı kaydedilemedi.");
            return redirect()->route('hesap_onayla')->with('error', 'Yorum yapabilmek için lütfen telefon numaranızını doğrulayın.');
        }
    }

    public function yayin_bagislarim()
    {
        return view('front.pages.yayinci-bagislarim');
    }

    public function cd_key_detay_satin_al_post(Request $request)
    {
        if (bloke_kontrol(Auth::user()->id)) {
            return back()->with('error', 'Bakiyeniz blokeli, Canlı Destek ile irtibata geçin.');
        }
        $cdkey = DB::table('muve_games')->where('id', $request->gameId)->whereNull('deleted_at')->first();



        if ($cdkey->discount_amount > 0 && $cdkey->discount_date != '') { # indirim tanımlıysa süresini kontrol edelim
            $date1 = new \DateTime("now"); # tarihleri al
            $date2 = new \DateTime($cdkey->discount_date);
            if ($date1 < $date2) { # indirim halen geçerli mi
                $fiyat = $cdkey->muvePrice - $cdkey->muvePrice * $cdkey->discount_amount / 100; # evet ise indirimi uygula
            } else {
                $fiyat = $cdkey->muvePrice;
            }  #indirim süresi dolmuş ise  normal fiyat
        } else {
            $fiyat = $cdkey->muvePrice;
        } // indirim yoxa normal fiyat


        //$tekilFiyatOrigin = number_format((float)$cdkey->muvePrice, 2, '.', '');
        $tekilFiyatOrigin = number_format((float)$fiyat, 2, '.', '');
        //$tekilFiyat = currencyConverter($cdkey->muvePrice, $cdkey->muveCurrency, 'TRY');
        $tekilFiyat = round($fiyat, 2);

        $price = $request->adet * $tekilFiyat;

        if (Auth::user()->bakiye + Auth::user()->bakiye_cekilebilir >= $price) {
            $transId = rand(1, 9999) . time();
            $kayit = DB::table('muve_games_satis')->insert([
                'transId' => $transId,
                'gameId' => $request->gameId,
                'muveId' => $request->muveId,
                'user' => Auth::user()->id,
                'adet' => $request->adet,
                'price' => $price,
                'status' => '0',
                'note' => 'Sipariş oluşturuldu, yanıt bekleniyor.',
                'created_at' => date('YmdHis'),
            ]);
            $lastId = DB::getPdo()->lastInsertId();
            if ($kayit) {
                $sipJson = json_encode(array(
                    "customer" => array("email" => Auth::user()->email, "ip" => $request->ip()),
                    "cart" => array(array("id" => $request->muveId, "quantity" => $request->adet, "unit_price" => $tekilFiyatOrigin)),
                    "country" => 'TR', # $cdkey->muveCountry,
                    "number" => $transId,
                    "created_at" => date("Y-m-d H:i:s"),
                ));
                $ch = curl_init();
                $headers = array(
                    'Authorization: Bearer ' . muveAuth(),
                    'Content-Type: application/json',
                );
                curl_setopt($ch, CURLOPT_URL, env('MUVE_BASE_URL') . '/api/orders/create');
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $sipJson);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 30);
                $response = curl_exec($ch);
                $sipSorgu = json_decode($response);
                curl_close($ch);
                if ($sipSorgu->code == 200) { //sipariş başarılı ise
                    if (isset($sipSorgu->data->number)) { // siparişi onaylayalım
                        $ch = curl_init();
                        $headers = array(
                            'Authorization: Bearer ' . muveAuth(),
                            'Content-Type: application/json',
                        );
                        curl_setopt($ch, CURLOPT_URL, env('MUVE_BASE_URL') . '/api/orders/' . $transId . '/confirm');
                        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                        curl_setopt($ch, CURLOPT_HEADER, 0);
                        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
                        curl_setopt($ch, CURLOPT_POSTFIELDS, "");
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
                        $response = curl_exec($ch);
                        curl_close($ch);
                        $sipSorgu2 = json_decode($response);
                        if ($sipSorgu2->code == 200) {
                            #-----------------------------------------------------------------------------------------------------------------------------------------------------------------
                            if (Auth::user()->id == '2497') {
                                $price = $cdkey->alis;
                            } # bizim ofis hesabına alış fiyatından werelim ;)
                            if ($price > Auth::user()->bakiye) { # fiyat bakiyeden büyükse
                                $bak_dus = 0;                     # bakiyede ne varsa çek sıfırla
                                $cek_dus = Auth::user()->bakiye_cekilebilir - ($price - Auth::user()->bakiye); # kalanı cbakiyeden al
                            } else {
                                $bak_dus = Auth::user()->bakiye - $price; # fiyat bakiyeden küçükse bakiyeden fiyatı çıkar
                                $cek_dus = Auth::user()->bakiye_cekilebilir; # c.bakiye ye dokunma
                            }
                            #-----------------------------------------------------------------------------------------------------------------------------------------------------------------

                            DB::table('users')->where('id', Auth::user()->id)->update(['bakiye' => $bak_dus, 'bakiye_cekilebilir' => $cek_dus]);
                            DB::table('muve_games_satis')->where('id', $lastId)->update([
                                'muveTransId' => $sipSorgu->data->number,
                                'status' => '1',
                            ]);

                            #------------------------------------Muve Key aliniyor karsidan DB kayit için
                            $ch = curl_init();
                            $headers = array('Authorization: Bearer ' . muveAuth(),);
                            curl_setopt($ch, CURLOPT_URL, env('MUVE_BASE_URL') . '/api/orders/' . $transId . '/keys');
                            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                            curl_setopt($ch, CURLOPT_HEADER, 0);
                            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
                            curl_setopt($ch, CURLOPT_POSTFIELDS, '');
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
                            $response = curl_exec($ch);
                            $result_game = json_decode($response);
                            curl_close($ch);
                            if ($result_game->code == 200) {
                                $bilgiler = $result_game->data;
                            } else {
                                $bilgiler = array();
                            }

                            $tar = date('YmdHis');
                            $uid = Auth::user()->id;

                            foreach ($bilgiler as $bilgi) {
                                if ($bilgi->key_value != '') {
                                    DB::select("insert into muve_keys (user,trans,mkey,created_at) values('$uid','$transId','$bilgi->key_value','$tar')");
                                }
                            }

                            #------------------------------------------------------------------------------------------

                            LogCall(Auth::user()->id, '1', "Kullanıcı cd key satın alma işlemi kaydedildi.");
                            return redirect()->route('siparislerim_cdkey')->with("success", 'Satın alma işleminiz başarıyla kaydedildi, CD-key tesliminizi görüntülemek için siparişinizin yanında yer alan detay butonuna basın.')->with('show', '1');
                        } else {
                            DB::table('muve_games_satis')->where('id', $lastId)->update([
                                'status' => '2',
                                'note' => getMuveErrorsDesc($sipSorgu2->errors[0]->code),
                            ]);
                            LogCall(Auth::user()->id, '1', "Kullanıcı cd key satın alma işlemi kaydedildi fakat cdkey'leri teslim edilemedi.");
                            return redirect()->route('siparislerim_cdkey')->with("error", "Satın alma işleminiz başarıyla kaydedildi fakat cd'keyleriniz teslim edilemedi.");
                        }
                    } else {
                        DB::table('muve_games_satis')->where('id', $lastId)->update([
                            'status' => '2',
                            'note' => getMuveErrorsDesc($sipSorgu->errors[0]->code),
                        ]);
                        LogCall(Auth::user()->id, '1', "Kullanıcı cd key satın alma işlemi kaydedildi fakat cdkey'leri teslim edilemedi.");
                        return redirect()->route('siparislerim_cdkey')->with("error", "Satın alma işleminiz başarıyla kaydedildi fakat cd'keyleriniz teslim edilemedi.");
                    }
                } else {
                    DB::table('muve_games_satis')->where('id', $lastId)->update([
                        'status' => '2',
                    ]);
                    LogCall(Auth::user()->id, '1', "Kullanıcı cd key satın alma işlemi kaydedildi fakat cdkey'leri teslim edilemedi.");
                    return redirect()->route('siparislerim_cdkey')->with("error", "Satın alma işleminiz kaydedilemedi!");
                }
            } else {
                LogCall(Auth::user()->id, '1', "Kullanıcı cd key satın alma işlemi kayıt edilemedi.");
                return back()->with('error', 'Sipariş kaydı açılamadı!');
            }
        } else {
            LogCall(Auth::user()->id, '1', "Kullanıcı cd key satın alma işlemi yetersiz bakiye nedeniyle iptal edildi.");
            return back()->with('error', 'Bu işlem için yeterli bakiyeniz yok.');
        }
    }

    public function siparislerim_cdkey()
    {
        return view('front.pages.muve.siparisler');
    }
}
