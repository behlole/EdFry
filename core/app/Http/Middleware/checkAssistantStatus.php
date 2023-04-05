<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
class checkAssistantStatus
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
        if (Auth::guard('assistant')->check()) {
            $assistant = Auth::guard('assistant')->user();
            if ($assistant->status && $assistant->tv  && $assistant->sv && $assistant->ev) {
                return $next($request);
            } else {
                return redirect()->route('assistant.authorization');
            }
        }
        abort(403);
    }
}
