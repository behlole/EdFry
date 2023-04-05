<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
class checkStaffStatus
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
        if (Auth::guard('staff')->check()) {
            $staff = Auth::guard('staff')->user();
            if ($staff->status && $staff->tv  && $staff->sv && $staff->ev) {
                return $next($request);
            } else {
                return redirect()->route('staff.authorization');
            }
        }
        abort(403);
    }
}
