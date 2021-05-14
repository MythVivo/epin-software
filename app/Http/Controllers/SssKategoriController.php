<?php

namespace App\Http\Controllers;

use App\Models\FaqCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class SssKategoriController extends Controller
{
    public function index()
    {
        return view('back.pages.faq.category.index');
    }

    public function add(Request $request)
    {
        $faqCategory = new FaqCategory();
        $faqCategory->title = $request->title;
        $faqCategory->status = 1;
        $faqCategory->lang = $request->lang;
        $faqCategory->created_at = date('YmdHis');
        $faqCategory->updated_at = date('YmdHis');
        if (!$faqCategory->save()) {
            return back()->with('error', __('admin.hata-2'));
        } else {
            return back()->with('success', __('admin.basarili'));
        }
    }

    public function table()
    {
        return view('back.pages.faq.category.table');
    }

    public function edit(Request $request)
    {
        $faqCategory = FaqCategory::find($request->id);
        $faqCategory->title = $request->title;
        $faqCategory->updated_at = date('YmdHis');
        if (!$faqCategory->save()) {
            return back()->with('error', __('admin.hata-2'));
        } else {
            return back()->with('success', __('admin.basarili'));
        }
    }
}
