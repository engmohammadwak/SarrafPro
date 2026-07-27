<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller {

    public function showLogin() {
        if (auth()->check()) {
            return $this->redirectByRole(auth()->user()->role);
        }
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
        switch ($role) {
            case 'super_admin':
                return redirect()->route('superadmin.dashboard');
            case 'admin':
            case 'shop_admin':
            case 'staff':
                return redirect()->route('admin.dashboard');
            case 'agent':
            case 'cooperation':
                return redirect()->route('agent.dashboard');
            default:
                // دور غير معروف → أخرجه وأظهر رسالة
                Auth::logout();
                return redirect()->route('login')
                    ->withErrors(['login' => 'ليس لديك صلاحية للدخول (دور: ' . $role . ')']);
        }
    }
}
