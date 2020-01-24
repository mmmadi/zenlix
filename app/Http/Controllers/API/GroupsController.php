<?php

namespace zenlix\Http\Controllers\API;

use Illuminate\Http\Request;
use JWTAuth;
use zenlix\Groups;
use zenlix\Http\Controllers\Controller;

class GroupsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        $user = JWTAuth::parseToken()->authenticate();

        $groupsDB = $user->groups()->wherePivot('priviliges', 'user')->orderBy('id', 'DESC')->get();

        $groups = [];

        foreach ($groupsDB as $groupsDB_once) {
            # code...
            array_push($groups, [

                'name' => $groupsDB_once->name,
                'description' => $groupsDB_once->description,
                'group_urlhash' => $groupsDB_once->group_urlhash,
                'created_at' => $groupsDB_once->created_at,

            ]);
        }

        $data = [

            'groups' => $groups,
        ];

        $res = [
            'status_code' => 200,
            'data' => $data,
        ];

        return $res;

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
    public function show(Request $request)
    {
        //

        $group = Groups::where('group_urlhash', $request->group_urlhash)->firstOrFail();

        $data = [

            'name' => $group->name,
            'description' => $group->description,
            'status' => $group->status,
            'description_full' => $group->description_full,
            'slogan' => $group->slogan,
            'address' => $group->address,
            'tags' => $group->tags,
            'facebook' => $group->facebook,
            'twitter' => $group->twitter,
            'group_urlhash' => $group->group_urlhash,
            'created_at' => $group->created_at,

        ];

        $res = [
            'status_code' => 200,
            'data' => $data,
        ];

        return $res;

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
