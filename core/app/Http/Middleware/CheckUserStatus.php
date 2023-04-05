<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class CheckUserStatus
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::guard('user')->check()) {
            $assistant = Auth::guard('user')->user();
            if ($assistant->status && $assistant->tv && $assistant->ev) {
                return $next($request);
            } else {
                return redirect()->route('user.authorization');
            }
        }

         app('redirect')->setIntendedUrl($request->getRequestUri());
        return redirect()->route('login');
    }
}
