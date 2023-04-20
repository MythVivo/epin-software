<?php
ob_start();

use App\Http\Controllers\AdminController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\HaberController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LangController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\OyunController;
use App\Http\Controllers\MuveController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SayfaController;
use App\Http\Controllers\SiparisController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\SssController;
use App\Http\Controllers\SssKategoriController;
use App\Http\Controllers\UyeController;
use App\Http\Controllers\YorumController;
use App\Http\Controllers\CariController;
use App\Integrations\Payment\Ozan;
use App\Integrations\Product\ContentCard;
use App\Integrations\Finance\BulutOdeme;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Helpers\Datatable;
use App\Http\Controllers\CampaignController;
use App\Classes\Cari\CariRouter;
use App\Integrations\Finance\Papara;

//Route::get('/testodeme', [TestController::class, 'form']);

/* Route::get('/caritest', [TestController::class, 'cari']); */

Route::get('/finance/papara', [Papara::class, 'test']);
Route::get('/finance/papara/list', [Papara::class, 'list']);
Route::get('/finance/papara/send', [Papara::class, 'send']);

Route::get('/bulut', [BulutOdeme::class, 'test']);
Route::get('/bulut/list', [BulutOdeme::class, 'list']);
Route::post('/bulut/update', [BulutOdeme::class, 'update']);
Route::get('/carixxx', [CariRouter::class, 'list']);
Route::get('/carixxx/{route}', [CariRouter::class, 'router']);
Route::get('/carixxx/{route}/{subroute}', [CariRouter::class, 'form']);
Route::post('/carixxx/{route}/{subroute}', [CariRouter::class, 'router']);

Route::get('/sitemap.xml', [MainController::class, 'sitemap'])->name('sitemap');


Route::get('/errors-403', [MainController::class, 'errors_403'])->name('errors_403');
Route::get('/errors-404', [MainController::class, 'errors_404'])->name('errors_404');
Route::get('/errors-100', [MainController::class, 'errors_100'])->name('errors_100');


Route::get('/', [MainController::class, 'index'])->name('homepage');
Route::get('/swx', [MainController::class, 'swx'])->name('homepage2');
Route::post('/priceguard', [MainController::class, 'priceguard'])->name('priceguard');

//Route::get('/cc/test', [ContentCard::class, 'test'])->name('cc_test');


//Route::get('/campaigns', [CampaignController::class, 'test'])->name('campaign_test');



Route::get('/search/{term}', [MainController::class, 'search'])->name('search');

Route::group(['middleware' => 'guard'], function () {
    Route::get('/guard', function (Request $request) {
        echo "GGG";
    })->name('guard');
    Route::post('/guard', function (Request $request) {
        echo "GGG";
    })->name('guard');
});
/*Route::get('/test', function () {
    return view('front.test');
});*/

Route::post('/tsend', [MainController::class, 'tsend'])->name('tsend');
Route::get('/haberler', function(){
    return Redirect::to('/blog', 301);
})->name('haber');
Route::get('/blog', [MainController::class, 'haberler'])->name('blog');
Route::get('/haberler/{haber}', [MainController::class, 'haber_detay'])->name('haber_detay');
Route::get('/yorumlar', [MainController::class, 'yorumlar'])->name('yorumlarTum');
Route::get('/sayfa/{sayfa}', [MainController::class, 'sayfa'])->name('sayfa');
Route::get('/sikca-sorulan-sorular', [MainController::class, 'sssSayfasi'])->name('sssSayfasi');
Route::get('/marka-yonergeleri', [MainController::class, 'marka_yonergeleri'])->name('marka_yonergeleri');

/*
 * Login - Register İşlemleri
 */
Route::get('/ykp_login/{id}', [LoginController::class, 'ykp_login'])->name('ykp_login');
Route::get('/giris', [LoginController::class, 'login'])->name('giris')->middleware('guest');
Route::post('/giris', [LoginController::class, 'login_post'])->name('login_post');
Route::get('/2-adimli-dogrulama/{key}/{yeniden?}', [LoginController::class, 'fa_auth'])->name('fa_auth');
Route::get('/2-adimli-dogrulama-google/{key}', [LoginController::class, 'fa_auth_google'])->name('fa_auth_google');
Route::get('/2-adimli-dogrulama-sms/{key}/{yeniden?}', [LoginController::class, 'fa_auth_sms'])->name('fa_auth_sms');
Route::post('/2-adimli-dogrulama/{key}', [LoginController::class, 'fa_auth_post'])->name('fa_auth_post');
Route::post('/2-adimli-dogrulama-google/{key}', [LoginController::class, 'fa_auth_google_post'])->name('fa_auth_google_post');
Route::post('/2-adimli-dogrulama-sms/{key}', [LoginController::class, 'fa_auth_sms_post'])->name('fa_auth_sms_post');

Route::get('/kayit', [RegisterController::class, 'register'])->name('kayit')->middleware('guest');
Route::post('/kayit', [RegisterController::class, 'register_post'])->name('kayit_post');

Route::get('/kayit_asoft', [RegisterController::class, 'register_asoft'])->name('kayit_asoft');
Route::post('/kayit_asoft', [RegisterController::class, 'register_post'])->name('kayit_asoft_post');

Route::get('/cikis', [LoginController::class, 'logout'])->name('cikis');
Route::get('/hesap-onayla/{email}/{key}', [RegisterController::class, 'active'])->name('active');
Route::get('/sifremi-unuttum', [LoginController::class, 'sifremi_unuttum'])->name('sifremi_unuttum');
Route::post('/sifremi-unuttum', [LoginController::class, 'sifremi_unuttum_post'])->name('sifremi_unuttum_post');
Route::get('/sifre-yenile/{email}/{key}', [LoginController::class, 'sifre_yenile'])->name('reset_password');
Route::post('/sifre-yenile/{email}/{key}', [LoginController::class, 'sifre_yenile_post'])->name('reset_password_post');
Route::get('/google-kayit-ol', [RegisterController::class, 'google_kayit_ol'])->name('google_kayit_ol')->middleware('guest');
Route::get('/google-kayit-ol-donus', [RegisterController::class, 'google_kayit_ol_donus'])->name('google_kayit_ol_donus');
Route::get('/twitch-kayit-ol', [RegisterController::class, 'twitch_kayit_ol'])->name('twitch_kayit_ol')->middleware('guest');
Route::get('/twitch-kayit-ol-donus', [RegisterController::class, 'twitch_kayit_ol_donus'])->name('twitch_kayit_ol_donus');
Route::get('/steam-kayit-ol', [RegisterController::class, 'steam_kayit_ol'])->name('steam_kayit_ol')->middleware('guest');
Route::get('/steam-kayit-ol-donus', [RegisterController::class, 'steam_kayit_ol_donus'])->name('steam_kayit_ol_donus');


