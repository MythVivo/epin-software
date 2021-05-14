<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login()
    {
        return view('front.pages.login');
    }

    public function login_post(Request $request)
    {
        request()->validate([
            'email'    => 'required|email|exists:users',
            'password' => 'required|min:6'
        ], [
            'email.required' => 'E-posta gereklidir.',
            'email.email' => 'Lütfen geçerli bir e-posta adresi girin.',
            'email.exists' => 'Böyle bir e-posta bulunamadı.',
            'password.required' => 'Şifre gereklidir.',
            'password.min' => 'Şifreniz en az 6 karakter olmalıdır.',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            return redirect()->route('hesabim');
        } else {
            return back()->with('errors', 'E-posta veya şifreniz hatalı, lütfen tekrar deneyin')->with('email', $request->email);
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('homepage');
    }
}
