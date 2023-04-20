<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use SoapClient;

class SiparisController extends Controller
{
    public function siparis_ver(Request $request)
    {
        if($request->tutar<1) {return back()->with("error", "Tutar hatalı.. Bakiyenize eklemek istediğiniz tutar 1 TL üzeri olmalıdır.");}

        if (!isset($_COOKIE['redirect'])) {
            echo "<meta http-equiv='refresh' content='2;url=" . route('homepage') . "' />";
            die(__('general.yonlendiriliyorsunuz'));
        }
        $adet = $_COOKIE['adet'];
        $package = $_COOKIE['package'];
        if (!isset(Auth::user()->id)) { //eğer kullanıcı oturum açmamışsa
            return redirect()->route('giris');
        } else { //kullanıcı oturum açtıktan sonra
            if (isset($_COOKIE['api'])) {
                $fiyat = $request->tutar;
            } else {
                $fiyat = findGamesPackagesPrice($package) * $adet;
            }
            if (Auth::user()->bakiye < $fiyat) { // eğer bakiyesi yeterli değilse
                return redirect()->route('bakiye_ekle')->with('fiyat', $fiyat);
            } else { //bakiyesi yeterli ise
                if (isset($_COOKIE['api'])) { // Eğer api üstünden sipariş ise
                    $api = $_COOKIE['api'];
                    $apiUrun = $_COOKIE['apiUrun'];

                    DB::table('siparisler')->insert([
                        'user' => Auth::user()->id,
                        'urun' => $apiUrun,
                        'amount' => $request->tutar,
                        'qty' => $request->adet,
                        'api' => '1',
                        'apiGameId' => $api,
                        'status' => '0',
                        'created_at' => date('YmdHis'),
                    ]);
                    $lastId = DB::getPdo()->lastInsertId();
                    /*
                     * Ürün Satın Alma İsteği
                     */
                    $ch = curl_init();
                    $headers = array(
                        'Authorization: ' . getAuthName(),
                        'ApiName: ' . getApiName(),
                        'ApiKey: ' . getApiKey(),
                        'Content-Type: Content-Type: application/json',
                    );
                    $payload = json_encode(array(
                        "TransactionId" => (int)$lastId,
                        "StockCode" => $package,
                        "PhoneNumber" => Auth::user()->telefon,
                        "Email" => Auth::user()->email,
                        "Quantity" => (int)$request->adet
                    ));
                    curl_setopt($ch, CURLOPT_URL, env('EPIN_BASE') . '/SaveOrder');
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    curl_setopt($ch, CURLOPT_HEADER, 0);
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
                    $response = curl_exec($ch);
                    $result_product = json_decode($response);
                    curl_close($ch);
                    if ($result_product->ResultStatus) {
                        return redirect()->route('siparislerim')->with('status', 'success');
                    } else {
                        return redirect()->route('siparislerim')->with('status', 'error');
                    }
                } else { //epin olmayan alışverişler için

                }
            }
        }
    }

    public function bakiye_ekle()
    {
        return view('front.pages.bakiye-ekle');
        /*
        if (getUserVerifiyStep() >= 2) {
            return view('front.pages.bakiye-ekle');
        } else {
            LogCall(Auth::user()->id, '1', "Kullanıcı bakiye ekleme sayfasına girmek istedi fakat telefon numarası onaylı değildi.");
            return redirect()->route('hesap_onayla')->with('error', 'Bakiye ekleyebilmek için lütfen telefonunuzu onaylayın.')->with('errorType', '2');
        } */
    }

    public function odeme_yap(Request $request)
    { //return back()->with("error", "Ödeme servislerinde bir hata meydana geldi!");

        if($request->tutar<1 && $request->tur != 3) {return back()->with("error", "Tutar hatalı.. Bakiyenize eklemek istediğiniz tutar 1 TL üzeri olmalıdır.");}

#--------Ödeme bildirim flood kontrol dakikada 5 den fazla bildirimi varsa red
        $tar = date('Y-m-d H:i'); $uid = Auth::user()->id;
        $al = DB::select("select * from odemeler where user='$uid' and status in ('0','2') and created_at like '$tar%'");
        if (count($al) > 4) {
            return redirect()->route("hesabim");
        }
#--------Ödeme bildirim flood kontrol


        if ($request->tur == 1) { //online ödeme
            $tutar = floatval($request->tutar . "." . $request->tutar2);
            if ($tutar != '' or $tutar > 0) {
                $tutarOrigin = $tutar;

                $telefon = Auth::user()->telefon;
                $merchant_ok_url = "https://oyuneks.com/siparis/basarili";
                $merchant_fail_url = "https://oyuneks.com/siparis/basarisiz";
                $codeAll = md5(uniqid(time(), true));
                if (isset($request->channel)) {
                    if($request->country=='sec') {return back()->with("error", "Yurtdışı ödemelerde ülkenizi belirtmeniz gerekiyor.");}
                    $yurtdisi = true;
                    $channel = $request->channel;
                    $komisyon = getCacheSetings()->onlineOdemeYurtdisiKomisyon;
                    //$tutar = number_format((float)($tutar * $komisyon / 100) + $tutar, 2, '.', '');
                    $tutar = number_format(($tutar / (1 - ($komisyon / 100))), 2, ".", "");
                } else {
                    $yurtdisi = false;
                    $channel = 1;
                    $komisyon = getCacheSetings()->onlineOdemeKomisyon;
                    //$tutar = number_format((float)($tutar * $komisyon / 100) + $tutar, 2, '.', '');
                    $tutar = number_format(($tutar / (1 - ($komisyon / 100))), 2, ".", "");
                }
                DB::table('odemeler')->insert([
                    'transId' => $codeAll,
                    'user' => Auth::user()->id,
                    'amount' => $tutarOrigin,
                    'channel' => $channel,
                    'ulke' => $yurtdisi?$request->country:'',
                    'created_at' => date('YmdHis')
                ]); //ödeme kaydı gerçekleştiriliyor
                //$lastId = DB::getPdo()->lastInsertId();
                $lastId = $codeAll;

                /*
                 * PAYTR DIRECT API STEP 1
                 */
                //$tutarOrigin == '1.67';
                $merchant_id = getPaytrMerchantId($yurtdisi);
                $merchant_key = getPaytrMerchantKey($yurtdisi);
                $merchant_salt = getPaytrMerchantSalt($yurtdisi);
                $merchant_ok_url = "https://oyuneks.com/siparis/basarili";
                $merchant_fail_url = "https://oyuneks.com/siparis/basarisiz";
                $user_basket = base64_encode(json_encode(array(
                    array("Oyuneks Bakiye Yükleme İşlemi", $tutar, 1), // 1. ürün (Ürün Ad - Birim Fiyat - Adet )
                )));
                $merchant_oid = $lastId;
                $test_mode = "0";
                $non_3d = "1"; //3d'siz işlem için 0
                $client_lang = getLang();
                $non3d_test_failed = "0"; //non3d işlemde, başarısız işlemi test etmek için 1 gönderilir (test_mode ve non_3d değerleri 1 ise dikkate alınır!)
                if (isset($_SERVER["HTTP_CLIENT_IP"])) {
                    $ip = $_SERVER["HTTP_CLIENT_IP"];
                } elseif (isset($_SERVER["HTTP_CF_PSEUDO_IPV4"])) {
                    $ip = $_SERVER["HTTP_CF_PSEUDO_IPV4"];
                } elseif (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
                    $ip = $_SERVER["HTTP_CF_CONNECTING_IP"];
                } elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
                    $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
                } else {
                    $ip = $_SERVER["REMOTE_ADDR"];
                }






                $user_ip = $ip;
                $email = Auth::user()->email;
                $user_name = Auth::user()->name;
                $user_address = "-";
                $user_phone = Auth::user()->telefon;
                $payment_amount = $tutar * 100;
                $currency = "TL";
                $timeout_limit = "30";
                $debug_on = 0;
                $no_installment = 0;
                $max_installment = 0;
                $post_url = "https://www.paytr.com/odeme/api/get-token";
                $hash_str = $merchant_id . $user_ip . $merchant_oid . $email . $payment_amount . $user_basket . $no_installment . $max_installment . $currency . $test_mode;
                $paytr_token = base64_encode(hash_hmac('sha256', $hash_str . $merchant_salt, $merchant_key, true));
                $post_vals = array(
                    'merchant_id' => $merchant_id,
                    'user_ip' => $user_ip,
                    'merchant_oid' => $merchant_oid,
                    'email' => $email,
                    'payment_amount' => $payment_amount,
                    'paytr_token' => $paytr_token,
                    'user_basket' => $user_basket,
                    'debug_on' => $debug_on,
                    'no_installment' => $no_installment,
                    'max_installment' => $max_installment,
                    'user_name' => $user_name,
                    'user_address' => $user_address,
                    'user_phone' => empty($user_phone) ? '00000000' : $user_phone,
                    'merchant_ok_url' => $merchant_ok_url,
                    'merchant_fail_url' => $merchant_fail_url,
                    'timeout_limit' => $timeout_limit,
                    'currency' => $currency,
                    'test_mode' => $test_mode
                );
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $post_url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post_vals);
                curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 20);
                $result = @curl_exec($ch);
                if (curl_errno($ch))
                    return back()->with("error", "Ödeme servislerinde bir hata meydana geldi!");
                curl_close($ch);


