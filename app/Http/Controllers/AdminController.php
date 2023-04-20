<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use File;

class AdminController extends Controller
{
    public function index()
    {
        return view('back.pages.index');
    }

    public function razer(){return view('back.pages.razer.index');}

    public function odemeler()
    {
        return view('back.pages.odemeler.index');
    }

    public function bayi()
    {
        return view('back.pages.bayi.bayiler');
    }
    public function epinf()
    {
        return view('back.pages.fatura.epinf');
    }
    public function goldf()
    {
        return view('back.pages.fatura.goldf');
    }
    public function pazarf()
    {
        return view('back.pages.fatura.pazarf');
    }
    public function siparisler()
    {
        return view('back.pages.siparisler.index');
    }
    public function odeme_onay()
    {
        return view('back.pages.odemeler.takip');
    }
    public function epintakip()
    {
        return view('back.pages.epin.epintakip');
    }


    public function odemeler_add(Request $request)
    {
        if ($request->hasFile('image')) {
            $destinationPath = "front/bank_logo";
            $file = $request->image;
            $title = Str::slug($request->title);
            $fileName = $title . "-" . time() . '.' . $file->clientExtension();
            $file->move($destinationPath, $fileName);
        } else {
            $fileName = "";
        }

        if ($request->hasFile('image_dark')) {
            $destinationPath = "front/bank_logo";
            $file = $request->image_dark;
            $title = Str::slug($request->title) . "-dark";
            $fileNameDark = $title . "-" . time() . '.' . $file->clientExtension();
            $file->move($destinationPath, $fileNameDark);
        } else {
            $fileNameDark = "";
        }

        $iban = str_replace(' ', '', $request->iban);
        DB::table('payment_channels_eft')->insert([
            'title' => $request->title,
            'bankSlug' => $request->bankSlug,
            'image' => $fileName,
            'image_dark' => $fileNameDark,
            'alici' => $request->alici,
            'iban' => $iban,
            'sube' => $request->sube,
            'hesap' => $request->hesap,
            'havale_kesinti' => $request->havale_kesinti,
            'atm_kesinti' => $request->atm_kesinti,
            'text' => $request->text,
            'channel_type' => $request->channel_type,
            'updated_at' => date('YmdHis'),
            'created_at' => date('YmdHis'),
        ]);
        LogCall(Auth::user()->id, '3', "Kullanıcı " . $request->title . " isimli yeni bir banka ekledi.");
        return back()->with("success", __('general.mesaj-9'));
    }

    public function odemeler_edit(Request $request)
    {
        $odemeYontemi = DB::table('payment_channels_eft')->where('id', $request->id)->first();
        if ($request->image != '') {
            $destinationPath = "front/bank_logo";
            $dosya = $destinationPath . "/" . $odemeYontemi->image;
            if ($odemeYontemi->image != "") {
                if (file_exists($dosya)) {
                    unlink($dosya);
                }
            }

            $file = $request->image;
            $title = Str::slug($request->title);
            $fileName = $title . "-" . time() . '.' . $file->clientExtension();
            $file->move($destinationPath, $fileName);
            DB::table('payment_channels_eft')->where('id', $request->id)->update([
                'image' => $fileName,
            ]);
        }

        if ($request->image_dark != '') {
            $destinationPath = "front/bank_logo";
            $dosya = $destinationPath . "/" . $odemeYontemi->image_dark;
            if ($odemeYontemi->image_dark != "") {
                if (file_exists($dosya)) {
                    unlink($dosya);
                }
            }

            $file = $request->image_dark;
            $title = Str::slug($request->title) . "-dark";
            $fileNameDark = $title . "-" . time() . '.' . $file->clientExtension();
            $file->move($destinationPath, $fileNameDark);
            DB::table('payment_channels_eft')->where('id', $request->id)->update([
                'image_dark' => $fileNameDark,
            ]);
        }
        $iban = str_replace(' ', '', $request->iban);
        DB::table('payment_channels_eft')->where('id', $request->id)->update([
            'title' => $request->title,
            'bankSlug' => $request->bankSlug,
            'alici' => $request->alici,
            'iban' => $iban,
            'sube' => $request->sube,
            'hesap' => $request->hesap,
            'havale_kesinti' => $request->havale_kesinti,
            'atm_kesinti' => $request->atm_kesinti,
            'text' => $request->text,
            'channel_type' => $request->channel_type,
            'updated_at' => date('YmdHis'),
        ]);
        LogCall(Auth::user()->id, '3', "Kullanıcı " . $odemeYontemi->title . " isimli bankayı düzenledi.");
        return back()->with("success", __('general.mesaj-9'));
    }

    public function tedarikciler()
    {
        return view('back.pages.tedarikciler.index');
    }

    public function tedarikciler_add(Request $request)
    {
        DB::table('games_packages_codes_suppliers')->insert([
            'title' => $request->title,
            'updated_at' => date('YmdHis'),
            'created_at' => date('YmdHis'),
        ]);
        $last_id_1 = DB::getPdo()->lastInsertId();

        DB::table('cariy_hesaplar')->insert([
           'grup' => 8,
           'unvan' => $request->title,
           'bakiye' =>0,
           'para_birimi' =>1,
           'fatura_info' =>'{"VD": null,"VN": null,"ADRES": null}',
           'created_at' => date('YmdHis')
        ]);
        $last_id_2 = DB::getPdo()->lastInsertId();

        DB::table('games_packages_codes_suppliers')->where('id',$last_id_1)->update(['cid'=>$last_id_2]);

        LogCall(Auth::user()->id, '3', "Kullanıcı " . $request->title . " isimli yeni bir tedarikçi ekledi.");
        return back()->with("success", __('general.mesaj-9'));
    }

    public function tedarikciler_edit(Request $request)
    {
        $tedarikci = DB::table('games_packages_codes_suppliers')->where('id', $request->id)->first();
        DB::table('games_packages_codes_suppliers')->where('id', $request->id)->update([
            'title' => $request->title,
            'updated_at' => date('YmdHis'),
        ]);

        DB::table('cariy_hesaplar')->where('id',$tedarikci->cid)->update(['unvan' => $request->title]);

        LogCall(Auth::user()->id, '3', "Kullanıcı " . $tedarikci->title . " isimli tedarikçiyi düzenledi.");
        return back()->with("success", __('general.mesaj-9'));
    }

    public function odemeler_onayla($durum, $id)
    {
//        return back();
        $users = DB::table('odemeler')->where('id', $id)->first();

        if ($users->status != 0)
            return back()->with("error", "Bu işlem daha once onaylanmış");

        DB::table('odemeler')->where('id', $id)->update([
            'status' => $durum,
        ]);
        $user = DB::table('users')->where('id', $users->user);
        if ($durum == 1) {

            //            DB::table('bakiye_log')->insert([
            //                'user'       => Auth::user()->id,
            //                'bakiye'     => $user->first()->bakiye,
            //                'bsonrasi'   => $user->first()->bakiye + $users->amount,
            //                'cbakiye'    => $user->first()->bakiye,
            //                'csonrasi'   => $user->first()->bakiye,
            //                'islem'      => $users->amount . " TL değerindeki ödeme yapıldı.",
            //                'tutar'      => $users->amount,
            //                'created_at' => date('YmdHis')
            //            ]);

            $user->update(['bakiye' => $user->first()->bakiye + $users->amount,]);

#------------------------------------------------------  Cari tabloya para girisi
            if(strpos($users->description,'Ziraat')!==false)        {$banka=28;$tur=11;}
            if(strpos($users->description,'QNB')!==false)           {$banka=29;$tur=13;}
            if(strpos($users->description,'Akbank')!==false)        {$banka=30;$tur=12;}
            if(strpos($users->description,'Kuveyttürk')!==false)    {$banka=31;$tur=15;}
            if(strpos($users->description,'Vakıfbank')!==false)     {$banka=32;$tur=14;}


            DB::table('cariy_fisler')->insert([
                'kaynak_cari' => 0,
                'hedef_cari' => $banka,
                'cikan_tutar' => $users->amount,
                'aktarilan_tutar' => $users->amount,
                'aciklama' => $user->first()->name . ' tarafından Banka EFT Bakiye yüklemesi UID:'.$user->first()->id,
                'turu' =>$tur,
                'created_at' => date('YmdHis')
            ]);
            #------Cari log
            $last_id = DB::getPdo()->lastInsertId();
            $bak_once=DB::table('cariy_hesaplar')->where('id',$banka)->first()->bakiye;
            $bak_sonra=$users->amount+$bak_once;
            DB::table('cariy_log')->insert([
                'hesap_id'=>$banka,
                'fis_id' =>$last_id,
                'onceki_bak'=> $bak_once,
                'sonraki_bak'=>$bak_sonra,
                'aciklama'=>'Kullanıcı Bakiye Ekleme',
                'user'=>$users->user,
                'created_at'=>date('YmdHis')
            ]);

            DB::select("update cariy_hesaplar set bakiye=bakiye+$users->amount where id=$banka"); // banka hesap bakiyesi upt.
#-----------------------------------------------------


            LogCall(Auth::user()->id, '3', "Kullanıcı " . $user->first()->name . " isimli kullanıcının " . $users->amount . " TL değerindeki ödemesini onayladı.");
            setBildirim($user->first()->id, '4', 'Ödeme Talebiniz Onaylandı', 'Ödeme talebiniz onaylandı ve bakiyenize eklendi. İyi alışverişler.', route('odemelerim'));
        }
        if ($durum == 2) {
            LogCall(Auth::user()->id, '3', "Kullanıcı " . $user->first()->name . " isimli kullanıcının " . $users->amount . " TL değerindeki ödemesini reddetti.");
            setBildirim($user->first()->id, '4', 'Ödeme Talebiniz Reddedildi', 'Ödeme talebiniz reddedildi, detaylar için tıklayın.', route('odemelerim'));
        }

        return back()->with("success", __('general.mesaj-9'));
    }

