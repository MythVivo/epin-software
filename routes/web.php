<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\HaberController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LangController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\OyunController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SayfaController;
use App\Http\Controllers\SiparisController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\SssController;
use App\Http\Controllers\SssKategoriController;
use App\Http\Controllers\UyeController;
use App\Http\Controllers\YorumController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;



Route::get('/', [MainController::class, 'index'])->name('homepage');
Route::get('/test', function() { return view('front.test'); } );

Route::get('/haberler', [MainController::class, 'haberler'])->name('haber');
Route::get('/haber/{haber}', [MainController::class, 'haber_detay'])->name('haber_detay');
Route::get('/yorumlar', [MainController::class, 'yorumlar'])->name('yorumlar');
Route::get('/sayfa/{sayfa}', [MainController::class, 'sayfa'])->name('sayfa');

/*
 * Login - Register İşlemleri
 */
Route::get('/giris', [LoginController::class, 'login'])->name('giris');
Route::post('/giris', [LoginController::class, 'login_post'])->name('login_post');
Route::get('/kayit', [RegisterController::class, 'register'])->name('kayit');
Route::post('/kayit', [RegisterController::class, 'register_post'])->name('kayit_post');
Route::get('/cikis', [LoginController::class, 'logout'])->name('logout');
Route::get('/hesap-onayla/{email}/{key}', [RegisterController::class, 'active'])->name('active');

/*
 * Oyunlar
 */
Route::get('/oyunlar', [MainController::class, 'oyunlar'])->name('oyunlarTum');
Route::get('/oyun/{oyun}', [MainController::class, 'oyunlar_baslik'])->name('oyun_baslik');
Route::get('/oyun/{oyun}/{baslik}', [MainController::class, 'baslik_detay'])->name('baslik_detay');

Route::get('/urun-onizle', [MainController::class, 'urun_onizle'])->name('urun_onizle');
Route::post('/urun-onizle', [MainController::class, 'urun_onizle_post'])->name('urun_onizle_post');

Route::post('/siparis-ver', [SiparisController::class, 'siparis_ver'])->name('siparis_ver');


Route::get('/bakiye-ekle', [SiparisController::class, 'bakiye_ekle'])->name('bakiye_ekle');

Route::post('/odeme-yap', [SiparisController::class, 'odeme_yap'])->name('odeme_yap');

/*
 * Lang
 */
Route::get('/lang/{lang}', [LangController::class, 'lang'])->name('lang');


Auth::routes();
Route::get('/hesabim', [HomeController::class, 'hesabim'])->name('hesabim');

Route::prefix('panel')->middleware('auth')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('panel');

    Route::get('slider', [SliderController::class, 'index'])->name('slider');
    Route::post('sliderEkle', [SliderController::class, 'add'])->name('slider_add');
    Route::post('sliderDuzenle', [SliderController::class, 'edit'])->name('slider_edit');
    Route::get('sliderTable', [SliderController::class, 'table'])->name('slider_table');

    Route::get('haberler', [HaberController::class, 'index'])->name('haberler');
    Route::post('haberEkle', [HaberController::class, 'add'])->name('haber_add');
    Route::post('haberDuzenle', [HaberController::class, 'edit'])->name('haber_edit');
    Route::get('haberTable', [HaberController::class, 'table'])->name('haber_table');

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
    Route::post('uyeEkle', [UyeController::class, 'add'])->name('uye_add');
    Route::get('uye-detay/{email}', [UyeController::class, 'detail'])->name('uye_detay');
    Route::post('uyeDuzenle', [UyeController::class, 'edit'])->name('uye_edit');


    Route::get('kategoriler', [KategoriController::class, 'index'])->name('kategoriler');
    Route::post('kategoriEkle', [KategoriController::class, 'add'])->name('kategori_add');
    Route::post('kategoriDuzenle', [KategoriController::class, 'edit'])->name('kategori_edit');

    Route::get('oyunlar', [OyunController::class, 'index'])->name('oyunlar');
    Route::post('oyun-ekle', [OyunController::class, 'add'])->name('oyun_add');
    Route::post('oyun-baslik-ekle', [OyunController::class, 'baslik_add'])->name('baslik_add');
    Route::post('oyun-duzenle', [OyunController::class, 'edit'])->name('oyun_edit');
    Route::get('oyunlar/detay/{title}', [OyunController::class, 'detay'])->name('oyun_detay');
    Route::post('oyun-paket-ekle', [OyunController::class, 'oyun_paket_add'])->name('oyun_paket_add');
    Route::post('oyun-paket-kod-ekle', [OyunController::class, 'oyun_paket_kod_add'])->name('oyun_paket_kod_add');
});

/*
 * Api - entegrasyon
 */
Route::get('getNewLogs', function() { return getNewLogs(); } )->name('getNewLogs');
Route::get('setStatus', function(Request $request) { setStatus($_GET['table'],$_GET['id']); } )->name('setStatus');
Route::get('deleteContent', function(Request $request) { deleteContent($_GET['table'],$_GET['id']); } )->name('deleteContent');
Route::get('getData', function(Request $request) { getData($_GET['table'],$_GET['id']); } )->name('getData');
