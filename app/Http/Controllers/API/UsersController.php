<?php

namespace zenlix\Http\Controllers\API;

use Illuminate\Http\Request;
use JWTAuth;
use zenlix\Http\Controllers\Controller;
use zenlix\User;
use zenlix\UserProfile;

class UsersController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        //$user = app('Dingo\Api\Auth\Auth')->user();
        $user = JWTAuth::parseToken()->authenticate();

        $myGroupsUser = [];
        foreach ($user->GroupUser() as $value) {
            # code...
            array_push($myGroupsUser, $value->id);
        }

        $usersDB = User::whereHas('groups', function ($q) use ($myGroupsUser) {
            $q->whereIn('id', $myGroupsUser);
        })
            ->where('id', '!=', $user->id)
            ->orderBy('id', 'DESC')->get();
        $users = [];

        foreach ($usersDB as $users_once) {
            # code...
            array_push($users, [

                'full_name' => $users_once->name,
                'user_urlhash' => $users_once->profile->user_urlhash,
                'position' => $users_once->profile->position,
                'user_img' => $users_once->profile->user_img,
                'telephone' => $users_once->profile->telephone,
                'address' => $users_once->profile->address,
                'email' => $users_once->profile->email,

            ]);
        }

        $data = [

            'users' => $users,
        ];

        $res = [
            'status_code' => 200,
            'data' => $data,
        ];

        return $res;
    }

    public function show(Request $request)
    {

        $user = UserProfile::where('user_urlhash', $request->user_urlhash)->firstOrFail();

        $data = [

            'user_img' => $user->user_img,
            'user_cover' => $user->user_cover,
            'lang' => $user->lang,
            'full_name' => $user->full_name,
            'user_urlhash' => $user->user_urlhash,
            'telephone' => $user->telephone,
            'skype' => $user->skype,
            'address' => $user->address,
            'position' => $user->position,
            'birthdayDay' => $user->birthdayDay,
            'birthdayMonth' => $user->birthdayMonth,
            'birthdayYear' => $user->birthdayYear,
            'email' => $user->email,
            'facebook' => $user->facebook,
            'twitter' => $user->twitter,
            'website' => $user->website,
            'about' => $user->about,
            'skills' => $user->skills,
            'created_at' => $user->created_at,
            'sms' => $user->sms,
            'pb' => $user->pb,

        ];

        $res = [
            'status_code' => 200,
            'data' => $data,
        ];

        return $res;

    }

}