    public function telefon_yonetim()
    {
        return view('back.pages.telefon.index');
    }

    public function telefon_onayla($durum, $id)
    {
        if ($durum == 1) {
            DB::table('users')->where('id', $id)->update([
                'telefon_verified_at' => date('YmdHis'),
            ]);
            LogCall(Auth::user()->id, '4', "Kullanıcı " . $id . " id'li kullanıcının telefonunu manuel olarak onayladı.");
            setBildirim($id, '1', 'Telefonunuz Onaylandı', 'Telefon onay işleminiz başarıyla tamamlanmıştır.', route('hesap_onayla'));
        }
        if ($durum == 2) {
            DB::table('users')->where('id', $id)->update([
                'telefon_verified_at_first' => NULL,
            ]);
            LogCall(Auth::user()->id, '4', "Kullanıcı " . $id . " id'li kullanıcının manuel telefon onaylamasını reddetti.");
            setBildirim($id, '1', 'Telefonunuz Reddedildi', 'Telefon onay işleminiz reddedildi, detayllar için tıklayın.', route('hesap_onayla'));
        }
        return back()->with("success", __('general.mesaj-9'));
    }


    public function crypto_add(Request $request)
    {
        if ($request->hasFile('image')) {
            $destinationPath = "front/crypto_logo";
            $file = $request->image;
            $title = Str::slug($request->title);
            $fileName = $title . "-" . time() . '.' . $file->clientExtension();
            $file->move($destinationPath, $fileName);
        } else {
            $fileName = "";
        }
        DB::table('payment_channels_crypto')->insert([
            'title' => $request->title,
            'text' => $request->text,
            'image' => $fileName,
            'updated_at' => date('YmdHis'),
            'created_at' => date('YmdHis'),
        ]);
        LogCall(Auth::user()->id, '3', "Kullanıcı " . $request->title . " isimli yeni bir kripto para ekledi.");
        return back()->with("success", __('general.mesaj-9'));
    }

    public function crypto_edit(Request $request)
    {
        $odemeYontemi = DB::table('payment_channels_crypto')->where('id', $request->id)->first();
        if ($request->image != '') {
            $destinationPath = "front/crypto_logo";
            $dosya = $destinationPath . "/" . $odemeYontemi->image;
            if ($odemeYontemi->image != "") {
                if (file_exists($dosya)) {
                    unlink($dosya);
                }
            }

            $file = $request->image;
            $title = Str::slug($request->title);
            $fileName = $title . "-" . time() . '.' . $file->clientExtension();
            $file->move($destinationPath, $fileName);
            DB::table('payment_channels_crypto')->where('id', $request->id)->update([
                'image' => $fileName,
            ]);
        }
        DB::table('payment_channels_crypto')->where('id', $request->id)->update([
            'title' => $request->title,
            'text' => $request->text,
            'updated_at' => date('YmdHis'),
        ]);
        LogCall(Auth::user()->id, '3', "Kullanıcı " . $odemeYontemi->title . " isimli kripto parayı düzenledi.");
        return back()->with("success", __('general.mesaj-9'));
    }

    public function digerOdeme_add(Request $request)
    {
        DB::table('payment_channels_diger')->insert([
            'title' => $request->title,
            'updated_at' => date('YmdHis'),
            'created_at' => date('YmdHis'),
        ]);
        LogCall(Auth::user()->id, '3', "Kullanıcı " . $request->title . " isimli yeni bir diğer ödeme yöntemi ekledi.");
        return back()->with("success", __('general.mesaj-9'));
    }

    public function digerOdeme_edit(Request $request)
    {
        $odemeYontemi = DB::table('payment_channels_diger')->where('id', $request->id)->first();
        DB::table('payment_channels_diger')->where('id', $request->id)->update([
            'title' => $request->title,
            'updated_at' => date('YmdHis'),
        ]);
        LogCall(Auth::user()->id, '3', "Kullanıcı " . $odemeYontemi->title . " isimli diğer ödeme yöntemini düzenledi.");
        return back()->with("success", __('general.mesaj-9'));
    }


    public function yorumlar()
    {
        return view('back.pages.yorumlar.index');
    }

    public function yorumlar_onayla($durum, $id)
    {
        $ilan = DB::table('ilan_yorumlar')->where('id', $id)->first();

        $table_name = $ilan->buy == 1 ? 'pazar_yeri_ilanlar_buy' : 'pazar_yeri_ilanlar';

        $ilanPazar = DB::table($table_name)->where('id', $ilan->ilan)->first();

        $yorumcular = DB::select("select DISTINCT user from ilan_yorumlar where status = 1 and ilan = '$ilan->ilan' and id > (SELECT IFNULL( (SELECT id FROM `ilan_yorumlar` WHERE `ilan` = '$ilan->ilan' and buy = '$ilan->buy' and user = '$ilanPazar->user' and status = 1 order by id desc limit 1) ,'1') as id)"); //ilan sahibinin son yorumundan sonra yorum yapan diger kullanicilar
        DB::table('ilan_yorumlar')->where('id', $id)->update([
            'status' => $durum,
        ]);


        $pazar = DB::table('games_titles')->where('id', $ilanPazar->pazar)->first();

        $link = route($ilan->buy == 1 ? 'item_buy_ic_detay' : 'item_ic_detay', [$pazar->link, $ilanPazar->sunucu, Str::slug($ilanPazar->title) . '-' . $ilanPazar->id]);
        $satici = DB::table('users')->where('id', $ilanPazar->user)->first();
        if ($durum == 1) {
            LogCall(Auth::user()->id, '4', "Kullanıcı " . $id . " id'li ilan yorumunu onayladı.");
            setBildirim($ilan->user, '2', 'İlan Yorumunuz Onaylandı', 'İlan yorumunuz onaylandı ve şu anda gösteriliyor.', $link);
            $smsText =  $ilanPazar->id . " ID'li " . $ilanPazar->title . " ilanınıza yorum yapılmıştır. Yanıtlamak için lütfen üye girişi yapınız.";

            if ($ilan->user != $ilanPazar->user) {
                setBildirim($ilanPazar->user, '2', 'Ilaninizda Yeni Yorum Var', 'İlaniniz icin yeni bir yorum var. Goruntulemek icin tiklayin.', $link);
                sendSms($satici->telefon, $smsText);

                //LogCall(Auth::user()->id, '4', "YAKUP --> sendSms(".$satici->telefon.")");
            }

            if ($ilan->user == $ilanPazar->user) {
                foreach ($yorumcular as $yorumcu) {
                    $yorumcuUser = DB::table('users')->where('id', $yorumcu->user)->first();
                    if ($yorumcuUser->telefon) {
                        $smsText = "Yorum yapmis oldugunuz " . $ilanPazar->id . " ID'li " . $ilanPazar->title . " ilanina cevap verilmistir";
                        sendSms($yorumcuUser->telefon, $smsText);
                  //      LogCall(Auth::user()->id, '4', "YAKUP --> sendSms(".$yorumcuUser->telefon.")");
                    }
                }
            }
        }
        if ($durum == 2) {
            LogCall(Auth::user()->id, '4', "Kullanıcı " . $id . " id'li ilan yorumunu reddetti.");
            setBildirim($ilan->user, '2', 'İlan Yorumunuz Reddedildi', 'İlanın yorumunuz reddedildi, tekrar yorum yapabilirsiniz.', $link);
        }
        return back()->with("success", __('general.mesaj-9'));
    }

    public function yorumlar_satici()
    {
        return view('back.pages.yorumlar-satici.index');
    }

    public function yorumlar_satici_onayla($durum, $id)
    {
        DB::table('satici_yorumlar')->where('id', $id)->update([
            'status' => $durum,
        ]);
        $yorum = DB::table('satici_yorumlar')->where('id', $id)->first();
        $link = route('satici', $yorum->satici);
        if ($durum == 1) {
            LogCall(Auth::user()->id, '4', "Kullanıcı " . $id . " id'li satıcı yorumunu onayladı.");
            setBildirim($yorum->yapan, '2', 'Satıcı Yorumunuz Onaylandı', 'Satıcı yorumunuz onaylandı ve şu anda gösteriliyor.', $link);
        }
        if ($durum == 2) {
            LogCall(Auth::user()->id, '4', "Kullanıcı " . $id . " id'li satıcı yorumunu reddetti.");
            setBildirim($yorum->yapan, '2', 'Satıcı Yorumunuz Reddedildi', 'Satıcıya yapmış olduğunuz yorumunuz reddedildi, tekrar yorum yapabilirsiniz.', $link);
        }
        return back()->with("success", __('general.mesaj-9'));
    }

    public function ilanlar_yonetim()
    {
        return view('back.pages.ilanlar.index');
    }

