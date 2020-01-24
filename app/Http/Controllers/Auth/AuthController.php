<?php

namespace zenlix\Http\Controllers\Auth;

use Event;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Validator;
use zenlix\Classes\Zen;

/*use zenlix\UserProfile;
use zenlix\UserTicketConf;
use zenlix\UserRole;
use zenlix\UserLdap;*/

use zenlix\Events\UserNotify;
use zenlix\Http\Controllers\Controller;
use zenlix\User;

class AuthController extends Controller
{

    protected $loginPath = 'login';

    //protected $redirectPath = '/';

    protected $redirectAfterLogout = '/';

    protected $redirectPath = '/dashboard';

    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
     */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
            'agree' => 'required',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {

/*    $user = User::create([
'name' => $data['name'],
'email' => $data['email'],
'password' => bcrypt($data['password']),
]);

$profile = new UserProfile;
$profile->full_name = $data['name'];
$profile->email = $data['email'];
$profile->user_urlhash=str_random(25);
$user->profile()->save($profile);

UserTicketConf::create([
'user_id'=>$user->id,
'ticket_form_id'=>'1',
'conf_params'=>'user'
]);

UserRole::create([
'user_id'=>$user->id,
'role'=>'client'
]);

UserLdap::create([
'user_id'=>$user->id
]);*/

        //

        $user = Zen::storeNewUser([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);

        Event::fire(new UserNotify($user->id, null, 'create'));

        //userCreate($userID)

        return $user;
    }
}
