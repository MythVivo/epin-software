<?php

namespace App\Http\Controllers;

use App\Models\Games;
use App\Models\GamesPackages;
use App\Models\GamesPackagesTrade;
use App\Models\GamesTitles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Auth;
use App\Helpers\simplexlsx;

class OyunController extends Controller
{
    public function index()
    {
        return view('back.pages.games.index');
    }

    public function add(Request $request)
    {
        $title = Str::slug($request->title);
        $game = new games();
        if ($request->hasFile('image')) {
            $destinationPath = env("ROOT") . env("FRONT") . env("GAMES");
            $file = $request->image;
            $fileName = $title . "-" . time() . '.' . $file->clientExtension();
            $fileOriginalName = $title . "-" . time();
            $file->move($destinationPath, $fileName);
            $game->image = $fileName;
            imageResize($file->clientExtension(), $destinationPath, $fileName, $fileOriginalName, '0.5', '100');
        }
        if ($request->hasFile('icon')) {
            $destinationPath = env("ROOT") . env("FRONT") . env("GAMES_ICON");
            $file = $request->icon;
            $fileName = $title . "-" . time() . '.' . $file->clientExtension();
            $fileOriginalName = $title . "-" . time();
            $file->move($destinationPath, $fileName);
            $game->icon = $fileName;
        }
        if ($request->hasFile('icon_2')) {
            $destinationPath = env("ROOT") . env("FRONT") . env("GAMES_ICON");
            $file = $request->icon_2;
            $fileName = $title . "-" . time() . '-1' . '.' . $file->clientExtension();
            $fileOriginalName = $title . "-" . time();
            $file->move($destinationPath, $fileName);
            $game->icon_2 = $fileName;
        }
        $game->title = $request->title;
        $game->text = $request->text;
        $game->category = $request->category;
        $game->link = $title;
        $game->status = 1;
        $game->lang = $request->lang;
        $game->sira = $request->sira;
        $game->created_at = date('YmdHis');
        $game->updated_at = date('YmdHis');
        if (!$game->save()) {
            return back()->with('error', __('admin.hata-2'));
        } else {
            LogCall(Auth::user()->id, '4', "Kullanıcı " . $request->title . " isimli yeni bir oyun ekledi.");
            return back()->with('success', __('admin.basarili'));
        }
    }

    public function baslik_add(Request $request)
    {
        $title = Str::slug($request->title);
        $gameTitle = new gamestitles();
        if ($request->hasFile('image')) {
            $destinationPath = env("ROOT") . env("FRONT") . env("GAMES_TITLES");
            $file = $request->image;
            $fileName = $title . "-" . time() . '.' . $file->clientExtension();
            $file->move($destinationPath, $fileName);
            $gameTitle->image = $fileName;
        }

        $gameTitle->title = $request->title;
        $gameTitle->text = $request->text;
        $gameTitle->type = $request->type;
        $gameTitle->game = $request->game;
        $gameTitle->link = $title;
        $gameTitle->status = 1;
        $gameTitle->lang = $request->lang;
        $gameTitle->epin = $request->epin;
        $gameTitle->etiket = $request->etiket;
        $gameTitle->sira = $request->sira;
        $gameTitle->kdv = $request->kdv;
        $gameTitle->fatura_kes = $request->fatura_kes;
        $gameTitle->created_at = date('YmdHis');
        $gameTitle->updated_at = date('YmdHis');
        if (!$gameTitle->save()) {
            return back()->with('error', __('admin.hata-2'));
        } else {
            if (isset($request->special)) {
                DB::table('games_titles_special')->insert([
                    'games_titles' => $gameTitle->id,
                    'special_id' => '1',
                    'special_value' => '1',
                    'updated_at' => date('YmdHis'),
                    'created_at' => date('YmdHis'),
                ]);
            }
            LogCall(Auth::user()->id, '4', "Kullanıcı " . $request->title . " isimli yeni bir başlık ekledi.");
            return back()->with('success', __('admin.basarili'));
        }
    }