    public function ilanlar_yonetim_onay($id, $durum)
    {
        DB::table('pazar_yeri_ilanlar')->where('id', $id)->update([
            'status' => $durum,
            'updated_at' => date('YmdHis'),
        ]);
        $ilan = DB::table('pazar_yeri_ilanlar')->where('id', $id)->first();
        $satici = DB::table('users')->where('id', $ilan->user)->first();

        if ($durum == 1) {
            DB::table('pazar_yeri_ilan_satis')->where('ilan',$id)->update(['deleted_at' => date('Ymdhis')]);

            LogCall(Auth::user()->id, '4', "Kullanıcı " . $id . " id'li ilan satış durumunu onayladı.");
            setBildirim($ilan->user, '2', 'İlanınız Onaylandı', 'İlanınız onaylandı ve şu anda listeleniyor.', route('satici_panelim'));
            $favoriler = DB::table('favoriler')->where('type', '1')->where('favoriId', $id)->whereNull('deleted_at');
            if ($favoriler->count() > 0) {
                foreach ($favoriler->get() as $fg) {
                    setBildirim($fg->user, '2', 'Favori İlanınızda Güncelleme', 'Favorilerinizde ekli olan ' . $ilan->title . ' başlıklı ilanda bir güncelleme olmuştur.', route('favorilerim'));
                }
            }
            if ($ilan->toplu == 1) {
                foreach (DB::table('pazar_yeri_ilan_toplu')->where('toplu', $ilan->id)->get() as $ilalns) {
                    DB::table('pazar_yeri_ilanlar')->where('id', $ilalns->ilan)->update([
                        'status' => '1',
                        'updated_at' => date('YmdHis'),
                    ]);
                }
            }
        }
        if ($durum == 2) {
            LogCall(Auth::user()->id, '4', "Kullanıcı " . $id . " id'li ilan satış durumunu reddetti.");
            setBildirim($ilan->user, '2', 'İlanınız Reddedildi', 'İlanınız reddedildi, detaylar için satıcı panelinizi kontrol edin.', route('satici_panelim'));
        }
        if ($durum == 4) {
            LogCall(Auth::user()->id, '4', "Kullanıcı " . $id . " id'li ilan satış durumunu satıldı ve item bekleniyor olarak işaretledi.");
            setBildirim($ilan->user, '2', 'İlanınız Satıldı', 'İlanınız satıldı, gerekli işlemleri yaparak satışınızı tamamlayın.', route('satici_panelim'));
        }
        if ($durum == 5) { //alıcı itemi almaya hazır
            $alanFind = DB::table('pazar_yeri_ilan_satis')->where('ilan', $ilan->id)->first()->satin_alan;
            $alan = DB::table('users')->where('id', $alanFind)->first();
            if ($ilan->toplu == 1) {
                foreach (DB::table('pazar_yeri_ilan_toplu')->where('toplu', $ilan->id)->get() as $ilalns) {
                    DB::table('pazar_yeri_ilanlar')->where('id', $ilalns->ilan)->update([
                        'status' => '5',
                        'updated_at' => date('YmdHis'),
                    ]);
                }
            }
            $smsText = "Sayın " . $alan->name . ",  satın almış olduğunuz ilan teslimata hazırdır, sitede oturum açarak itemi teslim alabilirsiniz.";
            if (sendSms($alan->telefon, $smsText)) {
                LogCall($alan->id, '4', "Kullanıcıya itemi teslim alabileceğine dair bir sms gönderildi.");
            } else {
                LogCall($alan->id, '4', "Kullanıcıya itemi teslim alabileceğine dair bir sms gönderilmek istendi fakat sms servisinde bir hata meydana geldi.");
            }
            setBildirim($alanFind, '2', 'Satın Aldığınız İlan Teslimata Hazır', 'Satın almış olduğunuz ilan teslimata hazırdır, teslim alabilirsiniz.', route('siparislerim_ilan'));
        }
        if ($durum == 6) { //satıcıya parasını ekleme

            if ($ilan->money_status == 1)
                return back()->with("error", "Bu işlem daha once onaylanmış");

            DB::table('pazar_yeri_ilanlar')->where('id', $id)->update(['money_status' => '1',]);

            #----------------------------Bakiye Extresi
            //            DB::table('bakiye_log')->insert([
            //                'user'       => $ilan->user,
            //                'bakiye'     => $satici->bakiye,
            //                'bsonrasi'   => $satici->bakiye,
            //                'cbakiye'    => $satici->bakiye_cekilebilir,
            //                'csonrasi'   => $satici->bakiye_cekilebilir + $ilan->moment_komisyon,
            //                'islem'      => $ilan->moment_komisyon . " İlan satışı yapıldı.",
            //                'tutar'      => $ilan->moment_komisyon,
            //                'created_at' => date('YmdHis')
            //            ]);
            #----------------------------Bakiye Extresi
            #
            DB::table('users')->where('id', $ilan->user)->update([
                //'bakiye' => $satici->bakiye + $ilan->moment_komisyon,
                'bakiye_cekilebilir' => $satici->bakiye_cekilebilir + $ilan->moment_komisyon,
            ]);
            LogCall(Auth::user()->id, '4', "Kullanıcı " . $satici->name . " isimli kullanıcının ilan satış durumunu onayladı ve parası yatırıldı.");
            if ($ilan->toplu == 1) {
                foreach (DB::table('pazar_yeri_ilan_toplu')->where('toplu', $ilan->id)->get() as $ilalns) {
                    DB::table('pazar_yeri_ilanlar')->where('id', $ilalns->ilan)->update([
                        'status' => '6',
                        'updated_at' => date('YmdHis'),
                    ]);
                }
            }

            //$faturaKesilecekTutar = $ilan->price - $ilan->moment_komisyon;
            /*
            if (faturaKes($ilan->user, $faturaKesilecekTutar, $ilan->id, findPackageKdvEpin($ilan->pazar), 1, 1, $ilan->id, 3)) {
                LogCall($ilan->user, '1', "Kullanıcıya item komisyon faturası başarıyla kesildi ve sisteme gönderildi.");
            } else {
                LogCall($ilan->user, '1', "Kullanıcıya item komisyon faturası kesilmek istendi fakat fatura sisteminde bir hata meydana geldi.");
            }*/

            setBildirim($ilan->user, '2', 'İlan Satışınız Başarıyla Tamamlandı', 'İlan satışınız başarıyla tamamlandı ve bakiyeniz aktarıldı.', route('satici_panelim'));
        }
        return back()->with("success", __('general.mesaj-9'));
    }

    public function ilanlar_yonetim_sms_gonder(Request $request)
    {
        $ilan = DB::table('pazar_yeri_ilanlar')->where('id', $request->id)->first();
        $satici = DB::table('users')->where('id', $ilan->user)->first();
        $smsText = $request->sms_metni;
        if (sendSms($satici->telefon, $smsText)) {
            LogCall(Auth::user()->id, '4', $satici->name . " isimli kullanıcıya manuel bir sms gönderildi.");
        } else {
            LogCall(Auth::user()->id, '4', $satici->name . " isimli kullanıcıya manuel bir sms gönderilmek istendi fakat sms servisinde bir hata meydana geldi.");
        }
        return back()->with("success", __('general.mesaj-9'));
    }


    public function ilanlar_yonetim_onay_red(Request $request)
    {
        DB::table('pazar_yeri_ilanlar')->where('id', $request->id)->update([
            'status' => $request->durum,
            'red_neden' => $request->red_neden,
            'updated_at' => date('YmdHis'),
        ]);
        LogCall(Auth::user()->id, '4', "Kullanıcı " . $request->id . " id'li ilanı reddetti. Red nedeni : " . $request->red_neden);
        return back()->with("success", __('general.mesaj-9'));
    }

    public function ilanlar_yonetim_onay_ozel($id, $durum)
    {
        if ($durum == 1) {
            DB::table('pazar_yeri_ilanlar')->where('id', $id)->update([
                'status' => '6',
            ]);
            DB::table('pazar_yeri_ilanlar')->where('id', $id)->update([
                'money_status' => '1',
            ]);
            $ilan = DB::table('pazar_yeri_ilanlar')->where('id', $id)->first();
            $satici = DB::table('users')->where('id', $ilan->user)->first();
            DB::table('users')->where('id', $ilan->user)->update([
                //'bakiye' => $satici->bakiye + $ilan->moment_komisyon,
                'bakiye_cekilebilir' => $satici->bakiye_cekilebilir + $ilan->moment_komisyon,
            ]);
            LogCall(Auth::user()->id, '4', "Kullanıcı " . $id . " id'li " . $ilan->title . " isimli ilan için satıcıya parasını manuel olarak gönderdi.");
        } elseif ($durum == 2) {
            DB::table('pazar_yeri_ilanlar')->where('id', $id)->update([
                'status' => '3',
            ]);
            DB::table('pazar_yeri_ilanlar')->where('id', $id)->update([
                'money_status' => '0',
            ]);
            $ilan = DB::table('pazar_yeri_ilanlar')->where('id', $id)->first();
            $satici = DB::table('users')->where('id', $ilan->user)->first();
            DB::table('users')->where('id', $ilan->user)->update([
                //'bakiye' => $satici->bakiye - $ilan->moment_komisyon,
                'bakiye_cekilebilir' => $satici->bakiye_cekilebilir - $ilan->moment_komisyon,
            ]);
            LogCall(Auth::user()->id, '4', "Kullanıcı " . $id . " id'li " . $ilan->title . " isimli ilan için satıcıya ödenmesi gereken parayı manuel olarak geri aldı.");
        } elseif ($durum == 3) { //alıcıya parasını gönder
            DB::table('pazar_yeri_ilanlar')->where('id', $id)->update([
                'status' => '3',
            ]);
            $alanSatis = DB::table('pazar_yeri_ilan_satis')->where('ilan', $id)->first();
            $alici = DB::table('users')->where('id', $alanSatis->satin_alan)->first();

            #--------------------------------------------------------------------------------------------------------------------------------
            $iade = DB::table("iade_bakiye")->where('iid', $alanSatis->id)->where('uid', $alici->id)->first();

            DB::table('users')->where('id', $alanSatis->satin_alan)->update([
                'bakiye' => $alici->bakiye + $iade->bakiye,
                'bakiye_cekilebilir' => $alici->bakiye_cekilebilir + $iade->cbakiye,
            ]);
            #--------------------------------------------------------------------------------------------------------------------------------

            LogCall(Auth::user()->id, '4', "Kullanıcı " . $id . " id'li isimli ilan için alıcıya parasını manuel olarak geri gönderdi.");
        }
        return back()->with("success", __('general.mesaj-9'));
    }

    public function ilanlar_yonetim_buy()
    {
        return view('back.pages.ilanlar-buy.index');
    }