/*
 * Oyunlar
 */
Route::get('/oyun/{oyun}', [MainController::class, 'oyunlar_baslik'])->name('oyun_baslik');
Route::get('/oyun/{oyun}/{baslik}', [MainController::class, 'baslik_detay'])->name('baslik_detay');

Route::get('/urun-onizle', [MainController::class, 'urun_onizle'])->name('urun_onizle');
Route::post('/urun-onizle', [MainController::class, 'urun_onizle_post'])->name('urun_onizle_post');

Route::post('/siparis-ver', [SiparisController::class, 'siparis_ver'])->name('siparis_ver');
Route::post('/api/odeme-al', [SiparisController::class, 'odeme_notify'])->name('api_odeme_al');
Route::post('/api/epinNotify', [SiparisController::class, 'epin_notify'])->name('epin_notify');
Route::get('/apiO/item-getir/{item}', [MainController::class, 'item_getir'])->name('item_getir');
Route::get('/apiO/item-buy-getir/{item}', [MainController::class, 'item_buy_getir'])->name('item_buy_getir');
Route::any('/siparis/{durum}', [SiparisController::class, 'siparis_durum'])->name('siparis_durum');
Route::post('/papara-notify', [SiparisController::class, 'papara_notify'])->name('papara_notify');
Route::post('/gpay-notify', [SiparisController::class, 'gpay_notify'])->name('gpay_notify');


Route::get('/item', [MainController::class, 'item'])->name('itemTum');
Route::get('/item/{item}', [MainController::class, 'item_detay'])->name('item_detay');
Route::get('/item-canli-akis/{item}', [MainController::class, 'item_canli_akis'])->name('item_canli_akis');
Route::get('/item-detay/{item}/{sunucu}/{ilan}', [MainController::class, 'item_ic_detay'])->name('item_ic_detay');
Route::get('/item-detay-satin-al/{item}/{sunucu}/{ilan}/satin-al', [MainController::class, 'item_ic_detay_satin_al'])->name('item_ic_detay_satin_al');

Route::get('/item-buy', [MainController::class, 'item_buy'])->name('item_buy');
Route::get('/item-buy/{item}', [MainController::class, 'item_buy_detay'])->name('item_buy_detay');
Route::get('/item-detay-buy/{item}/{sunucu}/{ilan}', [MainController::class, 'item_buy_ic_detay'])->name('item_buy_ic_detay');
Route::get('/item-detay-buy-satin-al/{item}/{sunucu}/{ilan}/satin-al', [MainController::class, 'item_buy_ic_detay_satin_al'])->name('item_buy_ic_detay_satin_al');

Route::get('/pazar-yeri', [MainController::class, 'item'])->name('itemTum');

Route::get('/satici/{satici}', [MainController::class, 'satici'])->name('satici');
Route::post('/satici-yorum-yap', [HomeController::class, 'satici_yorum_yap'])->name('satici_yorum_yap')->middleware('auth');


Route::get('/game-gold', [MainController::class, 'game_gold'])->name('game_gold');
Route::get('/game-gold/{gold}', [MainController::class, 'game_gold_detay'])->name('game_gold_detay');
Route::get('/test/{gold}', [TestController::class, 'game_gold_detay'])->name('game_gold_test');
Route::get('/game-gold/{gold}/{paket}', [MainController::class, 'game_gold_detay_paket'])->name('game_gold_detay_paket');
Route::get('/game-gold/{gold}/{paket}/{durum}/satin-al', [MainController::class, 'game_gold_detay_paket_satin_al'])->name('game_gold_detay_paket_satin_al');

Route::get('/e-pin', [MainController::class, 'oyunlar'])->name('oyunlarTum');
//Route::get('/e-pin-detay/{epin}', [MainController::class, 'epin_detay'])->name('epin_detay');
Route::get('/e-pin-detay/{epin}', [MainController::class, 'swx_301'])->name('epin_301');
Route::get('/e-pin-detay/{epin}/{paket}', [MainController::class, 'swx_301'])->name('epin_301_p');
Route::get('/e-pin-detay/{epin}/{paket}/{satinal}', [MainController::class, 'swx_301'])->name('epin_301_s');

Route::get('/e-pin/{epin}', [MainController::class, 'swx_epin_detay'])->name('epin_detay');
Route::get('/e-pin/{epin}/{paket}', [MainController::class, 'bgr_epin_detay_paket'])->name('epin_detay_paket');

Route::get('/e-pin/{epin}/{paket}/satin-al', [MainController::class, 'epin_detay_paket_satin_al'])->name('epin_detay_paket_satin_al');

//Route::get('/swx-e-pin-detay/{epin}', [MainController::class, 'swx_epin_detay'])->name('swx_epin_detay');

Route::get('/tum-oyunlar', [MainController::class, 'tum_oyunlar'])->name('oyunlarHepsi');

Route::get('/twitch-support/yayinci/{yayinci}', [MainController::class, 'twitch_support_yayinci'])->name('twitch_support_yayinci');
Route::get('/twitch-support', [MainController::class, 'twitch_support'])->name('twitch_support');

Route::get('/cd-key', [MainController::class, 'cd_key'])->name('cd_key');
Route::get('/cd-key-detay/{cdkey}', [MainController::class, 'cd_key_detay'])->name('cd_key_detay');
Route::get('/cd-key-detay/{cdkey}/satin-al', [MainController::class, 'cd_key_detay_satin_al'])->name('cd_key_detay_satin_al');

/*
 * Lang
 */
Route::get('/lang/{lang}', [LangController::class, 'lang'])->name('lang');


Auth::routes();

Route::get('/twitch-streamlabs-notify', [HomeController::class, 'stream_notify'])->name('stream_notify')->middleware('auth');
Route::get('/twitch-support/yayinci-ol', [HomeController::class, 'twitch_support_yayinci_ol'])->name('twitch_support_yayinci_ol')->middleware('auth');
Route::post('/twitch-support/yayinci-ol', [HomeController::class, 'twitch_support_yayinci_ol_post'])->name('twitch_support_yayinci_ol_post')->middleware('auth');
Route::post('/twitch-support/yayinci/support', [HomeController::class, 'twitch_support_yayinci_support'])->name('twitch_support_yayinci_support')->middleware('auth');

