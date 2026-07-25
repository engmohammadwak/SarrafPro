<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SuperAdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('superadmin.login');
        }

        if (auth()->user()->role !== 'super_admin') {
            auth()->logout();
            return redirect()->route('superadmin.login')
                ->withErrors(['email' => 'هذا الحساب ليس حساب سوبر أدمن']);
        }

        return $next($request);
    }
}