    public function ilanlar_yonetim_buy_onay($id, $durum)
    {
        $ilan = DB::table('pazar_yeri_ilanlar_buy')->where('id', $id)->first();
        $blokeTutar  = $ilan->price * 0.1;
        if ($durum == 66) {
            $alici = DB::table('users')->where('id', $ilan->user)->first();


            if ($alici->bakiye + $alici->bakiye_cekilebilir < ($ilan->price - $blokeTutar)) {
                return back()->with("error", "Alıcı üyenin bakiyesi yeterli değil, lütfen bakiye yüklemesini isteyin.");
            }
            DB::table('pazar_yeri_ilanlar_buy')->where('id', $id)->update(['aliciPara' => 1, 'updated_at' => date('YmdHis')]);
            /*
             * Alıcıdan kalan parayı alalım
             */

            #|-------------------------------------------------------------------------------------------------------------
            //if ($alici->bakiye+$alici->bakiye_cekilebilir >= ($ilan->price - $ilan->moment_komisyon)) {

            if (($ilan->price - $blokeTutar) > $alici->bakiye) { # fiyat bakiyeden büyükse
                $bak_dus = 0;                     # bakiyede ne varsa çek sıfırla
                $cek_dus = $alici->bakiye_cekilebilir - (($ilan->price - $blokeTutar) - $alici->bakiye); # kalanı cbakiyeden al
                $iadeb = $alici->bakiye;
                $iadec = ($ilan->price - $blokeTutar) - $alici->bakiye;
            } else {
                $bak_dus = $alici->bakiye - ($ilan->price - $blokeTutar); # fiyat bakiyeden küçükse bakiyeden fiyatı çıkar
                $cek_dus = $alici->bakiye_cekilebilir; # c.bakiye ye dokunma
                $iadeb = ($ilan->price - $blokeTutar);
                $iadec = 0;
            }

            DB::table('users')->where('id', $ilan->user)->update(['bakiye' => $bak_dus, 'bakiye_cekilebilir' => $cek_dus]);

            #|-------------------------------------------------------------------------------------------------------------
            //} else {
            //DB::table('users')->where('id', $ilan->user)->update(['bakiye' => $alici->bakiye - ($ilan->price - $ilan->moment_komisyon)]);
            //}


            # burada iade olayında ne kadar bakiye ne kadar cbakiye tarafına iade olacağını bilmek için kayıt alıyoruz
            $lastId = $id; // DB::getPdo()->lastInsertId();
            $guncel = DB::table('iade_bakiye')->where('iid', $lastId)->where('uid', $ilan->user);
            $guncel->update([
                'iid'           => $lastId,
                'uid'           => $ilan->user,
                'bakiye'        => $guncel->first()->bakiye + $iadeb,
                'cbakiye'       => $guncel->first()->cbakiye + $iadec,
                'tutar'         => $ilan->price - $blokeTutar
            ]);
            # burada iade olayında ne kadar bakiye ne kadar cbakiye tarafına iade olacağını bilmek için kayıt alıyoruz


            LogCall(Auth::user()->id, '4', "Kullanıcı " . $id . " id'li buy ilan için alıcıdan " . $ilan->price - $blokeTutar . " TL para tahsil etti.");
            return back()->with("success", __('general.mesaj-9'));
        }



        DB::table('pazar_yeri_ilanlar_buy')->where('id', $id)->update(['status' => $durum, 'updated_at' => date('YmdHis')]);
        $alici = DB::table('users')->where('id', $ilan->user)->first();
        if ($durum == 1) {
            LogCall(Auth::user()->id, '4', "Kullanıcı " . $id . " id'li buy ilan alış durumunu onayladı.");
            setBildirim($ilan->user, '2', 'Alış İlanınız Onaylandı', 'Alış İlanınız onaylandı ve şu anda listeleniyor.', route('alici_panelim'));
            $favoriler = DB::table('favoriler')->where('type', '2')->where('favoriId', $id)->whereNull('deleted_at');
            if ($favoriler->count() > 0) {
                foreach ($favoriler->get() as $fg) {
                    setBildirim($fg->user, '2', 'Favori İlanınızda Güncelleme', 'Favorilerinizde ekli olan ' . $ilan->title . ' başlıklı ilanda bir güncelleme olmuştur.', route('favorilerim'));
                }
            }
        }
        if ($durum == 2) {
            LogCall(Auth::user()->id, '4', "Kullanıcı " . $id . " id'li buy ilan alış durumunu reddetti.");
            setBildirim($ilan->user, '2', 'Alış İlanınız Reddedildi', 'Alış İlanınız reddedildi, detaylar için alıcı panelinizi kontrol edin.', route('alici_panelim'));
        }
        if ($durum == 3) {
            /*
             * Kullanıcıya komisyon tutarını geri aktaralım
             */
            DB::table('users')->where('id', $ilan->user)->update(['bakiye' => $alici->bakiye + $blokeTutar]);
            LogCall(Auth::user()->id, '4', "Kullanıcı " . $id . " id'li buy ilan alış durumunu pasif hale getirdi ve kullanıcıya bloke tutarı geri aktarıldı.");
            setBildirim($ilan->user, '2', 'Alış İlanınız Pasif Edildi', 'Alış İlanınız pasif hale getirildi ve bloke tutarınız iade edildi, detaylar için alıcı panelinizi kontrol edin.', route('alici_panelim'));
        }
        if ($durum == 4) {
            LogCall(Auth::user()->id, '4', "Kullanıcı " . $id . " id'li buy ilan alış durumunu satıcı bulundu ve itemi siteye teslim için bekliyor olarak işaretledi.");
            setBildirim($ilan->user, '2', 'Alış İlanınız İçin Satıcı Var', 'Alış İlanınız için satıcı bulundu, satıcı itemi siteye teslim ettikten sonra itemi teslim alabilirsiniz.', route('alici_panelim'));
        }
        if ($durum == 5) {
            LogCall(Auth::user()->id, '4', "Kullanıcı " . $id . " id'li buy ilan alış durumunu satıcı itemi teslim etti ve itemi alıcıya teslim için bekliyor olarak işaretledi.");
            setBildirim($ilan->user, '2', 'Alış İlanınız Teslim Bekliyor', 'Alış İlanınız için item teslim alındı, itemi teslim alabilmek için bakiyenizi tamamlayın.', route('alici_panelim'));
        }
        if ($durum == 6) { //satıcıya parasını ekleme
            $satisKayit = DB::table('pazar_yeri_ilan_satis_buy')->where('ilan', $id)->first();

            /*
             * Satıcıya parasını gönderelim
             */
            $satici = DB::table('users')->where('id', $satisKayit->satin_alan)->first();
            DB::table('users')->where('id', $satisKayit->satin_alan)->update([
                //'bakiye' => $satici->bakiye + ($ilan->price - $ilan->moment_komisyon),
                'bakiye_cekilebilir' => $satici->bakiye_cekilebilir + ($ilan->price - $ilan->moment_komisyon),
            ]);

            /*
             * İşlemin para durumu
             */


            DB::table('pazar_yeri_ilanlar_buy')->where('id', $id)->update([
                'money_status' => '1',
            ]);

            LogCall(Auth::user()->id, '4', "Kullanıcı " . $id . " id'li " . $satici->name . " isimli kullanıcının buy ilan satış durumunu onayladı ve parası yatırıldı.");

            /*
             * Alıcıya sms gönderme
             */
            $smsText = "Sayın " . substr($alici->name, 0, 2) . "**** ******, " . $ilan->id . " ID'li alış ilanınızın teslimatı başarılı şekilde sağlanmıştır. Oyuneks'i tercih ettiğiniz için teşekkür ederiz.";
            sendSms($alici->telefon, $smsText);

            /*
             * Satıcıya sms gönderme
             */
            $smsText1 = "Sayın " . substr($satici->name, 0, 2) . "**** ******, " . $ilan->id . " ID'li alış ilanı teslimatınız tamamlanmış ve hak edişiniz hesabınıza aktarılmıştır. Oyuneks'i tercih ettiğiniz için teşekkür ederiz.";
            sendSms($satici->telefon, $smsText1);
        }

        if ($durum == 98) { // Site itemi teslim edemedi sorun çıktı ise Alıcının parasını gönder

            // İlan durumunu RED yapalım
            DB::table('pazar_yeri_ilanlar_buy')->where('id', $id)->update(['status' => '2', 'updated_at' => date('YmdHis')]);

            $bak = DB::table('users')->where('id', $ilan->user)->first();
            $iadeler = DB::table('iade_bakiye')->where('id', $ilan->iade_id)->first(); //->where('iid', $ilan->id)->where('uid', $ilan->user)
            DB::table('users')->where('id', $ilan->user)->update(['bakiye' => $bak->bakiye + $iadeler->bakiye, 'bakiye_cekilebilir' => $bak->bakiye_cekilebilir + $iadeler->cbakiye]);

            #  ALICIYA iadesi yapıldı sıra satıcıya parasını gönderme olayında

            $satisKayit = DB::table('pazar_yeri_ilan_satis_buy')->where('ilan', $id)->first();

            # Satıcıya parasını gönderelim

            $satici = DB::table('users')->where('id', $satisKayit->satin_alan)->first();
            /*DB::table('users')->where('id', $satisKayit->satin_alan)->update(['bakiye_cekilebilir' => $satici->bakiye_cekilebilir + ($ilan->price - $ilan->moment_komisyon)]); */

            # İşlemin para durumu

            DB::table('pazar_yeri_ilanlar_buy')->where('id', $id)->update(['money_status' => '1']);

            LogCall(Auth::user()->id, '4', "Kullanıcı " . $id . " id'li " . $satici->name . " isimli  BAN 7k TESLİMAT YAPILAMADI ALICI SATICI İADESİ YAPILDI SİTE ZARAR");
            return back()->with("success", __('general.mesaj-9'));
        }

        if ($durum == 99) { //alıcı para yüklemedi
            DB::table('pazar_yeri_ilanlar_buy')->where('id', $id)->update(['olumsuz' => 1]);
            $olumsuzKontrol = DB::table('pazar_yeri_ilanlar_buy')->where('status', '99')->where('user', $ilan->user)->where('olumsuz', '1')->count();
            if ($olumsuzKontrol > 2) {
                DB::table('users')->where('id', $ilan->user)->update(['alisIzin' => 0]);
            }
        }
        return back()->with("success", __('general.mesaj-9'));
    }

