<?php

namespace App\Http\Controllers;

use App\Models\Pages;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SayfaController extends Controller
{
    public function index()
    {
        return view('back.pages.static-pages.index');
    }

    public function add(Request $request)
    {
        $title = Str::slug($request->title);
        $pages = new Pages();
        $pages->title = $request->title;
        $pages->text = $request->text;
        $pages->link = $title;
        $pages->status = 1;
        $pages->lang = $request->lang;
        $pages->created_at = date('YmdHis');
        $pages->updated_at = date('YmdHis');
        if (!$pages->save()) {
            return json_encode(array("sonuc" => "0"));
        } else {
            return json_encode(Pages::latest('created_at')->first());
        }
    }

    public function table()
    {
        return view('back.pages.static-pages.table');
    }

    public function edit(Request $request)
    {
        $title = Str::slug($request->title);
        $pages = Pages::find($request->id);
        $pages->title = $request->title;
        $pages->text = $request->text;
        $pages->link = $title;
        $pages->updated_at = date('YmdHis');
        if (!$pages->save()) {
            return json_encode(array("sonuc" => "0"));
        } else {
            return json_encode(Pages::find($request->id));
        }
    }
}