    public function baslik_edit(Request $request)
    {
        $title = Str::slug($request->title);
        $gameTitle = GamesTitles::find($request->id);
        if ($request->hasFile('image')) {
            $destinationPath = env("ROOT") . env("FRONT") . env("GAMES_TITLES");
            $file = $request->image;
            $fileName = $title . "-" . time() . '.' . $file->clientExtension();
            $fileOriginalName = $title . "-" . time();
            deleteImage('games_titles', $request->id);
            $file->move($destinationPath, $fileName);
            $gameTitle->image = $fileName;
        }
        if ($request->hasFile('image_alis')) {
            $destinationPath = env("ROOT") . env("FRONT") . env("GAMES_TITLES");
            $file = $request->image_alis;
            $fileName = $title . "-alis-" . time() . '.' . $file->clientExtension();
            $fileOriginalName = $title . "-alis-" . time();
            $file->move($destinationPath, $fileName);
            $gameTitle->image_alis = $fileName;
        }
        $gameTitle->title = $request->title;
        $gameTitle->text = $request->text;
        $gameTitle->link = $title;
        $gameTitle->status = $request->status;
        $gameTitle->lang = $request->lang;
        $gameTitle->epin = $request->epin;
        $gameTitle->etiket = $request->etiket;
        $gameTitle->sira = $request->sira;
        $gameTitle->kdv = $request->kdv;
        $gameTitle->siparis = $request->siparis;
        $gameTitle->fatura_kes = $request->fatura_kes;
        $gameTitle->updated_at = date('YmdHis');

#-----------------Sipariş sorular muhabbeti
        $soru=DB::table('epin_soru')->where('game_id',$request->game_id);
        if($soru->count()>0){
            if(strlen($request->soru1)==0 && strlen($request->soru2)==0) {
                //DB::table('epin_soru')->where('game_id',$request->game_id)->delete();
                }
                else{
                    DB::table('epin_soru')->where('game_id',$request->game_id)->update([
                        'game_id' => $request->game_id,
                        'soru1' => $request->soru1,
                        'soru2' => $request->soru2
                    ]);
                }

        } else {
            DB::table('epin_soru')->insert([
                'game_id' => $request->game_id,
                'soru1' => $request->soru1,
                'soru2' => $request->soru2
            ]);
        }

#-----------------------------------
        if (!$gameTitle->save()) {
            return back()->with('error', __('admin.hata-2'));
        } else {
            LogCall(Auth::user()->id, '4', "Kullanıcı " . $request->title . " isimli başlığı düzenledi.");
            return back()->with('success', __('admin.basarili'));
        }
    }

    public function edit(Request $request)
    {
        $title = Str::slug($request->title);
        $game = Games::find($request->id);
        if ($request->hasFile('image')) {
            $destinationPath = env("ROOT") . env("FRONT") . env("GAMES");
            $file = $request->image;
            $fileName = $title . "-" . time() . '.' . $file->clientExtension();
            $fileOriginalName = $title . "-" . time();
            deleteImage('games', $request->id);
            $file->move($destinationPath, $fileName);
            $game->image = $fileName;
            imageResize($file->clientExtension(), $destinationPath, $fileName, $fileOriginalName, '0.5', '80');
        }
        if ($request->hasFile('icon')) {
            $destinationPath = env("ROOT") . env("FRONT") . env("GAMES_ICON");
            $file = $request->icon;
            $fileName = $title . "-" . time() . '.' . $file->clientExtension();
            $fileOriginalName = $title . "-" . time();
            $file->move($destinationPath, $fileName);
            $game->icon = $fileName;
        }
        if ($request->hasFile('icon_2')) {
            $destinationPath = env("ROOT") . env("FRONT") . env("GAMES_ICON");
            $file = $request->icon_2;
            $fileName = $title . "-" . time() . '-1' . '.' . $file->clientExtension();
            $fileOriginalName = $title . "-" . time();
            $file->move($destinationPath, $fileName);
            $game->icon_2 = $fileName;
        }
        $game->title = $request->title;
        $game->text = $request->text;
        $game->category = $request->category;
        $game->link = $title;
        $game->sira = $request->sira;
        $game->updated_at = date('YmdHis');
        if (!$game->save()) {
            return back()->with('error', __('admin.hata-2'));
        } else {
            LogCall(Auth::user()->id, '4', "Kullanıcı " . $request->title . " isimli oyunu düzenledi.");
            return back()->with('success', __('admin.basarili'));
        }
    }

    public function detay($link = null)
    {
        return view('back.pages.games.detay')->with('link', $link);
    }

    public function silinen_stok()
    {
        return view('back.pages.epin.silinenler');
    }

    public function oyun_paket_add(Request $request)
    {
        $title = Str::slug($request->title);
        $games_packages = new GamesPackages();
        if ($request->hasFile('image')) {
            $destinationPath = env("ROOT") . env("FRONT") . env("GAMES_PACKAGES");
            $file = $request->image;
            $fileName = $title . "-" . time() . '.' . $file->clientExtension();
            $fileOriginalName = $title . "-" . time();
            $file->move($destinationPath, $fileName);
            $games_packages->image = $fileName;
        }
        if ($request->discount_type != 0) {
            $date = str_replace(array('-', ':'), "", ($request->discount_date . $request->discount_date_time . "00"));
            $games_packages->discount_amount = $request->discount_amount;
            $games_packages->discount_date = $date;
        }
        if ($request->bonus_type != 0) {
            $dateb = str_replace(array('-', ':'), "", ($request->bonus_date . $request->bonus_date_time . "00"));
            $games_packages->bonus_amount = $request->bonus_amount;
            $games_packages->bonus_date = $dateb;
        }
        $games_packages->games_titles = $request->games_titles;
        $games_packages->title = $request->title;
        $games_packages->text = $request->text;
        $games_packages->price = $request->price;
        $games_packages->discount_type = $request->discount_type;
        $games_packages->bonus_type = $request->bonus_type;
        $games_packages->etiket = $request->etiket;
        $games_packages->sira = $request->sira;
        $games_packages->updated_at = date('YmdHis');
        $games_packages->created_at = date('YmdHis');
        if (!$games_packages->save()) {
            return back()->with('error', __('admin.hata-2'));
        } else {
            LogCall(Auth::user()->id, '4', "Kullanıcı " . $request->title . " isimli yeni bir epin paketi ekledi.");
            return back()->with('success', __('admin.basarili'));
        }
    }

