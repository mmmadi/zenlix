<?php

namespace zenlix\Http\Middleware;

use Carbon\Carbon;
use Closure;
use File;
use Session;

class CoreCheck
{

    protected $except = [
        //
        'license/*',
        'install/*',
        'install',

    ];

/*
ENCODED:

CoreCheck.php
Kernel.php
AppServiceProvider.php
 */

    const DEF_KEY = "mLs5ANj372tmKnIc1PHWV98jyK1xnwpG";

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if ($this->shouldPassThroughNew($request)) {

            if ($request->is('license/error')) {
                if ($this->checkFile()) {
                    if ($this->checkLicense()) {
                        return redirect('/');
                    }

                }
            }

            return $next($request);
            //dd('true');
        } else {

            if ($this->checkFile()) {

                $licenseStatus = $this->checkLicense();

                if ($licenseStatus == false) {

                    return redirect('/license/error');

                } else {

                    if ($request->is('/license/error')) {

                        return $next($request);

                    }

                    return $next($request);
                }

            } else {
                //не найден файл лицензии
                return redirect('/license/error');
            }

        }

/*

if ($request->is('license/error') && $this->checkLicense()) {
return redirect('/');
//return 'fff'
}

if ($this->checkFile()) {

$licenseStatus=$this->checkLicense();

if ($licenseStatus == false) {

return redirect('/license/error');

}
else {

if ($request->is('/license/error')) {

return $next($request);

}

return $next($request);
}

}
else {
//не найден файл лицензии
return redirect('/license/error');
}

 */

    }

    public static function selfCheck()
    {

        return 'cpecialReturnCodeFromZenlixLicenseSystem';

    }

    public function readLicense()
    {

        return File::get(storage_path('license'));

    }

    public function showLicense()
    {

        $key = self::DEF_KEY;
        $data = $this->readLicense();

        $newDecrypter = new \Illuminate\Encryption\Encrypter($key, config('app.cipher'));

//return
        try {
            $dataRes = $newDecrypter->decrypt($data);
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        return $dataRes;

    }

    public function parseLicense()
    {

        $data = $this->showLicense();
        return json_decode($data, true);

    }

    public function storeLicenseInfo()
    {

        $data = $this->parseLicense();

        (isset($data['owner'])) ? $data['owner'] = $data['owner'] : $data['owner'] = 'Unregistered';
        (isset($data['UID'])) ? $data['UID'] = $data['UID'] : $data['UID'] = 'Unregistered';
        (isset($data['msg'])) ? $data['msg'] = $data['msg'] : $data['msg'] = 'Unregistered version';
        (isset($data['time'])) ? $data['time'] = $data['time'] : $data['time'] = Carbon::now()->timestamp;

        $sessionLicenseInfo = [
            'owner' => $data['owner'],
            'UID' => $data['UID'],
            'msg' => $data['msg'],
            'time' => $data['time'],
        ];

        Session::put('zenlix.license', $sessionLicenseInfo);

    }

    public function checkLicense()
    {

        $data = $this->parseLicense();

        $this->storeLicenseInfo();

        $timestampNow = Carbon::now();
        $timestampLicense = Carbon::createFromTimestamp($data['ts']);

        if ($data['result'] == 'false') {
            return false;
        }
        if ($timestampNow->diffInDays($timestampLicense) > 5) {
            return false;
        }

        return true;

    }

    public function checkFile()
    {

        if (File::exists(storage_path('license'))) {
            return true;
        } else {
            return false;
        }

    }

    /**
     * Determine if the request has a URI that should pass through CSRF verification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function shouldPassThroughNew($request)
    {
        foreach ($this->except as $except) {
            if ($except !== '/') {
                $except = trim($except, '/');
            }

            if ($request->is($except)) {
                return true;
            }
        }

        return false;
    }

}
