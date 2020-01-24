<?php

namespace zenlix\Http\Controllers;

use Adldap;
use Auth;
use Excel;
use File;
use Illuminate\Http\Request;
use Session;
use Validator;
use zenlix\Classes\Zen;
use zenlix\Groups;
use zenlix\Http\Controllers\Controller;
use zenlix\TicketForms;
use zenlix\User;
use zenlix\UserFields;
use zenlix\UserLdap;
use zenlix\UserRole;
use zenlix\UserTicketConf;


class ConfigUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        $users = User::all();

        $data = [
            'users' => $users,
        ];

        return view('admin.users')->with($data);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //

        $groups = Groups::all();
        $groupsArr = [];
        foreach ($groups as $key => $value) {
            $groupsArr[$value->id] = $value->name;
        }

        $TicketForms = TicketForms::all();
        $TicketFormsArr = [];
        foreach ($TicketForms as $key => $value) {
            $TicketFormsArr[$value->id] = $value->name;
        }
        $roles = [
            'admin' => 'Администратор',
            'user' => 'Пользователь',
            'client' => 'Клиент',
        ];

        $data = [
            'groups' => $groupsArr,
            'TicketForms' => $TicketFormsArr,
            'roles' => $roles,
        ];

        return view('admin.usersCreate')->with($data);
    }

//createAdv
    public function createAdv()
    {

        $fieldTypes = [

            'text' => 'Текстовое поле',
            'textarea' => 'Большое текстовое поле',
            'select' => 'Список',
            'multiselect' => 'Мультисписок',

        ];

        $data = [
            'fieldTypes' => $fieldTypes,
        ];

        return view('admin.usersAdvCreate')->with($data);
    }

    public function storeAdv(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
        ]);

        if ($validator->fails()) {

            $request->session()->flash('alert-warning', trans('handler.msgFixErrors'));

            return back()->withErrors($validator)->withInput();

        } else {

            UserFields::create([

                'status' => $request->status,
                'visible_client' => $request->visible_client,
                'field_type' => $request->field_type,
                'name' => $request->name,
                'placeholder' => $request->placeholder,
                'value' => $request->value,

            ]);

            $request->session()->flash('alert-success', trans('handler.fieldSuccessCreated2'));

            return redirect('/admin/users/adv');

        }

    }

    public function editAdv($id)
    {
        $UserField = UserFields::findOrFail($id);
        $fieldTypes = [

            'text' => trans('handler.textfield'),
            'textarea' => trans('handler.bigText'),
            'select' => trans('handler.list'),
            'multiselect' => trans('handler.multilist'),

        ];

        $data = [
            'fieldTypes' => $fieldTypes,
            'UserField' => $UserField,
        ];

        return view('admin.usersAdvEdit')->with($data);
    }

    public function updateAdv(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
        ]);

        if ($validator->fails()) {

            $request->session()->flash('alert-warning', trans('handler.msgFixErrors'));

            return back()->withErrors($validator)->withInput();

        } else {

            $UserFields = UserFields::findOrFail($id);
            $UserFields->update([

                'status' => $request->status,
                'visible_client' => $request->visible_client,
                'field_type' => $request->field_type,
                'name' => $request->name,
                'placeholder' => $request->placeholder,
                'value' => $request->value,

            ]);

            $request->session()->flash('alert-success', trans('handler.fieldSuccessCreated2'));

            return redirect('/admin/users/adv');

        }

    }

    public function destroyAdv($id)
    {
        $UserFields = UserFields::findOrFail($id);

        $UserFields->delete();

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

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|min:6',
            'ldapLogin' => 'max:255|unique:users,email|unique:user_ldap,login,NULL,user_id',
        ]);

        if ($validator->fails()) {

            $request->session()->flash('alert-warning', trans('handler.msgFixErrors'));

            return back()->withErrors($validator)->withInput();

        } else {

            /*        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            ]);*/

            $user = Zen::storeNewUser([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
            ]);

            if (!empty($request->groups)) {

                $uArr = $request->groups;
                $user->groups()->attach($uArr, array('status' => 'success', 'priviliges' => 'user'));

            }

            if (!empty($request->groupsSuper)) {

                $uArrSuper = $request->groupsSuper;
                $user->groups()->attach($uArrSuper, array('status' => 'success', 'priviliges' => 'admin'));

            }

            $user->profile->update([

                'full_name' => $request->name,
                'email' => $request->email,
                'user_urlhash' => str_random(25),

            ]);

            /*        $profile = new UserProfile;
            $profile->full_name = $request->name;
            $profile->email = $request->email;
            $profile->user_urlhash=str_random(25);
            $user->profile()->save($profile);*/

            if ($request->conf_params == 'user') {
                $utC = $request->ticket_form_id;
                $gtC = null;
            } else if ($request->conf_params == 'group') {
                $utC = null;
                $gtC = $request->group_conf_id;
            }

            $user->UserTicketConf->update([

                'conf_params' => $request->conf_params,
                'ticket_form_id' => $utC,
                'group_conf_id' => $gtC,

            ]);

            /*        $UserTicketConf= new UserTicketConf;
            $UserTicketConf->user_id=$user->id;
            $UserTicketConf->conf_params=$request->conf_params;

            $UserTicketConf->ticket_form_id=$utC;
            $UserTicketConf->group_conf_id=$gtC;

            $UserTicketConf->save();*/

            $user->roles->update([

                'role' => $request->userRole,

            ]);

            /* UserRole::create([
            'user_id'=>$user->id,
            'role'=>$request->userRole
            ]);*/

            $user->ldap->update([

                'status' => $request->LDAPStatus,
                'authType' => $request->LDAPAuth,
                'login' => $request->ldapLogin,

            ]);

            /*UserLdap::create([
            'user_id'=>$user->id,
            'status'=>$request->LDAPStatus,
            'authType'=>$request->LDAPAuth,
            'login'=>$request->ldapLogin

            ]);*/

            $request->session()->flash('alert-success', trans('handler.userSuccessAdded'));

            return redirect('/admin/users');

        }

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

        $user = User::findOrFail($id);
        $groups = Groups::all();

        $forms = TicketForms::all();

        $groupsArr = [];
        foreach ($groups as $key => $value) {
            $groupsArr[$value->id] = $value->name;
        }

        $formsArr = [];
        foreach ($forms as $key => $value) {
            $formsArr[$value->id] = $value->name;
        }

        $glU = $user->GroupUser();
        $arrU = array();
        foreach ($glU as $value) {
            array_push($arrU, $value->id);
        }
        $user['groups'] = $arrU;

        $glA = $user->GroupAdmin();
        $arrA = array();
        foreach ($glA as $value) {
            array_push($arrA, $value->id);
        }
        $user['groupsSuper'] = $arrA;

        $UserTicketConf = UserTicketConf::firstOrCreate(['user_id' => $user->id]);

        ($UserTicketConf->conf_params == 'group') ? $user['conf_params'] = 'group' : $user['conf_params'] = 'user';

        $user['group_conf_id'] = $UserTicketConf->group_conf_id;
        $user['ticket_form_id'] = $UserTicketConf->ticket_form_id;