    public function oyun_paket_nasil_yuklenir(Request $request)
    {
        if (isset($request->nasil_yuklenir)) {
            DB::table('epin_nasil_yuklenir')->where('id', $request->nasil_yuklenir)->update([
                'text' => $request->text_nasil_yuklenir,
                'updated_at' => date('YmdHis'),
            ]);
            LogCall(Auth::user()->id, '4', "Kullanıcı " . $request->nasil_yuklenir . " id'li paket için nasıl yüklenir bilgisini düzenledi.");
        } else {
            DB::table('epin_nasil_yuklenir')->insert([
                'epin' => $request->games_titles,
                'text' => $request->text_nasil_yuklenir,
                'updated_at' => date('YmdHis'),
                'created_at' => date('YmdHis'),
            ]);
            LogCall(Auth::user()->id, '4', "Kullanıcı " . $request->games_titles . " id'li paket için nasıl yüklenir bilgisi ekledi.");
        }
        return back()->with('success', __('admin.basarili'));
    }

    public function oyun_paket_edit(Request $request)
    {
        $title = Str::slug($request->title);
        $games_packages = GamesPackages::find($request->id);
        if ($request->hasFile('image')) {
            $destinationPath = env("ROOT") . env("FRONT") . env("GAMES_PACKAGES");
            $file = $request->image;
            $fileName = $title . "-" . time() . '.' . $file->clientExtension();
            $fileOriginalName = $title . "-" . time();
            deleteImage('games_packages', $request->id);
            $file->move($destinationPath, $fileName);
            $games_packages->image = $fileName;
        }
        if ($request->discount_type != 0) {
            $date = str_replace(array('-', ':'), "", ($request->discount_date . $request->discount_date_time . "00"));
            $games_packages->discount_amount = $request->discount_amount;
            $games_packages->discount_date = $date;
        }
        if ($request->bonus_type != 0) {
            $dateb = str_replace(array('-', ':'), "", ($request->bonus_date . $request->bonus_date_time . "00"));
            $games_packages->bonus_amount = $request->bonus_amount;
            $games_packages->bonus_date = $dateb;
        }
        $games_packages->title = $request->title;
        $games_packages->text = $request->text;
        $games_packages->price = $request->price;
        $games_packages->discount_type = $request->discount_type;
        $games_packages->bonus_type = $request->bonus_type;
        $games_packages->etiket = $request->etiket;
        $games_packages->sira = $request->sira;
        $games_packages->updated_at = date('YmdHis');
        if (!$games_packages->save()) {
            return back()->with('error', __('admin.hata-2'));
        } else {
            LogCall(Auth::user()->id, '4', "Kullanıcı " . $request->title . " isimli paketi düzenledi.");
            return back()->with('success', __('admin.basarili'));
        }
    }