Route::post('/ilan-yorum-yap', [HomeController::class, 'ilan_yorum_yap'])->name('ilan_yorum_yap')->middleware('auth');
Route::post('/ilan-satin-al', [HomeController::class, 'ilan_satin_al'])->name('ilan_satin_al')->middleware('auth');
Route::post('/ilan-buy-satin-al', [HomeController::class, 'ilan_buy_satin_al'])->name('ilan_buy_satin_al')->middleware('auth');
Route::post('/game-gold-satin-al', [HomeController::class, 'game_gold_satin_al'])->name('game_gold_satin_al')->middleware('auth');
Route::post('/epin-satin-al', [HomeController::class, 'epin_satin_al'])->name('epin_satin_al')->middleware('auth');

Route::get('/hesabim', [HomeController::class, 'hesabim'])->name('hesabim')->middleware('auth');
Route::get('/hesap-onayla', [HomeController::class, 'hesap_onayla'])->name('hesap_onayla')->middleware('auth');
Route::post('/email-onayla', [HomeController::class, 'email_onayla'])->name('email_onayla')->middleware('auth');
Route::post('/telefon-onayla', [HomeController::class, 'telefon_onayla'])->name('telefon_onayla')->middleware('auth');
Route::post('/telefon-onayla-kod', [HomeController::class, 'telefon_onayla_kod'])->name('telefon_onayla_kod')->middleware('auth');
Route::post('/kimlik-onayla', [HomeController::class, 'kimlik_onayla'])->name('kimlik_onayla')->middleware('auth');

Route::get('/favorilerim', [HomeController::class, 'favorilerim'])->name('favorilerim')->middleware('auth');

Route::get('/ykp', [HomeController::class, 'siparislerim_'])->name('siparislerim_')->middleware('auth');
Route::get('/siparislerim', [HomeController::class, 'siparislerim'])->name('siparislerim')->middleware('auth');
Route::get('/siparislerim-ilan', [HomeController::class, 'siparislerim_ilan'])->name('siparislerim_ilan')->middleware('auth');
Route::get('/siparislerim-game-gold', [HomeController::class, 'siparislerim_game_gold'])->name('siparislerim_game_gold')->middleware('auth');
Route::get('/siparislerim-cd-key', [HomeController::class, 'siparislerim_cdkey'])->name('siparislerim_cdkey')->middleware('auth');

Route::get('/odemelerim', [HomeController::class, 'odemelerim'])->name('odemelerim')->middleware('auth');
Route::get('/fatura-adreslerim', [HomeController::class, 'fatura_adreslerim'])->name('fatura_adreslerim')->middleware('auth');
Route::post('/fatura-adreslerim', [HomeController::class, 'fatura_adreslerim_post'])->name('fatura_adreslerim_post')->middleware('auth');
Route::get('/bildirimlerim', [HomeController::class, 'bildirimlerim'])->name('bildirimlerim')->middleware('auth');
Route::post('/bildirimlerim-post', [HomeController::class, 'bildirimlerim_post'])->name('bildirimlerim_post')->middleware('auth');
Route::get('/ayarlarim', [HomeController::class, 'ayarlarim'])->name('ayarlarim')->middleware('auth');
Route::post('/ayarlarim-post', [HomeController::class, 'ayarlarim_post'])->name('ayarlarim_post')->middleware('auth');
Route::get('/avatar-sec/{avatar}/{name}', [HomeController::class, 'avatar_sec'])->name('avatar_sec')->middleware('auth');
Route::get('/hizli-menu', [HomeController::class, 'hizli_menu'])->name('hizli_menu')->middleware('auth');
Route::post('/hizli-menu-post', [HomeController::class, 'hizli_menu_post'])->name('hizli_menu_post')->middleware('auth');
Route::get('/yayin-bagislarim', [HomeController::class, 'yayin_bagislarim'])->name('yayin_bagislarim')->middleware('auth');

Route::get('/satici-panelim', [HomeController::class, 'satici_panelim'])->name('satici_panelim')->middleware('auth');
Route::post('/satici-panelim-post', [HomeController::class, 'satici_panelim_post'])->name('satici_panelim_post')->middleware('auth');
Route::post('/satici-panelim-birlestir', [HomeController::class, 'satici_panelim_birlestir_post'])->name('satici_panelim_birlestir_post')->middleware('auth');
Route::get('/yeni-satis', [HomeController::class, 'yeni_satis'])->name('yeni_satis')->middleware('auth');

Route::get('/alici-panelim', [HomeController::class, 'alici_panelim'])->name('alici_panelim')->middleware('auth');
Route::get('/yeni-satis-buy', [HomeController::class, 'yeni_satis_buy'])->name('yeni_satis_buy')->middleware('auth');
Route::post('/alici-panelim-post', [HomeController::class, 'alici_panelim_post'])->name('alici_panelim_post')->middleware('auth');
Route::post('/yeni-satis-sozlesme-post', [HomeController::class, 'yeni_satis_sozlesme_post'])->name('yeni_satis_sozlesme_post')->middleware('auth');
Route::post('/alis-duzenle-post', [HomeController::class, 'alis_duzenle_post'])->name('alis_duzenle_post')->middleware('auth');

Route::get('/satis-duzenle/{ilan}', [HomeController::class, 'satis_duzenle'])->name('satis_duzenle')->middleware('auth');
Route::post('/satis-duzenle-post', [HomeController::class, 'satis_duzenle_post'])->name('satis_duzenle_post')->middleware('auth');
Route::get('/twitch-support/yayinci-panelim', [HomeController::class, 'twitch_support_yayinci_panelim'])->name('twitch_support_yayinci_panelim')->middleware('auth');
Route::get('/twitch-support/yayinci-ayarlarim', [HomeController::class, 'twitch_support_yayinci_ayarlarim'])->name('twitch_support_yayinci_ayarlarim')->middleware('auth');
Route::post('/twitch-support/yayinci-ayarlarim-post', [HomeController::class, 'twitch_support_yayinci_ayarlarim_post'])->name('twitch_support_yayinci_ayarlarim_post')->middleware('auth');;
Route::get('/twitch-support/yayinci-bakiye-cevir', [HomeController::class, 'twitch_support_yayinci_bakiye_cevir'])->name('twitch_support_yayinci_bakiye_cevir')->middleware('auth');
Route::post('/twitch-support/yayinci-bakiye-cevir-post', [HomeController::class, 'twitch_support_yayinci_bakiye_cevir_post'])->name('twitch_support_yayinci_bakiye_cevir_post')->middleware('auth');
Route::get('/twitch-support/yayinci-kesintisiz', [HomeController::class, 'twitch_support_yayinci_kesintisiz'])->name('twitch_support_yayinci_kesintisiz')->middleware('auth');
Route::post('/twitch-support/yayinci-kesintisiz-post', [HomeController::class, 'twitch_support_yayinci_kesintisiz_post'])->name('twitch_support_yayinci_kesintisiz_post')->middleware('auth');


