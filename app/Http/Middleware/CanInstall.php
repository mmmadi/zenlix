<?php

namespace zenlix\Http\Middleware;

use Closure;

class CanInstall
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

        //return 'fail';

        if ($this->alreadyInstalled()) {
            return redirect('/');
        }

        return $next($request);
    }

    public function alreadyInstalled()
    {
        return file_exists(storage_path('installed'));
    }

}
