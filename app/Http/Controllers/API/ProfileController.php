<?php

namespace zenlix\Http\Controllers\API;

use Illuminate\Http\Request;
use JWTAuth;
use Validator;
use zenlix\Http\Controllers\API\APIController;

class ProfileController extends APIController
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

        ($user->profile->user_img == null) ? $userImg = config('app.url') . '/dist/img/def_usr.png' : $userImg = $user->profile->user_img;

        $data = [

            'user_img' => $userImg,
            'user_cover' => $user->profile->user_cover,
            'lang' => $user->profile->lang,
            'full_name' => $user->profile->full_name,
            'user_urlhash' => $user->profile->user_urlhash,
            'telephone' => $user->profile->telephone,
            'skype' => $user->profile->skype,
            'address' => $user->profile->address,
            'position' => $user->profile->position,
            'birthdayDay' => $user->profile->birthdayDay,
            'birthdayMonth' => $user->profile->birthdayMonth,
            'birthdayYear' => $user->profile->birthdayYear,
            'email' => $user->profile->email,
            'facebook' => $user->profile->facebook,
            'twitter' => $user->profile->twitter,
            'website' => $user->profile->website,
            'about' => $user->profile->about,
            'skills' => $user->profile->skills,
            'created_at' => $user->profile->created_at,
            'sms' => $user->profile->sms,
            'pb' => $user->profile->pb,

        ];

        $res = [
            'status_code' => 200,
            'data' => $data,
        ];

        return $res;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function update(Request $request)
    {

        $user = JWTAuth::parseToken()->authenticate();

        $req = $request->except('created_at', 'user_img');

        $validator = Validator::make($req,
            [
                'full_name' => 'min:3|max:255',
                'email' => 'email|unique:users,email,' . $user->id,
                'user_urlhash' => 'alpha_num|min:2|max:255|unique:user_profiles,user_urlhash,' . $user->id . ',user_id',

            ]);

        if ($validator->fails()) {
            throw new \Dingo\Api\Exception\UpdateResourceFailedException('Could not update user profile.', $validator->errors());
            //return back()->withErrors($validator);

        } else {

            $user->profile()->update($req);

//dd($req);

            if (isset($req['full_name'])) {
                $user->update(['name' => $req['full_name']]);
            }

            $res = [
                'status_code' => 200,
                'data' => $req,
            ];

            return $res;
        }

    }

}
