<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class YorumController extends Controller
{
    public function index()
    {
        return view('back.pages.comments.index');
    }
}
