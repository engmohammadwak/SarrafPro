<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;

class AgentMiddleware {
    public function handle(Request $request, Closure $next) {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        if (auth()->user()->role !== 'agent') {
            auth()->logout();
            return redirect()->route('login')
                ->withErrors(['email' => 'هذا الحساب ليس حساب مندوب.']);
        }
        return $next($request);
    }
}
