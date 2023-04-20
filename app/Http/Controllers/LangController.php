<?php

namespace App\Http\Controllers;

use App\Models\Language;
use Illuminate\Http\Request;

class LangController extends Controller
{
    public function lang($lang)
    {
        if(Language::where('lang', $lang)->count() > 0) {
            setLang($lang);
        }
        return back();
    }
}