Route::get('/bakiye-ekle', [SiparisController::class, 'bakiye_ekle'])->name('bakiye_ekle')->middleware('auth');
Route::get('/bakiye-cek', [SiparisController::class, 'bakiye_cek'])->name('bakiye_cek')->middleware('auth');
Route::post('/bakiye-cek-odeme-kanali', [SiparisController::class, 'bakiye_cek_odeme_kanali'])->name('bakiye_cek_odeme_kanali')->middleware('auth');
Route::post('/bakiye-cek-odeme-kanali-duzenlle', [SiparisController::class, 'bakiye_cek_odeme_kanali_duzenle'])->name('bakiye_cek_odeme_kanali_duzenle')->middleware('auth');
Route::post('/bakiye-cek', [SiparisController::class, 'bakiye_cek_post'])->name('bakiye_cek_post')->middleware('auth');
Route::get('/havale-eft/{token}', [SiparisController::class, 'havale_eft'])->name('havale_eft')->middleware('auth');


Route::post('/odeme-yap', [SiparisController::class, 'odeme_yap'])->name('odeme_yap')->middleware('auth');
Route::get('/bildirim-oku', [HomeController::class, 'bildirim_oku'])->name('bildirim_oku');
Route::get('/favorilere-ekle/{type}/{id}', [HomeController::class, 'favori_ekle'])->name('favori_ekle');
Route::get('/favorilere-kaldir/{type}/{id}', [HomeController::class, 'favori_kaldir'])->name('favori_kaldir');



// Ozan Entegrasyon
Route::get('/ozan/send/{amount}', [Ozan::class, 'send'])->name('ozan_send')->middleware('auth');
Route::get('/ozan/return', [Ozan::class, 'return'])->name('ozan_return');
Route::post('/ozan/return', [Ozan::class, 'return'])->name('ozan_return_post');
// Ozan Entegrasyon

Route::post('/yorum_yap', [HomeController::class, 'yorum_yap'])->name('yorum_yap')->middleware('auth');

Route::post('/cd-key-detay/satin-al', [HomeController::class, 'cd_key_detay_satin_al_post'])->name('cd_key_detay_satin_al_post');