    public function oyun_paket_epin_edit(Request $request)
    {
        $title = Str::slug($request->title);
        if (DB::table('games_packages')->where('stockCode', $request->stockCode)->count() > 0) { //düzenler
            if ($request->discount_type != 0) {
                $date = str_replace(array('-', ':'), "", ($request->discount_date . $request->discount_date_time . "00"));
                $discount_amount = $request->discount_amount;
                $discount_date = $date;
            } else {
                $discount_amount = 0;
                $discount_date = date('YmdHis');
            }
            if ($request->bonus_type != 0) {
                $dateb = str_replace(array('-', ':'), "", ($request->bonus_date . $request->bonus_date_time . "00"));
                $bonus_amount = $request->bonus_amount;
                $bonus_date = $dateb;
            } else {
                $bonus_amount = 0;
                $bonus_date = date('YmdHis');
            }
            $package = DB::table('games_packages')->where('stockCode', $request->stockCode)->update([
                'games_titles' => $request->games_titles,
                'title' => $request->title,
                'text' => $request->text,
                'price' => $request->price,
                'discount_type' => $request->discount_type,
                'discount_amount' => $discount_amount,
                'discount_date' => $discount_date,
                'bonus_type' => $request->bonus_type,
                'bonus_amount' => $bonus_amount,
                'bonus_date' => $bonus_date,
                'etiket' => $request->etiket,
                'updated_at' => date('YmdHis'),
            ]);
            if ($request->hasFile('image')) {
                $destinationPath = env("ROOT") . env("FRONT") . env("GAMES_PACKAGES");
                $file = $request->image;
                $fileName = $title . "-epin-" . time() . '.' . $file->clientExtension();
                $fileOriginalName = $title . "-epin-" . time();
                $file->move($destinationPath, $fileName);
                DB::table('games_packages')->where('stockCode', $request->stockCode)->update([
                    'image' => $fileName,
                ]);
            }
        } else { //yeni oluşturur
            if ($request->discount_type != 0) {
                $date = str_replace(array('-', ':'), "", ($request->discount_date . $request->discount_date_time . "00"));
                $discount_amount = $request->discount_amount;
                $discount_date = $date;
            } else {
                $discount_amount = 0;
                $discount_date = date('YmdHis');
            }
            if ($request->bonus_type != 0) {
                $dateb = str_replace(array('-', ':'), "", ($request->bonus_date . $request->bonus_date_time . "00"));
                $bonus_amount = $request->bonus_amount;
                $bonus_date = $dateb;
            } else {
                $bonus_amount = 0;
                $bonus_date = date('YmdHis');
            }
            if ($request->hasFile('image')) {
                $destinationPath = env("ROOT") . env("FRONT") . env("GAMES_PACKAGES");
                $file = $request->image;
                $fileName = $title . "-epin-" . time() . '.' . $file->clientExtension();
                $fileOriginalName = $title . "-epin-" . time();
                $file->move($destinationPath, $fileName);
            } else {
                $fileName = "";
            }
            $package = DB::table('games_packages')->where('stockCode', $request->stockCode)->insert([
                'games_titles' => $request->games_titles,
                'title' => $request->title,
                'text' => $request->text,
                'price' => $request->price,
                'discount_type' => $request->discount_type,
                'discount_amount' => $discount_amount,
                'discount_date' => $discount_date,
                'bonus_type' => $request->bonus_type,
                'bonus_amount' => $bonus_amount,
                'bonus_date' => $bonus_date,
                'etiket' => $request->etiket,
                'stockCode' => $request->stockCode,
                'image' => $fileName,
                'updated_at' => date('YmdHis'),
                'created_at' => date('YmdHis'),
            ]);
        }
        LogCall(Auth::user()->id, '4', "Kullanıcı " . $request->title . " isimli epin oyun paketini düzenledi.");
        return back()->with('success', __('admin.basarili'));
    }


    public function oyun_paket_trade_add(Request $request)
    {
        $title = Str::slug($request->title);
        $games_packages_trade = new GamesPackagesTrade();
        if ($request->hasFile('image')) {
            $destinationPath = env("ROOT") . env("FRONT") . env("GAMES_PACKAGES_TRADE");
            $file = $request->image;
            $fileName = $title . "-" . time() . '.' . $file->clientExtension();
            $fileOriginalName = $title . "-" . time();
            $file->move($destinationPath, $fileName);
            $games_packages_trade->image = $fileName;
        }
        if (isset($request->bonus_date)) {
            $dateb = str_replace(array('-', ':'), "", ($request->bonus_date . $request->bonus_date_time . "00"));
            $games_packages_trade->bonus_date = $dateb;
        }
        $games_packages_trade->games_titles = $request->games_titles;
        $games_packages_trade->title = $request->title;
        $games_packages_trade->description = $request->text;
        $games_packages_trade->alis_fiyat = $request->alis;
        $games_packages_trade->satis_fiyat = $request->satis;
        $games_packages_trade->stok = $request->stok;
        $games_packages_trade->bonus_type = $request->bonus_type;
        $games_packages_trade->bonus_amount = $request->bonus_amount;
        $games_packages_trade->etiket = $request->etiket;
        $games_packages_trade->updated_at = date('YmdHis');
        $games_packages_trade->created_at = date('YmdHis');
        if (!$games_packages_trade->save()) {
            return back()->with('error', __('admin.hata-2'));
        } else {
            LogCall(Auth::user()->id, '4', "Kullanıcı " . $request->title . " isimli yeni bir trade başlığı ekledi.");
            return back()->with('success', __('admin.basarili'));
        }
    }

