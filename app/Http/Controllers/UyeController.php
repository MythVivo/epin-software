<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UyeController extends Controller
{
    public function index()
    {
        return view('back.pages.users.index');
    }

    public function add(Request $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
    }
}
