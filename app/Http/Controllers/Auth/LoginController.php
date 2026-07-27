<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller {

    public function showLogin() {
        if (auth()->check()) return $this->redirectByRole(auth()->user()->role);
        return view('auth.login');
    }

    public function login(Request $request) {
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required',
        ], [
            'login.required'    => 'البريد أو اسم المستخدم مطلوب',
            'password.required' => 'كلمة المرور مطلوبة',
        ]);

        $loginValue = $request->login;
        $field = filter_var($loginValue, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (!Auth::attempt([$field => $loginValue, 'password' => $request->password], $request->remember)) {
            return back()->withErrors(['login' => 'بيانات الدخول غير صحيحة'])->withInput();
        }

        // تحقق من حالة الحساب
        $user = auth()->user();
        if (isset($user->status) && $user->status === 'suspended') {
            Auth::logout();
            $request->session()->invalidate();
            return back()->withErrors(['login' => 'تم تعليق هذا الحساب'])->withInput();
        }

        $request->session()->regenerate();
        return $this->redirectByRole($user->role);
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    private function redirectByRole(string $role) {
        return match($role) {
            'super_admin'            => redirect()->route('superadmin.dashboard'),
            'admin', 'shop_admin', 'staff' => redirect()->route('admin.dashboard'),
            'agent', 'cooperation'   => redirect()->route('agent.dashboard'),
            default                  => redirect('/')->with('error', 'صلاحياتك غير محددة بعد'),
        };
    }
}