    public function ilanlar_yonetim_buy_onay_red(Request $request)
    {
        $ilan = DB::table('pazar_yeri_ilanlar_buy')->where('id', $request->id)->first();
        $alici = DB::table('users')->where('id', $ilan->user)->first();
        DB::table('pazar_yeri_ilanlar_buy')->where('id', $request->id)->update([
            'status' => $request->durum,
            'red_neden' => $request->red_neden,
            'updated_at' => date('YmdHis'),
        ]);

        #--------------------------------------------------------------------------------------------------------------------------------
        $iade = DB::table("iade_bakiye")->where('iid', $request->id)->where('uid', $ilan->user)->first();

        DB::table('users')->where('id', $ilan->user)->update([
            'bakiye' => $alici->bakiye + $iade->bakiye,
            'bakiye_cekilebilir' => $alici->bakiye_cekilebilir + $iade->cbakiye
        ]);
        #--------------------------------------------------------------------------------------------------------------------------------

        //DB::table('users')->where('id', $ilan->user)->update(['bakiye' => $alici->bakiye + $ilan->moment_komisyon]);

        LogCall(Auth::user()->id, '4', "Kullanıcı " . $request->id . " id'li buy ilan alış durumunu reddetti ve kullanıcıya blokeli olan " . $ilan->moment_komisyon . "₺ bakiyesi geri aktarıldı.");
        LogCall(Auth::user()->id, '4', "Kullanıcı " . $request->id . " id'li ilanı reddetti. Red nedeni : " . $request->red_neden);
        return back()->with("success", __('general.mesaj-9'));
    }

    public function ilanlar_yonetim_buy_onay_ozel($id, $durum)
    {
        if ($durum == 1) {
            DB::table('pazar_yeri_ilanlar_buy')->where('id', $id)->update([
                'status' => '6',
            ]);
            DB::table('pazar_yeri_ilanlar_buy')->where('id', $id)->update([
                'money_status' => '1',
            ]);
            $ilan = DB::table('pazar_yeri_ilanlar_buy')->where('id', $id)->first();
            $satisKayit = DB::table('pazar_yeri_ilan_satis_buy')->where('ilan', $ilan->id)->first();
            $satici = DB::table('users')->where('id', $satisKayit->satin_alan)->first();
            DB::table('users')->where('id', $ilan->user)->update([
                //'bakiye' => $satici->bakiye + $ilan->moment_komisyon,
                'bakiye_cekilebilir' => $satici->bakiye_cekilebilir + $ilan->moment_komisyon,
            ]);
            LogCall(Auth::user()->id, '4', "Kullanıcı " . $id . " id'li " . $ilan->title . " isimli ilan için satıcıya parasını manuel olarak gönderdi.");
        } elseif ($durum == 2) {
            DB::table('pazar_yeri_ilanlar_buy')->where('id', $id)->update([
                'status' => '3',
            ]);
            DB::table('pazar_yeri_ilanlar_buy')->where('id', $id)->update([
                'money_status' => '0',
            ]);
            $ilan = DB::table('pazar_yeri_ilanlar_buy')->where('id', $id)->first();
            $satisKayit = DB::table('pazar_yeri_ilan_satis_buy')->where('ilan', $ilan->id)->first();
            $satici = DB::table('users')->where('id', $satisKayit->satin_alan)->first();
            DB::table('users')->where('id', $ilan->user)->update([
                //'bakiye' => $satici->bakiye - $ilan->moment_komisyon,
                'bakiye_cekilebilir' => $satici->bakiye_cekilebilir - $ilan->moment_komisyon,
            ]);
            LogCall(Auth::user()->id, '4', "Kullanıcı " . $id . " id'li " . $ilan->title . " isimli ilan için satıcıya ödenmesi gereken parayı manuel olarak geri aldı.");
        } elseif ($durum == 3) { //alıcıya parasını gönder
            DB::table('pazar_yeri_ilanlar_buy')->where('id', $id)->update([
                'status' => '3',
            ]);
            $ilan = DB::table('pazar_yeri_ilanlar_buy')->where('id', $id)->first();
            $alici = DB::table('users')->where('id', $ilan->user)->first();
            DB::table('users')->where('id', $ilan->user)->update([
                'bakiye' => $alici->bakiye + $ilan->price,
            ]);
            LogCall(Auth::user()->id, '4', "Kullanıcı " . $id . " id'li " . $ilan->title . " isimli ilan için alıcıya parasını manuel olarak geri gönderdi.");
        }
        return back()->with("success", __('general.mesaj-9'));
    }

    public function game_gold_yonetim()
    {
        return view('back.pages.game_gold.index');
    }

    public function game_gold_yonetim_onayla($durum, $id)
    {
        $userF = DB::table('game_gold_satis')->where('id', $id)->first();
        if ($userF->status == 1) { //eğer daha önceden onaylanmış ise
            LogCall(Auth::user()->id, '4', "Kullanıcı " . $id . " id'li op satın almasını onaylamak istedi fakat bu ilan zaten onaylanmıştı.");
            return back()->with("error", "Bu satın alma işlemi zaten onaylandı, tekrar onaylayamazsınız.");
        }
        if ($userF->tur == "bize-sat") {
            $paket = DB::table('games_packages_trade')->where('id', $userF->paket)->first();
            if ($paket->alis_stok < $userF->adet) {  //eğer yeterli stok yoksa onaylaayamaz
                LogCall(Auth::user()->id, '4', "Kullanıcı " . $id . " id'li op satın almasını onaylamak istedi fakat yeterli alış stoğu yoktu.");
                return back()->with("error", "Bu işlemi yapabilmeniz için yeterli alış stoğu yok. Lütfen alış stoğunu güncelleyin.");
            }
        }
        DB::table('game_gold_satis')->where('id', $id)->update([
            'status' => $durum,
            'updated_at' => date('YmdHis'),
        ]);

        $user = DB::table('users')->where('id', $userF->user)->first();
        if ($durum == 2) {
            if ($userF->tur == "bizden-al") {
                #--------------------------------------------------------------------------------------------------------------------------------
                $iade = DB::table("iade_bakiye")->where('iid', $id)->where('uid', $userF->user)->first();
                //LogCall(Auth::user()->id, '4', "Yakup Dikkat--> tablo = iade_bakiye  / iid= $id  /  uid= $userF->user");

                DB::table('users')->where('id', $user->id)->update([
                    'bakiye' => $user->bakiye + $iade->bakiye,
                    'bakiye_cekilebilir' => $user->bakiye_cekilebilir + $iade->cbakiye,
                ]);
                #--------------------------------------------------------------------------------------------------------------------------------

                LogCall(Auth::user()->id, '4', "Kullanıcı " . $id . " id'li op satın almasını reddetti.");
                setBildirim($user->id, '4', 'OP Satın Alımı Reddedildi', 'Oyun parası satın alma işleminiz site tarafından reddedildi, detaylar için tıklayın.', route('siparislerim_game_gold'));
            }
        } elseif ($durum == 1) {
            $paket = DB::table('games_packages_trade')->where('id', $userF->paket)->first();

            $stok_id = $userF->paket;
            $once = $paket->stok;
            if ($userF->tur == "bize-sat") {
                $sonra = $once + $userF->adet;
            } else {
                $sonra = $once - $userF->adet;
            }
            $islem = $userF->adet;
            $admin = Auth::user()->id;
            $det = $userF->tur . " islemi # USER:" . $user->id . " # ID:" . $id . " # tutar: ";

            if ($userF->tur == "bize-sat") {
                if ($paket->alis_stok < $userF->adet) {  //eğer yeterli stok yoksa onaylaayamaz
                    LogCall(Auth::user()->id, '4', "Kullanıcı " . $id . " id'li op satın almasını onaylamak istedi fakat yeterli alış stoğu yoktu.");
                    return back()->with("error", "Bu işlemi yapabilmleniz için yeterli alış stoğu yok.");
                }
                DB::table('users')->where('id', $user->id)->update([
                    //'bakiye' => $user->bakiye + $userF->price,
                    'bakiye_cekilebilir' => $user->bakiye_cekilebilir + $userF->price,
                ]);
                DB::table('games_packages_trade')->where('id', $userF->paket)->update([
                    'stok' => $paket->stok + $userF->adet,
                ]);
                //max alış stoğunu düşürelim
                DB::table('games_packages_trade')->where('id', $userF->paket)->update([
                    'alis_stok' => $paket->alis_stok - $userF->adet,
                ]);
                LogCall(Auth::user()->id, '4', "Kullanıcı " . $id . " id'li op satın almasını onayladı.");
                setBildirim($user->id, '4', 'OP Satışınız Onaylandı', 'Oyun parası satışınız onaylandı, detaylar için tıklayın.', route('siparislerim_game_gold'));
                $SatisFiyatimiz = findGamesPackagesTradeMusteriyeSatPrice($paket->id) * $userF->adet;
                $faturaKesilecekTutar = $SatisFiyatimiz - $userF->price;

                #($admin,$stok_id,$once,$islem,$sonra,$det)
                log_stok($admin, $stok_id, $once, $islem, $sonra, $det . $userF->price);


                /*
                $baslikFaturaBilgisi = DB::table('games_titles')->where('id', $paket->games_titles)->first()->fatura_kes;
                if ($baslikFaturaBilgisi == 1) {
                    try {
                        if (faturaKes($userF->user, $faturaKesilecekTutar, $userF->id, findPackageKdvEpin($paket->games_titles), 1, $userF->adet, $paket->id, 2)) {
                            LogCall($userF->user, '1', "Kullanıcıya game gold komisyon faturası ₺" . $faturaKesilecekTutar . " üzerinden " . findPackageKdvEpin($paket->games_titles) . "% ile başarıyla kesildi ve sisteme gönderildi.");
                        } else {
                            LogCall($userF->user, '1', "Kullanıcıya game gold komisyon faturası ₺" . $faturaKesilecekTutar . " üzerinden " . findPackageKdvEpin($paket->games_titles) . "% kesilmek istendi fakat fatura sisteminde bir hata meydana geldi.");
                        }
                    } catch (Exception $e) {
                        LogCall($userF->user, '1', "Kullanıcıya game gold komisyon faturası ₺" . $faturaKesilecekTutar . " üzerinden " . findPackageKdvEpin($paket->games_titles) . "% kesilmek istendi fakat bizim tarafımızda bir hata meydana geldi.");
                    }
                } else {
                    LogCall($userF->user, '1', "Kullanıcıya game gold komisyon faturası ₺" . $faturaKesilecekTutar . " üzerinden " . findPackageKdvEpin($paket->games_titles) . "% kesilmek istendi fakat satın aldığı başlık türü için fatura kesme özelliği kapalıydı.");
                }*/

                /*
                if (faturaKes($userF->user, $faturaKesilecekTutar, 1, findPackageKdvEpin($paket->games_titles), 1, $userF->adet, $paket->id, 2)) {
                    LogCall($userF->user, '1', "Kullanıcıya faturası başarıyla kesildi ve sisteme gönderildi.");
                } else {
                    LogCall($userF->user, '1', "Kullanıcıya faturası kesilmek istendi fakat fatura sisteminde bir hata meydana geldi.");
                }*/
            } else {
                //max alış stoğunu arttıralım genel stoğu düşürelim, müşteri bize satıyor
                DB::table('games_packages_trade')->where('id', $userF->paket)->update([
                    'stok' => $paket->stok - $userF->adet,
                    'alis_stok' => $paket->alis_stok + $userF->adet,
                ]);

                #($admin,$stok_id,$once,$islem,$sonra,$det)
                log_stok($admin, $stok_id, $once, '-' . $islem, $sonra, $det . $userF->price);
            }
        }
        return back()->with("success", __('general.mesaj-9'));
    }

