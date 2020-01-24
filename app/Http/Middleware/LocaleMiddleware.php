<?php

namespace zenlix\Http\Middleware;

use Auth;
use Closure;
use Session;

class LocaleMiddleware
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

        if (Auth::user()) {
            app()->setLocale(Auth::user()->profile->lang);
        } elseif ($locale = Session::has('locale')) {
            app()->setLocale($locale);
        }

        return $next($request);
    }
}
