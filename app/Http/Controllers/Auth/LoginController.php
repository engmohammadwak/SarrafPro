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

        // تحديد طريقة التحقق: إيميل أو username
        $field = filter_var($loginValue, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (!Auth::attempt([$field => $loginValue, 'password' => $request->password], $request->remember)) {
            return back()->withErrors(['login' => 'بيانات الدخول غير صحيحة'])->withInput();
        }

        $request->session()->regenerate();
        return $this->redirectByRole(auth()->user()->role);
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    private function redirectByRole(string $role) {
        return match($role) {
            'super_admin' => redirect()->route('superadmin.dashboard'),
            'shop_admin'  => redirect()->route('admin.dashboard'),
            'agent'       => redirect()->route('agent.dashboard'),
            'staff'       => redirect()->route('admin.dashboard'),
            default       => redirect()->route('login'),
        };
    }
}