    public function game_gold_yonetim_onayla_post($durum, $id, Request $request)
    {
        if ($durum == 0) {
            $satis = DB::table('game_gold_satis')->where('id', $id)->first();
            if ($satis->status == 1) {
                return back()->with("error", "Teslimat Tamamlanmis");
            }
            $user = DB::table('users')->where('id', $satis->user)->first();
            DB::table('game_gold_satis')->where('id', $id)->update([
                'status' => $durum,
                'teslim_nick' => $request->teslim_nick,
                'updated_at' => date('YmdHis'),
            ]);
            LogCall(Auth::user()->id, '4', "Kullanıcı " . $id . " id'li op satın alma için nick verdi, verilen nick:" . $request->teslim_nick);
            setBildirim($user->id, '4', 'OP Satışı Nick Verildi', 'Oyun parası satış işleminiz için verilen nick : ' . $request->teslim_nick, route('siparislerim_game_gold'));
        } else if ($durum == 2) {

            $satis = DB::table('game_gold_satis')->where('id', $id)->first();

            $notx = $request->get('notx');

            DB::table('game_gold_satis')->where('id', $id)->update(['notx' => $notx]);
            $this->game_gold_yonetim_onayla($durum, $id);
            return true;
        }
        return back()->with("success", __('general.mesaj-9'));
    }

    public function epin_yonetim()
    {
        return view('back.pages.epin.index');
    }

    public function epin_siparis_detaylari()
    {
        return view('back.pages.epin.detaylar');
    }

    public function para_cek_yonetim()
    {
        return view('back.pages.para-cek.index');
    }

    public function para_cek_yonetim_onayla($durum, $id)
    {
        $para_cek = DB::table('para_cek')->where('id', $id)->first();
        if ($para_cek->status == 9)
            return back()->with("error", "Talep API Tarafindan Isleniyor");
        if ($para_cek->status != 0)
            return back()->with("error", "Beklemede Olmayan Taleple Ilgili Islem Yapilamaz.");


        DB::table('para_cek')->where('id', $id)->update([
            'status' => $durum,
        ]);

        if ($durum == 1) {
            $para_cek = DB::table('para_cek')->where('id', $id)->first();
            $user = DB::table('users')->where('id', $para_cek->user)->first();
            /*DB::table('users')->where('id', $para_cek->user)->update([
                'bakiye' => $user->bakiye - $para_cek->amount,
                'bakiye_cekilebilir' => $user->bakiye_cekilebilir - $para_cek->amount,
            ]);*/
            LogCall(Auth::user()->id, '4', "Kullanıcı " . $id . " id'li ödeme çekim talebini onayladı.");
            setBildirim($user->id, '4', 'Ödeme Çekim Talebiniz Onaylandı', 'Ödeme çekim talebiniz onaylandı, 1-15 dakika içerisinde çekmiş olduğunuz tutar hesabınıza yatırılacaktır.', route('bakiye_cek'));
            $smsText = "Sayın " . $user->name . ",  Ödeme çekim talebiniz onaylandı, 1-15 dakika içerisinde çekmiş olduğunuz tutar hesabınıza yatırılacaktır. Bir sorunla karşılaşmanız durumunda lütfen bizimle iletişime geçin.";
            if (sendSms($user->telefon, $smsText)) {
                LogCall($user->id, '4', "Kullanıcıya ödeme çekim talebinin onaylandığına dair bilgi sms'i gönderildi.");
            } else {
                LogCall($user->id, '4', "Kullanıcıya ödeme çekim talebinin onaylandığına dair bilgi sms'i gönderilmek istendi fakat sms servisinde bir hata meydana geldi.");
            }
        }
        if ($durum == 2) {
            $_GET['red'] = isset($_GET['red']) ? $_GET['red'] : ''; //yakup log hatası için eklendi
            DB::table('para_cek')->where('id', $id)->update([
                'text' => $_GET['red'],
            ]);
            $para_cek = DB::table('para_cek')->where('id', $id)->first();
            $user = DB::table('users')->where('id', $para_cek->user)->first();
            DB::table('users')->where('id', $para_cek->user)->update([
                //'bakiye' => $user->bakiye + $para_cek->amount,
                'bakiye_cekilebilir' => $user->bakiye_cekilebilir + $para_cek->amount,
            ]);
            LogCall(Auth::user()->id, '4', "Kullanıcı " . $id . " id'li ödeme çekim talebini reddetti.");
            setBildirim($user->id, '4', 'Ödeme Çekim Talebiniz Reddedildi', 'Ödeme çekim talebiniz reddedildi, detaylar için tıklayın.', route('bakiye_cek'));
        }
        return back()->with("success", __('general.mesaj-9'));
    }

    public function header_menu()
    {
        return view('back.pages.header_menu.index');
    }

    public function header_menu_add(Request $request)
    {
        if ($request->hasFile('image')) {
            $destinationPath = "front/mega_menu";
            $file = $request->image;
            $title = Str::slug($request->title);
            $fileName = $title . "-" . time() . '.' . $file->clientExtension();
            $file->move($destinationPath, $fileName);
        } else {
            $fileName = "";
        }
        DB::table('header_menu')->insert([
            'title' => $request->title,
            'sub_menu' => $request->sub_menu,
            'link' => $request->link,
            'image' => $fileName,
            'status' => 1,
            'created_at' => date('YmdHis'),
        ]);
        LogCall(Auth::user()->id, '4', "Kullanıcı " . $request->title . " başlıklı yeni bir menü ekledi.");
        return back()->with("success", __('general.mesaj-9'));
    }

    public function header_menu_edit(Request $request)
    {
        if ($request->image != NULL) {
            $destinationPath = "front/mega_menu";
            $file = $request->image;
            $title = Str::slug($request->title);
            $fileName = $title . "-" . time() . '.' . $file->clientExtension();
            $file->move($destinationPath, $fileName);
            DB::table('header_menu')->where('id', $request->id)->update([
                'image' => $fileName,
            ]);
        }
        DB::table('header_menu')->where('id', $request->id)->update([
            'title' => $request->title,
            'sub_menu' => $request->sub_menu,
            'link' => $request->link,
        ]);
        LogCall(Auth::user()->id, '4', "Kullanıcı " . $request->title . " başlıklı menüyü düzenledi.");
        return back()->with("success", __('general.mesaj-9'));
    }

    public function twitch_para_cek_yonetim()
    {
        return view('back.pages.twitch-para-cek.index');
    }

    public function twitch_para_cek_yonetim_onayla($durum, $id)
    {
        DB::table('twitch_support_cevirmeler')->where('id', $id)->update([
            'status' => $durum,
        ]);
        $twitch_para_cek = DB::table('twitch_support_cevirmeler')->where('id', $id)->first();
        $user = DB::table('users')->where('id', $twitch_para_cek->user)->first();
        if ($durum == 1) {
            LogCall(Auth::user()->id, '4', "Kullanıcı " . $id . " id'li twitch para çekim talebini onayladı.");
            setBildirim($user->id, '3', 'Twitch Para Çekim Talebiniz Onaylandı', $twitch_para_cek->amount . ' TL tutarında para çekim talebiniz onaylandı, detaylar için tıklayın.', route('twitch_support_yayinci_bakiye_cevir'));
        }
        if ($durum == 2) {
            DB::table('users')->where('id', $twitch_para_cek->user)->update([
                'bagis_bakiye' => $user->bagis_bakiye + $twitch_para_cek->amount,
            ]);
            LogCall(Auth::user()->id, '4', "Kullanıcı " . $id . " id'li twitch para çekim talebini reddetti.");
            setBildirim($user->id, '3', 'Twitch Para Çekim Talebiniz Reddedildi', $twitch_para_cek->amount . ' TL tutarında para çekim talebiniz reddedildi, detaylar için tıklayın.', route('twitch_support_yayinci_bakiye_cevir'));
            return back()->with("success", "Kullanıcının para çekim talebi reddedildi ve bağış bakiyesine geri eklendi.");
        }
        return back()->with("success", __('general.mesaj-9'));
    }