Route::group(['middleware' => 'admin'], function () {
    Route::prefix('panel')->middleware('auth')->group(function () {

        Route::get('/integrations/api', [ContentCard::class, 'router'])->name('integrations_api_router');

        Route::get('/', [AdminController::class, 'index'])->name('panel');
        Route::get('/datatable', [Datatable::class, 'get'])->name('datatable');
        Route::get('/datatable/ajax', [Datatable::class, 'ajax'])->name('datatable_ajax');
        /*
         * Seo paneli
         */
        Route::get('/seo-yonetim', [AdminController::class, 'seo_yonetim'])->name('seo_yonetim');
        Route::get('/seo-yonetim-view', [AdminController::class, 'seo_yonetim_view'])->name('seo_yonetim_view');
        Route::post('/seo-yonetim-meta-save', [AdminController::class, 'seo_yonetim_meta_save'])->name('seo_yonetim_meta_save');
        Route::post('/seo-yonetim-oyun-meta-save', [AdminController::class, 'seo_yonetim_oyun_meta_save'])->name('seo_yonetim_oyun_meta_save');
        Route::post('/seo-yonetim-media-save', [AdminController::class, 'seo_yonetim_media_save'])->name('seo_yonetim_media_save');
        Route::post('/seo-yonetim-oyun-alt-meta-save', [AdminController::class, 'seo_yonetim_oyun_alt_meta_save'])->name('seo_yonetim_oyun_alt_meta_save');
        Route::post('/seo-yonetim-muve-meta-save', [AdminController::class, 'seo_yonetim_muve_meta_save'])->name('seo_yonetim_muve_meta_save');

        Route::get('odeme_onay', [AdminController::class, 'odeme_onay'])->name('odeme_onay');
        Route::get('cari',  [CariController::class, 'index'])->name('cari');
        Route::get('grup',  [CariController::class, 'grup'])->name('grup');
        Route::get('tur',  [CariController::class, 'tur'])->name('tur');
        Route::get('hesap', [CariController::class, 'hesap'])->name('hesap');
        Route::get('kur',   [CariController::class, 'kur'])->name('kur');
        Route::get('fisler', [CariController::class, 'fisler'])->name('fisler');
        Route::get('rapor', [CariController::class, 'rapor'])->name('rapor');
        Route::post('cari_api', [CariController::class, 'cari_api'])->name('cari_api');


        Route::get('slider', [SliderController::class, 'index'])->name('slider');
        Route::post('sliderEkle', [SliderController::class, 'add'])->name('slider_add');
        Route::post('sliderMiniEkle', [SliderController::class, 'mini_add'])->name('slider_mini_add');
        Route::post('sliderDuzenle', [SliderController::class, 'edit'])->name('slider_edit');
        Route::get('sliderTable', [SliderController::class, 'table'])->name('slider_table');

        Route::get('haberler', [HaberController::class, 'index'])->name('haberler');
        Route::post('haberEkle', [HaberController::class, 'add'])->name('haber_add');
        Route::post('haberDuzenle', [HaberController::class, 'edit'])->name('haber_edit');
        Route::get('haberTable', [HaberController::class, 'table'])->name('haber_table');

        Route::get('avatarlar', [AdminController::class, 'avatarlar'])->name('avatarlar');
        Route::post('avatarEkle', [AdminController::class, 'avatar_add'])->name('avatar_add');
        Route::get('avatarlar-kategori', [AdminController::class, 'avatarlar_kategori'])->name('avatarlar_kategori');
        Route::post('avatarlar-kategori-ekle', [AdminController::class, 'avatarlar_kategori_add'])->name('avatarlar_kategori_add');

        Route::get('ikonlar', [AdminController::class, 'ikonlar'])->name('ikonlar');
        Route::post('ikonEkle', [AdminController::class, 'ikon_add'])->name('ikon_add');

        Route::get('yorumlar', [YorumController::class, 'index'])->name('yorumlar');

        Route::get('sayfalar', [SayfaController::class, 'index'])->name('sayfalar');
        Route::post('sayfaEkle', [SayfaController::class, 'add'])->name('sayfa_add');
        Route::post('sayfaDuzenle', [SayfaController::class, 'edit'])->name('sayfa_edit');
        Route::get('sayfaTable', [SayfaController::class, 'table'])->name('sayfa_table');

        Route::get('sss', [SssController::class, 'index'])->name('sss');
        Route::post('sssEkle', [SssController::class, 'add'])->name('sss_add');
        Route::post('sssDuzenle', [SssController::class, 'edit'])->name('sss_edit');
        Route::get('sssTable', [SssController::class, 'table'])->name('sss_table');
        Route::get('sss-kategori', [SssKategoriController::class, 'index'])->name('sss_kategori');
        Route::post('sssKategoriEkle', [SssKategoriController::class, 'add'])->name('sss_kategori_add');
        Route::post('sssKategoriDuzenle', [SssKategoriController::class, 'edit'])->name('sss_kategori_edit');
        Route::get('sssKategoriTable', [SssKategoriController::class, 'table'])->name('sss_kategori_table');

        Route::get('uyeler', [UyeController::class, 'index'])->name('uyeler');
        Route::get('uye-aktivite', [UyeController::class, 'uye_aktivite'])->name('uye_aktivite');
        Route::get('uye-ozel-fiyat', [UyeController::class, 'uye_ozel_fiyat'])->name('uye_ozel_fiyat');
        Route::post('uyeEkle', [UyeController::class, 'add'])->name('uye_add');
        Route::get('uye-detay/{email}', [UyeController::class, 'detail'])->name('uye_detay');
        Route::post('uyeDuzenle', [UyeController::class, 'edit'])->name('uye_edit');
        Route::get('kullanici-gruplari', [UyeController::class, 'kullanici_gruplari'])->name('kullanici_gruplari');
        Route::post('kullanici-gruplari-add', [UyeController::class, 'kullanici_gruplari_add'])->name('kullanici_gruplari_add');
        Route::post('kullanici-gruplari-edit', [UyeController::class, 'kullanici_gruplari_edit'])->name('kullanici_gruplari_edit');
        Route::get('panel-loglar', [AdminController::class, 'panel_loglar'])->name('panel_loglar');


        Route::get('kategoriler', [KategoriController::class, 'index'])->name('kategoriler');
        Route::post('kategoriEkle', [KategoriController::class, 'add'])->name('kategori_add');
        Route::post('kategoriDuzenle', [KategoriController::class, 'edit'])->name('kategori_edit');

        Route::get('oyunlar', [OyunController::class, 'index'])->name('oyunlar');
        Route::post('oyun-ekle', [OyunController::class, 'add'])->name('oyun_add');
        Route::post('oyun-baslik-ekle', [OyunController::class, 'baslik_add'])->name('baslik_add');
        Route::post('oyun-duzenle', [OyunController::class, 'edit'])->name('oyun_edit');
        Route::post('oyun-baslik-duzenle', [OyunController::class, 'baslik_edit'])->name('baslik_edit');
        Route::get('oyunlar/detay/{title}', [OyunController::class, 'detay'])->name('oyun_detay');
        Route::post('oyun-paket-ekle', [OyunController::class, 'oyun_paket_add'])->name('oyun_paket_add');
        Route::post('oyun-paket-nasil-yuklenir', [OyunController::class, 'oyun_paket_nasil_yuklenir'])->name('oyun_paket_nasil_yuklenir');
        Route::post('oyun-paket-duzenle', [OyunController::class, 'oyun_paket_edit'])->name('oyun_paket_edit');
        Route::post('oyun-paket-epin-duzenle', [OyunController::class, 'oyun_paket_epin_edit'])->name('oyun_paket_epin_edit');
        Route::post('oyun-paket-trade-ekle', [OyunController::class, 'oyun_paket_trade_add'])->name('oyun_paket_trade_add');
        Route::post('oyun-paket-trade-duzenle', [OyunController::class, 'oyun_paket_trade_edit'])->name('oyun_paket_trade_edit');
        Route::post('oyun-paket-trade-toplu-duzenle', [OyunController::class, 'oyun_paket_trade_toplu_edit'])->name('oyun_paket_trade_toplu_edit');
        Route::post('oyun-paket-trade-toplu-stok-ekle', [OyunController::class, 'oyun_paket_trade_toplu_stok_ekle'])->name('oyun_paket_trade_toplu_stok_ekle');
        Route::post('oyun-paket-trade-toplu-stok-cikar', [OyunController::class, 'oyun_paket_trade_toplu_stok_cikar'])->name('oyun_paket_trade_toplu_stok_cikar');
        Route::post('oyun-paket-kod-ekle', [OyunController::class, 'oyun_paket_kod_add'])->name('oyun_paket_kod_add');
        Route::get('oyun-paket-kod-goruntule/{paket}', [OyunController::class, 'oyun_paket_kod_view'])->name('oyun_paket_kod_view');
        Route::get('oyun-paket-kod-duzenle/{paket}', [OyunController::class, 'oyun_paket_kod_edit'])->name('oyun_paket_kod_edit');
        Route::post('oyun-paket-kod-duzenle', [OyunController::class, 'oyun_paket_kod_edit_post'])->name('oyun_paket_kod_edit_post');
        Route::post('oyun-paket-kod-duzenle-excel', [OyunController::class, 'oyun_paket_kod_edit_excel_post'])->name('oyun_paket_kod_edit_excel_post');
        Route::post('oyun-market-ozellik-ekle', [OyunController::class, 'oyun_market_ozellik_add'])->name('oyun_market_ozellik_add');
        Route::post('oyun-market-ozellik-duzenle', [OyunController::class, 'oyun_market_ozellik_edit'])->name('oyun_market_ozellik_edit');
        Route::post('oyun-market-item-ekle', [OyunController::class, 'oyun_market_item_add'])->name('oyun_market_item_add');
        Route::post('oyun-market-item-duzenle', [OyunController::class, 'oyun_market_item_edit'])->name('oyun_market_item_edit');

        /*
         * Muve Api Sayfaları
         */
        Route::get('muve-oyunlar', [MuveController::class, 'index'])->name('muve_oyunlar');
        Route::get('muve-satislar', [MuveController::class, 'muve_satislar'])->name('muve_satislar');
        Route::post('muve-oyun-ekle', [MuveController::class, 'add'])->name('muve_oyun_add');
        Route::get('muve-oyun-detaylari-ajax', [MuveController::class, 'detail_ajax'])->name('muve_oyun_detaylari_ajax'); //muve ajax oyun bilgileri sorgulama
        Route::post('muve-oyun-duzenle', [MuveController::class, 'edit'])->name('muve_oyun_edit');

        Route::get('muve-kategoriler', [MuveController::class, 'muve_kategoriler'])->name('muve_kategoriler');
        Route::post('muve-kategori-ekle', [MuveController::class, 'muve_kategori_add'])->name('muve_kategori_add');
        Route::post('muve-kategori-duzenle', [MuveController::class, 'muve_kategori_edit'])->name('muve_kategori_edit');

        Route::get('currencyConverter', [MuveController::class, 'currencyConverter'])->name('currencyConverter'); //muve ajax para birimi çevirme

        Route::get('oyun-toplu-paket', [OyunController::class, 'toplu_paket'])->name('toplu_paket');
        Route::post('oyun-toplu-paket-duzenle', [OyunController::class, 'toplu_paket_edit'])->name('toplu_paket_edit');

        Route::get('oyun-toplu-oyun-parasi', [OyunController::class, 'toplu_oyun_parasi'])->name('toplu_oyun_parasi');
        Route::post('oyun-toplu-oyun-parasi-duzenle', [OyunController::class, 'toplu_oyun_parasi_edit'])->name('toplu_oyun_parasi_edit');

        Route::get('oyun-toplu-stok', [OyunController::class, 'toplu_stok'])->name('toplu_stok');
        Route::get('silinen-stok', [OyunController::class, 'silinen_stok'])->name('silinen_stok');

        Route::get('oyunlar/detay/{title}/{market}', [OyunController::class, 'detay_market'])->name('oyun_detay_market');
        Route::get('oyunlar/detay/{title}/trade/{trade}', [OyunController::class, 'detay_trade'])->name('oyun_detay_trade');

        Route::get('hediye-kodlari', [AdminController::class, 'hediye_kodlari_yonetim'])->name('hediye_kodlari_yonetim');
        Route::post('hediye-kodlari-uret', [AdminController::class, 'hediye_kodlari_yonetim_uret'])->name('hediye_kodlari_yonetim_uret');

        Route::get('tedarikciler', [AdminController::class, 'tedarikciler'])->name('tedarikciler');
        Route::post('tedarikciler-ekle', [AdminController::class, 'tedarikciler_add'])->name('tedarikciler_add');
        Route::post('tedarikciler-duzenle', [AdminController::class, 'tedarikciler_edit'])->name('tedarikciler_edit');


        Route::get('epintakip', [AdminController::class, 'epintakip'])->name('epintakip');
        Route::get('bayi', [AdminController::class, 'bayi'])->name('bayi');
        Route::get('odemeler', [AdminController::class, 'odemeler'])->name('odemeler');
        Route::get('epinf', [AdminController::class, 'epinf'])->name('epinf');
        Route::get('goldf', [AdminController::class, 'goldf'])->name('goldf');
        Route::get('pazarf', [AdminController::class, 'pazarf'])->name('pazarf');
        Route::get('siparisler', [AdminController::class, 'siparisler'])->name('siparisler');
        Route::get('razer', [AdminController::class, 'razer'])->name('razer');
        Route::post('odemeler-ekle', [AdminController::class, 'odemeler_add'])->name('odemeler_add');
        Route::post('odemeler-duzenle', [AdminController::class, 'odemeler_edit'])->name('odemeler_edit');
        Route::get('odemeler-onayla/{durum}/{id}', [AdminController::class, 'odemeler_onayla'])->name('odemeler_onayla');
        Route::post('crypto-ekle', [AdminController::class, 'crypto_add'])->name('crypto_add');
        Route::post('crypto-duzenle', [AdminController::class, 'crypto_edit'])->name('crypto_edit');
        Route::post('digerOdeme-ekle', [AdminController::class, 'digerOdeme_add'])->name('digerOdeme_add');
        Route::post('digerOdeme-duzenle', [AdminController::class, 'digerOdeme_edit'])->name('digerOdeme_edit');

        Route::get('/kampanya', function () {
            return view('back.pages.bayi.kampanya', ['goster' => 'on']);
        })->name('kampanya');

        Route::get('yorumlar-ilan', [AdminController::class, 'yorumlar'])->name('yorumlarIlan');
        Route::get('yorumlar-ilan-onayla/{durum}/{id}', [AdminController::class, 'yorumlar_onayla'])->name('yorumlar_ilan_onayla');

        Route::get('yorumlar-satici', [AdminController::class, 'yorumlar_satici'])->name('yorumlar_satici');
        Route::get('yorumlar-satici-onayla/{durum}/{id}', [AdminController::class, 'yorumlar_satici_onayla'])->name('yorumlar_satici_onayla');


        Route::get('ilanlar-yonetim', [AdminController::class, 'ilanlar_yonetim'])->name('ilanlar_yonetim');
        Route::get('ilanlar-yonetim-onay/{id}/{durum}', [AdminController::class, 'ilanlar_yonetim_onay'])->name('ilanlar_yonetim_onay');
        Route::post('ilanlar-yonetim-onay_red', [AdminController::class, 'ilanlar_yonetim_onay_red'])->name('ilanlar_yonetim_onay_red');
        Route::get('ilanlar-yonetim-onay-ozel/{id}/{durum}', [AdminController::class, 'ilanlar_yonetim_onay_ozel'])->name('ilanlar_yonetim_onay_ozel');
        Route::post('ilanlar_yonetim_sms_gonder', [AdminController::class, 'ilanlar_yonetim_sms_gonder'])->name('ilanlar_yonetim_sms_gonder');

        Route::get('ilanlar-yonetim-buy', [AdminController::class, 'ilanlar_yonetim_buy'])->name('ilanlar_yonetim_buy');
        Route::get('ilanlar-yonetim-buy-onay/{id}/{durum}', [AdminController::class, 'ilanlar_yonetim_buy_onay'])->name('ilanlar_yonetim_buy_onay');
        Route::post('ilanlar-yonetim-buy-onay_red', [AdminController::class, 'ilanlar_yonetim_buy_onay_red'])->name('ilanlar_yonetim_buy_onay_red');
        Route::get('ilanlar-yonetim-buy-onay-ozel/{id}/{durum}', [AdminController::class, 'ilanlar_yonetim_buy_onay_ozel'])->name('ilanlar_yonetim_buy_onay_ozel');


        Route::get('game-gold-yonetim', [AdminController::class, 'game_gold_yonetim'])->name('game_gold_yonetim');
        Route::get('game-gold-yonetim-onayla/{durum}/{id}', [AdminController::class, 'game_gold_yonetim_onayla'])->name('game_gold_yonetim_onayla');
        Route::post('game-gold-yonetim-onayla/{durum}/{id}', [AdminController::class, 'game_gold_yonetim_onayla_post'])->name('game_gold_yonetim_onayla_post');

        Route::get('epin-yonetim', [AdminController::class, 'epin_yonetim'])->name('epin_yonetim');

        Route::get('epin-siparis-detaylari', [AdminController::class, 'epin_siparis_detaylari'])->name('epin_siparis_detaylari');

        Route::get('para-cek-yonetim', [AdminController::class, 'para_cek_yonetim'])->name('para_cek_yonetim');
        Route::get('para-cek-yonetim-onayla/{durum}/{id}', [AdminController::class, 'para_cek_yonetim_onayla'])->name('para_cek_yonetim_onayla');

        Route::get('header-menu', [AdminController::class, 'header_menu'])->name('header_menu');
        Route::post('header-menu-ekle', [AdminController::class, 'header_menu_add'])->name('header_menu_add');
        Route::post('header-menu-duzenle', [AdminController::class, 'header_menu_edit'])->name('header_menu_edit');

        Route::get('twitch-para-cek-yonetim', [AdminController::class, 'twitch_para_cek_yonetim'])->name('twitch_para_cek_yonetim');
        Route::get('twitch-para-cek-yonetim-onayla/{durum}/{id}', [AdminController::class, 'twitch_para_cek_yonetim_onayla'])->name('twitch_para_cek_yonetim_onayla');

        Route::get('twitch-kesintisiz-yonetim', [AdminController::class, 'twitch_kesintisi_yonetim'])->name('twitch_kesintisi_yonetim');
        Route::get('twitch-kesintisiz-yonetim-onayla/{durum}/{id}', [AdminController::class, 'twitch_kesintisi_yonetim_onayla'])->name('twitch_kesintisi_yonetim_onayla');

        Route::get('twitch-yayincilar-yonetim', [AdminController::class, 'twitch_yayincilar_yonetim'])->name('twitch_yayincilar_yonetim');
        Route::get('twitch-yayincilar-yonetim-favorilere-ekle/{id}', [AdminController::class, 'twitch_yayincilar_yonetim_favori_ekle'])->name('twitch_yayincilar_yonetim_favori_ekle');
        Route::get('twitch-yayincilar-yonetim-favorilere-kaldir/{id}', [AdminController::class, 'twitch_yayincilar_yonetim_favori_kaldir'])->name('twitch_yayincilar_yonetim_favori_kaldir');

        Route::get('kimlik-yonetim', [AdminController::class, 'kimlik_yonetim'])->name('kimlik_yonetim');
        Route::get('kimlik-yonetim-onayla/{durum}/{id}', [AdminController::class, 'kimlik_yonetim_onayla'])->name('kimlik_yonetim_onayla');

        Route::get('twitch-donate-yonetim', [AdminController::class, 'twitch_donate_yonetim'])->name('twitch_donate_yonetim');

        Route::get('site-yonetim', [AdminController::class, 'site_yonetim'])->name('site_yonetim');
        Route::post('site-yonetim-post', [AdminController::class, 'site_yonetim_post'])->name('site_yonetim_post');

        Route::get('manuel-telefon-onaylama', [AdminController::class, 'telefon_yonetim'])->name('telefon_yonetim');
        Route::get('manuel-telefon-onayla/{durum}/{id}', [AdminController::class, 'telefon_onayla'])->name('telefon_onaylaAdmin');


        Route::prefix('rapor')->middleware('auth')->group(function () {
            Route::get('odeme-kanallari',     [AdminController::class, 'odeme_kanallari'])->name('odeme_kanallari');
            Route::get('epin-rapor',         [AdminController::class, 'epin_rapor'])->name('epin_rapor');
            Route::get('oyun-parasi-rapor', [AdminController::class, 'oyun_parasi_rapor'])->name('oyun_parasi_rapor');
            Route::get('ilan-rapor',         [AdminController::class, 'ilan_rapor'])->name('ilan_rapor');
            Route::get('boss-rapor',         [AdminController::class, 'boss_rapor'])->name('boss_rapor');
        });
        Route::post('resimYukle', [AdminController::class, 'resimYukle'])->name('resimYukle');
        Route::post('resimYukle2', [AdminController::class, 'resimYukle2'])->name('resimYukle2');
    });
});
/*
 * Api - entegrasyon
 */
