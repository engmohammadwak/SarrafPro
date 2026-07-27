<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;

class AdminMiddleware {
    public function handle(Request $request, Closure $next) {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        if (!in_array(auth()->user()->role, ['admin', 'shop_admin', 'staff'])) {
            auth()->logout();
            return redirect()->route('login')
                ->withErrors(['email' => 'هذا الحساب ليس حساب مدير محل.']);
        }
        return $next($request);
    }
}
