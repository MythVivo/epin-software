<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SliderController extends Controller
{
    public function index()
    {
        return view('back.pages.slider.index');
    }

    public function add(Request $request)
    {
        $slider = new Slider();
        if ($request->hasFile('image')) {
            $destinationPath = env("root") . env("front") . env("slider");
            $file = $request->image;
            $title = Str::slug($request->title);
            $fileName = $title . "-" . time() . '.' . $file->clientExtension();
            $file->move($destinationPath, $fileName);
        } else {
            $fileName = "";
        }
        $slider->title = $request->title;
        $slider->text = $request->text;
        $slider->link = $request->link;
        $slider->image = $fileName;
        $slider->status = 1;
        $slider->lang = $request->lang;
        $slider->created_at = date('YmdHis');
        $slider->updated_at = date('YmdHis');
        if (!$slider->save()) {
            return json_encode(array("sonuc" => "0"));
        } else {
            return json_encode(Slider::latest('created_at')->first());
        }
    }

    public function table()
    {
        return view('back.pages.slider.table');
    }

    public function edit(Request $request)
    {
        $slider = Slider::find($request->id);
        if ($request->image != '') {
            $destinationPath = env("root") . env("front") . env("slider");
            $file = $request->image;
            $title = Str::slug($request->title);
            $fileName = $title . "-" . time() . '.' . $file->clientExtension();
            $file->move($destinationPath, $fileName);
            deleteImage('slider', $request->id);
            $slider->image = $fileName;
        }
        $slider->title = $request->title;
        $slider->text = $request->text;
        $slider->link = $request->link;
        $slider->updated_at = date('YmdHis');
        if (!$slider->save()) {
            return json_encode(array("sonuc" => "0"));
        } else {
            return json_encode(Slider::find($request->id));
        }
    }
}
