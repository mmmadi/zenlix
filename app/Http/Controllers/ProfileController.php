<?php

namespace zenlix\Http\Controllers;

use Auth;
use File;
use Hash;
use Illuminate\Http\Request;
use Image;
use Setting;
use Validator;
use zenlix\Classes\Zen;
use zenlix\Http\Controllers\Controller;
use zenlix\User;
use zenlix\UserFields;
use zenlix\UserFieldsData;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function edit()
    {
        //
        $user = Auth::user();

//$user->profile->skills

        $skills = [];
        $skillsSel = [];
        $skillsDB = explode(',', $user->profile->skills);
        foreach ($skillsDB as $value) {
            # code...
            $skills[$value] = $value;
            array_push($skillsSel, $value);
        }

//$skills=explode(',', $user->profile->skills);

        $fields = UserFields::where('status', 'true')->get();

        foreach ($fields as $f) {
            $user->fields()->firstOrCreate(['user_field_id' => $f->id]);
        }

        $mailNotify = Zen::showNotifyList();

        $smsNotify = Zen::showNotifyList();

        $smsAccess = explode(',', Setting::get('smsAccess'));
        $smsNotifyTrue = [];
        foreach ($smsNotify as $key => $value) {
            # code...

            if (in_array($key, $smsAccess)) {
                $smsNotifyTrue[$key] = $value;
                /*array_push($smsNotifyTrue, [
            $key => $value
            ]);*/
            }

        }

/*$mailNotifySelected=[

'create',
'refer',
'lock',
'unlock',
'ok',
'unok',
'waitok',
'aprrove',
'noapprove',
'delete',
'restore',
'comment',
'edit',

];*/

        $uNotify = $user->NotifyConfigCount('mail');
        //dd($uNotify);
        $mailNotifySelected = [];

        if (count($uNotify) > 0) {
            foreach ($uNotify as $Notify) {
                array_push($mailNotifySelected, $Notify->type);
            }
        }

        $uNotifySMS = $user->NotifyConfigCount('sms');
        //dd($uNotify);
        $smsNotifySelected = [];

        if (count($uNotifySMS) > 0) {
            foreach ($uNotifySMS as $NotifySMS) {
                array_push($smsNotifySelected, $NotifySMS->type);
            }
        }

        $data = [
            'user' => $user,
            'skills' => $skills,
            'skillsSel' => $skillsSel,
            'fields' => $fields,
            'mailNotify' => $mailNotify,
            'mailNotifySelected' => $mailNotifySelected,
            'smsNotify' => $smsNotifyTrue,
            'smsNotifySelected' => $smsNotifySelected,
        ];

        return view('user.profileEdit')->with($data);
    }

//updateUserCover
    //deleteUserCover
    public function destroyUserImg(Request $request)
    {
        $user = User::findOrFail(Auth::user()->id);
        $profile = $user->profile;
        if ($profile->user_img != null) {
            $file_name = pathinfo('files/users/img/' . $profile->user_img, PATHINFO_FILENAME);
            $extension = pathinfo('files/users/img/' . $profile->user_img, PATHINFO_EXTENSION);
            //dd($file_name);

            File::delete('files/users/img/' . $profile->user_img);
            File::delete('files/users/img/' . $file_name . "_small." . $extension);

            // $profile->user_img = Null;
            $user->profile()->update(['user_img' => null]);
        }
        return back();
    }

    public function updateUserImg(Request $request)
    {

        $user = User::findOrFail(Auth::user()->id);
        $file = $request->file('user_img');
        $validator = Validator::make(array('user_img' => $file), [
            'user_img' => 'mimes:jpeg,bmp,png',
        ]);
        $extension = $file->getClientOriginalExtension();

        if ($validator->fails()) {

            return back()->withErrors($validator);
        } else {
            // read image from temporary file
            if ($user->profile->user_img != null) {
                $file_name = pathinfo('files/users/img/' . $user->profile->user_img, PATHINFO_FILENAME);
                $extension = pathinfo('files/users/img/' . $user->profile->user_img, PATHINFO_EXTENSION);
                //dd($file_name);

                File::delete('files/users/img/' . $user->profile->user_img);
                File::delete('files/users/img/' . $file_name . "_small." . $extension);
            }

            //Make main avatar
            $img = Image::make($file);
            // resize image
            $img->fit(250, 250);
            // save image
            $string = str_random(20);

            $img->save('files/users/img/' . $string . '.' . $extension);

            //Make small_size
            $img_small = Image::make($file);
            // resize image
            $img_small->fit(60, 60);
            // save image
            //$string = str_random(40);

            $img_small->save('files/users/img/' . $string . '_small.' . $extension);

            //$profile = $user->profile;

            //$profile->user_img = $string . '.' . $extension;

            $user->profile()->update(['user_img' => $string . '.' . $extension]);

            return back();
        }

    }

    public function updatePassword(Request $request)
    {

        $user = User::findOrFail(Auth::user()->id);

//dd($request->password);

        Validator::extend('passcheck', function ($attribute, $value, $parameters) {
            return Hash::check($value, Auth::user()->getAuthPassword());
        });

        $validator = Validator::make($request->all(), [
            'password' => 'required|confirmed|min:6',
            'old_password' => 'required|passcheck|min:6',
        ],
            [
                'passcheck' => 'Your old password was incorrect',
            ]);

        if ($validator->fails()) {
            return redirect('profile/edit#security')->withErrors($validator);
        } else {
            $user->password = bcrypt($request->password);
            $user->save();

            return back();
        }

    }