//TicketForms

        $roles = [
            'admin' => 'Администратор',
            'user' => 'Пользователь',
            'client' => 'Клиент',
        ];

        $data = [

            'user' => $user,
            'roles' => $roles,
            'groups' => $groupsArr,
            'forms' => $formsArr,

        ];

        return view('admin.usersEdit')->with($data);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'ldapLogin' => 'max:255|unique:users,email,' . $user->id . '|unique:user_ldap,login,' . $user->id . ',user_id',
        ]);

        if ($validator->fails()) {

            $request->session()->flash('alert-warning', trans('handler.msgFixErrors'));

            return back()->withErrors($validator)->withInput();

        } else {

            $UserTicketConf = UserTicketConf::firstOrCreate(['user_id' => $user->id]);

            $UserRole = UserRole::firstOrCreate(['user_id' => $user->id]);

            $UserRole->update([

                'role' => $request->userRole,

            ]);
//dd($UserTicketConf);

            $UserLdap = UserLdap::firstOrCreate(['user_id' => $user->id]);
            $UserLdap->update([

                'status' => $request->LDAPStatus,
                'authType' => $request->LDAPAuth,
                'login' => $request->ldapLogin,

            ]);

            $conf_params = $request->conf_params;
            if ($conf_params == 'group') {
                $UserTicketConf->update([
                    'ticket_form_id' => null,
                    'group_conf_id' => $request->group_conf_id,
                    'conf_params' => 'group',
                ]);
            } else if ($conf_params == 'user') {
                $UserTicketConf->update([
                    'ticket_form_id' => $request->ticket_form_id,
                    'group_conf_id' => null,
                    'conf_params' => 'user',
                ]);
            }

            $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            $user->groups()->detach();
            if (!empty($request->groups)) {
                $uArr = $request->groups;
                $user->groups()->attach($uArr, array('status' => 'success', 'priviliges' => 'user'));
            }
            if (!empty($request->groupsSuper)) {
                $uArrS = $request->groupsSuper;
                $user->groups()->attach($uArrS, array('status' => 'success', 'priviliges' => 'admin'));
            }

            $request->session()->flash('alert-success', trans('handler.usersSuccessUpdated'));

            return redirect('/admin/users');

        }

    }

    public function showImport()
    {

        return view('admin.usersImport');

    }

    public function showImportCSV()
    {

        $data = ['upload' => 'false'];
        File::delete(storage_path('/tmp/') . 'import_users.csv');

//$file=storage_path('/tmp/').'import_users.csv';

        return view('admin.usersImportCSV')->with($data);

    }

