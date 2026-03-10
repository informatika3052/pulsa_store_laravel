<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController
{
    public function login(Request $request) {
    $credentials = $request->validate([
        'email'    => 'required|email',
        'password' => 'required',
    ]);
    if (auth()->attempt($credentials, $request->remember)) {
        $request->session()->regenerate();
        return redirect()->intended(route('dashboard'));
    }
    return back()->with('error', 'Email atau password salah!');
}

public function logout(Request $request) {
    auth()->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login');
    }
}