<?php

namespace zenlix\Http\Controllers;

use Artisan;
use File;
use Illuminate\Http\Request;
use Validator;
use zenlix\Classes\Zen;
use zenlix\Http\Controllers\Controller;

class InstallController extends Controller
{

    /**
     * @var array
     */
    protected $permissionResults = [];

    public function __construct()
    {
        $this->permissionResults['permissions'] = [];
        $this->permissionResults['errors'] = null;
        $this->permissionArr = [
            'storage/app/' => '775',
            'storage/framework/' => '775',

            'storage/framework/cache' => '775',
            'storage/framework/sessions' => '775',
            'storage/framework/views' => '775',

            'storage/logs/' => '775',
            'storage/tmp/' => '775',
            'storage/users/' => '775',
            'bootstrap/cache/' => '775',
            'nodejs/' => '775',

        ];
        $this->requirementsArr = [
            'openssl',
            'pdo',
            'mbstring',
            'tokenizer',
            //'ionCube Loader',
            'ldap',
            'imap',
            'gd',
            'fileinfo',
            'curl',
            'zip',
        ];

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        $license = File::get(base_path('LICENSE.txt'));

        $data = [
            'license' => $license,
        ];

        return view('install.welcome')->with($data);

    }

    public function showFinal()
    {

        return view('install.final');
    }

    public function showPermissions()
    {

        $permissions = $this->permissionCheck($this->permissionArr);

        $data = [

            'permissions' => $permissions,

        ];

        return view('install.permission')->with($data);
    }

    /**
     * @param array $folders
     * @return mixed
     */
    public function permissionCheck(array $folders)
    {

        //File::allFiles($directory)
        //File::isWritable($filename)

        foreach ($folders as $folder => $permission) {
            if (!($this->getPermission($folder) >= $permission)) {
                $this->addFileAndSetErrors($folder, $permission, false);
            } else {
                $this->addFile($folder, $permission, true);
            }
        }
        return $this->permissionResults;
    }

    /**
     * @param $folder
     */
    private function getPermission($folder)
    {
        return substr(sprintf('%o', fileperms(base_path($folder))), -4);
    }

    /**
     * 
     * 
     * @param $folder
     * @param $permission
     * @param $isSet
     */
    private function addFile($folder, $permission, $isSet)
    {
        array_push($this->permissionResults['permissions'], [
            'folder' => $folder,
            'permission' => $permission,
            'isSet' => $isSet,
        ]);
    }

    /**
     * @param $folder
     * @param $permission
     * @param $isSet
     */
    private function addFileAndSetErrors($folder, $permission, $isSet)
    {
        $this->addFile($folder, $permission, $isSet);
        $this->permissionResults['errors'] = true;
    }

    public function showRequirements()
    {

        $requirements = $this->requirementsCheck(
            $this->requirementsArr
        );

        $data = [

            'requirements' => $requirements,

        ];

        return view('install.requirement')->with($data);
    }

    /**
     * @param array $requirements
     * @return mixed
     */
    public function requirementsCheck(array $requirements)
    {
        $results = [];
        foreach ($requirements as $requirement) {
            $results['requirements'][$requirement] = true;
            if (!extension_loaded($requirement)) {
                $results['requirements'][$requirement] = false;
                $results['errors'] = true;
            }
        }
        return $results;
    }

    public function showConfig()
    {

        return view('install.config');
    }

    /**
     * 
     * Storing config to .env file
     * 
     * @param Request $request
     */
    public function storeConfig(Request $request)
    {

/*dbtype
dbhost
dbname
dblogin
dbpass*/

        $validator = Validator::make($request->all(), [
            'dbhost' => 'required|max:255',
            'dbname' => 'required|max:255',
            'dblogin' => 'required|max:255',

        ]);

        if ($validator->fails()) {

            $request->session()->flash('alert-warning', trans('handler.msgFixErrors'));
            return back()->withErrors($validator)->withInput();

        } else {

            $path = base_path('.env');
            $setting = config('database');
            $data = [

                'default' => $setting['default'],
                'dbhost' => $setting['env_DB_HOST'],
                'dbname' => $setting['env_DB_DATABASE'],
                'dblogin' => $setting['env_DB_USERNAME'],
                'dbpass' => $setting['env_DB_PASSWORD'],

            ];

//dd($data);
            $newKey = str_random(32);

            if (file_exists($path)) {

                file_put_contents($path, str_replace(
                    'DB_CONNECTION=' . $data['default'], 'DB_CONNECTION=' . $request->dbtype, file_get_contents($path)
                ));
                file_put_contents($path, str_replace(
                    'DB_HOST=' . $data['dbhost'], 'DB_HOST=' . $request->dbhost, file_get_contents($path)
                ));
                file_put_contents($path, str_replace(
                    'DB_DATABASE=' . $data['dbname'], 'DB_DATABASE=' . $request->dbname, file_get_contents($path)
                ));
                file_put_contents($path, str_replace(
                    'DB_USERNAME=' . $data['dblogin'], 'DB_USERNAME=' . $request->dblogin, file_get_contents($path)
                ));
                file_put_contents($path, str_replace(
                    'DB_PASSWORD=' . $data['dbpass'], 'DB_PASSWORD=' . $request->dbpass, file_get_contents($path)
                ));

                file_put_contents($path, str_replace(
                    'APP_KEY=' . config('app.key'), 'APP_KEY=' . $newKey, file_get_contents($path)
                ));

            } else {
                return "file .env not exists";
            }

/*

ВТОРАЯ ЧАСТЬ УСТАНОВКИ!

 */
            return redirect('install/configPreInstall');

        }

    }

    public function showPreInstall()
    {

        return view('install.install');

    }

    /**
     * 
     * Starting install
     * 
     * @return mixed
     */
    public function storePreInstall()
    {

        try {
            //dd('call migrate');

            //Artisan::call('migrate:install');
            Artisan::call('key:generate');

        } catch (Exception $e) {
            return $this->response($e->getMessage());
        }

        try {
            //dd('call migrate');

            //Artisan::call('migrate:install');
            Artisan::call('migrate', ["--force" => true]);

        } catch (Exception $e) {
            return $this->response($e->getMessage());
        }

//dd('artisan called');

        try {
            //Artisan::call('db:seed');
            Artisan::call('db:seed', ["--force" => true]);
        } catch (Exception $e) {
            return $this->response($e->getMessage());
        }

        file_put_contents(storage_path('installed'), '');

/*        $user=Zen::storeNewUser([
'name'=>'System Account',
'email'=>'admin@local',
'password'=>'p@ssw0rd'
]);

 */
        $user = Zen::storeNewUser([
            'name' => 'System Account',
            'email' => 'admin@local',
            'password' => 'p@ssw0rd',
        ]);

        $user->roles->update([

            'role' => 'admin',

        ]);

        return redirect('install/final');

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
