<?php

namespace App\Http\Controllers;

use App\Models\Muve;
use App\Models\GamesPackages;
use App\Models\GamesPackagesTrade;
use App\Models\GamesTitles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Auth;
use App\Helpers\simplexlsx;

class MuveController extends Controller
{
    public function index()
    {
        return view('back.pages.muve.index');
    }

    public function detail_ajax(Request $request)
    {
        return muveGetProductsDetail($request->id);
    }

    public function add(Request $request)
    {
        $title = Str::slug($request->title);
        $game = new muve();
        if ($request->steamId > 0) {
            $game->image = $request->imageText;
            $game->background = $request->arkaplanText;
        } else {
            if ($request->hasFile('image')) {
                $destinationPath = env("ROOT") . env("FRONT") . env("GAMES");
                $file = $request->image;
                $fileName = $title . "-" . time() . '.' . $file->clientExtension();
                $fileOriginalName = $title . "-" . time();
                $file->move($destinationPath, $fileName);
                $game->image = $fileName;
            }
            if ($request->hasFile('arkaplan')) {
                $destinationPath = env("ROOT") . env("FRONT") . env("GAMES");
                $file = $request->arkaplan;
                $fileName = $title . "-arkaplan-" . time() . '.' . $file->clientExtension();
                $fileOriginalName = $title . "-arkaplan-" . time();
                $file->move($destinationPath, $fileName);
                $game->background = $fileName;
            }
        }

        if ($request->windowsSup != 1) {
            $request->windowsSup = 0;
        }
        if ($request->macSup != 1) {
            $request->macSup = 0;
        }
        if ($request->linuxSup != 1) {
            $request->linuxSup = 0;
        }
        $game->muveId = $request->muveId;
        $game->muveCode = $request->muveCode;
        $game->muveCountry = $request->country;
        $game->muveCurrency = $request->currency;
        $game->muvePrice = $request->fiyat;
        $game->title = $request->title;
        $game->steamId = $request->steamId;
        $game->supLang = $request->desteklenenDiller;
        $game->winGer = $request->windowsGer;
        $game->macGer = $request->macGer;
        $game->linuxGer = $request->linuxGer;
        $game->winSup = $request->windowsSup;
        $game->macSup = $request->macSup;
        $game->linuxSup = $request->linuxSup;
        $game->developers = $request->gelistiriciler;
        $game->categories = $request->kategoriler;
        $game->metaScore = $request->metaScor;
        $game->metaLink = $request->metaLink;
        $game->releaseDate = $request->yayinTarihi;
        $game->sira = $request->sira;
        $game->shortDesc = $request->shortDesc;
        $game->description = $request->text;
        $game->images = $request->galeri;
        $game->videos = $request->galeriVideo;
        $game->link = $title;
        $game->lang = 'tr';
        $game->status = '1';
        $game->created_at = date('YmdHis');
        $game->updated_at = date('YmdHis');
        $game->alis=$request->alis;

        if (!$game->save()) {
            return back()->with('error', __('admin.hata-2'));
        } else {
            LogCall(Auth::user()->id, '4', "Kullanıcı " . $request->title . " isimli yeni bir muve oyunu ekledi.");
            return back()->with('success', __('admin.basarili'));
        }
    }

