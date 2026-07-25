<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check() && Auth::user()->role === 'super_admin') {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required'    => 'البريد الإلكتروني مطلوب',
            'email.email'       => 'صيغة البريد غير صحيحة',
            'password.required' => 'كلمة المرور مطلوبة',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();

            if ($user->role !== 'super_admin') {
                Auth::logout();
                return back()->withErrors(['email' => 'ليس لديك صلاحية الدخول']);
            }

            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors(['email' => 'الحساب موقوف']);
            }

            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['email' => 'البريد أو كلمة المرور غير صحيحة'])->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
}
