<?php
namespace App\Http\Controllers\Agent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller {
    public function showLogin() {
        if (auth()->check() && auth()->user()->role === 'agent')
            return redirect()->route('agent.dashboard');
        return view('agent.auth.login');
    }

    public function login(Request $request) {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'role' => 'agent'], $request->remember)) {
            $request->session()->regenerate();
            return redirect()->route('agent.dashboard');
        }
        return back()->withErrors(['email' => 'البيانات غير صحيحة أو ليس لديك صلاحية الوصول.'])->withInput();
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('agent.login');
    }
}
