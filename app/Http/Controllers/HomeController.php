<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function hesabim()
    {
        return view('front.pages.hesabim');
    }
}
