<?php
namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // ========== STEP 1: show identifier form ==========
    public function showLogin()
    {
        if (auth()->check() && auth()->user()->role === 'super_admin') {
            return redirect()->route('superadmin.dashboard');
        }
        return view('superadmin.auth.login');
    }

    // ========== STEP 1 POST: check identifier → redirect to step 2 ==========
    public function checkIdentifier(Request $request)
    {
        $request->validate([
            'identifier' => 'required|string',
        ], ['identifier.required' => 'البريد أو اسم المستخدم مطلوب']);

        $identifier = trim($request->identifier);

        $user = User::where('email', $identifier)
                    ->orWhere('username', $identifier)
                    ->first();

        if (!$user || $user->role !== 'super_admin') {
            return back()->withErrors(['identifier' => 'لا يوجد حساب بهذا البريد أو اسم المستخدم'])
                         ->withInput();
        }

        // Store identifier in session for step 2
        session(['login_identifier' => $identifier, 'login_user_id' => $user->id]);

        return redirect()->route('superadmin.login.method', [
            'has_pin' => $user->hasPin() ? '1' : '0',
        ]);
    }

    // ========== STEP 2: show password/pin choice ==========
    public function showMethod(Request $request)
    {
        if (!session('login_user_id')) {
            return redirect()->route('superadmin.login');
        }
        $hasPIN = $request->query('has_pin', '0') === '1';
        return view('superadmin.auth.login-method', compact('hasPIN'));
    }

    // ========== STEP 2 POST: authenticate with password or pin ==========
    public function login(Request $request)
    {
        $userId = session('login_user_id');
        if (!$userId) {
            return redirect()->route('superadmin.login');
        }

        $method = $request->input('method', 'password');
        $user   = User::findOrFail($userId);

        if ($method === 'pin') {
            $request->validate(['pin' => 'required|digits:6'], [
                'pin.required' => 'رمز PIN مطلوب',
                'pin.digits'   => 'رمز PIN يجب أن يكون 6 أرقام',
            ]);
            if (!$user->pin_code || !Hash::check($request->pin, $user->pin_code)) {
                return back()->withErrors(['pin' => 'رمز PIN غير صحيح']);
            }
        } else {
            $request->validate(['password' => 'required'], [
                'password.required' => 'كلمة المرور مطلوبة',
            ]);
            if (!Hash::check($request->password, $user->password)) {
                return back()->withErrors(['password' => 'كلمة المرور غير صحيحة']);
            }
        }

        Auth::login($user);
        session()->forget(['login_identifier','login_user_id']);
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

    // ========== PIN Settings ==========
    public function showPinSettings()
    {
        return view('superadmin.auth.pin-settings');
    }

    public function savePIN(Request $request)
    {
        $request->validate([
            'pin'     => 'required|digits:6|confirmed',
            'pin_confirmation' => 'required',
        ], [
            'pin.required'      => 'رمز PIN مطلوب',
            'pin.digits'        => 'يجب أن يكون الرمز 6 أرقام بالضبط',
            'pin.confirmed'     => 'تأكيد الرمز غير متطابق',
        ]);

        auth()->user()->update(['pin_code' => bcrypt($request->pin)]);
        return back()->with('success', 'تمّ حفظ رمز PIN بنجاح');
    }

    public function removePIN(Request $request)
    {
        auth()->user()->update(['pin_code' => null]);
        return back()->with('success', 'تمّ حذف رمز PIN');
    }
}
