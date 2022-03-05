<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        $title = 'Login';
        $subtitle = 'Login - ARKA Document Manager';
        return view('login', compact('title', 'subtitle'));
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ],[
            'email.required' => 'Please fill your email.',
            'password.required' => 'Please fill your password.'
        ]);

        
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        } else {
            return back()->with('loginError', 'Login failed!');
        }

    }

    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('login');
    }
}