//ConfigUsersController@updateUsersImportCsv

    public function updateUsersImportCsv(Request $request)
    {

        $file = $request->file('users_csv');

        $file->move(storage_path('/tmp/'), 'import_users.csv');

        $file = storage_path('/tmp/') . 'import_users.csv';

        $dataImport = Excel::load($file)->first();

//dd($dataImport);
        $attrVal = $dataImport->toArray();

//dd($attrVal);
        $groups = Groups::all();
        $groupsArr = [];
        foreach ($groups as $group) {
            $groupsArr[$group->id] = $group->name;
        }

        $TicketForms = TicketForms::all();
        $TicketFormsArr = [];
        foreach ($TicketForms as $key => $value) {
            $TicketFormsArr[$value->id] = $value->name;
        }

        $data = ['upload' => 'true',
                 'groups' => $groupsArr,
                 'TicketForms' => $TicketFormsArr,
                 'attrs' => [null => 'Пусто'] + $attrVal,
        ];

        return view('admin.usersImportCSV')->with($data);

    }

    public function updateUsersImportCsvStep2(Request $request)
    {

        /*login
        email
        name
        pass
        position
        telephone
        address

        groups

        conf_params
        group_conf_id
        ticket_form_id
        role
        notify
         */

//dd($request->all());

        $file = storage_path('/tmp/') . 'import_users.csv';
        $dataImport = Excel::load($file);
        $count = 0;
        foreach ($dataImport->toArray() as $someUser) {
            # code...
            //dd($someUser[2]);
            $reqVar = [];

            if (!empty($request->login) || !empty($request->email) || !empty($request->name)) {

                $reqVar['login'] = $someUser[$request->login];
                $reqVar['email'] = $someUser[$request->email];
                $reqVar['name'] = $someUser[$request->name];

                (empty($request->pass)) ? $reqVar['password'] = str_random(6) : $reqVar['password'] = $someUser[$request->pass];

                (empty($request->position)) ? $reqVar['position'] = null : $reqVar['position'] = $someUser[$request->position];
                (empty($request->telephone)) ? $reqVar['telephone'] = null : $reqVar['telephone'] = $someUser[$request->telephone];
                (empty($request->address)) ? $reqVar['address'] = null : $reqVar['address'] = $someUser[$request->address];

                if ($request->conf_params == "user") {
                    $reqVar['group_conf_id'] = null;
                    $reqVar['ticket_form_id'] = $request->ticket_form_id;
                } else if ($request->conf_params == "group") {
                    $reqVar['group_conf_id'] = $request->group_conf_id;
                    $reqVar['ticket_form_id'] = null;
                }

            }

            if ((User::where('email', $reqVar['login'])->count() == 0) && (UserLdap::where('login', $reqVar['login'])->count() == 0)) {

                $user = Zen::storeNewUser([
                    'name' => $reqVar['name'],
                    'email' => $reqVar['login'],
                    'password' => $reqVar['password'],
                ]);

                $user->profile->update([
                    'full_name' => $reqVar['name'],
                    'email' => $reqVar['email'],
                    'user_urlhash' => str_random(25),
                    'position' => $reqVar['position'],
                    'telephone' => $reqVar['telephone'],
                    'address' => $reqVar['address'],
                ]);

                $user->UserTicketConf->update([
                    'group_conf_id' => $reqVar['group_conf_id'],
                    'ticket_form_id' => $reqVar['ticket_form_id'],
                    'conf_params' => $request->conf_params,

                ]);

                $user->roles->update([
                    'role' => $request->role,
                ]);

                /*    $user = User::create([
                'name' => $reqVar['name'],
                'email' => $reqVar['login'],
                'password' => bcrypt($reqVar['password']),
                ]);*/

                /*        UserProfile::create([
                'user_id'=>$user->id,
                'full_name'=>$reqVar['name'],
                'email'=>$reqVar['email'],
                'user_urlhash'=>str_random(25),
                'position'=>$reqVar['position'],
                'telephone'=>$reqVar['telephone'],
                'address'=>$reqVar['address']
                ]);*/

                /*        UserTicketConf::create([
                'user_id'=>$user->id,
                'group_conf_id'=>$reqVar['group_conf_id'],
                'ticket_form_id'=>$reqVar['ticket_form_id'],
                'conf_params'=>$request->conf_params
                ]);*/

                /*        UserRole::create([
                'user_id'=>$user->id,
                'role'=>$request->role
                ]);*/

                if ($request->ldapStatus == "true") {
                    $ldapLogin = null;
                    if ((User::where('email', $someUser[$request->ldapLogin])->where('id', '!=', $user->id)->count() == 0) && (UserLdap::where('login', $someUser[$request->ldapLogin])->where('user_id', '!=', $user->id)->count() == 0)) {
                        $ldapLogin = $someUser[$request->ldapLogin];
                    }

                } else {
                    $ldapLogin = null;
                }

                //$ldapLogin=Null : $ldapLogin=$uSel[$request->ldapLogin];

                $user->ldap->update([
                    'status' => $request->ldapStatus,
                    'login' => $ldapLogin,
                    'authType' => $request->ldapType,
                ]);

                /*        UserLdap::create([
                'user_id'=>$user->id,
                'status'=>$request->ldapStatus,
                'login'=>$ldapLogin,
                'authType'=>$request->ldapType
                ]);*/

                if (count($request->groups) > 0) {
                    $uArr = $request->groups;
                    $user->groups()->attach($uArr, array('status' => 'success', 'priviliges' => 'user'));
                }
                $count++;
            }
//end foreach

        }

        File::delete(storage_path('/tmp/') . 'import_users.csv');

        $data = [

            'count' => $count,

        ];

        return view('admin.usersImportCSVSuccess')->with($data);

    }

