<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('admin.login');
        }

        if (auth()->user()->role !== 'admin') {
            auth()->logout();
            return redirect()->route('admin.login')
                ->withErrors(['email' => 'هذا الحساب ليس حساب مدير محل.']);
        }

        return $next($request);
    }
}