                $result = json_decode($result, 1);
                \Log::debug('PAYTR-REQUEST:' . serialize($post_vals));
                \Log::debug('PAYTR-RESPONSE:' . serialize($result));
                if ($result['status'] == 'success') {
                    $token = $result['token'];
                    echo '<script src="https://www.paytr.com/js/iframeResizer.min.js"></script>';
                    echo '<iframe src="https://www.paytr.com/odeme/guvenli/' . $token . '" id="paytriframe" frameborder="0" scrolling="no" style="width: 100%;"></iframe>';
                    echo "<script>iFrameResize({},'#paytriframe');</script>";
                } else {
                    return back()->with("error", "Ödeme servislerinde bir hata meydana geldi. Bankadan dönen hata sebebi : " . $result['reason']);
                }
            } else {
                return back()->with("error", "Ödeme tutarını girin!");
            }
        } elseif ($request->tur == 2) { //havale eft
            $tutar = floatval($request->tutar . "." . $request->tutar2);
            if ($tutar > 0) {
                $codeAll = md5(uniqid(time(), true));
                DB::table('odemeler')->insert([
                    'transId' => $codeAll,
                    'user' => Auth::user()->id,
                    'amount' => $tutar,
                    'channel' => 2,
                    'description' => $request->banka,
                    'status' => 0,
                    'created_at' => date('YmdHis')
                ]); //ödeme kaydı gerçekleştiriliyor
                /*
                 * PAYTR Havale / EFT
                 */
                $merchant_id = getPaytrMerchantId();
                $merchant_key = getPaytrMerchantKey();
                $merchant_salt = getPaytrMerchantSalt();



                if (isset($_SERVER["HTTP_CLIENT_IP"])) {
                    $ip = $_SERVER["HTTP_CLIENT_IP"];
                } elseif (isset($_SERVER["HTTP_CF_PSEUDO_IPV4"])) {
                    $ip = $_SERVER["HTTP_CF_PSEUDO_IPV4"];
                } elseif (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
                    $ip = $_SERVER["HTTP_CF_CONNECTING_IP"];
                } elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
                    $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
                } else {
                    $ip = $_SERVER["REMOTE_ADDR"];
                }


                $user_ip = $ip;
                $merchant_oid = $codeAll;
                $email = Auth::user()->email;
                $payment_amount = $tutar * 100;
                $payment_type = 'eft';
                $debug_on = 1; //hata mesajlarını ekrana bas
                $timeout_limit = "30";
                $test_mode = 0;
                $bank = DB::table('payment_channels_eft')->where('id', $request->banka)->first()->bankSlug;
                $hash_str = $merchant_id . $user_ip . $merchant_oid . $email . $payment_amount . $payment_type . $test_mode;
                $paytr_token = base64_encode(hash_hmac('sha256', $hash_str . $merchant_salt, $merchant_key, true));
                $post_vals = array(
                    'merchant_id' => $merchant_id,
                    'user_ip' => $user_ip,
                    'merchant_oid' => $merchant_oid,
                    'email' => $email,
                    'payment_amount' => $payment_amount,
                    'payment_type' => $payment_type,
                    'paytr_token' => $paytr_token,
                    'debug_on' => $debug_on,
                    'timeout_limit' => $timeout_limit,
                    'test_mode' => $test_mode,
                    'bank' => $bank,
                    'user_phone' => Auth::user()->telefon,
                );
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "https://www.paytr.com/odeme/api/get-token");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post_vals);
                curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 20);
                $result = @curl_exec($ch);
                if (curl_errno($ch)) {
                    die("PAYTR EFT IFRAME connection error. err:" . curl_error($ch));
                }
                curl_close($ch);
                $result = json_decode($result, 1);
                if ($result['status'] == 'success') {
                    LogCall(Auth::user()->id, '3', "Kullanıcı ödeme talebi oluşturuldu.");
                    setBildirim(Auth::user()->id, '4', 'Ödeme Talebiniz Oluşturuldu', 'Ödeme talebiniz oluşturuldu, detaylar için tıklayın.', route('odemelerim'));
                    $token = $result['token'];
                    return redirect()->route('havale_eft', $token);
                } else {
                    LogCall(Auth::user()->id, '3', "Kullanıcı ödeme talebi oluşturulamadı, nedeni: " . $result['reason'] . ".");
                    setBildirim(Auth::user()->id, '4', 'Ödeme Talebiniz Oluşturulamadı', 'Ödeme talebiniz oluşturulamadı, detaylar için tıklayın.', route('bakiye_ekle'));
                    return back()->with("error", "Ödemeniz işlenirken bir sorun meydana geldi.");
                    die("PAYTR EFT IFRAME failed. reason:" . $result['reason']);
                }
                //return redirect()->route('odemelerim')->with('success', 'Yaptığınız ödeme başvurusu başarıyla alınmıştır, incelendikten sonra onaylanarak bakiyeniz ekllenecektir.');
            } else {
                return back()->with("error", "Ödeme tutarını girin!");
            }
        } elseif ($request->tur == 3) { //hediye kodu
            $request->kod = \epin::ENC($request->kod);
            $kod = DB::table('hediye_kodlari_kodlar')->where('kod', $request->kod)->whereNull('deleted_at');
            if ($kod->count() > 0) {
                if ($kod->first()->isUsed == 0) {
                    if ($kod->first()->expired_at > date('Y-m-d H:i:s')) {
                        $paket = DB::table('hediye_kodlari')->where('id', $kod->first()->hediye_kodu)->whereNull('deleted_at');
                        if ($paket->count() > 0) {
                            DB::table('odemeler')->insert([
                                'user' => Auth::user()->id,
                                'amount' => $paket->first()->price,
                                'channel' => 3,
                                'description' => \epin::DEC($request->kod) . " kodu kullanılarak yükleme yapıldı.",
                                'status' => 1,
                                'created_at' => date('YmdHis')
                            ]); //ödeme kaydı gerçekleştiriliyor
                            $kod->update(['isUsed' => '1']);
                            DB::table('users')->where('id', Auth::user()->id)->update([
                                'bakiye' => Auth::user()->bakiye + $paket->first()->price,
                            ]);

                            odeme_kontrol(); // Ödeme limit kontrol

                            LogCall(Auth::user()->id, '3', "Kullanıcı hediye kodu tanımlandı.");
                            setBildirim(Auth::user()->id, '4', 'Hediye Kodunuz Tanımlandı', 'Hediye kodunuz başarıyla tanımlandı, detaylar için tıklayın.', route('odemelerim'));
                            return redirect()->route('odemelerim')->with('success', 'Hediye kodu kullanarak yapmış olduğunuz yükleme başarılıdır!');
                        } else {
                            LogCall(Auth::user()->id, '3', "Kullanıcı hediye kodu tanımlanırken bir hata oluştu. Hata nedeni : Kullanmaya çalışılan hediye kodu kampanyası son bulmuştu.");
                            return back()->with("error", "Kullanmaya çalıştığığınız kodun kampanyası silinmiştir.");
                        }
                    } else {
                        LogCall(Auth::user()->id, '3', "Kullanıcı hediye kodu tanımlanırken bir hata oluştu. Hata nedeni : Kullanmaya çalışılan hediye kodu süresi dolmuştu.");
                        return back()->with("error", "Kullanmaya çalıştığığınız kodun süresi dolmuş!");
                    }
                } else {
                    LogCall(Auth::user()->id, '3', "Kullanıcı hediye kodu tanımlanırken bir hata oluştu. Hata nedeni : Kullanmaya çalışılan hediye kodu zaten kullanılmıştı.");
                    return back()->with("error", "Girdiğiniz kod zaten kullanılmış!");
                }
            } else {
                LogCall(Auth::user()->id, '3', "Kullanıcı hediye kodu tanımlanırken bir hata oluştu. Hata nedeni : Kullanmaya çalışılan hediye kodu mevcut değil.");
                return back()->with("error", "Geçersiz bir kod girdiniz!");
            }
        } elseif ($request->tur == 4) { // kripto para ödemesi
            $tutar = floatval($request->tutar . "." . $request->tutar2);
            if ($tutar != '' or $tutar > 0) {
                DB::table('odemeler')->insert([
                    'user' => Auth::user()->id,
                    'amount' => $tutar,
                    'channel' => 6,
                    'description' => $request->crypto,
                    'status' => 0,
                    'created_at' => date('YmdHis')
                ]); //ödeme kaydı gerçekleştiriliyor
                LogCall(Auth::user()->id, '3', "Kullanıcı kripto para ödeme talebi oluşturuldu.");
                setBildirim(Auth::user()->id, '4', 'Ödeme Talebiniz Oluşturuldu', 'Ödeme talebiniz oluşturuldu, detaylar için tıklayın.', route('odemelerim'));
                return redirect()->route('odemelerim')->with('success', 'Yaptığınız ödeme başvurusu başarıyla alınmıştır, incelendikten sonra onaylanarak bakiyeniz eklenecektir.');
            } else {
                return back()->with("error", "Ödeme tutarını girin!");
            }
        } elseif ($request->tur == 5) { //mobile ödeme
            $tutar = floatval($request->tutar . "." . $request->tutar2);
            if ($tutar != '' or $tutar > 0) {
                DB::table('odemeler')->insert([
                    'user' => Auth::user()->id,
                    'amount' => $tutar,
                    'channel' => 7,
                    'description' => 'PayByMe Mobil Ödeme İşlemi',
                    'status' => 0,
                    'created_at' => date('YmdHis')
                ]); //ödeme kaydı gerçekleştiriliyor

                /*
                 * PayByMe Ödeme İsteği STEP 1
                 */
                $request_url = 'https://oyuneks.com/siparis/basarili';
                $payment_url = 'https://oyuneks.com/siparis/basarisiz';
                $username = 'MyUsername';
                $password = 'MyPasword';
                $syncId = 123456;
                $subCompany = 'Oyuneks';
                $assetName = 'Oyuneks Mobil Bakiye Yükleme İşlemi';
                $assetPrice = $tutar * 100; // 100 = 1 TL
                $clientIp = $_SERVER['REMOTE_ADDR'];
                $countryCode = 'TR';
                $languageCode = 'tr';
                $notifyPage = 'https://oyuneks.com/api/mobil-odeme-notify';
                $redirectPage = 'https://oyuneks.com/siparis/basarili';
                $errorPage = 'https://oyuneks.com/siparis/basarisiz';

                LogCall(Auth::user()->id, '3', "Kullanıcı mobil ödeme talebi oluşturuldu.");
                setBildirim(Auth::user()->id, '4', 'Ödeme Talebiniz Oluşturuldu', 'Ödeme talebiniz oluşturuldu, detaylar için tıklayın.', route('odemelerim'));
                //return redirect()->route('odemelerim')->with('success', 'Yaptığınız ödeme başvurusu başarıyla alınmıştır, incelendikten sonra onaylanarak bakiyeniz eklenecektir.');
            } else {
                return back()->with("error", "Ödeme tutarını girin!");
            }
        } elseif ($request->tur == 7) { //diğer ödeme
            $tutar = $request->tutar;
            if ($tutar > 0) {
                $codeAll = md5(uniqid(time(), true));
                DB::table('odemeler')->insert([
                    'transId' => $codeAll,
                    'user' => Auth::user()->id,
                    'amount' => $tutar,
                    'channel' => 8,
                    'description' => "T.c. : " . $request->tcno . " Ödeme Kanalı : " . $request->odeme_kanali . " | Açıklama : " . $request->aciklama,
                    'status' => 0,
                    'created_at' => date('YmdHis')
                ]); //ödeme kaydı gerçekleştiriliyor
                LogCall(Auth::user()->id, '3', "Kullanıcı ödeme talebi oluşturuldu.");
                setBildirim(Auth::user()->id, '4', 'Ödeme Talebiniz Oluşturuldu', 'Ödeme talebiniz oluşturuldu, detaylar için tıklayın.', route('odemelerim'));
                return redirect()->route('odemelerim')->with('success', 'Yaptığınız ödeme başvurusu başarıyla alınmıştır, incelendikten sonra onaylanarak bakiyeniz eklenecektir.');
            } else {
                return back()->with("error", "Ödeme tutarını girin!");
            }
        } elseif ($request->tur == 8) { //papara ile ödeme
            if (getUserVerifiyStep() < 3) {
                LogCall(Auth::user()->id, '1', "Kullanıcı PAPARA yatirmak istedi fakat telefon numarası ve/veya kimliği onaylı değildi.");
                return redirect()->route('hesap_onayla')->with('error', 'Bakiye Yuklemek için lütfen telefonunuzu ve kimliğinizi onaylayın.');
            }
            //return back()->with("error", "Ödeme tutarını boş girdiniz veya 1 TL'den düşük bir tutar girdiniz!");
            $tutar = floatval($request->tutar . "." . $request->tutar2);
            if ($tutar >= 1) {
                $tutarOrigin = $tutar;
                $komisyon = getCacheSetings()->paparaKomisyon;
                $tutar = number_format(($tutar / (1 - ($komisyon / 100))), 2, ".", "");
                //$tutar = number_format((float)($tutar * $komisyon / 100) + $tutar, 2, '.', '');
                $codeAll = md5(uniqid(time(), true));
                DB::table('odemeler')->insert([
                    'transId' => $codeAll,
                    'user' => Auth::user()->id,
                    'amount' => $tutarOrigin,
                    'channel' => 9,
                    'description' => 'Ödeme işleniyor...',
                    'status' => 0,
                    'created_at' => date('YmdHis')
                ]); //ödeme kaydı gerçekleştiriliyor
                /*
                 * Papara Ödeme İsteği Gönderiliyor
                 */
                $paparaKey = getPaparaKey();
                $amount = $tutar;
                $referenceId = $codeAll;
                $orderDescription = "Oyuneks bakiye yükleme işlemi";
                $notificationUrl = getPaparaNotifyUrl();
                $failNotificationUrl = getPaparaNotifyUrl();
                $redirectUrl = getPaparaRedirectUrl();
                if (Auth::user()->tc_verified_at == NULL) {
                    $turkishNationalId = "11111111111";
                } else {
                    $turkishNationalId = Auth::user()->tcno;
                }
                $post_vals = array(
                    'amount' => $amount,
                    'referenceId' => $referenceId,
                    'orderDescription' => $orderDescription,
                    'notificationUrl' => $notificationUrl,
                    'failNotificationUrl' => $failNotificationUrl,
                    'redirectUrl' => $redirectUrl,
                    'turkishNationalId' => $turkishNationalId,
                );
                $post_vals = json_encode($post_vals);
                $ch = curl_init();
                $headers = array(
                    'ApiKey: ' . getPaparaKey(),
                    'Content-Type: application/json',
                );
                curl_setopt($ch, CURLOPT_URL, "https://merchant-api.papara.com/payments");
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post_vals);
                curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 20);
                $result = @curl_exec($ch);
                curl_close($ch);
                $result = json_decode($result, 1);
                $islemSonucu = $result['succeeded'];

                if ($islemSonucu) {
                    LogCall(Auth::user()->id, '3', "Kullanıcı papara ile ödeme yapma sayfasına yönlendirildi.");
                    return redirect($result['data']['paymentUrl']);
                    die();
                } else {
                    LogCall(Auth::user()->id, '3', "Kullanıcı papara ödeme talebi oluşturulamadı, nedeni: " . $result['error']['message'] . ".");
                    setBildirim(Auth::user()->id, '4', 'Ödeme Talebiniz Oluşturulamadı', 'Ödeme talebiniz oluşturulamadı.', route('bakiye_ekle'));
                    return back()->with("error", "Ödemeniz işlenirken bir sorun meydana geldi, detaylı bilgi için lütfen bizimle iletişime geçin.");
                }
                //return redirect()->route('odemelerim')->with('success', 'Yaptığınız ödeme başvurusu başarıyla alınmıştır, incelendikten sonra onaylanarak bakiyeniz ekllenecektir.');
            } else {
                return back()->with("error", "Ödeme tutarını boş girdiniz veya 1 TL'den düşük bir tutar girdiniz!");
            }
        } elseif ($request->tur == 9) { //gpay ile ödeme
            $tutar = floatval($request->tutar . "." . $request->tutar2);
            if ($tutar >= 1) {
                $tutarOrigin = $tutar;

                $codeAll = md5(uniqid(time(), true));
                $channel = $request->channel;
                if ($channel == 10) {
                    $komisyon = getCacheSetings()->gpayKomisyon;
                    $tutar = number_format(($tutar / (1 - ($komisyon / 100))), 2, ".", "");
                    //$tutar = number_format((float)($tutar * $komisyon / 100) + $tutar, 2, '.', '');
                } elseif ($channel == 11) {
                    $komisyon = getCacheSetings()->ininalKomisyon;
                    $tutar = number_format(($tutar / (1 - ($komisyon / 100))), 2, ".", "");
                    //$tutar = number_format((float)($tutar * $komisyon / 100) + $tutar, 2, '.', '');
                } elseif ($channel == 12) {
                    $komisyon = getCacheSetings()->bkmKomisyon;
                    $tutar = number_format(($tutar / (1 - ($komisyon / 100))), 2, ".", "");
                    //$tutar = number_format((float)($tutar * $komisyon / 100) + $tutar, 2, '.', '');
                } elseif ($channel == 14) {
                    if($request->country=='sec') {return back()->with("error", "Yurtdışı ödemelerde ülkenizi belirtmeniz gerekiyor.");}
                    $komisyon = getCacheSetings()->gpayYurtdisiKomisyon;
                    $tutar = number_format(($tutar / (1 - ($komisyon / 100))), 2, ".", "");
                    //$tutar = number_format((float)($tutar * $komisyon / 100) + $tutar, 2, '.', '');
                } elseif ($channel == 15) {
                    $tutar = floatval($request->tutar . "." . $request->tutar2);
                }
                DB::table('odemeler')->insert([
                    'transId' => $codeAll,
                    'user' => Auth::user()->id,
                    'amount' => $tutarOrigin,
                    'channel' => $channel,
                    'description' => 'Ödeme işleniyor...',
                    'ulke' => $channel == 14 ? $request->country:'',
                    'status' => 0,
                    'created_at' => date('YmdHis')
                ]); //ödeme kaydı gerçekleştiriliyor
                /*
                 * Gpay Ödeme İsteği Gönderiliyor
                 */
                $username = getGpayUsername();
                $gpayKey = getGpayKey();

                $amount = $tutar;
                $referenceId = $codeAll;
                $orderDescription = "Oyuneks bakiye yükleme işlemi";
                $notificationUrl = getPaparaNotifyUrl();
                $failNotificationUrl = getPaparaNotifyUrl();
                $redirectUrl = getPaparaRedirectUrl();
                if (Auth::user()->tc_verified_at == NULL) {
                    $turkishNationalId = "11111111111";
                } else {
                    $turkishNationalId = Auth::user()->tcno;
                }
                if ($channel == 15) {
                    $selected_bank_id = DB::table('payment_channels_eft')->where('id', $request->banka)->first()->bankSlug;
                } else {
                    $selected_bank_id = '';
                }
                $data = array(
                    'username' => $username,
                    'key' => $gpayKey,
                    'order_id' => $codeAll,
                    'amount' => $amount,
                    'currency' => '949',
                    'selected_payment' => $request->selected_payment,
                    'selected_bank_id' => $selected_bank_id,
                    'nufus_bilgileri' => array(
                        'ad_soyad' => Auth::user()->name,
                        'tc_no' => $turkishNationalId,
                    ),
                    'products' => array(
                        array(
                            'product_name' => 'Oyuneks Bakiye Yükleme İşlemi',
                            'product_amount' => $tutar,
                            'product_currency' => '949',
                            'product_type' => 'oyun',
                            'product_img' => 'https://oyuneks.com/brand/brandlogo.png'
                        ),
                    ),
                );


                $url = 'https://gpay.com.tr/ApiRequest';
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $result = curl_exec($ch);
                curl_close($ch);
                $json_data = json_decode($result);
                if ($json_data->state > 0) {
                    LogCall(Auth::user()->id, '3', "Kullanıcı gpay ile ödeme yapma sayfasına yönlendirildi.");
                    $link = $json_data->link;
                    return redirect($link);
                    die();
                } else {
                    LogCall(Auth::user()->id, '3', "Kullanıcı gpay ödeme talebi oluşturulamadı, nedeni: " . $json_data->message . ".");
                    setBildirim(Auth::user()->id, '4', 'Ödeme Talebiniz Oluşturulamadı', 'Ödeme talebiniz oluşturulamadı.', route('bakiye_ekle'));
                    return back()->with("error", "Ödemeniz işlenirken bir sorun meydana geldi, detaylı bilgi için lütfen bizimle iletişime geçin.");
                }
            } else {
                return back()->with("error", "Ödeme tutarını boş girdiniz veya 1 TL'den düşük bir tutar girdiniz!");
            }
        }
    }

    public function havale_eft($token)
    {
        return view('front.pages.odeme.havale-eft-odeme')->with('token', $token);
    }

    public function papara_notify(Request $request)
    {
        $post = $request;
        if ($post['status'] == 1) { //işlem başarılı fakat kontrole muhtaç
            /*
             * Papara üzerinden olup olmadığını tekrar kontrol edelim
             */
            $ch = curl_init();
            $headers = array(
                'ApiKey: ' . getPaparaKey(),
                'Content-Type: application/json',
            );
            curl_setopt($ch, CURLOPT_URL, "https://merchant-api.papara.com/payments?id=" . $post['id']);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
            curl_setopt($ch, CURLOPT_POSTFIELDS, '');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            $result = @curl_exec($ch);
            curl_close($ch);
            $result = json_decode($result, 1);
            if ($result['data']['status'] == 1) { //ödeme işlemi sorgulama sonucunda başarılı ise
                if ($post['merchantSecretKey'] == getPaparaSecretKey()) { //secret key üye işyeri ile uyuşuyorsa
                    $islemId = $post['referenceId'];
                    $odeme = DB::table('odemeler')->where('transId', $islemId);
                    $odeme->update([
                        'status' => '1',
                        'description' => 'Kullanıcı ödeme işlemi başarılı',
                    ]);
                    $odemeUser = $odeme->first()->user;
                    $user = DB::table('users')->where('id', $odemeUser)->first();
                    $incomeTotal = $odeme->first()->amount;
                    $bakiye = $user->bakiye;
                    $totalUserWallet = $incomeTotal + $bakiye;
                    DB::table('users')->where('id', $odemeUser)->update([
                        'bakiye' => $totalUserWallet,
                    ]);

                    odeme_kontrol(); // Ödeme limit kontrol

#------------------------------------------------------  Cari tabloya para girisi
                    DB::table('cariy_fisler')->insert([
                        'kaynak_cari' => 0,
                        'hedef_cari' => 21,
                        'cikan_tutar' => $incomeTotal,
                        'aktarilan_tutar' => $incomeTotal,
                        'aciklama' => $user->name . ' tarafından Papara Bakiye yüklemesi UID:'.$user->id,
                        'turu' =>8,
                        'created_at' => date('YmdHis')
                    ]);
#------Cari log
                    $last_id = DB::getPdo()->lastInsertId();
                    $bak_once=DB::table('cariy_hesaplar')->where('id',21)->first()->bakiye;
                    $bak_sonra=$incomeTotal+$bak_once;
                    DB::table('cariy_log')->insert([
                        'hesap_id'=>21,
                        'fis_id' =>$last_id,
                        'onceki_bak'=> $bak_once,
                        'sonraki_bak'=>$bak_sonra,
                        'aciklama'=>'Kullanıcı Bakiye Ekleme',
                        'user'=>$odemeUser,
                        'created_at'=>date('YmdHis')
                    ]);

                    DB::select("update cariy_hesaplar set bakiye=bakiye+$incomeTotal where id=21"); // Papara hesap bakiyesi upt.
#-----------------------------------------------------


                    LogCall($user->id, '3', "Kullanıcı " . $incomeTotal . " TL tutarındaki papara ödeme talebi onaylandı.");
                    setBildirim($user->id, '4', 'Ödeme Talebiniz Onaylandı', $incomeTotal . 'TL değerindeki papara ödeme işleminiz onaylanmıştır. Keyifli alışverişler dileriz.', route('odemelerim'));
                    sendSms($user->telefon, $incomeTotal . " TL değerindeki papara ödeme işleminiz onaylanmıştır. Keyifli alışverişler dileriz.");
                }
            }
        }
    }

    public function gpay_notify(Request $request)
    {
        $post = $_POST;


        $callback_ip = ["77.223.135.234", "185.197.196.99"];
        $has_ip = false;
        $gpayKey = getGpayKey();
        $siparis_id = $post['siparis_id'];
        $tutar = $post['tutar'];
        $islem_sonucu = $post['islem_sonucu'];

        $hash = md5(base64_encode(substr($gpayKey, 0, 7) . substr($siparis_id, 0, 5) . strval($tutar) . $islem_sonucu));

        if (isset($_SERVER["HTTP_CLIENT_IP"])) {
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        } elseif (isset($_SERVER["HTTP_CF_PSEUDO_IPV4"])) {
            $ip = $_SERVER["HTTP_CF_PSEUDO_IPV4"];
        } elseif (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
            $ip = $_SERVER["HTTP_CF_CONNECTING_IP"];
        } elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } else {
            $ip = $_SERVER["REMOTE_ADDR"];
        }

        foreach ($callback_ip as $c_ip) {
            if ($ip === $c_ip) {
                $has_ip = true;
                break;
            }
        }

        if ($has_ip != true || $hash != $post['hash']) {
            die('4');
        }


        $res = DB::table('odemeler')->where('transId', $siparis_id);
        if (!$res->first()) {
            die('2');
        }
        if ($post['islem_sonucu'] == 2) { //ödeme işlemi başarılı
            $res->update([
                'status' => '1',
                'description' => 'Kullanıcı ödeme işlemi başarılı',
            ]);
            $odemeUser = $res->first()->user;
            $user = DB::table('users')->where('id', $odemeUser)->first();
            $incomeTotal = $res->first()->amount;
            $bakiye = $user->bakiye;
            $totalUserWallet = $incomeTotal + $bakiye;
            DB::table('users')->where('id', $odemeUser)->update([
                'bakiye' => $totalUserWallet,
            ]);

            odeme_kontrol(); // Ödeme limit kontrol
#------------------------------------------------------  Cari tabloya para girisi
            DB::table('cariy_fisler')->insert([
                                'kaynak_cari' => 0,
                                'hedef_cari' => 20,
                                'cikan_tutar' => $incomeTotal,
                                'aktarilan_tutar' => $incomeTotal,
                                'aciklama' => $user->name . ' tarafından Gpay Bakiye yüklemesi UID:'.$user->id,
                                'turu' =>7,
                                'created_at' => date('YmdHis')
                            ]);
            #------Cari log
            $last_id = DB::getPdo()->lastInsertId();
            $bak_once=DB::table('cariy_hesaplar')->where('id',20)->first()->bakiye;
            $bak_sonra=$incomeTotal+$bak_once;
            DB::table('cariy_log')->insert([
                'hesap_id'=>20,
                'fis_id' =>$last_id,
                'onceki_bak'=> $bak_once,
                'sonraki_bak'=>$bak_sonra,
                'aciklama'=>'Kullanıcı Bakiye Ekleme',
                'user'=>$odemeUser,
                'created_at'=>date('YmdHis')
            ]);

            DB::select("update cariy_hesaplar set bakiye=bakiye+$incomeTotal where id=20"); // Gpay hesap bakiyesi upt.
#-----------------------------------------------------

            LogCall($user->id, '3', "Kullanıcı " . $incomeTotal . " TL tutarındaki gpay ödeme talebi onaylandı.");
            setBildirim($user->id, '4', 'Ödeme Talebiniz Onaylandı', $incomeTotal . 'TL değerindeki gpay ödeme işleminiz onaylanmıştır. Keyifli alışverişler dileriz.', route('odemelerim'));
            sendSms($user->telefon, $incomeTotal . " TL değerindeki gpay ödeme işleminiz onaylanmıştır. Keyifli alışverişler dileriz.");
        }
        if ($post['islem_sonucu'] == 3 or $post['islem_sonucu'] == 4 or $post['islem_sonucu'] == 5) {
            $res->update([
                'status' => '2',
                'description' => $post['islem_mesaji'],
            ]);
            $odemeUser = $res->first()->user;
            $user = DB::table('users')->where('id', $odemeUser)->first();
            $incomeTotal = $res->first()->amount;
            LogCall($user->id, '3', "Kullanıcı " . $incomeTotal . " TL tutarındaki gpay ödeme talebi reddedildi.");
            setBildirim($user->id, '4', 'Ödeme Talebiniz Reddedildi', $incomeTotal . 'TL değerindeki gpay ödeme işleminiz reddedilmiştir.', route('odemelerim'));
            sendSms($user->telefon, $incomeTotal . " TL değerindeki gpay ödeme işleminiz reddedilmiştir.");
        }
        echo "1";
    }

    public function odeme_notify(Request $request)
    {

        //  \Log::debug(serialize(['odeme_notify',request()->all(),request()->server()]));



        $post = $_POST;

        if (substr($post['merchant_oid'], 0, 4) == 'TEST') {
            echo 'OK';
            exit;
        }


        $merchant_key = getPaytrMerchantKey();
        $merchant_salt = getPaytrMerchantSalt();

        $hash = base64_encode(hash_hmac('sha256', $post['merchant_oid'] . $merchant_salt . $post['status'] . $post['total_amount'], $merchant_key, true));
        if ($hash != $post['hash']) {
            $merchant_key2 = getPaytrMerchantKey(true);
            $merchant_salt2 = getPaytrMerchantSalt(true);
            $hash2 = base64_encode(hash_hmac('sha256', $post['merchant_oid'] . $merchant_salt2 . $post['status'] . $post['total_amount'], $merchant_key2, true));
            if ($hash2 != $post['hash'])
                die('PAYTR notification failed: bad hash');
        }
        $odeme = DB::table('odemeler')->where('transId', $post['merchant_oid'])->first();
        if (isset($odeme->status) && $odeme->status != 0) {
            echo "OK";
            exit;
        }

        if ($post['status'] == 'success') { ## Ödeme Onaylandı
            $gelen = $post['total_amount'] / 100;
            $komisyon = getCacheSetings()->onlineOdemeKomisyon;
            //$reelTutar = $gelen / ($komisyon / 100 + 1);
            //$reelTutar = number_format((float)$reelTutar, 2, '.', '');
            //$reelTutar = $gelen;
            $odeme = DB::table('odemeler')->where('transId', $post['merchant_oid']);
            $odeme->update([
                'status' => '1',
                'description' => 'Kullanıcı ödeme işlemi başarılı',
            ]);
            //'amount' => $reelTutar,
            $odemeUser = $odeme->first()->user;
            $user = DB::table('users')->where('id', $odemeUser)->first();
            $incomeTotal = $odeme->first()->amount;
            $bakiye = $user->bakiye;
            $totalUserWallet = $incomeTotal + $bakiye;
            DB::table('users')->where('id', $odemeUser)->update([
                'bakiye' => $totalUserWallet,
            ]);

            odeme_kontrol(); // Ödeme limit kontrol
            #------------------------------------------------------  Cari tabloya para girisi
            DB::table('cariy_fisler')->insert([
                'kaynak_cari' => 0,
                'hedef_cari' => 27,
                'cikan_tutar' => $incomeTotal,
                'aktarilan_tutar' => $incomeTotal,
                'aciklama' => $user->name . ' tarafından PayTr Bakiye yüklemesi UID:'.$user->id,
                'turu' =>9,
                'created_at' => date('YmdHis')
            ]);
            #------Cari log
            $last_id = DB::getPdo()->lastInsertId();
            $bak_once=DB::table('cariy_hesaplar')->where('id',27)->first()->bakiye;
            $bak_sonra=$incomeTotal+$bak_once;
            DB::table('cariy_log')->insert([
                'hesap_id'=>27,
                'fis_id' =>$last_id,
                'onceki_bak'=> $bak_once,
                'sonraki_bak'=>$bak_sonra,
                'aciklama'=>'Kullanıcı Bakiye Ekleme',
                'user'=>$odemeUser,
                'created_at'=>date('YmdHis')
            ]);

            DB::select("update cariy_hesaplar set bakiye=bakiye+$incomeTotal where id=27"); // Papara hesap bakiyesi upt.
#-----------------------------------------------------


            LogCall($user->id, '3', "Kullanıcı " . $incomeTotal . " TL tutarındaki ödeme talebi onaylandı.");
            setBildirim($user->id, '4', 'Ödeme Talebiniz Onaylandı', $incomeTotal . 'TL değerindeki ödeme işleminiz onaylanmıştır. Keyifli alışverişler dileriz.', route('odemelerim'));
            sendSms($user->telefon, $incomeTotal . " TL değerindeki ödeme işleminiz onaylanmıştır. Keyifli alışverişler dileriz.");
        } else { ## Ödemeye Onay Verilmedi
            $odeme = DB::table('odemeler')->where('transId', $post['merchant_oid']);
            $odeme->update([
                'status' => '2',
                'description' => $post['failed_reason_code'] . " - Açıklama : " . $post['failed_reason_msg'],
            ]);
            $odemeUser = $odeme->first()->user;
            $user = DB::table('users')->where('id', $odemeUser)->first();
            LogCall($user->id, '3', "Kullanıcı ödeme talebi reddedildi.");
            setBildirim($user->id, '4', 'Ödeme Talebiniz Reddedildi', 'Ödeme işleminiz reddedilmiştir. Detaylar için bizimle iletişime geçebilirsiniz.', route('odemelerim'));
            sendSms($user->telefon, "Ödeme işleminiz reddedilmiştir. Detaylar için bizimle iletişime geçebilirsiniz.");
        }
        echo "OK";
        exit;
    }

    public function siparis_durum($durum)
    {
        // \Log::debug(serialize([request()->all(),request()->server()]));

        if ($durum == 'basarili' or $durum == 'basarisiz') {
            if ($durum == 'basarili') {
                return redirect()->route('odemelerim')->with('success', 'Ödeme işleminiz başarıyla kaydedilmiştir, yaptığınız işlem başarıyla tamamlandıysa kısa süre içerisinde tutar bakiyenize aktarılacaktır. Teşekkür eder, keyifli alışverişler dileriz.');
            } else {


                if (request()->has('fail_message')) {
                    return redirect()->route('odemelerim')->with('error', request()->input('fail_message'));
                }

                return redirect()->route('odemelerim')->with('error', 'Ödeme işleminiz sırasında bir hata meydana gelmiştir.');
            }
            $post = $_POST;
            $hash = hashReturnHesapla($post['TURKPOS_RETVAL_Dekont_ID'], $post['TURKPOS_RETVAL_Tahsilat_Tutari'], $post['TURKPOS_RETVAL_Siparis_ID'], $post['TURKPOS_RETVAL_Islem_ID']);
            if ($post['TURKPOS_RETVAL_Hash'] == $hash) {
                if ($post['TURKPOS_RETVAL_Sonuc'] > 0 and $post['TURKPOS_RETVAL_Dekont_ID'] > 0) { //işlem başarılıdır
                    $odeme = DB::table('odemeler')->where('transId', $post['TURKPOS_RETVAL_Siparis_ID']);
                    $odeme->update([
                        'amount' => $post['TURKPOS_RETVAL_Odeme_Tutari'],
                        'status' => '1',
                        'description' => 'Kullanıcı ödeme işlemi başarılı',
                    ]);
                    $odemeUser = $odeme->first()->user;
                    $user = DB::table('users')->where('id', $odemeUser)->first();
                    $incomeTotal = str_replace(",", ".", $post['TURKPOS_RETVAL_Odeme_Tutari']);
                    $bakiye = $user->bakiye;
                    $totalUserWallet = $incomeTotal + $bakiye;
                    DB::table('users')->where('id', $odemeUser)->update([
                        'bakiye' => $totalUserWallet,
                    ]);
                    odeme_kontrol(); // Ödeme limit kontrol
                    LogCall($user->id, '3', "Kullanıcı " . $incomeTotal . " TL tutarındaki ödeme talebi onaylandı.");
                    return redirect()->route('odemelerim')->with('success', 'Ödeme işleminiz başarıyla tamamlanmıştır. İyi alışverişler.');
                } else {  //işlem başarısız
                    $odeme = DB::table('odemeler')->where('transId', $post['TURKPOS_RETVAL_Siparis_ID']);
                    $odemeUser = $odeme->first()->user;
                    $odeme->update([
                        'status' => '2',
                        'description' => 'Ödeme işlemi hatalı : ' . $post['TURKPOS_RETVAL_Sonuc_Str'],
                    ]);
                    $user = DB::table('users')->where('id', $odemeUser)->first();
                    LogCall($user->id, '3', "Kullanıcı ödeme talebi reddedildi. Hata nedeni : " . $post['TURKPOS_RETVAL_Sonuc_Str']);
                    return redirect()->route('odemelerim')->with('error', 'Ödeme işleminiz sırasında bir hata meydana geldi. Hata ile ilgili destek alabilirsiniz.');
                }
            } else {
                LogCall($user->id, '3', "Ödeme altyapısın bir saldırı denemesi oldu.");
                return redirect()->route('odemelerim')->with('error', 'Ödeme işleminde güvenlik ihlali meydana geldi.');
            }
        } else {
            echo "<meta http-equiv='refresh' content='2;url=" . route('homepage') . "' />";
            die(__('general.yonlendiriliyorsunuz'));
        }
    }

    public function epin_notify()
    {
        $post = json_decode($_POST);
        \Log::debug(serialize($post));
        if ($post->Source == "SaveOrder") {
            $sipId = $post->TransactionId;
            if ($post->ResultCode == 100) {
                DB::table('epin_satis')->where('transId', $sipId)->update(['status', '1']);
                $id = DB::table('epin_satis')->where('transId', $sipId)->first();
                foreach ($post->PinCode as $item) {
                    DB::table('epin_satis_kodlar')->insert([
                        'epin_satis' => $id->id,
                        'code' => $item,
                        'created_at' => date('YmdHis'),
                    ]);
                }
            } else {
                DB::table('epin_satis')->where('transId', $sipId)->update(['status', '2']);
            }
        }
        exit();
    }

    public function bakiye_cek()
    {
        if (getUserVerifiyStep() >= 3) {
            return view('front.pages.bakiye-cek');
        } else {
            LogCall(Auth::user()->id, '1', "Kullanıcı bakiye çekme sayfasına girmek istedi fakat telefon numarası ve/veya kimliği onaylı değildi.");
            return redirect()->route('hesap_onayla')->with('error', 'Bakiye çekebilmek için lütfen telefonunuzu ve kimliğinizi onaylayın.');
        }
    }

    public function bakiye_cek_post(Request $request)
    {
        //if (Auth::user()->bakiye_cekilebilir > Auth::user()->bakiye) { //çekilebilir 50 lira, bakiye 40 lira, çekmek istediği 20 lira

        if (bloke_kontrol(Auth::user()->id)) {
            return back()->with("error", "Bakiyeniz blokeli, Canlı Destek ile irtibata geçin.");
            return false;
        }

// günlük para çekim limit kontrol
            $id=Auth::user()->id;
            $tar=date("Y-m-d");
            $toplam=DB::select("
            select sum(amount) btop,(select sum(amount)
            from para_cek where user='$id' and status in ('1','0') and text LIKE 'PAPARA%' and created_at like '$tar%') ptop
            from para_cek where user='$id' and status in ('1','0') and (text IS NULL OR text NOT LIKE 'PAPARA%') and created_at like '$tar%'
            ");

            if(($toplam[0]->btop>getCacheSetings()->banka_max  && getCacheSetings()->banka_max>0)  || ($request->amount+$toplam[0]->btop)>getCacheSetings()->banka_max)  {return back()->with("error","Girilen tutar günlük çekim limitinizi aşıyor.");}
            if(($toplam[0]->ptop>getCacheSetings()->papara_max && getCacheSetings()->papara_max>0) || ($request->amount+$toplam[0]->ptop)>getCacheSetings()->banka_max) {return back()->with("error", "Girilen tutar günlük çekim limitinizi aşıyor.");}
// günlük para çekim limit kontrol papara



        if ($request->amount <= Auth::user()->bakiye_cekilebilir) {
            $bakiye_dusur = DB::table('users')->where('id', Auth::user()->id)->update([
                'bakiye_cekilebilir' => Auth::user()->bakiye_cekilebilir - $request->amount
            ]);
            LogCall(Auth::user()->id, '3', "Kullanıcı " . $request->amount . " TL tutarındaki bakiye çekme talebi kaydedildi. Kul.Çek.Bak: " . Auth::user()->bakiye_cekilebilir);
        } else {
            LogCall(Auth::user()->id, '3', "Kullanıcı " . $request->amount . " TL tutarındaki bakiye çekme talebi reddedildi.");
            return back()->with("error", "Ödeme talebiniz alınırken bir sorun oluştu. Lütfen canlı destek ile iletişime geçin.");
        }
        /*} else {
            if ($request->amount <= Auth::user()->bakiye_cekilebilir) {
                $bakiye_dusur = DB::table('users')->where('id', Auth::user()->id)->update([
                    'bakiye_cekilebilir' => Auth::user()->bakiye_cekilebilir - $request->amount,
                    'bakiye' => Auth::user()->bakiye - $request->amount,
                ]);
                LogCall(Auth::user()->id, '3', "Kullanıcı " . $request->amount . " TL tutarındaki bakiye çekme talebi kaydedildi.");
            } else {
                LogCall(Auth::user()->id, '3', "Kullanıcı " . $request->amount . " TL tutarındaki bakiye çekme talebi reddedildi.");
                return back()->with("error", "Ödeme talebiniz alınırken bir sorun oluştu. Lütfen canlı destek ile iletişime geçin.");
            }
        }*/
        $papara = 0;

        if ($bakiye_dusur) {
            $papara = 5;
            if ($request->odeme_kanali == "PAPARA") { // papara çekim talebi varsa önce hesap ekle
                $papara = 1;
                if (DB::table('odeme_kanallari')->where('user', Auth::user()->id)->where('title', 'PAPARA')->whereNull('deleted_at')->count() < 1) {  // PAPARA hesabı var mı kontrol yoksa ekle
                    DB::table('odeme_kanallari')->insert([
                        'user' => Auth::user()->id,
                        'title' => 'PAPARA',
                        'alici' => $request->isim,
                        'iban' => $request->papara,
                        'text' => '',
                        'created_at' => date('YmdHis'),
                    ]);
                }

                $hesap = DB::table('odeme_kanallari')->where('user', Auth::user()->id)->where('title', 'PAPARA')->whereNull('deleted_at')->first();
                $request->odeme_kanali = $hesap->id;
                $request->text = 'PAPARA (' . $hesap->iban . ')';
            }

            DB::table('para_cek')->insert([
                'user' => Auth::user()->id,
                'amount' => $request->amount,
                'kesinti' => $papara == 1 ? 1.5 : (Auth::user()->para_cek_kom == 1 ? DB::table('settings')->first()->yayin_komisyon : 0),
                'odeme_kanali' => $request->odeme_kanali,
                'text' => $request->text,
                'status' => 0,
                /* 'updated_at' => date('YmdHis'), */
                'created_at' => date('YmdHis'),
            ]);
            setBildirim(Auth::user()->id, '4', 'Ödeme Talebiniz Alınmıştır', 'Ödeme talebiniz başarıyla alınmıştır, ödemeniz onaylandıktan sonra gerçekleştirilecektir', route('bakiye_cek'));
            return back()->with("success", "Ödeme talebiniz başarıyla alınmıştır. Ödemeniz onaylandıktan sonra gerçekleştirilecektir.");
        } else {
            return back()->with("error", "Ödeme talebiniz kaydedilirken bir sorun oluştu. Lütfen canlı destek ile iletişime geçin.");
        }
    }

    public function bakiye_cek_odeme_kanali(Request $request)
    {
        DB::table('odeme_kanallari')->insert([
            'user' => Auth::user()->id,
            'title' => $request->title,
            'alici' => $request->alici,
            'iban' => $request->iban,
            //   'text' => $request->text,
            'created_at' => date('YmdHis'),
        ]);
        LogCall(Auth::user()->id, '3', "Kullanıcı " . $request->title . " isimli yeni bir ödeme kanalı ekledi.");
        return back()->with("success", "Ödeme kanalı başarıyla kaydedilmiştir.");
    }

    public function bakiye_cek_odeme_kanali_duzenle(Request $request)
    {
        $request->title = isset($request->title) ? $request->title : 'PAPARA';
        DB::table('odeme_kanallari')->where('id', $request->id)->update([
            'title' => $request->title,
            'alici' => $request->alici,
            'iban' => $request->iban,
            //   'text' => $request->text,
        ]);
        LogCall(Auth::user()->id, '3', "Kullanıcı " . $request->title . " isimli ödeme kanalını düzenledi.");
        return back()->with("success", "Ödeme kanalı başarıyla düzenlendi.");
    }
}