//updateUsersImportCsvStep3
    public function showImportLDAPStep3(Request $request)
    {

        $config = new \Adldap\Connections\Configuration();
        $config->setAccountSuffix(Session::get('ad.suffix'));
        $config->setDomainControllers([Session::get('ad.ip')]);
        $config->setPort(Session::get('ad.port'));
        $config->setBaseDn(Session::get('ad.dn'));
        $config->setAdminUsername(Session::get('ad.login'));
        $config->setAdminPassword(Session::get('ad.pass'));
        $config->setFollowReferrals(true);
        $config->setUseSSL(false);
        $config->setUseTLS(false);
        $config->setUseSSO(false);

        Session::flush();
        $ad = new \Adldap\Adldap($config);

        $users = $ad->users()->all();

        $userArr = [];
        foreach ($users as $userLDAP) {

            if ($request->targetImport == "selected") {

                if (in_array($userLDAP->getName(), $request->selectedUsers)) {

                    array_push($userArr, [

                        'name' => $userLDAP->getName(),
                        'mail' => $userLDAP->getEmail(),
                        'telephone' => $userLDAP->getTelephoneNumber(),
                        'department' => $userLDAP->getDepartment(),
                        'title' => $userLDAP->getTitle(),
                        'samaccountname' => $userLDAP->samaccountname[0],
                        'userprincipalname' => $userLDAP->getUserPrincipalName(),

                    ]);
                }

            } else {

                array_push($userArr, [

                    'name' => $userLDAP->getName(),
                    'mail' => $userLDAP->getEmail(),
                    'telephone' => $userLDAP->getTelephoneNumber(),
                    'department' => $userLDAP->getDepartment(),
                    'title' => $userLDAP->getTitle(),
                    'samaccountname' => $userLDAP->samaccountname[0],
                    'userprincipalname' => $userLDAP->getUserPrincipalName(),

                ]);

            }

        }

//dd($userArr);

//добавить настройки ldap в импорт csv,ldap
        //dd($request->all());

        $count = 0;
        foreach ($userArr as $uSel) {

            $reqVar['login'] = $uSel[$request->login];
            $reqVar['email'] = $uSel[$request->email];
            $reqVar['name'] = $uSel[$request->name];

            if (!empty($reqVar['login']) || !empty($reqVar['email']) || !empty($reqVar['name'])) {

                (empty($request->position)) ? $reqVar['position'] = null : $reqVar['position'] = $uSel[$request->position];
                (empty($request->telephone)) ? $reqVar['telephone'] = null : $reqVar['telephone'] = $uSel[$request->telephone];

                if ($request->conf_params == "user") {
                    $reqVar['group_conf_id'] = null;
                    $reqVar['ticket_form_id'] = $request->ticket_form_id;
                } else if ($request->conf_params == "group") {
                    $reqVar['group_conf_id'] = $request->group_conf_id;
                    $reqVar['ticket_form_id'] = null;
                }

                $reqVar['password'] = str_random(6);

                if ((User::where('email', $reqVar['login'])->count() == 0) && (UserLdap::where('login', $reqVar['login'])->count() == 0)) {

                    $user = Zen::storeNewUser([
                        'name' => $reqVar['name'],
                        'email' => $reqVar['login'],
                        'password' => $reqVar['password'],
                    ]);

                    $user->profile->update(
                        [
                            'full_name' => $reqVar['name'],
                            'email' => $reqVar['email'],
                            'user_urlhash' => str_random(25),
                            'position' => $reqVar['position'],
                            'telephone' => $reqVar['telephone'],
                        ]
                    );

                    $user->UserTicketConf->update([
                        'group_conf_id' => $reqVar['group_conf_id'],
                        'ticket_form_id' => $reqVar['ticket_form_id'],
                        'conf_params' => $request->conf_params,
                    ]);

                    $user->roles->update([
                        'role' => $request->role,
                    ]);

                    if (count($request->groups) > 0) {
                        $uArr = $request->groups;
                        $user->groups()->attach($uArr, array('status' => 'success',
                                                             'priviliges' => 'user'));
                    }
                    $count++;

                    if ($request->ldapStatus == "true") {
                        $ldapLogin = null;
                        if ((User::where('email', $uSel[$request->ldapLogin])->where('id', '!=', $user->id)->count() == 0) && (UserLdap::where('login', $uSel[$request->ldapLogin])->where('user_id', '!=', $user->id)->count() == 0)) {
                            $ldapLogin = $uSel[$request->ldapLogin];
                        }

                    } else {
                        $ldapLogin = null;
                    }

                    //$ldapLogin=Null : $ldapLogin=$uSel[$request->ldapLogin];

                    $user->ldap->update([
                        'status' => $request->ldapStatus,
                        'login' => $ldapLogin,
                        'authType' => $request->ldapType,
                    ]);

                    /*        UserLdap::create([
                    'user_id'=>$user->id,
                    'status'=>$request->ldapStatus,
                    'login'=>$ldapLogin,
                    'authType'=>$request->ldapType
                    ]);*/

                }

                # code...
            }

        }

        $data = [

            'counts' => $count,

        ];

        return view('admin.usersImportLDAPSuccess')->with($data);
    }