    public function edit(Request $request)
    {
        $title = Str::slug($request->title);
        $game = muve::find($request->id);

        if ($request->hasFile('image')) {
            $destinationPath = env("ROOT") . env("FRONT") . env("GAMES");
            $file = $request->image;
            $fileName = $title . "-" . time() . '.' . $file->clientExtension();
            $fileOriginalName = $title . "-" . time();
            $file->move($destinationPath, $fileName);
            if($request->steamId > 0) {
                $fileName = env("APP_URL").env("FRONT").env("GAMES").$fileName;
            }
            $game->image = $fileName;
        }
        if ($request->hasFile('arkaplan')) {
            $destinationPath = env("ROOT") . env("FRONT") . env("GAMES");
            $file = $request->arkaplan;
            $fileName = $title . "-arkaplan-" . time() . '.' . $file->clientExtension();
            $fileOriginalName = $title . "-arkaplan-" . time();
            $file->move($destinationPath, $fileName);
            if($request->steamId > 0) {
                $fileName = env("APP_URL").env("FRONT").env("GAMES").$fileName;
            }
            $game->background = $fileName;
        }

        if ($request->windowsSup != 1) {
            $request->windowsSup = 0;
        }
        if ($request->macSup != 1) {
            $request->macSup = 0;
        }
        if ($request->linuxSup != 1) {
            $request->linuxSup = 0;
        }
        if ($request->discount_type != 0) {
            $date = str_replace(array('-', ':'), "", ($request->discount_date . $request->discount_date_time . "00"));
            $game->discount_amount = $request->discount_amount;
            $game->discount_date = $date;
        }
        $game->muveCurrency = $request->muveCurrency;
        $game->discount_type = $request->discount_type;
        $game->muvePrice = $request->fiyat;
        $game->title = $request->title;
        $game->supLang = $request->desteklenenDiller;
        $game->winGer = $request->windowsGer;
        $game->macGer = $request->macGer;
        $game->linuxGer = $request->linuxGer;
        $game->winSup = $request->windowsSup;
        $game->macSup = $request->macSup;
        $game->linuxSup = $request->linuxSup;
        $game->developers = $request->gelistiriciler;
        $game->categories = $request->kategoriler;
        $game->metaScore = $request->metaScor;
        $game->metaLink = $request->metaLink;
        $game->releaseDate = $request->yayinTarihi;
        $game->sira = $request->sira;
        $game->shortDesc = $request->shortDesc;
        $game->description = $request->text;
        $game->images = $request->galeri;
        $game->videos = $request->galeriVideo;
        $game->link = $title;
        $game->lang = 'tr';
        $game->updated_at = date('YmdHis');
        if (!$game->save()) {
            return back()->with('error', __('admin.hata-2'));
        } else {
            LogCall(Auth::user()->id, '4', "Kullanıcı " . $request->title . " isimli muve oyununu düzenledi.");
            return back()->with('success', __('admin.basarili'));
        }
    }

    public function muve_satislar()
    {
        return view('back.pages.muve.satislar');
    }

    public function muve_kategoriler()
    {
        return view('back.pages.muve.categories.index');
    }

    public function muve_kategori_add(Request $request)
    {
        $title = Str::slug($request->title);
        DB::table('muve_games_categories')->insert([
            'muve_id' => $request->muveId,
            'parent_id' => $request->parent_id,
            'title' => $request->title,
            'updated_at' => date('YmdHis'),
            'created_at' => date('YmdHis'),
        ]);
        $lastId = DB::getPdo()->lastInsertId();
        if ($request->hasFile('image')) {
            $destinationPath = env("ROOT") . env("FRONT") . env("CATEGORIES");
            $file = $request->image;
            $fileName = $title . "-" . time() . '.' . $file->clientExtension();
            $file->move($destinationPath, $fileName);
            DB::table('muve_games_categories')->where('id', $lastId)->update([
                'image' => $fileName,
            ]);
        }
        return back()->with('success', __('admin.basarili'));
    }

    public function muve_kategori_edit(Request $request)
    {
        $title = Str::slug($request->title);
        DB::table('muve_games_categories')->where('id', $request->id)->update([
            'parent_id' => $request->parent_id,
            'title' => $request->title,
            'updated_at' => date('YmdHis'),
        ]);
        dd($_FILES);
        die();
        if ($request->hasFile('image')) {
            $destinationPath = env("ROOT") . env("FRONT") . env("CATEGORIES");
            $file = $request->image;
            $fileName = $title . "-" . time() . '.' . $file->clientExtension();
            deleteImage('muve_games_categories', $request->id);
            $file->move($destinationPath, $fileName);
            DB::table('muve_games_categories')->where('id', $request->id)->update([
                'image' => $fileName,
            ]);
        }
        return back()->with('success', __('admin.basarili'));
    }

    public function currencyConverter(Request $request)
    {
        return currencyConverter($request->price, $request->currency, 'TRY');
    }
}
