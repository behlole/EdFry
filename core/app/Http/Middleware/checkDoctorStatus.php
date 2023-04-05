<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
class checkDoctorStatus
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
        if (Auth::guard('doctor')->check()) {
            $doctor = Auth::guard('doctor')->user();
            if ($doctor->status && $doctor->tv  && $doctor->sv && $doctor->ev) {
                return $next($request);
            } else {
                return redirect()->route('doctor.authorization');
            }
        }
        abort(403);
    }
}
