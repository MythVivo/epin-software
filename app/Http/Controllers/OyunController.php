<?php

namespace App\Http\Controllers;

use App\Models\Games;
use App\Models\GamesPackages;
use App\Models\GamesTitles;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
            $destinationPath = env("root") . env("front") . env("games");
            $file = $request->image;
            $fileName = $title . "-" . time() . '.' . $file->clientExtension();
            $fileOriginalName = $title . "-" . time();
            $file->move($destinationPath, $fileName);
            $game->image = $fileName;
            imageResize($file->clientExtension(), $destinationPath, $fileName, $fileOriginalName, '0.5', '100');
        }
        $game->title = $request->title;
        $game->text = $request->text;
        $game->category = $request->category;
        $game->link = $title;
        $game->status = 1;
        $game->lang = $request->lang;
        $game->created_at = date('YmdHis');
        $game->updated_at = date('YmdHis');
        if (!$game->save()) {
            return back()->with('error', __('admin.hata-2'));
        } else {
            return back()->with('success', __('admin.basarili'));
        }
    }

    public function baslik_add(Request $request)
    {
        $title = Str::slug($request->title);
        $gameTitle = new gamestitles();
        if ($request->hasFile('image')) {
            $destinationPath = env("root") . env("front") . env("games_titles");
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
            return back()->with('success', __('admin.basarili'));
        }
    }

    public function edit(Request $request)
    {
        $title = Str::slug($request->title);
        $game = Games::find($request->id);
        if ($request->hasFile('image')) {
            $destinationPath = env("root") . env("front") . env("games");
            $file = $request->image;
            $fileName = $title . "-" . time() . '.' . $file->clientExtension();
            $fileOriginalName = $title . "-" . time();
            deleteImage('games', $request->id);
            $file->move($destinationPath, $fileName);
            $game->image = $fileName;
            imageResize($file->clientExtension(), $destinationPath, $fileName, $fileOriginalName, '0.5', '80');
        }
        $game->title = $request->title;
        $game->text = $request->text;
        $game->category = $request->category;
        $game->link = $title;
        $game->updated_at = date('YmdHis');
        if (!$game->save()) {
            return back()->with('error', __('admin.hata-2'));
        } else {
            return back()->with('success', __('admin.basarili'));
        }
    }

    public function detay($link = null)
    {
        return view('back.pages.games.detay')->with('link', $link);
    }

    public function oyun_paket_add(Request $request)
    {
        $title = Str::slug($request->title);
        $games_packages = new GamesPackages();
        if ($request->hasFile('image')) {
            $destinationPath = env("root") . env("front") . env("games_packages");
            $file = $request->image;
            $fileName = $title . "-" . time() . '.' . $file->clientExtension();
            $fileOriginalName = $title . "-" . time();
            $file->move($destinationPath, $fileName);
            $games_packages->image = $fileName;
        }
        $games_packages->games_titles = $request->games_titles;
        $games_packages->title = $request->title;
        $games_packages->text = $request->text;
        $games_packages->price = $request->price;
        $games_packages->discount_type = $request->discount_type;
        $games_packages->discount_amount = $request->discount_amount;
        $games_packages->updated_at = date('YmdHis');
        $games_packages->created_at = date('YmdHis');
        if (!$games_packages->save()) {
            return back()->with('error', __('admin.hata-2'));
        } else {
            return back()->with('success', __('admin.basarili'));
        }
    }
}
