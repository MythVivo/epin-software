<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class KategoriController extends Controller
{
    public function index()
    {
        return view('back.pages.category.index');
    }

    public function add(Request $request)
    {
        $title = Str::slug($request->title);
        $category = new Category();
        if ($request->hasFile('image')) {
            $destinationPath = env("root") . env("front") . env("categories");
            $file = $request->image;
            $fileName = $title . "-" . time() . '.' . $file->clientExtension();
            $file->move($destinationPath, $fileName);
            $category->image = $fileName;
        }
        $category->title = $request->title;
        $category->link = $title;
        $category->status = 1;
        $category->lang = $request->lang;
        $category->created_at = date('YmdHis');
        $category->updated_at = date('YmdHis');
        if (!$category->save()) {
            return json_encode(array("sonuc" => "0"));
        } else {
            return json_encode(Category::latest('created_at')->first());
        }
    }

    public function edit(Request $request)
    {
        $title = Str::slug($request->title);
        $category = Category::find($request->id);
        if ($request->image != '') {
            $destinationPath = env("root") . env("front") . env("categories");
            $file = $request->image;
            $fileName = $title . "-" . time() . '.' . $file->clientExtension();
            $file->move($destinationPath, $fileName);
            deleteImage('categories', $request->id);
            $category->image = $fileName;
        }
        $category->title = $request->title;
        $category->link = $title;
        $category->updated_at = date('YmdHis');
        if (!$category->save()) {
            return json_encode(array("sonuc" => "0"));
        } else {
            return json_encode(Category::find($request->id));
        }
    }
}
