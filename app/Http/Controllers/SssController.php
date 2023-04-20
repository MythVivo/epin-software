<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Auth;

class SssController extends Controller
{
    public function index()
    {
        return view('back.pages.faq.index');
    }

    public function add(Request $request)
    {
        $faq = new Faq();
        $faq->title = $request->title;
        $faq->text = $request->text;
        $faq->one_cikan = $request->one_cikan;
        $faq->category = $request->category;
        $faq->status = 1;
        $faq->lang = $request->lang;
        $faq->created_at = date('YmdHis');
        $faq->updated_at = date('YmdHis');
        if (!$faq->save()) {
            return back()->with('error', __('admin.hata-2'));
        } else {
            LogCall(Auth::user()->id, '4', "Kullanıcı ".$request->title." isimli yeni bir sss ekledi.");
            return back()->with('success', __('admin.basarili'));
        }
    }

    public function table()
    {
        return view('back.pages.faq.table');
    }

    public function edit(Request $request)
    {
        $faq = Faq::find($request->id);
        $faq->title = $request->title;
        $faq->text = $request->text;
        $faq->one_cikan = $request->one_cikan;
        $faq->category = $request->category;
        $faq->updated_at = date('YmdHis');
        if (!$faq->save()) {
            return back()->with('error', __('admin.hata-2'));
        } else {
            LogCall(Auth::user()->id, '4', "Kullanıcı ".$request->title." isimli sss'i düzenledi.");
            return back()->with('success', __('admin.basarili'));
        }
    }
}
