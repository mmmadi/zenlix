<?php

namespace zenlix\Http\Controllers;

use Auth;
use Carbon\Carbon;
use File;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Image;
use Mail;
use PushBullet;
use Redis;
use Session;
use Setting;

//use PushBullet;
//use Symfony\Component\Process\Exception\ProcessFailedException;
//use Symfony\Component\Process\Process;
////use zenlix\Events\UserNotify;
use SMSCenter\SMSCenter;
use Validator;
use zenlix\Classes\Zen;
use zenlix\Http\Controllers\Controller;
use ZenEnv\ZenEnv;

class ConfigSystemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function indexTest()
    {

        $client = new Client;

        $res = $client->post('http://api3.zenlix.com/', [
            'body' => [

                'deviceHash' => '3e6795cb042635b1fa26ec3e16ed3333e83aca1d878df180249252626b9c4d03',
                'title'      => 'titlesssss',
                'message'    => 'msgggg',

            ],
        ]);

        return $res->getBody();

        /*
        to lock ioncube:

        app/Http/Middlaware/CoreCheck.php
        app/Http/Kernel.php
        app/Providers/AppServiceProvider.php

        client send to server Request plain text from license file:

        [
        'body' => [
        'data' => [
        'license_content'=>$this->readLicense()
        ]
        ]
        ]

        client receive encoded by mLs5ANj372tmKnIc1PHWV98jyK1xnwpG and save to license file

        [
        'result'=>'true',
        'ts'=>'1458041713',
        'owner'=>'Yaroslav Snisar',
        'UID'=>'008',
        'msg'=>'Your license ok!'
        ]

        zenlix check:
        1. result (true or false)
        2. ts if over 5 days

        на стороне сервера принимать лицензию и парсить,

        выбрать с БД UID, по нему выдавать ответ.

        заносить IP, дату сверки, UID.

         */

        $key = 'mLs5ANj372tmKnIc1PHWV98jyK1xnwpG';

        /*$timestampNow = Carbon::now();

        return $timestampNow->timestamp;
         */
        /*
        $data=[
        'result'=>'true',
        'ts'=>'1458041713',
        'owner'=>'Yaroslav Snisar',
        'UID'=>'008',
        'msg'=>'Your license ok!'
        ];

        $data=json_encode($data);

        $newEncrypter = new \Illuminate\Encryption\Encrypter( $key, 'AES-256-CBC' );
        $encrypted = $newEncrypter->encrypt( $data );

        return $encrypted;

         */

    }

    public function indexTest2()
    {

        $dt = Carbon::now();
        $tc = Carbon::parse('2016-01-17 21:10:56');
        dd($dt->diffInSeconds($tc, false));

    }

    public function index()
    {
        //
        (config('app.debug')) ? $cd = 'true' : $cd = 'false';

        (config('app.secure')) ? $ssl = 'true' : $ssl = 'false';
        $path = base_path('.env');
        $contents = File::get($path);

        //strpos($contents, "APP_LOCALE");
        //$match=substr($contents, strpos($contents, "APP_LOCALE"));
        $match = explode(PHP_EOL, substr($contents, strpos($contents, "APP_LOCALE")));
        $match = explode("=", $match[0]);
        $locale = trim($match[1]);
//dd($locale);
        //dd('ok');

        $data = [
            'cd'     => $cd,
            'ssl'    => $ssl,
            'locale' => $locale,
        ];

        return view('admin.config')->with($data);

    }

    public function indexAuth()
    {
        //

        $setting = config('adldap.connection_settings');
//dd($setting['domain_controllers'][0]);

        $data = [

            'ldapServer' => $setting['domain_controllers'][0],
            'ldapPort'   => $setting['port'],
            'ldapDomain' => $setting['account_suffix'],
            'ldapDC'     => $setting['base_dn'],

        ];

        return view('admin.configAuth')->with($data);

    }

    public function indexNotify()
    {
        //
        $setting = config('mail');

        $smsNotify = Zen::showNotifyList();

        $smsAccess = Setting::get('smsAccess');

        $smsAccess = explode(',', $smsAccess);
        $smsAccessArr = [];
        foreach ($smsAccess as $value) {
            # code...
            array_push($smsAccessArr, $value);

        }

        $PB_KEY = config('services.pushbullet');

        $env = file_get_contents(base_path('/nodejs/config.env'));
        $env = explode('=', $env);

        $data = [

            'mailFromMail' => $setting['from']['address'],
            'mailFromName' => $setting['from']['name'],
            'smsNotify'    => $smsNotify,
            'smsNotifySel' => $smsAccessArr,
            'pbKeyVal'     => $PB_KEY['apiKey'],
            'WPPORT'       => $env[1],

        ];

        return view('admin.configNotify')->with($data);

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

    public function showNotifyTestMail(Request $request)
    {

        $adr = $request->val;

//return $adr;
        try {
            // try

            Mail::raw('ZENLIX TEST OK!', function ($message) use ($adr) {
                $message->subject('ZENLIX TEST OK!');
                $message->to($adr);
            });

        } catch (\Exception $e) {
            // fail
            return '<pre>' . $e . '</pre>';
        }

        return 'ok!';
    }

    public function showNotifyTestSMS(Request $request)
    {

        $adr = $request->val;

        $login = Setting::get('smsLogin');
        $pass = Setting::get('smsPassword');

        $smsc = new SMSCenter($login, md5($pass), false, [
            'charset' => SMSCenter::CHARSET_UTF8,
            'fmt'     => SMSCenter::FMT_XML,
        ]);

        if (!empty($adr)) {

            try {
                return $smsc->send($adr, 'ZENLIX test ok!');
            } catch (\Exception $e) {
                // fail
                return '<pre>' . $e . '</pre>';
            }

        } else {
            return 'empty tel';
        }

        return 'ok!';
    }

    public function showNotifyTestPB(Request $request)
    {

        $adr = $request->val;

        try {
            PushBullet::user($adr)->note('ZENLIX', 'TEST OK!');
        } catch (\Exception $e) {
            // fail
            return '<pre>' . $e . '</pre>';
        }

        return 'ok!';
    }

    public function showNotifyTestWP(Request $request)
    {

        $adr = Auth::user()->email;

        try {
            Redis::publish('ZEN-channel', json_encode([
                'msgType' => 'webPush',
                'login'   => $adr,
                'title'   => 'ZENLIX OK',
                'message' => 'ZENLIX MESSAGE OK!',
                'url'     => '/',

            ]));
        } catch (\Exception $e) {
            // fail
            return '<pre>' . $e . '</pre>';
        }

        return 'ok!';
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    public function destroyLogo(Request $request)
    {

        if (Setting::get('sitelogo') != 'false') {

            $oldfile = Setting::get('sitelogo');
            File::delete('files/uploads/' . $oldfile);

        }

        Setting::set('sitelogo', 'false');
        Setting::save();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //

//dd($request->locale);
        /*sitename
        sitenameShort
        slogan
        logo*/

//dd($request->file('sitelogo'));

        $validator = Validator::make($request->all(), [
            'sitename'      => 'required|max:128',
            'sitenameShort' => 'max:5',
            'sitelogo'      => 'mimes:jpeg,bmp,png',
        ]);

        if ($validator->fails()) {

            $request->session()->flash('alert-warning', trans('handler.msgFixErrors'));

            return back()->withErrors($validator)->withInput();

        } else {

            if (!(empty($request->file('sitelogo')))) {

                if (Setting::get('sitelogo') != 'false') {

                    $oldfile = Setting::get('sitelogo');
                    File::delete('files/uploads/' . $oldfile);

                }

                $file = $request->file('sitelogo');
                $extension = $file->getClientOriginalExtension();
                $img = Image::make($file);
                // resize image
                $img->fit(250, 250);
                // save image
                $string = str_random(20);

                $img->save('files/uploads/' . $string . '.' . $extension);
                Setting::set('sitelogo', $string . '.' . $extension);
            }

            Setting::set('sitename', $request->sitename);
            Setting::set('sitenameShort', $request->sitenameShort);
            Setting::set('slogan', $request->slogan);
            Setting::set('apiStatus', $request->APIStatus);
            Setting::save();

            $path = base_path('.env');

//$path = base_path('.env');
            $contents = File::get($path);

            //strpos($contents, "APP_LOCALE");
            //$match=substr($contents, strpos($contents, "APP_LOCALE"));
            $match = explode(PHP_EOL, substr($contents, strpos($contents, "APP_LOCALE")));
            $match = explode("=", $match[0]);
            $locale = trim($match[1]);

//dd($request->DebugMode);

            if (file_exists($path)) {

                file_put_contents($path, str_replace(
                    'APP_LOCALE=' . $locale, 'APP_LOCALE=' . $request->locale, file_get_contents($path)
                ));

                file_put_contents($path, str_replace(
                    'APP_URL=' . config('app.url'), 'APP_URL=' . $request->siteURL, file_get_contents($path)
                ));
                file_put_contents($path, str_replace(
                    'APP_TIMEZONE=' . config('app.timezone'), 'APP_TIMEZONE=' . $request->timezone, file_get_contents($path)
                ));
                /*            file_put_contents($path, str_replace(
                'APP_LOCALE='.config('app.locale'), 'APP_LOCALE='.$request->langDef, file_get_contents($path)
                ));*/

                (config('app.debug')) ? $cd = 'true' : $cd = 'false';

                file_put_contents($path, str_replace(
                    'APP_DEBUG=' . $cd, 'APP_DEBUG=' . $request->DebugMode, file_get_contents($path)
                ));

                (config('app.secure')) ? $ssl = 'true' : $ssl = 'false';
                file_put_contents($path, str_replace(
                    'APP_SECURE=' . $ssl, 'APP_SECURE=' . $request->SSLMode, file_get_contents($path)
                ));

            }

            $request->session()->flash('alert-success', trans('handler.confSuccessChanged'));

            return redirect('/admin/config');

        }

    }

    public function updateAuth(Request $request)
    {
        //
        /*AuthUsers
        RecoveryPasswords
        LdapAuth*/

        $validator = Validator::make($request->all(), [
            //'sitename' => 'required|max:255'
        ]);

        if ($validator->fails()) {

            $request->session()->flash('alert-warning', trans('handler.msgFixErrors'));

            return back()->withErrors($validator)->withInput();

        } else {

            Setting::set('AuthUsers', $request->AuthUsers);
            Setting::set('RecoveryPasswords', $request->RecoveryPasswords);
            Setting::set('LdapAuth', $request->LdapAuth);

            Setting::save();

            $setting = config('adldap.connection_settings');
//dd($setting['domain_controllers'][0]);

            $data = [

                'ldapServer' => $setting['domain_controllers'][0],
                'ldapPort'   => $setting['port'],
                'ldapDomain' => $setting['account_suffix'],
                'ldapDC'     => $setting['base_dn'],

            ];

            $path = base_path('.env');

//dd($request->DebugMode);

            if (file_exists($path)) {
                file_put_contents($path, str_replace(
                    'APP_LDAP_DOMAIN_CONTROLLERS=' . $data['ldapServer'], 'APP_LDAP_DOMAIN_CONTROLLERS=' . $request->ldapServer, file_get_contents($path)
                ));

                file_put_contents($path, str_replace(
                    'APP_LDAP_PORT=' . $data['ldapPort'], 'APP_LDAP_PORT=' . $request->ldapPort, file_get_contents($path)
                ));

                file_put_contents($path, str_replace(
                    'APP_LDAP_ACCOUNT_SUFFIX=' . $data['ldapDomain'], 'APP_LDAP_ACCOUNT_SUFFIX=' . $request->ldapDomain, file_get_contents($path)
                ));

                file_put_contents($path, str_replace(
                    'APP_LDAP_BASE_DN=' . $data['ldapDC'], 'APP_LDAP_BASE_DN=' . $request->ldapDC, file_get_contents($path)
                ));

            }

            $request->session()->flash('alert-success', trans('handler.confSuccessChanged'));

            return redirect('/admin/config/auth');

        }

    }

    public function updateNotify(Request $request)
    {
        //


//dd($request->all());

        $validator = Validator::make($request->all(), [
            //'mailLogin' => 'required',
            //'mailPass' => 'required',

            //'sitename' => 'required|max:255'
        ]);

        if ($validator->fails()) {

            $request->session()->flash('alert-warning', trans('handler.msgFixErrors'));

            return back()->withErrors($validator)->withInput();

        } else {

            Setting::set('mailStatus', $request->mailStatus);

            Setting::set('smsStatus', $request->smsStatus);
            Setting::set('smsLogin', $request->smsLogin);
            if ($request->smsPassword) {
                Setting::set('smsPassword', $request->smsPassword);
            }

            Setting::set('WPURL', $request->WPURL);

            if (count($request->smsAccess)) {
                $a = [];
                foreach ($request->smsAccess as $value) {
                    # code...
                    array_push($a, $value);
                }
                $r = implode(',', $a);
                Setting::set('smsAccess', $r);

            } else {
                Setting::set('smsAccess', '');
            }

            /*$s=$request->smsAccess->toArray();
            $smsAccess=implode(',' $s);
            dd($smsAccess);*/
//
            //smsAccess

            Setting::set('pbStatus', $request->pbStatus);
//Setting::set('pbKey', $request->pbKey);

            Setting::save();

            $setting = config('mail');

            $data = [

                'mailFromMail' => $setting['from']['address'],
                'mailFromName' => $setting['from']['name'],
                //'mailSecurity' => $setting['encryption'],

            ];

            $envPATH = base_path('/nodejs/config.env');
            $envContent = file_get_contents(base_path('/nodejs/config.env'));
            $env = explode('=', $envContent);

            file_put_contents($envPATH, str_replace(
                'NODE_PORT=' . $env[1], 'NODE_PORT=' . $request->WPPORT, file_get_contents($envPATH)
            ));
            /*
            RESTART NODEJS SERVER!!!
            ./pm2 delete zenserver
            ./pm2 start ../../../nodejs/server.js -n zenserver
             */
            /*$pmPATH=base_path('/node_modules/pm2/bin/pm2');
            $command = $pmPATH." delete zenserver";
            $process = new Process($command);
            //$process->run();
            try {
            $process->mustRun();

            echo $process->getOutput();
            } catch (ProcessFailedException $e) {
            echo $e->getMessage();
            }*/

            $path = base_path('.env');

//dd($request->DebugMode);

            if (file_exists($path)) {
                /*                file_put_contents($path, str_replace(
                                    'MAIL_DRIVER=' . config('mail.driver'), 'MAIL_DRIVER=' . $request->mailType, file_get_contents($path)
                                ));
                                file_put_contents($path, str_replace(
                                    'MAIL_ADDRESS=' . $data['mailFromMail'], 'MAIL_ADDRESS=' . $request->mailFromMail, file_get_contents($path)
                                ));
                                file_put_contents($path, str_replace(
                                    'MAIL_NAME=' . $data['mailFromName'], 'MAIL_NAME=' . $request->mailFromName, file_get_contents($path)
                                ));

                                file_put_contents($path, str_replace(
                                    'MAIL_HOST=' . config('mail.host'), 'MAIL_HOST=' . $request->mailAddress, file_get_contents($path)
                                ));

                                file_put_contents($path, str_replace(
                                    'MAIL_PORT=' . config('mail.port'), 'MAIL_PORT=' . $request->mailPort, file_get_contents($path)
                                ));*/


                //dd(config('mail.encryption'));

                $env = new ZenEnv(base_path('./.env'));
                //dd($env->get());

                //dd($request->all());

                $configArr = [
                    'MAIL_ENCRYPTION' => $request->mailSecurity,
                    'MAIL_USERNAME'   => $request->mailLogin,
                    'PB_KEY'          => $request->pbKey,
                    'MAIL_DRIVER'     => $request->mailType,
                    'MAIL_ADDRESS'    => $request->mailFromMail,
                    'MAIL_NAME'       => $request->mailFromName,
                    'MAIL_HOST'       => $request->mailAddress,
                    'MAIL_PORT'       => $request->mailPort,
                ];

                if ($request->mailPass) {
                    $configArr['MAIL_PASSWORD'] = $request->mailPass;
                }


                $env->set($configArr);


                /*                file_put_contents($path, str_replace(
                                    'MAIL_ENCRYPTION=' . config('mail.encryption'), 'MAIL_ENCRYPTION=' . $request->mailSecurity, file_get_contents($path)
                                ));*/

                /*                file_put_contents($path, str_replace(
                                    'MAIL_USERNAME=' . config('mail.username'), 'MAIL_USERNAME=' . $request->mailLogin, file_get_contents($path)
                                ));

                                if ($request->mailPass) {
                                    file_put_contents($path, str_replace(
                                        'MAIL_PASSWORD=' . config('mail.password'), 'MAIL_PASSWORD=' . $request->mailPass, file_get_contents($path)
                                    ));
                                }*/

                /*                $PB_KEY = config('services.pushbullet');

                                file_put_contents($path, str_replace(
                                    'PB_KEY=' . $PB_KEY['apiKey'], 'PB_KEY=' . $request->pbKey, file_get_contents($path)
                                ));*/

            }

            $request->session()->flash('alert-success', trans('handler.confSuccessChanged'));

            return redirect('/admin/config/notify');

        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