    public function oyun_paket_trade_edit(Request $request)
    {
        $title = Str::slug($request->title);
        $games_packages_trade = GamesPackagesTrade::find($request->id);
        if ($request->hasFile('image')) {
            $destinationPath = env("ROOT") . env("FRONT") . env("GAMES_PACKAGES_TRADE");
            $file = $request->image;
            $fileName = $title . "-" . time() . '.' . $file->clientExtension();
            $fileOriginalName = $title . "-" . time();
            deleteImage('games_packages_trade', $request->id);
            $file->move($destinationPath, $fileName);
            $games_packages_trade->image = $fileName;
        }

        if (isset($request->bonus_date)) {
            $dateb = str_replace(array('-', ':'), "", ($request->bonus_date . $request->bonus_date_time . "00"));
            $games_packages_trade->bonus_date = $dateb;
        }

        $games_packages_trade->title = $request->title;
        $games_packages_trade->description = $request->text;
        $games_packages_trade->alis_fiyat = $request->alis;
        $games_packages_trade->satis_fiyat = $request->satis;
        $games_packages_trade->alis_stok = $request->alis_stok;
        $games_packages_trade->satis_stok = $request->satis_stok;
        #$games_packages_trade->stok = $request->stok;
        $games_packages_trade->bonus_type = $request->bonus_type;
        $games_packages_trade->bonus_amount = $request->bonus_amount;
        $games_packages_trade->etiket = $request->etiket;
        $games_packages_trade->sira = $request->sira;
        $games_packages_trade->indirim = $request->indirim;///////////////////////////////////////////////////////////////////////////////
        $games_packages_trade->updated_at = date('YmdHis');
        $games_packages_trade->created_at = date('YmdHis');
        if (!$games_packages_trade->save()) {
            return back()->with('error', __('admin.hata-2'));
        } else {
            LogCall(Auth::user()->id, '4', "Kullanıcı " . $request->title . " isimli trade başlığını düzenledi.");
            return back()->with('success', __('admin.basarili'));
        }
    }

    public function oyun_paket_trade_toplu_edit(Request $request)
    {
        foreach (\App\Models\GamesPackagesTrade::where('games_titles', $request->games_titles)->get() as $item) {
            $nameAlis = "alisT_" . $item->id;
            $nameSatis = "satisT_" . $item->id;
            DB::table('games_packages_trade')->where('id', $item->id)->update(['alis_fiyat' => $request->$nameAlis, 'satis_fiyat' => $request->$nameSatis]);
        }
        LogCall(Auth::user()->id, '4', "Kullanıcı " . $request->games_titles . " id'li trade başlığı için toplu güncelleme yaptı.");
        return back()->with('success', __('admin.basarili'));
    }

    public function oyun_paket_trade_toplu_stok_ekle(Request $request)
    {
        foreach (\App\Models\GamesPackagesTrade::where('games_titles', $request->games_titles)->get() as $item) {
            $eklenecek = "eklenecek_" . $item->id;
            $neden = "eklemeNedeni_" . $item->id;
            DB::table('games_packages_trade')->where('id', $item->id)->update(['stok' => $item->stok + $request->$eklenecek]);
            if ($request->$eklenecek > 0) {
                LogCall(Auth::user()->id, '4', "Kullanıcı " . $item->title . "  paketinin " . $item->stok . " olan stoğuna " . $request->$eklenecek . " adet ekleme yaparak " . $item->stok + $request->$eklenecek . " adet yaptı. Ekleme nedeni : " . $request->$neden);
            }
        }
        return back()->with('success', __('admin.basarili'));
    }

    public function oyun_paket_trade_toplu_stok_cikar(Request $request)
    {
        foreach (\App\Models\GamesPackagesTrade::where('games_titles', $request->games_titles)->get() as $item) {
            $eklenecek = "cikartilacak_" . $item->id;
            $neden = "cikartmaNedeni_" . $item->id;
            DB::table('games_packages_trade')->where('id', $item->id)->update(['stok' => $item->stok - $request->$eklenecek]);
            if ($request->$eklenecek > 0) {
                LogCall(Auth::user()->id, '4', "Kullanıcı " . $item->title . "  paketinin " . $item->stok . " olan stoğundan " . $request->$eklenecek . " adet çıkartma yaparak " . $item->stok - $request->$eklenecek . " adet yaptı. Çıkartma nedeni : " . $request->$neden);
            }
        }
        return back()->with('success', __('admin.basarili'));
    }

    public function toplu_paket()
    {
        return view('back.pages.toplu_paket.index');
    }

    public function toplu_paket_edit(Request $request)
    {
        $sorgu = DB::table('games_packages')
            ->select('games_packages.*')
            ->join('games_titles', 'games_packages.games_titles', '=', 'games_titles.id')
            ->whereNull('games_titles.deleted_at')
            ->whereNull('games_packages.deleted_at')
            ->get();
        foreach ($sorgu as $pk) {
            $fiyat = "price_" . $pk->id;
            $discount_type = "discount_type_" . $pk->id;
            $discount_date = "discount_date_" . $pk->id;
            $discount_date_time = "discount_date_time_" . $pk->id;
            $discount_amount = "discount_amount_" . $pk->id;
            if (isset($request->$fiyat)) {
                if ($request->$discount_type != 0) {
                    $date = str_replace(array('-', ':'), "", ($request->$discount_date . $request->$discount_date_time . "00"));
                } else {
                    $date = date('YmdHis');
                }
                DB::table('games_packages')->where('id', $pk->id)->update([
                    'price' => $request->$fiyat,
                    'discount_type' => $request->$discount_type,
                    'discount_amount' => $request->$discount_amount,
                    'discount_date' => $request->$discount_date,
                ]);
            }
        }
        LogCall(Auth::user()->id, '4', "Kullanıcı toplu paket güncellemesi yaptı.");
        return back()->with('success', __('admin.basarili'));
    }