//updateNotify
    public function updateNotify(Request $request)
    {
        $user = User::findOrFail(Auth::user()->id);

//dd($request->mailNotify);
        $user->notify()->delete();

        if (count($request->mailNotify) > 0) {
            foreach ($request->mailNotify as $mailNotify) {
                $user->notify()->create([

                    'target' => 'mail',
                    'type' => $mailNotify,

                ]);
            }

        }

        if (count($request->smsNotify) > 0) {
            foreach ($request->smsNotify as $smsNotify) {
                $user->notify()->create([

                    'target' => 'sms',
                    'type' => $smsNotify,

                ]);
            }

        }

        (empty($request->sms)) ? $sms = null : $sms = $request->sms;

        $user->profile->update([

            'sms' => $sms,
            'pb' => $request->pb,

        ]);

//$user->notify()->sync($request->mailNotify);

        return back();
    }

    public function updateInterface(Request $request)
    {

        $user = User::findOrFail(Auth::user()->id);
        $profile = $user->profile;
        $UserProfileReq = array_map('trim', $request->all());

        $validator = Validator::make($UserProfileReq, [
            'user_urlhash' => 'alpha_num|required|min:2|max:255|unique:user_profiles,user_urlhash,' . Auth::user()->id . ',user_id',
        ]);

        if ($validator->fails()) {

            return redirect('profile/edit#interface')->withErrors($validator);
        } else {

            $arr = array('lang' => $request->lang,
                'user_urlhash' => $request->user_urlhash);

            $user->profile()->update($arr);

            return back();
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //

        $user = User::findOrFail(Auth::user()->id);
        $profile = $user->profile;

/*if (!empty($request->profile->skills)) {
$request->profile->skills=implode(',', $request->profile->skills);
}*/

//$request->profile['skills']=['false'];

//dd($request->profile['skills']);
        //$request->profile['skills']=Null;
        //$UserProfileReq = array_map('trim', $request->profile->except('profile.skills'));

        $request->name = trim($request->name);

        $UserReq = $request->all();

//dd($request->except('profile.skills'));
        $UserProfileReq = $request->except('profile.skills');

        //$req = array_merge($UserProfileReq, $UserReq);

        $validator = Validator::make([
            'name' => $request->name,
            'profile.position' => $request->profile['position'],
            'profile.email' => $request->profile['email'],
        ],
            [
                'name' => 'required|min:2|max:255',
                'profile.position' => 'min:2|max:128|required',
                'profile.email' => 'required|email|unique:users,email,' . Auth::user()->id,

            ]);

        if ($validator->fails()) {

            return back()->withErrors($validator);
        } else {

            $profileReq = $request->except('profile.skills');
            $profileReq['profile']['skills'] = null;
            if (!empty($request->profile['skills'])) {
                $profileReq['profile']['skills'] = implode(',', $request->profile['skills']);
                //array_push($profileReq['profile'], ['skills'=>implode(',', $request->profile['skills'])]);
            }
//dd($profileReq['profile']);
            //dd($profileReq['profile']);

            $user->profile()->update($profileReq['profile']);

//update fields

            $fields = UserFields::where('status', 'true')->get();
//dd($request->all());
            foreach ($fields as $field) {
                # code...

                $v = 'userfield_' . $field->id;

                if ($field->field_type == 'multiselect') {
                    if (!empty($request->$v)) {
                        $val = implode(',', $request->$v);
                    } else {
                        $val = null;
                    }
                } else {
                    $val = $request->$v;
                }

//    $f=$user->fields()->first(['user_field_id'=>$field->id]);
                UserFieldsData::where('user_field_id', $field->id)->where('user_id', $user->id)
                    ->update([

                        'field_data' => $val,

                    ]);

            }
            

            $user->name = $request->name;
            //$user->email = $request->email;
            $user->save();

            return redirect('profile/edit');
        }

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
