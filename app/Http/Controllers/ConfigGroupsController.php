<?php

namespace zenlix\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Validator;
use zenlix\Groups;
use zenlix\GroupTicketConf;
use zenlix\Http\Controllers\Controller;
use zenlix\Ticket;
use zenlix\TicketForms;
use zenlix\User;
use zenlix\UserTicketConf;

class ConfigGroupsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        $groups = Groups::all();

        $data = [
            'groups' => $groups,
        ];

        return view('admin.groups')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //

        $users = User::all();

        $usersArr = [];
        foreach ($users as $key => $value) {
            $usersArr[$value->id] = $value->name;
        }
        $forms = TicketForms::all();
        $formsArr = [];
        foreach ($forms as $key => $value) {
            $formsArr[$value->id] = $value->name;
        }
        $data = [
            'users' => $usersArr,
            'superusers' => $usersArr,
            'forms' => $formsArr,
        ];

        return view('admin.groupCreate')->with($data);
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

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:2|max:255',
            'description' => 'required|min:2|max:255',
        ]);

        $urlhash = str_random(40);

        if ($validator->fails()) {

            $request->session()->flash('alert-warning', trans('handler.msgFixErrors'));
            return back()->withErrors($validator)->withInput();

        } else {

            $arr = array(
                'name' => $request->name,
                'description' => $request->description,
                'group_urlhash' => $urlhash,
            );
            $group = Groups::create($arr);

            //GroupTicketConf

            $GroupTicketConf = new GroupTicketConf;
            $GroupTicketConf->group_id = $group->id;
            $GroupTicketConf->ticket_form_id = $request->ticketForm;
            $GroupTicketConf->status = 'true';
            $GroupTicketConf->group_type = 'firm';
            $GroupTicketConf->save();

            if (!empty($request->users)) {

                $uArr = $request->users;
                $group->users()->attach($uArr, array('status' => 'success', 'priviliges' => 'user'));
            }

            if (!empty($request->superusers)) {

                $suArr = $request->superusers;
                $group->users()->attach($suArr, array('status' => 'success', 'priviliges' => 'admin'));
            }

            $request->session()->flash('alert-success', trans('handler.groupOkCreate'));
            return redirect('/admin/groups');

        }
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

        $group = Groups::findOrFail($id);
        $users = User::all();
//$forms= TicketForms::all();

        $usersArr = [];
        foreach ($users as $key => $value) {
            $usersArr[$value->id] = $value->name;
        }

        $gl = $group->users()->where('priviliges', 'user')->get();
        $arr = array();
        foreach ($gl as $value) {

            array_push($arr, $value->id);

        }

        $group['users'] = $arr;

        $gls = $group->users()->where('priviliges', 'admin')->get();
        $arrs = array();
        foreach ($gls as $value) {

            array_push($arrs, $value->id);

        }
        $forms = TicketForms::all();
        $formsArr = [];
        foreach ($forms as $key => $value) {
            $formsArr[$value->id] = $value->name;
        }

        $gtf = GroupTicketConf::where('group_id', $group->id)->firstOrFail();
        $group['ticketForm'] = $gtf->ticket_form_id;
//dd($gtf->ticket_form_id);
        $group['superusers'] = $arrs;

        $data = [
            'group' => $group,
            'users' => $usersArr,
            'superusers' => $usersArr,
            'forms' => $formsArr,
        ];

        return view('admin.groupEdit')->with($data);

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

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:2|max:255',
            'description' => 'required|min:2|max:255',
        ]);

        if ($validator->fails()) {

            $request->session()->flash('alert-warning', trans('handler.msgFixErrors'));
            return back()->withErrors($validator)->withInput();

        } else {

            $arr = array(
                'name' => $request->name,
                'description' => $request->description,
            );
            $group = Groups::findOrFail($id);
            $group->update($arr);

            $GroupTicketConf = GroupTicketConf::firstOrCreate(['group_id' => $group->id]);
            $GroupTicketConf->update([
                'ticket_form_id' => $request->ticketForm,
//'status'=>,
                //'group_type'=>,
            ]);

            $group->users()->detach();

            if (!empty($request->users)) {

                $uArr = $request->users;
                $group->users()->attach($uArr, array('status' => 'success', 'priviliges' => 'user'));
            }
            if (!empty($request->superusers)) {

                $uArrS = $request->superusers;
                $group->users()->attach($uArrS, array('status' => 'success', 'priviliges' => 'admin'));
            }

            $request->session()->flash('alert-success', trans('handler.groupOkSave'));
            return redirect('/admin/groups');

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

        $group = Groups::findOrFail($id);
        $user = Auth::user();
        UserTicketConf::where('group_conf_id', $id)->where('conf_params', 'group')->update([

            'conf_params' => 'user',
            'ticket_form_id' => '1',

        ]);

        $tickets = Ticket::where('target_group_id', $id)->get();
        foreach ($tickets as $ticket) {
            $ticket->watchingUsers()->attach([$user->id]);
            $ticket->update([
                'target_group_id' => null,
            ]);
        }

        $group->users()->detach();

        $group->delete();

    }
}