    public function toplu_oyun_parasi()
    {
        return view('back.pages.toplu_oyun_parasi.index');
    }

    public function toplu_oyun_parasi_edit(Request $request)
    {
        $sorgu = DB::table('games_packages_trade')
            ->select('games_packages_trade.*', 'games_titles.id as gameId', 'games_titles.title as gameTitle', 'games_titles.epin as epin')
            ->join('games_titles', 'games_packages_trade.games_titles', '=', 'games_titles.id')
            ->join('games', 'games_titles.game', '=', 'games.id')
            ->orderBy('games_titles.id', 'asc')
            ->whereNull('games_titles.deleted_at')
            ->whereNull('games_packages_trade.deleted_at')
            ->whereNull('games.deleted_at')
            ->get();
        foreach ($sorgu as $pk) {
            $alisT = "alisT_" . $pk->id;
            $satisT = "satisT_" . $pk->id;
            $alis_stok = "alis_stok_" . $pk->id;
            if (isset($request->$alisT) and isset($request->$satisT)) {
                DB::table('games_packages_trade')->where('id', $pk->id)->update(['alis_fiyat' => $request->$alisT, 'satis_fiyat' => $request->$satisT, 'alis_stok' => $request->$alis_stok]);
            }
        }
        LogCall(Auth::user()->id, '4', "Kullanıcı toplu oyun parası güncellemesi yaptı.");
        return back()->with('success', __('admin.basarili'));
    }

    public function toplu_stok()
    {
        return view('back.pages.toplu_stok.index');
    }

    public function oyun_paket_kod_add(Request $request)
    {
        $lines = explode("\n", $request->code);
        foreach ($lines as $line) {
            DB::table('games_packages_codes')->insert([
                'package_id' => $request->games_titles_package,
                'code' => \epin::ENC(trim($line)),
                'alis_fiyati' => $request->alis_fiyati,
                'kdv' => $request->kdv,
                'tedarikci' => $request->tedarikci,
                'created_at' => date('Ymdhis'),
            ]);
        }
        LogCall(Auth::user()->id, '4', "Kullanıcı " . $request->games_titles_package . " id'li paket için yeni kodlar ekledi.");
        return back()->with('success', __('admin.basarili'));
    }

    public function oyun_paket_kod_view($paket)
    {
        return view('back.pages.games.views.kod')->with('paket', $paket);
    }

    public function oyun_paket_kod_edit($paket)
    {
        return view('back.pages.games.views.kod-edit')->with('paket', $paket);
    }

    public function oyun_paket_kod_edit_post(Request $request)
    {
        $sorgu = DB::table('games_packages_codes')->where('package_id', $request->paketId)->get();
        foreach ($sorgu as $pk) {
            $fiyat = "alis_fiyati_" . $pk->id;
            $kdv = "kdv_" . $pk->id;
            $tedarikci = "tedarikci_" . $pk->id;
            if (isset($request->$fiyat)) {
                DB::table('games_packages_codes')->where('id', $pk->id)->update([
                    'alis_fiyati' => $request->$fiyat,
                    'kdv' => $request->$kdv,
                    'tedarikci' => $request->$tedarikci,
                ]);
            }
        }
        LogCall(Auth::user()->id, '4', "Kullanıcı toplu kod güncellemesi yaptı.");
        return back()->with('success', __('admin.basarili'));
    }

