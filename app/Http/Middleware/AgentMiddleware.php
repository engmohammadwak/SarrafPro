<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;

class AgentMiddleware {
    public function handle(Request $request, Closure $next) {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        $role = auth()->user()->role;
        if (!in_array($role, ['agent', 'cooperation'])) {
            auth()->logout();
            return redirect()->route('login')
                ->withErrors(['email' => 'هذا الحساب ليس حساب مندوب أو تعاون.']);
        }
        return $next($request);
    }
}
