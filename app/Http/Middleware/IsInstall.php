<?php

namespace zenlix\Http\Middleware;

use Closure;

class IsInstall
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
        if ($this->alreadyInstalled()) {
            return $next($request);
        }
        return redirect('install');

    }

    public function alreadyInstalled()
    {
        return file_exists(storage_path('installed'));
    }

}