    public function oyun_paket_kod_edit_excel_post(Request $request)
    {
        if ($xlsx = SimpleXLSX::parse($request->excelDosyasi)) {
            $i = 0;
            // ÖNEMLİ NOT : SATIR HER ZAMAN 3. İNDİSTE BAŞLAR
            //İLK 2 İNDİS BİLGİ İNDİSİDİR
            foreach ($xlsx->rows() as $a) {
                if($i > 1) { //bilgi satırlarını atlayalım
                    $id = $a[0];
                    $alis_fiyati = $a[3];
                    $kdv = $a[4];
                    $tedarikci = $a[5];
                    if(!is_numeric($alis_fiyati)) {
                        $alis_fiyati = NULL;
                    }
                    if(!is_numeric($kdv)) {
                        $kdv = NULL;
                    }
                    if(is_string($tedarikci)) {
                        $tedarikciVT = DB::table('games_packages_codes_suppliers')->where('title', $tedarikci);
                        if ($tedarikciVT->count() > 0) {
                            $tedarikci = $tedarikciVT->first()->id;
                        } else {
                            $tedarikci = 0;
                        }
                    }
                    DB::table('games_packages_codes')->where('id', $id)->update([
                        'alis_fiyati' => $alis_fiyati,
                        'kdv' => $kdv,
                        'tedarikci' => $tedarikci,
                    ]);
                }
                $i++;
            }
            LogCall(Auth::user()->id, '4', "Kullanıcı toplu kod güncellemesini excel yükleyerek yaptı.");
            return back()->with('success', __('admin.basarili'));
        } else {
            LogCall(Auth::user()->id, '4', "Kullanıcı toplu kod güncellemesini excel yükleyerek yapmak isterken şu hatayı aldı:.".SimpleXLSX::parseError());
            return back()->with('error', __('admin.basarisiz'));
        }
        LogCall(Auth::user()->id, '4', "Kullanıcı toplu kod güncellemesini excel yükleyerek yapmak isterken hata aldı.");
        return back()->with('error', __('admin.basarisiz'));
    }

    public function oyun_market_ozellik_add(Request $request)
    {
        $value = json_encode(explode("\n", str_replace("\r", "", $request->value)));
        DB::table('games_titles_features')->insert([
            'game_title' => $request->game_title,
            'title' => $request->title,
            'type' => $request->type,
            'value' => $value,
            'updated_at' => date('YmdHis'),
            'created_at' => date('YmdHis'),
        ]);
        LogCall(Auth::user()->id, '4', "Kullanıcı " . $request->title . " isimli yeni bir özellik ekledi.");
        return back()->with('success', __('admin.basarili'));

    }

    public function oyun_market_ozellik_edit(Request $request)
    {
        $value = json_encode(explode("\n", str_replace("\r", "", $request->value)));
        DB::table('games_titles_features')->where('id', $request->id)->update([
            'title' => $request->title,
            'type' => $request->type,
            'value' => $value,
            'updated_at' => date('YmdHis'),
        ]);
        LogCall(Auth::user()->id, '4', "Kullanıcı " . $request->title . " isimli özelliği düzenledi.");
        return back()->with('success', __('admin.basarili'));
    }

    public function oyun_market_item_add(Request $request)
    {

        DB::table('games_titles_items_info')->insert([
            'game_title' => $request->game_title,
            'title' => $request->title,
            'description' => $request->description,
            'updated_at' => date('YmdHis'),
            'created_at' => date('YmdHis'),
        ]);
        $lastId = DB::getPdo()->lastInsertId();
        foreach (DB::table('games_titles_features')->whereNull('deleted_at')->where('game_title', $request->game_title)->get() as $f) {
            if ($f->type == 1) {
                $find = "feature_" . $f->id;
                DB::table('games_titles_items')->insert([
                    'item' => $lastId,
                    'feature' => $f->id,
                    'value' => $request->$find,
                    'created_at' => date('YmdHis'),
                ]);
            } elseif ($f->type == 2) {
                $find = "feature_" . $f->id;
                foreach ($request->$find as $item) {
                    DB::table('games_titles_items')->insert([
                        'item' => $lastId,
                        'feature' => $f->id,
                        'value' => $item,
                        'created_at' => date('YmdHis'),
                    ]);
                }
            }

        }
        $title = Str::slug($request->title);
        if ($request->hasFile('image')) {
            $destinationPath = env("ROOT") . env("FRONT") . env("GAMES_ITEMS");
            $file = $request->image;
            $fileName = $title . "-" . time() . '.' . $file->clientExtension();
            $file->move($destinationPath, $fileName);
            $extansion = $file->clientExtension();

            /*
             * Placeholder
             */
            $damgaLogo = 'brand/watermark.png';
            $damga = imagecreatefrompng('brand/watermark.png');
            imagealphablending($damga, false);
            imagesavealpha($damga, true);
            if ($extansion == "png") {
                $foto = imagecreatefrompng('front/games_items/' . $fileName);
            } else {
                $foto = imagecreatefromjpeg('front/games_items/' . $fileName);
            }
            $imageWidth = imagesx($foto);
            $imageHeight = imagesy($foto);

            $pngTransparency = imagecolorallocatealpha($damga, 0, 0, 0, 127);
            $damgaRotate = imagerotate($damga, 30, $pngTransparency, 0);
            imagealphablending($damgaRotate, false);
            imagesavealpha($damgaRotate, true);


            $logoWidth = imagesx($damgaRotate);
            $logoHeight = imagesy($damgaRotate);
            $logoOran = $logoWidth / $logoHeight;


            $damgaNew = imagecreatetruecolor($imageWidth, $imageWidth / $logoOran);
            imagealphablending($damgaNew, false);
            imagesavealpha($damgaNew, true);
            imagecopyresampled($damgaNew, $damgaRotate, 0, 0, 0, 0, $imageWidth, $imageWidth / $logoOran, $logoWidth, $logoHeight);


            $dst_x = ($imageWidth / 2) - ($imageWidth / 2);
            $dst_y = ($imageHeight / 2) - ($imageWidth / $logoOran / 2);
            imagecopy($foto, $damgaNew, $dst_x, $dst_y, 0, 0, $imageWidth, $imageWidth / $logoOran);
            imagepng($foto, $destinationPath . $fileName);


            DB::table('games_titles_items_photos')->insert([
                'item' => $lastId,
                'image' => $fileName,
                'created_at' => date('YmdHis'),
            ]);
        }
        LogCall(Auth::user()->id, '4', "Kullanıcı " . $request->title . " isimli yeni bir hazır item ekledi.");
        return back()->with('success', __('admin.basarili'));
    }

