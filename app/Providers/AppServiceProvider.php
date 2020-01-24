<?php

namespace zenlix\Providers;

use Illuminate\Support\ServiceProvider;
use zenlix\Http\Middleware\CoreCheck;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //

        $CoreCheck = new CoreCheck;

        if (method_exists($CoreCheck, 'selfCheck')) {

            if (CoreCheck::selfCheck() != "cpecialReturnCodeFromZenlixLicenseSystem") {
                die('no license file checker available!');
            }
        } else {

            die('no license file checker available!');

        }
//die('no license');

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //

    }

}
