<?php

namespace zenlix\Http\Middleware;

use Auth;
use Closure;
use zenlix\User;

class RoleUser
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
        $user = Auth::user();
//dd($user->roles->role);

        if ($user->roles->role == "user") {
            return $next($request);
        }

        return redirect('/');
    }
}