    public function twitch_kesintisi_yonetim()
    {
        return view('back.pages.twitch-kesintisiz.index');
    }

    public function twitch_kesintisi_yonetim_onayla($durum, $id)
    {
        DB::table('twitch_kesintisiz_yayinci')->where('id', $id)->update([
            'status' => $durum,
        ]);
        $user = DB::table('twitch_kesintisiz_yayinci')->where('id', $id)->first();
        if ($durum == 1) {
            LogCall(Auth::user()->id, '4', "Kullanıcı " . $id . " id'li twitch kesintisiz talebini onayladı.");
            setBildirim($user->streamer, '3', 'Twitch Kesintisiz Talebiniz Onaylandı', 'Twitch kesintisiz talebiniz onaylandı, detaylar için tıklayın.', route('twitch_support_yayinci_kesintisiz'));
        }
        if ($durum == 2) {
            DB::table('twitch_kesintisiz_yayinci')->where('id', $id)->update([
                'red_neden' => $_GET['red'],
            ]);
            LogCall(Auth::user()->id, '4', "Kullanıcı " . $id . " id'li twitch kesintisiz talebini reddetti. Red nedeni:" . $_GET['red']);
            setBildirim($user->streamer, '3', 'Twitch Kesintisiz Talebiniz Reddedildi', 'Twitch kesintisiz talebiniz reddedildi, detaylar için tıklayın.', route('twitch_support_yayinci_kesintisiz'));
        }
        return back()->with("success", __('general.mesaj-9'));
    }

    public function avatarlar()
    {
        return view('back.pages.avatarlar.index');
    }

    public function avatar_add(Request $request)
    {
        if ($request->hasFile('image')) {
            $destinationPath = env("ROOT") . env("FRONT") . 'avatars/' . $request->category . '/';
            $file = $request->image;
            $title = $request->image->getClientOriginalName();
            $fileName = $title . "-" . time() . '.' . $file->clientExtension();
            $file->move($destinationPath, $fileName);
        }
        LogCall(Auth::user()->id, '4', "Kullanıcı yeni bir avatar ekledi.");
        return back()->with("success", __('general.mesaj-9'));
    }

    public function avatarlar_kategori()
    {
        return view('back.pages.avatarlar.category.index');
    }