//showImportLDAPStep2
    public function showImportLDAPStep2(Request $request)
    {

        /*LDAPAddress
        LDAPPort
        LDAPSuffix
        LDAPDN
        LDAPLogin*/

        $validator = Validator::make($request->all(), [
            'LDAPAddress' => 'required|max:255',
            'LDAPPort' => 'required',
            'LDAPSuffix' => 'required|max:255',
            'LDAPDN' => 'required',

        ]);

        if ($validator->fails()) {

            $request->session()->flash('alert-warning', trans('handler.msgFixErrors'));

            return back()->withErrors($validator)->withInput();

        } else {

            Session::put('ad.ip', $request->LDAPAddress);
            Session::put('ad.port', $request->LDAPPort);
            Session::put('ad.suffix', $request->LDAPSuffix);
            Session::put('ad.dn', $request->LDAPDN);
            Session::put('ad.login', $request->LDAPLogin);
            Session::put('ad.pass', $request->LDAPPassword);

            $config = new \Adldap\Connections\Configuration();
            $config->setAccountSuffix($request->LDAPSuffix);
            $config->setDomainControllers([$request->LDAPAddress]);
            $config->setPort($request->LDAPPort);
            $config->setBaseDn($request->LDAPDN);
            $config->setAdminUsername($request->LDAPLogin);
            $config->setAdminPassword($request->LDAPPassword);
            $config->setFollowReferrals(true);
            $config->setUseSSL(false);
            $config->setUseTLS(false);
            $config->setUseSSO(false);

            $ad = new \Adldap\Adldap($config);

            $users = $ad->users()->all();

//dd($users);

            $testArr = [];
            $usersAll = [];

            $i = 0;
            $countRes = count($users);

            foreach ($users as $user) {

                $u = $user->getName();
                $usersAll[$u] = $u;
            }

            foreach ($users as $user) {

                array_push($testArr, [

                    'name' => $user->getName(),
                    'mail' => $user->getEmail(),
                    'telephone' => $user->getTelephoneNumber(),
                    'department' => $user->getDepartment(),
                    'title' => $user->getTitle(),
                    'samaccountname' => $user->samaccountname[0],
                    'userprincipalname' => $user->getUserPrincipalName(),

                ]);

                if ($i == 5) {
                    break;
                }

                $i++;
//if ($i)
            }

            $groups = Groups::all();
            $groupsArr = [];
            foreach ($groups as $group) {
                $groupsArr[$group->id] = $group->name;
            }

            $TicketForms = TicketForms::all();
            $TicketFormsArr = [];
            foreach ($TicketForms as $key => $value) {
                $TicketFormsArr[$value->id] = $value->name;
            }

            $attrsArr = [
                null => 'пусто',
                'name' => trans('handler.name'),
                'mail' => 'Email',
                'telephone' => trans('handler.tel'),
                'department' => trans('handler.unit'),
                'title' => trans('handler.name2'),
                'samaccountname' => trans('handler.login2'),
                'userprincipalname' => trans('handler.login3'),

            ];

            $data = [

                'users' => $testArr,
                'usersAll' => $usersAll,
                'countRes' => $countRes,
                'groups' => $groupsArr,
                'TicketForms' => $TicketFormsArr,
                'attrs' => $attrsArr,

            ];

            return view('admin.usersImportLDAPStep2')->with($data);

        }

    }

    public function showImportLDAP()
    {

        /*$config = new \Adldap\Connections\Configuration();

        $config->setAccountSuffix('@gpext.local');
        $config->setDomainControllers(['10.32.90.5']);
        $config->setPort(389);
        $config->setBaseDn('dc=gpext,dc=local');
        $config->setAdminUsername('snisar.ya');
        $config->setAdminPassword('57wzx434');
        $config->setFollowReferrals(true);
        $config->setUseSSL(false);
        $config->setUseTLS(false);
        $config->setUseSSO(false);

        $ad = new \Adldap\Adldap($config);

        $users=$ad->users()->all();
        $i=0;
        foreach ($users as $user) {
        # code...

        //dd($user->samaccountname);

        if ($i > 300) break;

        echo $user->getDepartment();
        echo "<hr>";
        $i++;
        }
        dd('false');*/

        /*
        name $user->getName()
        mail  getEmail()
        telephone getTelephoneNumber();
        department getDepartment();
        title getTitle();
        samaccountname $user->getMailNickname()
        userprincipalname getUserPrincipalName();

         */

        return view('admin.usersImportLDAP');

    }

//updateLoginAs
    public function updateLoginAs(Request $request)
    {

//Auth::login($request->userid);
        Auth::loginUsingId($request->userid, true);
        Session::put('returnToAdmin', 'true');


    }

    public function updateLoginAsAdmin(Request $request)
    {
        if (Session::has('returnToAdmin')) {
            Auth::loginUsingId(1, true);
            Session::forget('returnToAdmin');

        }
//Auth::login($request->userid);


    }

    public function showAdv()
    {

        $fields = UserFields::all();
        $data = ['fields' => $fields];

        return view('admin.usersAdv')->with($data);
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

        $user = User::findOrFail($id);
        $user->update([
            'email' => '[deleted:' .str_random(4). $user->email . ']'
        ]);
        $user->profile->update(['email' => $user->email]);
        $user->delete();



        /*

        при удалении пользователя - помечать как удалён
        нигде не присутствует

        что делать с заявками
        пользователь остаётся

        но показывать его в заявках

        автор заявки
        исполнитель заявки
        клиент
        комментарий
        лог-автор

         */

    }
}
