<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckSuperAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Auth::check() || Auth::user()->user_role != 'Administrator') {
            if (Auth::user()->user_role =='Teacher') {
                return redirect()->route('admin.dashboard');
            } elseif (Auth::user()->user_role =='Student') {
                return redirect()->route('editor.dashboard');
            } elseif (Auth::user()->user_role =='Parent') {
                return redirect()->route('member.dashboard');
            }

        }

        return $next($request);
    }
}
