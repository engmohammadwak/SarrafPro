<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        // Already logged in as super_admin → go to dashboard
        if (auth()->check() && auth()->user()->role === 'super_admin') {
            return redirect()->route('superadmin.dashboard');
        }
        return view('superadmin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ], [
            'email.required'    => 'البريد الإلكتروني مطلوب',
            'email.email'       => 'البريد الإلكتروني غير صحيح',
            'password.required' => 'كلمة المرور مطلوبة',
        ]);

        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials, false)) {
            return back()->withErrors([
                'email' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة',
            ])->withInput($request->only('email'));
        }

        // Check role after successful auth
        if (auth()->user()->role !== 'super_admin') {
            Auth::logout();
            return back()->withErrors([
                'email' => 'هذا الحساب ليس حساب سوبر أدمن',
            ])->withInput($request->only('email'));
        }

        $request->session()->regenerate();

        return redirect()->intended(route('superadmin.dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('superadmin.login');
    }
}
