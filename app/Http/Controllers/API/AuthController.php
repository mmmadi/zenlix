<?php

namespace zenlix\Http\Controllers\API;

use Auth;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use zenlix\Http\Controllers\API\APIController;
use zenlix\UserDevices;

use Adldap;
use zenlix\UserLdap;

use zenlix\User;
use Setting;

class AuthController extends APIController
{

    /**
     * API Login, on success return JWT Auth token
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');


/*        if (Adldap::authenticate($request->email, $request->password)) {
            $uldap=UserLdap::where('login', $request->email)->firstOrFail();
            $user=User::findOrFail($uldap->user_id);
*/

        $canSystemAuth=true;

        if (Setting::get('LdapAuth') == true ) {

            $canSystemAuth=false;
            try {
                if (Adldap::authenticate($request->email, $request->password)) {
                    $uldap=UserLdap::where('login', $request->email)->firstOrFail();

                    $user=User::findOrFail($uldap->user_id);


                    try {
                        //dd('o');
                        // attempt to verify the credentials and create a token for the user
                        if (!$token = JWTAuth::fromUser($user)) {
                            return response()->json(['error' => 'invalid_credentials'], 401);
                        }
                    } catch (JWTException $e) {
                        // something went wrong whilst attempting to encode the token
                        return response()->json(['error' => 'could_not_create_token'], 500);
                    }
                } else {
                    $canSystemAuth=true;
                }
            } catch (\Exception $e) {
                $canSystemAuth=true;
                // Binding exception
            }
        }



//If ActiveDirectory User?



//if no AD-user
if ($canSystemAuth) {
        //dd('oo');
    //dd('this');
            try {
            // attempt to verify the credentials and create a token for the user
                //$token = JWTAuth::attempt($credentials);

            if (!$token = JWTAuth::attempt($credentials)) {
                //dd($token);
                return response()->json(['error' => 'invalid_credentials'], 401);
            }

            $user = JWTAuth::authenticate($token);
                //$user = JWTAuth::parseToken()->authenticate();

        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
    }



/*        try {
            // attempt to verify the credentials and create a token for the user
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }*/



        $uDeviceCode = $request->uDeviceCode;
        $uDeviceName = $request->uDeviceName;
        $uDeviceToken = $request->uDeviceToken;


        //TODO: USER вместо Auth::User...
        $userID = $user->id;

        $userDevice = UserDevices::where('device_hash', $uDeviceToken)->where('user_id', $userID)->firstOrCreate([
            'user_id' => $userID,
            'device_name' => $uDeviceName,
            'device_code' => $uDeviceCode,
            'device_hash' => $uDeviceToken,

        ]);

        $userDevice->update([
            'device_name' => $uDeviceName,
            'device_code' => $uDeviceCode,
        ]);

/*Авторизация API
вносить token в БД (user, token)

Запрос API
+проверка token из БД
обновлять token в БД (user, token)

При удалении token из БД
Если token отсутствует - logout

User Devices:
user
device_token
status:true,false
datetime
 */

        // all good so return the token
        return response()->json(compact('token'));
    }

    /**
     * Log out
     * Invalidate the token, so user cannot use it anymore
     * They have to relogin to get a new token
     *
     * @param Request $request
     */
    public function logout(Request $request)
    {
        $this->validate($request, [
            'token' => 'required',
        ]);

        JWTAuth::invalidate($request->input('token'));
    }
}
