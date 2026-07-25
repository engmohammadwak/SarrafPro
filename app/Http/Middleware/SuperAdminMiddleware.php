<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || auth()->user()->role !== 'super_admin') {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
            abort(403, 'غير مصرح لك بالدخول');
        }

        if (!auth()->user()->is_active) {
            auth()->logout();
            return redirect()->route('admin.login')->with('error', 'الحساب موقوف، تواصل مع الدعم');
        }

        return $next($request);
    }
}