    public function avatarlar_kategori_add(Request $request)
    {
        $destinationPath = env("ROOT") . env("FRONT") . 'avatars/' . $request->title;
        if (!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, $mode = 0777, true, true);
        }
        LogCall(Auth::user()->id, '4', "Kullanıcı yeni bir avatar kategorisi ekledi.");
        return back()->with("success", __('general.mesaj-9'));
    }

    public function ikonlar()
    {
        return view('back.pages.ikonlar.index');
    }

    public function ikon_add(Request $request)
    {
        DB::table('icons')->insert([
            'title' => $request->title,
            'icon' => $request->icon,
            'created_at' => date('YmdHis'),
        ]);
        LogCall(Auth::user()->id, '4', "Kullanıcı " . $request->title . " isimli yeni bir ikon ekledi.");
        return back()->with("success", __('general.mesaj-9'));
    }

    public function twitch_yayincilar_yonetim()
    {
        return view('back.pages.yayincilar.index');
    }

    public function twitch_yayincilar_yonetim_favori_ekle($id)
    {
        DB::table('twitch_support_streamer')->where('id', $id)->update(['favori' => '1']);
        LogCall(Auth::user()->id, '4', "Kullanıcı " . $id . " id'li twitch yayıncısını favorilere ekledi.");
        return back()->with("success", __('general.mesaj-9'));
    }

    public function twitch_yayincilar_yonetim_favori_kaldir($id)
    {
        DB::table('twitch_support_streamer')->where('id', $id)->update(['favori' => '0']);
        LogCall(Auth::user()->id, '4', "Kullanıcı " . $id . " id'li twitch yayıncısını favoriden kaldırdı.");
        return back()->with("success", __('general.mesaj-9'));
    }

    public function hediye_kodlari_yonetim()
    {
        return view('back.pages.hediye-kodlari.index');
    }

    public function hediye_kodlari_yonetim_uret(Request $request)
    {
        $expired_at = str_replace(array('-', ':'), "", ($request->expired_at_date . $request->expired_at_time . "00"));
        DB::table('hediye_kodlari')->insert([
            'title' => $request->title,
            'price' => $request->price,
            'adet' => $request->adet,
            'created_at' => date('YmdHis'),
            'expired_at' => $expired_at,
        ]);
        $lastId = DB::getPdo()->lastInsertId();
        for ($i = 0; $i < $request->adet; $i++) {
            $codeAll = md5(uniqid(time(), true));
            /*$codeFirst = substr($codeAll, 0, 4);*/
            $codeFirst = Str::slug($request->title);
            $codeSecond = substr($codeAll, 4, 4);
            $codeThird = substr($codeAll, 8, 4);
            $codeFourth = substr($codeAll, 12, 4);
            $codeLast = strtoupper($codeFirst . "-" . $codeSecond . "-" . $codeThird . "-" . $codeFourth);
            DB::table('hediye_kodlari_kodlar')->insert([
                'hediye_kodu' => $lastId,
                'kod' => \epin::ENC($codeLast),
                'created_at' => date('YmdHis'),
                'expired_at' => $expired_at,
            ]);
        }
        LogCall(Auth::user()->id, '4', "Kullanıcı " . $request->title . " başlıklı hediye kodu üretti.");
        return back()->with("success", __('general.mesaj-9'));
    }

    public function kimlik_yonetim()
    {
        return view('back.pages.kimlik.index');
    }

    public function kimlik_yonetim_onayla($durum, $id)
    {
        if ($durum == 1) {
            DB::table('users')->where('id', $id)->update([
                'tc_verified_at' => date('YmdHis'),
            ]);
            LogCall(Auth::user()->id, '4', "Kullanıcı " . $id . " id'li kullanıcının kimliğini onayladı.");
            setBildirim($id, '1', 'Kimliğiniz Onaylandı', 'Kimlik onay işleminiz başarıyla tamamlanmıştır.', route('hesap_onayla'));
        }
        if ($durum == 2) {
            DB::table('users')->where('id', $id)->update([
                'tc_verified_at_first' => NULL,
            ]);
            LogCall(Auth::user()->id, '4', "Kullanıcı " . $id . " id'li kullanıcının kimliğini reddetti.");
            setBildirim($id, '1', 'Kimliğiniz Reddedildi', 'Kimlik onay işleminiz reddedildi, detayllar için tıklayın.', route('hesap_onayla'));
        }
        return back()->with("success", __('general.mesaj-9'));
    }

    public function twitch_donate_yonetim()
    {
        return view('back.pages.twitch-donate.index');
    }

    public function site_yonetim()
    {
        return view('back.pages.site-yonetim.index');
    }

    public function site_yonetim_post(Request $request)
    {
        $u = \App\Models\Settings::first();
        if ($request->logo != '') {
            $destinationPath = "front/site/";
            $file = $request->logo;
            $title = Str::slug($request->site_name);
            $fileName = $title . "-" . time() . '.' . $file->clientExtension();
            $file->move($destinationPath, $fileName);
            if ($u->logo != '') {
                deleteImageManually('front/site/' . $u->logo);
            }
            $u->logo = $fileName;
        }
        if ($request->logo_white != '') {
            $destinationPath = "front/site/";
            $file = $request->logo_white;
            $title = Str::slug($request->site_name) . "-1";
            $fileName = $title . "-" . time() . '.' . $file->clientExtension();
            $file->move($destinationPath, $fileName);
            if ($u->logo_white != '') {
                deleteImageManually('front/site/' . $u->logo_white);
            }
            $u->logo_white = $fileName;
        }
        if ($request->favicon != '') {
            $destinationPath = "front/site/";
            $file = $request->favicon;
            $title = Str::slug($request->site_name) . "-2";
            $fileName = $title . "-" . time() . '.' . $file->clientExtension();
            $file->move($destinationPath, $fileName);
            if ($u->favicon != '') {
                deleteImageManually('front/site/' . $u->favicon);
            }
            $u->favicon = $fileName;
        }
        $u->site_name = $request->site_name;
        $u->description = $request->description;
        $u->footer_text = $request->footer_text;
        $u->meta = $request->meta;
        $u->analytics = $request->analytics;
        $u->tel_1 = $request->tel_1;
        $u->tel_2 = $request->tel_2;
        $u->email_1 = $request->email_1;
        $u->email_2 = $request->email_2;
        $u->facebook = $request->facebook;
        $u->twitter = $request->twitter;
        $u->youtube = $request->youtube;
        $u->linkedin = $request->linkedin;
        $u->instagram = $request->instagram;
        $u->address = $request->address;
        $u->address_iframe = $request->address_iframe;
        $u->robots = $request->robots;
        $u->pazar_komisyon = $request->pazar_komisyon;
        $u->pazar_komisyon_alis = $request->pazar_komisyon_alis;
        $u->yayin_komisyon = $request->yayin_komisyon;
        $u->yayin_min = $request->yayin_min;
        $u->smsUsername = $request->smsUsername;
        $u->smsUserpass = $request->smsUserpass;
        $u->smsSendTitle = $request->smsSendTitle;
        $u->epinAuthName = $request->epinAuthName;
        $u->epinApiName = $request->epinApiName;
        $u->epinApiKey = $request->epinApiKey;
        $u->paytrMerchantId = $request->paytrMerchantId;
        $u->paytrMerchantKey = $request->paytrMerchantKey;
        $u->paytrMerchantSalt = $request->paytrMerchantSalt;
        $u->onlineOdemeKomisyon = $request->onlineOdemeKomisyon;
        $u->onlineOdemeYurtdisiKomisyon = $request->onlineOdemeYurtdisiKomisyon;
        $u->paparaKomisyon = $request->paparaKomisyon;
        $u->paparaKey = $request->paparaKey;
        $u->paparaSecretKey = $request->paparaSecretKey;
        $u->gpayUsername = $request->gpayUsername;
        $u->gpayKey = $request->gpayKey;
        $u->gpayKomisyon = $request->gpayKomisyon;
        $u->gpayYurtdisiKomisyon = $request->gpayYurtdisiKomisyon;
        $u->ininalKomisyon = $request->ininalKomisyon;
        $u->bkmKomisyon = $request->bkmKomisyon;
        $u->ozanKomisyon = $request->ozanKomisyon;
        $u->razertl = $request->razertl;
        $u->razerusd = $request->razerusd;
        $u->razercstn = $request->razercstn;
        $u->razercsto = $request->razercsto;
        $u->razercstk = $request->razercstk;
        $u->razerusdk = $request->razerusdk;

        /*
         * Env düzenlemeleri KRİTİK
         */
        setEnv('MAIL_MAILER', $request->MAIL_MAILER);
        setEnv('MAIL_HOST', $request->MAIL_HOST);
        setEnv('MAIL_PORT', $request->MAIL_PORT);
        setEnv('MAIL_USERNAME', $request->MAIL_USERNAME);
        setEnv('MAIL_PASSWORD', $request->MAIL_PASSWORD);
        setEnv('MAIL_ENCRYPTION', $request->MAIL_ENCRYPTION);
        setEnv('MAIL_FROM_ADDRESS', $request->MAIL_FROM_ADDRESS);
        setEnv('CONTATC_MAIL', $request->CONTATC_MAIL);
        setEnv('TWITCH_SECRET', $request->TWITCH_SECRET);
        setEnv('TWITCH_ID', $request->TWITCH_ID);
        setEnv('TWITCH_REDIRECT_URI', $request->TWITCH_REDIRECT_URI);
        setEnv('STREAM_ID', $request->STREAM_ID);
        setEnv('STREAM_SECRET', $request->STREAM_SECRET);
        setEnv('STREAM_URL', $request->STREAM_URL);
        setEnv('GOOGLE_CLIENT_ID', $request->GOOGLE_CLIENT_ID);
        setEnv('GOOGLE_CLIENT_SECRET', $request->GOOGLE_CLIENT_SECRET);
        setEnv('GOOGLE_REDIRECT_URI', $request->GOOGLE_REDIRECT_URI);
        setEnv('STEAM_CLIENT_SECRET', $request->STEAM_CLIENT_SECRET);
        setEnv('STEAM_REDIRECT_URI', $request->STEAM_REDIRECT_URI);
        setEnv('PARAM_CLIENT_CODE', $request->PARAM_CLIENT_CODE);
        setEnv('PARAM_CLIENT_USERNAME', $request->PARAM_CLIENT_USERNAME);
        setEnv('PARAM_CLIENT_PASSWORD', $request->PARAM_CLIENT_PASSWORD);
        setEnv('PARAM_GUID', $request->PARAM_GUID);
        setEnv('EPIN_BASE', $request->EPIN_BASE);
        setEnv('BILNEX_TOKEN', $request->BILNEX_TOKEN);
        setEnv('BILNEX_TEDARIKCI', $request->BILNEX_TEDARIKCI);
        setEnv('BILNEX_BRANCH', $request->BILNEX_BRANCH);


        if ($u->save()) {
            LogCall(Auth::user()->id, '4', "Kullanıcı site ayarlarını güncelledi.");
            return back()->with("success", __('general.mesaj-9'));
        } else {
            LogCall(Auth::user()->id, '4', "Kullanıcı site ayarlarını güncellemeye çalıştı ama bir hata meydana geldi.");
            return back()->with("error", "İşlem sırasında bir hata meydana geldi.");
        }
    }

    public function deviceTokenRegister(Request $request)
    {
        return deviceTokenRegister($request->token);
    }

    public function odeme_kanallari()
    {
        return view('back.pages.rapor.odeme-kanallari.index');
    }

    public function epin_rapor()
    {
        return view('back.pages.rapor.epin-rapor.index');
    }
    public function oyun_parasi_rapor()
    {
        return view('back.pages.rapor.oyun-parasi-rapor.index');
    }
    public function ilan_rapor()
    {
        return view('back.pages.rapor.ilan-rapor.index');
    }
    public function boss_rapor()
    {
        return view('back.pages.rapor.boss-rapor.index');
    }



    public function panel_loglar()
    {
        return view('back.pages.loglar.index');
    }

    public function resimYukle(Request $request)
    {
        $accepted_origins = array("https://oyuneks.com");
        $imgFolder = "front/news/" . time();
        reset($_FILES);
        $tmp = current($_FILES);
        if (is_uploaded_file($tmp['tmp_name'])) {
            if (isset($_SERVER['HTTP_ORIGIN'])) {
                if (in_array($_SERVER['HTTP_ORIGIN'], $accepted_origins)) {
                    header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
                } else {
                    header("HTTP/1.1 403 Origin Denied");
                    return;
                }
            }

            if (preg_match("/([^\w\s\d\-_~,;:\[\]\(\).])|([\.]{2,})/", $tmp['name'])) {
                header("HTTP/1.1 400 Invalid file name.");
                return;
            }
            if (!in_array(strtolower(pathinfo($tmp['name'], PATHINFO_EXTENSION)), array("gif", "jpg", "png"))) {
                header("HTTP/1.1 400 Invalid extension.");
                return;
            }

            $filePath = $imgFolder . $tmp['name'];
            move_uploaded_file($tmp['tmp_name'], $filePath);
            echo json_encode(array('location' => "/" . $filePath));
        } else {
            header("HTTP/1.1 500 Server Error");
        }
    }

    public function resimYukle2(Request $request)
    {
        $accepted_origins = array("https://oyuneks.com");
        $imgFolder = "front/nasil_yuklenir/" . time();
        reset($_FILES);
        $tmp = current($_FILES);
        if (is_uploaded_file($tmp['tmp_name'])) {
            if (isset($_SERVER['HTTP_ORIGIN'])) {
                if (in_array($_SERVER['HTTP_ORIGIN'], $accepted_origins)) {
                    header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
                } else {
                    header("HTTP/1.1 403 Origin Denied");
                    return;
                }
            }

            if (preg_match("/([^\w\s\d\-_~,;:\[\]\(\).])|([\.]{2,})/", $tmp['name'])) {
                header("HTTP/1.1 400 Invalid file name.");
                return;
            }
            if (!in_array(strtolower(pathinfo($tmp['name'], PATHINFO_EXTENSION)), array("gif", "jpg", "png"))) {
                header("HTTP/1.1 400 Invalid extension.");
                return;
            }

            $filePath = $imgFolder . $tmp['name'];
            move_uploaded_file($tmp['tmp_name'], $filePath);
            echo json_encode(array('location' => "/" . $filePath));
        } else {
            header("HTTP/1.1 500 Server Error");
        }
    }

    public function seo_yonetim()
    {
        return view('back.pages.seo.index');
    }

    public function seo_yonetim_view(Request $request)
    {
        if ($request->getir == "meta") {
            return view('back.pages.seo.data.meta');
        } elseif ($request->getir == "media") {
            return view('back.pages.seo.data.media');
        } elseif ($request->getir == "meta_data") {
            return view('back.pages.seo.data.meta-data')->with('id', $request->id);
        } elseif ($request->getir == "media_data") {
            return view('back.pages.seo.data.media-data')->with('id', $request->id);
        } elseif ($request->getir == "oyunlar") {
            return view('back.pages.seo.data.oyunlar')->with('id', $request->id);
        } elseif ($request->getir == "oyunlar_data") {
            return view('back.pages.seo.data.oyunlar-data')->with('id', $request->id);
        } elseif ($request->getir == "oyun_alt") {
            return view('back.pages.seo.data.oyun-alt')->with('id', $request->id);
        } elseif ($request->getir == "oyun_alt_data") {
            return view('back.pages.seo.data.oyun-alt-data')->with('id', $request->id);
        } elseif ($request->getir == "muve") {
            return view('back.pages.seo.data.muve')->with('id', $request->id);
        } elseif ($request->getir == "muve_data") {
            return view('back.pages.seo.data.muve_data')->with('id', $request->id);
        }
    }

    public function seo_yonetim_meta_save(Request $request)
    {
        $kayit = DB::table('pages')->where('id', $request->id)->update([
            'title' => $request->title,
            'description' => $request->description,
            'keywords' => $request->keywords,
        ]);
        if ($kayit) {
            return true;
        } else {
            return false;
        }
    }

    public function seo_yonetim_media_save(Request $request)
    {
        $kayit = DB::table($request->table)->where('id', $request->id)->update([
            'alt' => $request->alt,
        ]);
        if ($kayit) {
            return true;
        } else {
            return false;
        }
    }

    public function seo_yonetim_oyun_meta_save(Request $request)
    {
        $kayit = DB::table('games')->where('id', $request->id)->update([
            'title' => $request->title,
            'description' => $request->description,
            'keywords' => $request->keywords,
            'link' => $request->link,
        ]);
        if ($kayit) {
            return true;
        } else {
            return false;
        }
    }

    public function seo_yonetim_oyun_alt_meta_save(Request $request)
    {
        $kayit = DB::table('games_titles')->where('id', $request->id)->update([
            'title' => $request->title,
            'description' => $request->description,
            'keywords' => $request->keywords,
            'link' => $request->link,
        ]);
        if ($kayit) {
            return true;
        } else {
            return false;
        }
    }

    public function seo_yonetim_muve_meta_save(Request $request)
    {
        $kayit = DB::table('muve_games')->where('id', $request->id)->update([
            'title' => $request->title,
            'metaDescription' => $request->description,
            'keywords' => $request->keywords,
            'link' => $request->link,
        ]);
        if ($kayit) {
            return true;
        } else {
            return false;
        }
    }
}
