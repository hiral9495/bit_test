<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle($request, Closure $next, $role)
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();
        if ($user->hasRole($role)) {
            return $next($request);
        }

        return redirect('login')->with('error', 'You do not have access to this page');
    }
}
