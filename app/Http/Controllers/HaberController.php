<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class HaberController extends Controller
{
    public function index()
    {
        return view('back.pages.haber.index');
    }

    public function add(Request $request)
    {
        $title = Str::slug($request->title);
        $news = new News();
        if ($request->hasFile('image')) {
            $destinationPath = env("ROOT") . env("FRONT") . env("NEWS");
            $file = $request->image;
            $fileName = $title . "-" . time() . '.' . $file->clientExtension();
            $title = $title . "-" . time();
            $file->move($destinationPath, $fileName);
            $news->image = $fileName;
            imageResize($file->clientExtension(), $destinationPath, $fileName, $title, '0.5', '100');
        }
        $news->title = $request->title;
        $news->text = $request->text;
        $news->text_short = $request->text_short;
        $news->link = $title;
        $news->status = 1;
        $news->lang = $request->lang;
        $news->created_at = date('YmdHis');
        $news->updated_at = date('YmdHis');
        if (!$news->save()) {
            return json_encode(array("sonuc" => "0"));
        } else {
            LogCall(Auth::user()->id, '4', "Kullanıcı ".$request->title." isimli yeni bir haber ekledi.");
            return json_encode(News::latest('created_at')->first());
        }
    }

    public function table()
    {
        return view('back.pages.haber.table');
    }

    public function edit(Request $request)
    {
        $title = Str::slug($request->title);
        $news = News::find($request->id);
        if ($request->image != '') {
            $destinationPath = env("ROOT") . env("FRONT") . env("NEWS");
            $file = $request->image;
            $fileName = $title . "-" . time() . '.' . $file->clientExtension();
            $titleFileName = $title . "-" . time();
            $file->move($destinationPath, $fileName);
            deleteImage('news', $request->id);
            imageResize($file->clientExtension(), $destinationPath, $fileName, $titleFileName, '0.5', '100');
            $news->image = $fileName;
        }
        $news->title = $request->title;
        $news->text = $request->text;
        $news->text_short = $request->text_short;
        $news->link = $title;
        $news->updated_at = date('YmdHis');
        if (!$news->save()) {
            return json_encode(array("sonuc" => "0"));
        } else {
            LogCall(Auth::user()->id, '4', "Kullanıcı ".$request->title." isimli haberi düzenledi.");
            return json_encode(News::find($request->id));
        }
    }
}