Route::get('getNewLogs', function () {
    return getNewLogs();
})->name('getNewLogs');
Route::get('setStatus', function (Request $request) {
    setStatus($_GET['table'], $_GET['id']);
})->name('setStatus');

Route::get('ykp_yorum', function (Request $request) {
    ykp_yorum($_GET['dr'],    $_GET['id']);
})->name('ykp_yorum');

Route::get('deleteContent', function (Request $request) {
    deleteContent($_GET['table'], $_GET['id']);
})->name('deleteContent');
Route::get('deleteItem', function (Request $request) {
    deleteItem($_GET['id']);
})->name('deleteItem');
Route::get('getData', function (Request $request) {
    getData($_GET['table'], $_GET['id']);
})->name('getData');
Route::get('game_gold_durum_sorgula', function (Request $request) {
    findGameGoldStatusById($_GET['id']);
})->name('game_gold_durum_sorgula');
Route::get('getGameGold', function () {
    return getGameGold();
})->name('getGameGold');
Route::get('getGameGoldCont', function () {
    return getGameGoldCont();
})->name('getGameGoldCont');
Route::get('getIlanCont', function () {
    return getIlanCont();
})->name('getIlanCont');
Route::get('bildirimGetir', function () {
    exit();
    return getNewBildirim(5);
})->name('bildirimGetir');
Route::get('getDataItem', function (Request $request) {
    getDataItem($_GET['table'], $_GET['id']);
})->name('getDataItem');


