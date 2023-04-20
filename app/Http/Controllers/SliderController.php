<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use DB;
use Auth;

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
            $destinationPath = env("ROOT") . env("FRONT") . env("SLIDER");
            $file = $request->image;
            $title = Str::slug($request->title);
            $fileName = $title . "-" . time() . '.' . $file->clientExtension();
            $file->move($destinationPath, $fileName);
        } else {
            $fileName = "";
        }
        if ($request->hasFile('image_mobile')) {
            $destinationPath = env("ROOT") . env("FRONT") . env("SLIDER");
            $file = $request->image_mobile;
            $title = Str::slug($request->title) . "-mobil";
            $fileName2 = $title . "-" . time() . '.' . $file->clientExtension();
            $file->move($destinationPath, $fileName2);
        } else {
            $fileName2 = "";
        }
        $slider->title = $request->title;
        $slider->text = $request->text;
        $slider->link = $request->link;
        $slider->image = $fileName;
        $slider->image_mobile = $fileName2;
        $slider->status = 1;
        $slider->lang = $request->lang;
        $slider->created_at = date('YmdHis');
        $slider->updated_at = date('YmdHis');
        if (!$slider->save()) {
            return json_encode(array("sonuc" => "0"));
        } else {
            LogCall(Auth::user()->id, '4', "Kullanıcı " . $request->title . " isimli yeni bir slider ekledi.");
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
            $destinationPath = env("ROOT") . env("FRONT") . env("SLIDER");
            $file = $request->image;
            $title = Str::slug($request->title);
            $fileName = $title . "-" . time() . '.' . $file->clientExtension();
            $file->move($destinationPath, $fileName);
            deleteImage('slider', $request->id);
            $slider->image = $fileName;
        }
        if ($request->image_mobile != '') {
            if ($slider->image_mobile != '') {
                deleteImageManually('front/slider/' . $slider->image_mobile);
            }
            $destinationPath = env("ROOT") . env("FRONT") . env("SLIDER");
            $file = $request->image_mobile;
            $title = Str::slug($request->title) . "-mobil";
            $fileName2 = $title . "-" . time() . '.' . $file->clientExtension();
            $file->move($destinationPath, $fileName2);
            $slider->image_mobile = $fileName2;
        }
        $slider->title = $request->title;
        $slider->text = $request->text;
        $slider->link = $request->link;
        $slider->updated_at = date('YmdHis');
        if (!$slider->save()) {
            return json_encode(array("sonuc" => "0"));
        } else {
            LogCall(Auth::user()->id, '4', "Kullanıcı " . $request->title . " isimli slider'ı düzenledi.");
            return json_encode(Slider::find($request->id));
        }
    }

    public function mini_add(Request $request)
    {
        if ($request->hasFile('image_mini_1')) {
            $destinationPath = env("ROOT") . env("FRONT") . env("SLIDER");
            $file = $request->image_mini_1;
            $title = Str::slug($request->title_mini_1) . "-mini-1";
            $fileName = $title . "-" . time() . '.' . $file->clientExtension();
            $file->move($destinationPath, $fileName);
            DB::table('slider_mini')->where('id', '1')->update([
                'image' => $fileName,
                'updated_at' => date('YmdHis'),
            ]);
        }
        if ($request->hasFile('image_mobile_mini_1')) {
            $destinationPath = env("ROOT") . env("FRONT") . env("SLIDER");
            $file = $request->image_mobile_mini_1;
            $title = Str::slug($request->title) . "-mobil-mini-1";
            $fileName2 = $title . "-" . time() . '.' . $file->clientExtension();
            $file->move($destinationPath, $fileName2);
            DB::table('slider_mini')->where('id', '1')->update([
                'image_mobile' => $fileName2,
                'updated_at' => date('YmdHis'),
            ]);
        }
        DB::table('slider_mini')->where('id', '1')->update([
            'title' => $request->title_mini_1,
            'text' => $request->text_mini_1,
            'link' => $request->link_mini_1,
            'status' => 1,
            'lang' => $request->lang,
            'created_at' => date('YmdHis'),
            'updated_at' => date('YmdHis'),
        ]);



        if ($request->hasFile('image_mini_2')) {
            $destinationPath = env("ROOT") . env("FRONT") . env("SLIDER");
            $file = $request->image_mini_2;
            $title = Str::slug($request->title_mini_2) . "-mini-2";
            $fileName = $title . "-" . time() . '.' . $file->clientExtension();
            $file->move($destinationPath, $fileName);
            DB::table('slider_mini')->where('id', '2')->update([
                'image' => $fileName,
                'updated_at' => date('YmdHis'),
            ]);
        }
        if ($request->hasFile('image_mobile_mini_2')) {
            $destinationPath = env("ROOT") . env("FRONT") . env("SLIDER");
            $file = $request->image_mobile_mini_2;
            $title = Str::slug($request->title) . "-mobil-mini-2";
            $fileName2 = $title . "-" . time() . '.' . $file->clientExtension();
            $file->move($destinationPath, $fileName2);
            DB::table('slider_mini')->where('id', '2')->update([
                'image_mobile' => $fileName2,
                'updated_at' => date('YmdHis'),
            ]);
        }
        DB::table('slider_mini')->where('id', '2')->update([
            'title' => $request->title_mini_2,
            'text' => $request->text_mini_2,
            'link' => $request->link_mini_2,
            'status' => 1,
            'lang' => $request->lang,
            'created_at' => date('YmdHis'),
            'updated_at' => date('YmdHis'),
        ]);

        LogCall(Auth::user()->id, '4', "Kullanıcı mini sliderları düzenledi.");
        return back()->with("success", __('general.mesaj-9'));
    }
}
