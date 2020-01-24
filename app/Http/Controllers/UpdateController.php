<?php

namespace zenlix\Http\Controllers;

use Artisan;
use Illuminate\Http\Request;
use zenlix\Http\Controllers\Controller;

class UpdateController extends Controller
{

    const UPDATE_CHECK_URL = "http://update.zenlix.com/check.php";
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        return view('admin.upgrade.page');

    }

    public function showNewVersionNum()
    {

        $content_json = file_get_contents(self::UPDATE_CHECK_URL);
        $json_responce = json_decode($content_json, true);
        //dd($json_responce);
        return $json_responce['version'];
    }

    public function checkVersion()
    {

        $current_version = config('app.zenlix_version');
        $newVersion = $this->showNewVersionNum();

        //$newVersion='3.0.2';

        if ($current_version < $newVersion) {
            return true;
        } else {
            return false;
        }

    }

    public function showVersion(Request $request)
    {

        $data = [
            'res' => $this->checkVersion(),
        ];

        return response()->json([$data]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //

        Artisan::call('zenlix:update', []);
        return Artisan::output();

/*    $r= Artisan::output();

$data=['res'=>$r];*/

//queue!

/*

$contents = File::get("http://example.com/path/to/master.zip");
//https://github.com/shakyShane/laravel-backbone-boilerplate/blob/master/laravel/cli/tasks/bundle/providers/provider.php

1. check version
2. download zip
2.1 make file/DB backup
2.2 delete old backup
3. unpack zip
4. run migrations

download ZIP file

unpack only:

+zenlix/app/*
+zenlix/bootstrap/*
+zenlix/config/*
+zenlix/database/*
-zenlix/nodejs_modules/
+zenlix/nodejs/*

+zenlix/public/*
-zenlix/public/files/uploads/
-zenlix/public/files/users/

+zenlix/resources/
-zenlix/storage/

+zenlix/tests/
+zenlix/vendor/
+zenlix/.env
+zenlix/composer.json
+zenlix/composer.lock
+zenlix/package.json
+zenlix/server.php
+zenlix/phpspec.yml

zip -r $target/zenlix-update.zip app
zip -r $target/zenlix-update.zip bootstrap
zip -r $target/zenlix-update.zip config
zip -r $target/zenlix-update.zip database
zip -r $target/zenlix-update.zip nodejs/server.js
zip -r $target/zenlix-update.zip public/bootstrap
zip -r $target/zenlix-update.zip public/dist
zip -r $target/zenlix-update.zip public/installer
zip -r $target/zenlix-update.zip public/plugins
zip -r $target/zenlix-update.zip public/.htaccess
zip -r $target/zenlix-update.zip public/favicon.ico
zip -r $target/zenlix-update.zip public/index.php
zip -r $target/zenlix-update.zip public/robots.txt
zip -r $target/zenlix-update.zip resources
zip -r $target/zenlix-update.zip tests
zip -r $target/zenlix-update.zip vendor
zip -r $target/zenlix-update.zip .env
zip -r $target/zenlix-update.zip composer.json
zip -r $target/zenlix-update.zip composer.lock
zip -r $target/zenlix-update.zip package.json
zip -r $target/zenlix-update.zip server.php
zip -r $target/zenlix-update.zip phpspec.yml

run migration

 */

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