/*
 * İndex loads

Route::get('indexGamesIcons', function () {
    return view('front.modules.games-icons');
})->name('indexGamesIcons');
Route::get('indexCokSatanlar', function () {
    return view('front.modules.popular-products');
})->name('indexCokSatanlar');
Route::get('indexTwitch', function () {
    return view('front.modules.twitch');
})->name('indexTwitch');
Route::get('indexNews', function () {
    return view('front.modules.news');
})->name('indexNews');
Route::get('indexFaq', function () {
    return view('front.modules.faq');
})->name('indexFaq');
*/
/*
 * Panel Loads
 */

Route::get('getWaitAll', function () {
   /*  return [

        "getWaitIlanSiparisler" => -1,
        "getWaitAlisIlanlari" => -1,
        "getWaitOyunParasiSiparisleri" => -1,
        "getWaitParaCekimTalepleri" => -1,
        "getWaitTwitchParaCekim" => -1,
        "getWaitTwitchKesintisiz" => -1,
        "getWaitKimlikOnaylari" => -1,
        "getWaitIlanYorumlari" => -1,
        "getWaitYorumlar" => -1,
        "getWaitSiparisler" => -1,
        "getWaitodeme_onay" => -1

    ]; */
    return [
        "getWaitIlanSiparisler" => getWaitIlanSiparisler(),
        "getWaitAlisIlanlari" => getWaitAlisIlanlari(),
        "getWaitOyunParasiSiparisleri" => getWaitOyunParasiSiparisleri(),
        "getWaitParaCekimTalepleri" => getWaitParaCekimTalepleri(),
        "getWaitTwitchParaCekim" => getWaitTwitchParaCekim(),
        "getWaitTwitchKesintisiz" => getWaitTwitchKesintisiz(),
        "getWaitKimlikOnaylari" => getWaitKimlikOnaylari(),
        "getWaitIlanYorumlari" => getWaitIlanYorumlari(),
        "getWaitYorumlar" => getWaitYorumlar(),
        "getWaitSiparisler" => getWaitSiparisler(),
        "getWaitRazer" => getWaitRazer(),
        "getWaitodeme_onay" => getWaitodeme_onay()

    ];
})->name('getWaitAll');