    public function oyun_market_item_edit(Request $request)
    {
        DB::table('games_titles_items_info')->where('id', $request->item_id)->update([
            'title' => $request->title,
            'description' => $request->description,
            'updated_at' => date('YmdHis'),
        ]);
        $item = DB::table('games_titles_items_info')->where('id', $request->item_id)->first();
        foreach (DB::table('games_titles_features')->whereNull('deleted_at')->where('game_title', $item->game_title)->get() as $f) {
            if ($f->type == 1) {
                $find = "feature_" . $f->id;
                DB::table('games_titles_items')->where('item', $request->item_id)->where('feature', $f->id)->update([
                    'value' => $request->$find,
                ]);
            } elseif ($f->type == 2) {
                $find = "feature_" . $f->id;
                foreach ($request->$find as $item) {
                    DB::table('games_titles_items')->where('item', $request->item_id)->where('feature', $f->id)->update([
                        'value' => $item,
                    ]);
                }
            }

        }
        $title = Str::slug($request->title);
        if ($request->hasFile('image')) {
            $destinationPath = env("ROOT") . env("FRONT") . env("GAMES_ITEMS");
            $file = $request->image;
            $fileName = $title . "-" . time() . '.' . $file->clientExtension();
            $file->move($destinationPath, $fileName);
            $extansion = $file->clientExtension();

            /*
             * Placeholder
             */
            $damgaLogo = 'brand/watermark.png';
            $damga = imagecreatefrompng('brand/watermark.png');
            imagealphablending($damga, false);
            imagesavealpha($damga, true);
            if ($extansion == "png") {
                $foto = imagecreatefrompng('front/games_items/' . $fileName);
            } else {
                $foto = imagecreatefromjpeg('front/games_items/' . $fileName);
            }
            $imageWidth = imagesx($foto);
            $imageHeight = imagesy($foto);

            $pngTransparency = imagecolorallocatealpha($damga, 0, 0, 0, 127);
            $damgaRotate = imagerotate($damga, 30, $pngTransparency, 0);
            imagealphablending($damgaRotate, false);
            imagesavealpha($damgaRotate, true);


            $logoWidth = imagesx($damgaRotate);
            $logoHeight = imagesy($damgaRotate);
            $logoOran = $logoWidth / $logoHeight;


            $damgaNew = imagecreatetruecolor($imageWidth, $imageWidth / $logoOran);
            imagealphablending($damgaNew, false);
            imagesavealpha($damgaNew, true);
            imagecopyresampled($damgaNew, $damgaRotate, 0, 0, 0, 0, $imageWidth, $imageWidth / $logoOran, $logoWidth, $logoHeight);


            $dst_x = ($imageWidth / 2) - ($imageWidth / 2);
            $dst_y = ($imageHeight / 2) - ($imageWidth / $logoOran / 2);
            imagecopy($foto, $damgaNew, $dst_x, $dst_y, 0, 0, $imageWidth, $imageWidth / $logoOran);
            imagepng($foto, $destinationPath . $fileName);
            if (DB::table('games_titles_items_photos')->where('item', $request->item_id)->count() > 0) {
                DB::table('games_titles_items_photos')->where('item', $request->item_id)->update([
                    'image' => $fileName,
                ]);
            } else {
                DB::table('games_titles_items_photos')->insert([
                    'item' => $request->item_id,
                    'image' => $fileName,
                    'created_at' => date('YmdHis'),
                ]);
            }

        }
        LogCall(Auth::user()->id, '4', "Kullanıcı " . $request->title . " isimli hazır itemi düzenledi.");
        return back()->with('success', __('admin.basarili'));
    }

    public function detay_market($oyun, $market)
    {
        return view('back.pages.games.market')->with('oyun', $oyun)->with('market', $market);
    }

    public function detay_trade($oyun, $trade)
    {
        return view('back.pages.games.trade')->with('oyun', $oyun)->with('trade', $trade);
    }
}
