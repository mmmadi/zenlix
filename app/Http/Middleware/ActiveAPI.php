<?php

namespace zenlix\Http\Middleware;

use Closure;
use Setting;

class ActiveAPI
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


if (Setting::get('apiStatus') == 'false') {

    throw new \Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException('API is disabled!');


}


        return $next($request);
    }
}