Route::get('getWaitAll2', function () {
    echo "<br>" . time() . " " . microtime() . "<br>";
    getWaitIlanSiparisler();
    echo "<br>" . time() . " " . microtime() . "<br>";
    getWaitAlisIlanlari();
    echo "<br>" . time() . " " . microtime() . "<br>";
    getWaitOyunParasiSiparisleri();
    echo "<br>" . time() . " " . microtime() . "<br>";
    getWaitParaCekimTalepleri();
    echo "<br>" . time() . " " . microtime() . "<br>";
    getWaitTwitchParaCekim();
    echo "<br>" . time() . " " . microtime() . "<br>";
    getWaitTwitchKesintisiz();
    echo "<br>" . time() . " " . microtime() . "<br>";
    getWaitKimlikOnaylari();
    echo "<br>" . time() . " " . microtime() . "<br>";
    getWaitIlanYorumlari();
    echo "<br>" . time() . " " . microtime() . "<br>";
    getWaitYorumlar();
    echo "<br>" . time() . " " . microtime() . "<br>";
    getWaitSiparisler();
    echo "<br>" . time() . " " . microtime() . "<br>";
    getWaitRazer();
    echo "<br>" . time() . " " . microtime() . "<br>";
    getWaitodeme_onay();
    echo "<br>" . time() . " " . microtime() . "<br>";
    exit();
    return [
        "getWaitIlanSiparisler" => getWaitIlanSiparisler(),
        "getWaitAlisIlanlari" => getWaitAlisIlanlari(),
        "getWaitOyunParasiSiparisleri" => getWaitOyunParasiSiparisleri(),
        "getWaitParaCekimTalepleri" => getWaitParaCekimTalepleri(),
        "getWaitTwitchParaCekim" => getWaitTwitchParaCekim(),
        "getWaitTwitchKesintisiz" => getWaitTwitchKesintisiz(),
        "getWaitKimlikOnaylari" => getWaitKimlikOnaylari(),
        "getWaitIlanYorumlari" => getWaitIlanYorumlari(),
        "getWaitYorumlar" => getWaitYorumlar(),
        "getWaitSiparisler" => getWaitSiparisler(),
        "getWaitRazer" => getWaitRazer(),
        "getWaitodeme_onay" => getWaitodeme_onay()

    ];
})->name('getWaitAll2');

Route::get('getWaitRazer', function () {return getWaitRazer();})->name('getWaitRazer');


Route::get('getWaitIodeme_onay', function () {
    return getWaitodeme_onay();
})->name('getWaitodeme_onay');
Route::get('getWaitIlanSiparisler', function () {
    return getWaitIlanSiparisler();
})->name('getWaitIlanSiparisler');
Route::get('getWaitAlisIlanlari', function () {
    return getWaitAlisIlanlari();
})->name('getWaitAlisIlanlari');
Route::get('getWaitOyunParasiSiparisleri', function () {
    return getWaitOyunParasiSiparisleri();
})->name('getWaitOyunParasiSiparisleri');
Route::get('getWaitParaCekimTalepleri', function () {
    return getWaitParaCekimTalepleri();
})->name('getWaitParaCekimTalepleri');
Route::get('getWaitTwitchParaCekim', function () {
    return getWaitTwitchParaCekim();
})->name('getWaitTwitchParaCekim');
Route::get('getWaitTwitchKesintisiz', function () {
    return getWaitTwitchKesintisiz();
})->name('getWaitTwitchKesintisiz');
Route::get('getWaitKimlikOnaylari', function () {
    return getWaitKimlikOnaylari();
})->name('getWaitKimlikOnaylari');

Route::get('getWaitIlanYorumlari', function () {
    return getWaitIlanYorumlari();
})->name('getWaitIlanYorumlari');
Route::get('getWaitYorumlar', function () {
    return getWaitYorumlar();
})->name('getWaitYorumlar');
Route::get('getWaitSiparisler', function () {
    return getWaitSiparisler();
})->name('getWaitSiparisler');

Route::get('panelBrowser', function () {
    return view('back.modules.browser');
})->name('panelBrowser');
Route::get('panelAktivite', function () {
    return view('back.modules.aktivite');
})->name('panelAktivite');
Route::get('panelBilgiler', function () {
    return view('back.modules.bilgiler');
})->name('panelBilgiler');
Route::get('panelZiyaretci', function () {
    return view('back.modules.ziyaretci');
})->name('panelZiyaretci');
Route::get('panelCihaz', function () {
    return view('back.modules.cihaz');
})->name('panelCihaz');
Route::get('panelEpinler', function () {
    return view('back.modules.epinler');
})->name('panelEpinler');


/*
 * Datatables Ajax
 */
Route::get('toplu_stok_ajax', function () {
    return view('back.ajax.toplu_stok');
})->name('toplu_stok_ajax');




/*
 * firebase
 */
Route::post('deviceTokenRegister', [AdminController::class, 'deviceTokenRegister'])->name('deviceTokenRegister');
