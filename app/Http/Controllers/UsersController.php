<?php

namespace zenlix\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use zenlix\Groups;
use zenlix\Http\Controllers\Controller;
use zenlix\User;
use zenlix\UserFields;
use zenlix\UserProfile;

class UsersController extends Controller
{

//showFind
    public function showFind(Request $request)
    {

        $iam = Auth::user();
        $myGroupsUser = [];
        foreach ($iam->GroupUser() as $value) {
            # code...
            array_push($myGroupsUser, $value->id);
        }

        if ($request->group) {
            $group = $request->group;
            $users = User::where('name', 'LIKE', '%' . $request->name . '%')
                ->whereHas('groups', function ($q) use ($group) {
                    $q->where('id', $group);
                })
                ->where('id', '!=', $iam->id)

                ->orderBy('id', 'DESC')->paginate(10);
        } else {

            $users = User::where('name', 'LIKE', '%' . $request->name . '%')
                ->whereHas('groups', function ($q) use ($myGroupsUser) {
                    $q->whereIn('id', $myGroupsUser);
                })
                ->where('id', '!=', $iam->id)
                ->orderBy('id', 'DESC')->paginate(10);

        }

        $users->setPath('users');

        $groups = $iam->groups;

        $groupsArr = [];
        $groupsArr[''] = 'empty';
        foreach ($groups as $group) {
            $groupsArr[$group->id] = $group->name;
            # code...
        }

        $data = [
            'users' => $users,
            'groups' => $groupsArr,
        ];

        return view('user.users.list')->with($data);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        $iam = Auth::user();
        $myGroupsUser = [];
        foreach ($iam->GroupUser() as $value) {
            # code...
            array_push($myGroupsUser, $value->id);
        }

        $users = User::whereHas('groups', function ($q) use ($myGroupsUser) {
            $q->whereIn('id', $myGroupsUser);
        })
            ->where('id', '!=', $iam->id)
            ->orderBy('id', 'DESC')->paginate(10);

        $users->setPath('users');

        $groups = $iam->groups;

        $groupsArr = [];
        $groupsArr[''] = 'empty';
        foreach ($groups as $group) {
            $groupsArr[$group->id] = $group->name;
            # code...
        }

        $data = [
            'users' => $users,
            'groups' => $groupsArr,
        ];

        return view('user.users.list')->with($data);
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
        // return $id;

        $userProfile = UserProfile::where('user_urlhash', $id)->firstOrFail();
        $user = User::withTrashed()->findOrFail($userProfile->user_id);
        $fields = UserFields::where('status', 'true')->get();
        $data = ['user' => $user,
            'fields' => $fields];

        if ($user->deleted_at == null) {
            return view('user.page')->with($data);
        } else {
            return view('user.pageDeleted')->with($data);
        }

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
