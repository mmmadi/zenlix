<?php

namespace zenlix\Http\Controllers;

use Illuminate\Http\Request;
use PhpImap\Mailbox as ImapMailbox;
use Setting;
use Validator;
use zenlix\Groups;
use zenlix\GroupTicketConf;
use zenlix\Http\Controllers\Controller;
use zenlix\Ticket;
use zenlix\TicketAdv;
use zenlix\TicketForms;
use zenlix\TicketSla;
use zenlix\TicketSubj;
use zenlix\User;
use zenlix\UserTicketConf;

//use CarbonInterval;

class ConfigTicketController extends Controller
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

//indexConfig
    public function indexConfig()
    {

        $selTicketCode = Setting::get('ticket.code', 'code');

        if ($selTicketCode == 'autoinc') {
            $ticketCodeInc = true;
            $ticketCodeCode = false;
        } else if ($selTicketCode == 'code') {
            $ticketCodeInc = false;
            $ticketCodeCode = true;
        }

        $data = [
            'ticketCodeInc' => $ticketCodeInc,
            'ticketCodeCode' => $ticketCodeCode,
        ];

        return view('admin.ticket.config')->with($data);
    }

    public function indexForms()
    {
        //
        //dd(CarbonInterval::create(2, 0, 0, 1, 1, 2, 7));

        $forms = TicketForms::all();

        $data = [
            'forms' => $forms,

        ];

        return view('admin.ticket.list')->with($data);
    }

    public function indexSubj()
    {
        //

        $subj = TicketSubj::all();
        $data = [
            'subjs' => $subj,
        ];

        return view('admin.ticket.listSubj')->with($data);

    }

    public function indexSla()
    {
        //

        $slas = TicketSla::all();
        $data = [
            'slas' => $slas,
        ];

        return view('admin.ticket.listSla')->with($data);

    }

    public function indexAdv()
    {
        //

        $advs = TicketAdv::all();

        $data = [
            'advs' => $advs,
        ];

        return view('admin.ticket.listAdv')->with($data);

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

    public function createSubj()
    {
        //
        $groups = Groups::all();
        $groupsArr = [];
        foreach ($groups as $key => $value) {
            $groupsArr[$value->id] = $value->name;
        }

        $users = User::all();
        $usersArr = [];
        foreach ($users as $key => $value) {
            $usersArr[$value->id] = $value->name;
        }

        $data = [
            'groups' => $groupsArr,
            'users' => $usersArr,
        ];

        return view('admin.ticket.createSubj')->with($data);
    }
    public function storeSubj(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
        ]);

        if ($validator->fails()) {

            $request->session()->flash('alert-warning', trans('handler.msgFixErrors'));
            return back()->withErrors($validator)->withInput();

        } else {

            $user = TicketSubj::create([
                'name' => $request->name,
            ]);

            $request->session()->flash('alert-success', trans('handler.subjSuccessCreated'));
            return redirect('/admin/ticket/subj');

        }

    }

    public function createForms()
    {
        //

        $fields = TicketAdv::all();
        $fieldsArr = [];
        foreach ($fields as $key => $value) {
            $fieldsArr[$value->id] = $value->name;
        }

        $slas = TicketSla::all();
        $slasArr = [];
        foreach ($slas as $key => $value) {
            $slasArr[$value->id] = $value->name;
        }

        $groups = Groups::all();
        $groupsArr = [];
        foreach ($groups as $key => $value) {
            $groupsArr[$value->id] = $value->name;
        }

        $users = User::all();
        $usersArr = [];
        foreach ($users as $key => $value) {
            $usersArr[$value->id] = $value->name;
        }

        $subjs = TicketSubj::all();
        $subjsArr = [];
        foreach ($subjs as $key => $value) {
            $subjsArr[$value->id] = $value->name;
        }

        $data = [
            'fields' => $fieldsArr,
            'slas' => $slasArr,
            'groups' => $groupsArr,
            'users' => $usersArr,
            'subjs' => $subjsArr,
        ];

        return view('admin.ticket.createForm')->with($data);

    }

    public function editForms(Request $request, $id)
    {
        //
        $form = TicketForms::findOrFail($id);

        $fields = TicketAdv::all();
        $fieldsArr = [];
        foreach ($fields as $key => $value) {
            $fieldsArr[$value->id] = $value->name;
        }

        $fieldsSel = $form->fields;
        $fieldsArrSel = [];
        foreach ($fieldsSel as $value) {
            array_push($fieldsArrSel, $value->id);
        }

        $slas = TicketSla::all();
        $slasArr = [];
        foreach ($slas as $key => $value) {
            $slasArr[$value->id] = $value->name;
        }

        $slasSel = $form->slas;
        $slasArrSel = [];
        foreach ($slasSel as $value) {
            array_push($slasArrSel, $value->id);
        }

        $groups = Groups::all();
        $groupsArr = [];
        foreach ($groups as $key => $value) {
            $groupsArr[$value->id] = $value->name;
        }
        //dd($form->lists('clientGroups'));
        $groupsSel = $form->clientGroups;
        $groupsArrSel = [];
        foreach ($groupsSel as $value) {
            array_push($groupsArrSel, $value->id);
        }

        $groupsTargetSel = $form->targetGroups;
        $groupsTargetArrSel = [];
        foreach ($groupsTargetSel as $value) {
            array_push($groupsTargetArrSel, $value->id);
        }

        $users = User::all();
        $usersArr = [];
        foreach ($users as $key => $value) {
            $usersArr[$value->id] = $value->name;
        }

        $usersTargetSel = $form->targetUsers;
        $usersTargetArrSel = [];
        foreach ($usersTargetSel as $value) {
            array_push($usersTargetArrSel, $value->id);
        }

        $subjs = TicketSubj::all();
        $subjsArr = [];
        foreach ($subjs as $key => $value) {
            $subjsArr[$value->id] = $value->name;
        }

        $subjsSel = $form->subjs;
        $subjsArrSel = [];
        foreach ($subjsSel as $value) {
            array_push($subjsArrSel, $value->id);
        }

        $upload_files_types = ['jpeg' => 'jpeg',
            'bmp' => 'bmp',
            'png' => 'png',
            'pdf' => 'pdf',
            'doc' => 'doc',
            'docx' => 'docx'];

        $upload_files_typesDB = explode(',', $form->upload_files_types);
        $upload_files_typesSel = [];
        foreach ($upload_files_typesDB as $value) {
            # code...
            $upload_files_types[$value] = $value;
            array_push($upload_files_typesSel, $value);
        }

//$upload_files_typesSel=[];

        $data = [
            'fields' => $fieldsArr,
            'fieldsSel' => $fieldsArrSel,
            'slas' => $slasArr,
            'slasSel' => $slasArrSel,
            'form' => $form,
            'groups' => $groupsArr,
            'groupsSel' => $groupsArrSel,
            'groupsTargetSel' => $groupsTargetArrSel,
            'usersTargetSel' => $usersTargetArrSel,
            'users' => $usersArr,
            'subjs' => $subjsArr,
            'subjsSel' => $subjsArrSel,
            'upload_files_types' => $upload_files_types,
            'upload_files_typesSel' => $upload_files_typesSel,
        ];

        return view('admin.ticket.editForm')->with($data);

    }
    public function updateForms(Request $request, $id)
    {

        $form = TicketForms::findOrFail($id);
        //
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
        ]);

        if ($validator->fails()) {

            $request->session()->flash('alert-warning', trans('handler.msgFixErrors'));
            return back()->withErrors($validator)->withInput();

        } else {

//ОБНОВЛЕнИЕ ФОРМЫ
$upload_files_types=Null;
if (!empty($request->upload_files_types)) {
    $upload_files_types=implode(',', $request->upload_files_types);
}

            $form->update([

                'name' => $request->name,
                'client_field' => $request->client_field,
                'target_field' => $request->target_field,
                'prio' => $request->prio,
                'subj_field' => $request->subj_field,
                'upload_files' => $request->upload_files,
                'upload_files_types' => $upload_files_types,
                'upload_files_count' => $request->upload_files_count,
                'upload_files_size' => $request->upload_files_size,
                'deadline_field' => $request->deadline_field,
                'watching_field' => $request->watching_field,
                'individual_ok_field' => $request->individual_ok_field,
                'check_after_ok' => $request->check_after_ok,
                'create_user' => $request->create_user,

            ]);

            if (!empty($request->clientGroups)) {

                $clientGroupsArr = $request->clientGroups;
                $form->clientGroups()->sync($clientGroupsArr);

            } else { $form->clientGroups()->detach();}

            if (!empty($request->target_groups)) {

                $targetGroupsArr = $request->target_groups;
                $form->targetGroups()->sync($targetGroupsArr);

            } else { $form->targetGroups()->detach();}

            if (!empty($request->target_users)) {

                $targetUsersArr = $request->target_users;
                $form->targetUsers()->sync($targetUsersArr);

            } else { $form->targetUsers()->detach();}

            if (!empty($request->subj_lists)) {

                $subjsArr = $request->subj_lists;
                $form->subjs()->sync($subjsArr);

            } else { $form->subjs()->detach();}

            if (!empty($request->slas)) {

                $slasArr = $request->slas;
                $form->slas()->sync($slasArr);

            } else { $form->slas()->detach();}

            if (!empty($request->fields)) {

                $fieldsArr = $request->fields;
                $form->fields()->sync($fieldsArr);

            } else { $form->fields()->detach();}

            $request->session()->flash('alert-success', trans('handler.FormSuccessUpdated'));
            return redirect('/admin/ticket/forms');

        }

    }

    public function createAdv()
    {
        //

        $data = [

        ];

        return view('admin.ticket.createAdv')->with($data);

    }

    public function createSla()
    {
        //

        $data = [

        ];

        return view('admin.ticket.createSla')->with($data);

    }

//storeForms
    public function storeForms(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
        ]);

        if ($validator->fails()) {

            $request->session()->flash('alert-warning', trans('handler.msgFixErrors'));
            return back()->withErrors($validator)->withInput();

        } else {

$upload_files_types=Null;
if (!empty($request->upload_files_types)) {
    $upload_files_types=implode(',', $request->upload_files_types);
}

            $ticketForm = TicketForms::create([

                'name' => $request->name,
                'client_field' => $request->client_field,
                'target_field' => $request->target_field,
                'prio' => $request->prio,
                'subj_field' => $request->subj_field,
                'upload_files' => $request->upload_files,
                'upload_files_types' => $upload_files_types,
                'upload_files_count' => $request->upload_files_count,
                'upload_files_size' => $request->upload_files_size,
                'deadline_field' => $request->deadline_field,
                'watching_field' => $request->watching_field,
                'individual_ok_field' => $request->individual_ok_field,
                'check_after_ok' => $request->check_after_ok,
                'create_user' => $request->create_user,

            ]);

            if (!empty($request->clientGroups)) {

                $clientGroupsArr = $request->clientGroups;
                $ticketForm->clientGroups()->attach($clientGroupsArr);

            }

            if (!empty($request->target_groups)) {

                $targetGroupsArr = $request->target_groups;
                $ticketForm->targetGroups()->attach($targetGroupsArr);

            }

            if (!empty($request->target_users)) {

                $targetUsersArr = $request->target_users;
                $ticketForm->targetUsers()->attach($targetUsersArr);

            }

            if (!empty($request->subj_lists)) {

                $subjsArr = $request->subj_lists;
                $ticketForm->subjs()->attach($subjsArr);

            }

            if (!empty($request->slas)) {

                $slasArr = $request->slas;
                $ticketForm->slas()->attach($slasArr);

            }

            if (!empty($request->fields)) {

                $fieldsArr = $request->fields;
                $ticketForm->fields()->attach($fieldsArr);

            }

            $request->session()->flash('alert-success', trans('handler.FormSuccessCreated'));
            return redirect('/admin/ticket/forms');

        }

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

    public function storeSla(Request $request)
    {
/*
digits_between:min,max

react_low_1
react_low_2
react_low_3
react_low_4
react_def_1
react_def_2
react_def_3
react_def_4
react_high_1
react_high_2
react_high_3
react_high_4

work_low_1
work_low_2
work_low_3
work_low_4
work_def_1
work_def_2
work_def_3
work_def_4
work_high_1
work_high_2
work_high_3
work_high_4

deadline_low_1
deadline_low_2
deadline_low_3
deadline_low_4
deadline_def_1
deadline_def_2
deadline_def_3
deadline_def_4
deadline_high_1
deadline_high_2
deadline_high_3
deadline_high_4
 */

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',

        ]);

        if ($validator->fails()) {

            $request->session()->flash('alert-warning', trans('handler.msgFixErrors'));
            return back()->withErrors($validator)->withInput();

        } else {

//= ((($request->react_low_1 * 24 + $request->react_low_2) * 60 + $request->react_low_3) * 60)+$request->react_low_4;

            $react_low = ((($request->react_low_1 * 24 + $request->react_low_2) * 60 + $request->react_low_3) * 60) + $request->react_low_4;

            $react_def = ((($request->react_def_1 * 24 + $request->react_def_2) * 60 + $request->react_def_3) * 60) + $request->react_def_4;

            $react_high = ((($request->react_high_1 * 24 + $request->react_high_2) * 60 + $request->react_high_3) * 60) + $request->react_high_4;

            $work_low = ((($request->work_low_1 * 24 + $request->work_low_2) * 60 + $request->work_low_3) * 60) + $request->work_low_4;

            $work_def = ((($request->work_def_1 * 24 + $request->work_def_2) * 60 + $request->work_def_3) * 60) + $request->work_def_4;

            $work_high = ((($request->work_high_1 * 24 + $request->work_high_2) * 60 + $request->work_high_3) * 60) + $request->work_high_4;

            $deadline_low = ((($request->deadline_low_1 * 24 + $request->deadline_low_2) * 60 + $request->deadline_low_3) * 60) + $request->deadline_low_4;

            $deadline_def = ((($request->deadline_def_1 * 24 + $request->deadline_def_2) * 60 + $request->deadline_def_3) * 60) + $request->deadline_def_4;

            $deadline_high = ((($request->deadline_high_1 * 24 + $request->deadline_high_2) * 60 + $request->deadline_high_3) * 60) + $request->deadline_high_4;

            $user = TicketSla::create([
                'name' => $request->name,

                'reaction_time_def' => $react_def,
                'reaction_time_low_prio' => $react_low,
                'reaction_time_high_prio' => $react_high,
                'work_time_def' => $work_def,
                'work_time_low_prio' => $work_low,
                'work_time_high_prio' => $work_high,
                'deadline_time_def' => $deadline_def,
                'deadline_time_low_prio' => $deadline_low,
                'deadline_time_high_prio' => $deadline_high,

            ]);

            $request->session()->flash('alert-success', trans('handler.SlaSuccessCreated'));
            return redirect('/admin/ticket/sla');

        }

    }

    public function storeAdv(Request $request)
    {
        //

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'field_name' => 'required|max:255',
        ]);

        if ($validator->fails()) {

            $request->session()->flash('alert-warning', trans('handler.msgFixErrors'));
            return back()->withErrors($validator)->withInput();

        } else {

            TicketAdv::create([
                'name' => $request->name,
                'f_type' => $request->f_type,
                'required' => $request->required,
                'field_name' => $request->field_name,
                'field_value' => $request->field_value,
                'field_placeholder' => $request->field_placeholder,
                'field_hash' => str_random(40),
            ]);

            $request->session()->flash('alert-success', trans('handler.FieldSuccessCreated'));
            return redirect('/admin/ticket/adv');

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
    }

    public function editAdv($id)
    {
        //
        $ticketAdv = TicketAdv::findOrFail($id);

        $data = ['field' => $ticketAdv];

        return view('admin.ticket.editAdv')->with($data);
    }

    public function updateAdv(Request $request, $id)
    {
        //

        $ticketAdv = TicketAdv::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'field_name' => 'required|max:255',
        ]);

        if ($validator->fails()) {

            $request->session()->flash('alert-warning', trans('handler.msgFixErrors'));
            return back()->withErrors($validator)->withInput();

        } else {

            $ticketAdv->update([
                'name' => $request->name,
                'f_type' => $request->f_type,
                'required' => $request->required,
                'field_name' => $request->field_name,
                'field_value' => $request->field_value,
                'field_placeholder' => $request->field_placeholder,
            ]);

            $request->session()->flash('alert-success', trans('handler.FieldSuccessChanged'));
            return redirect('/admin/ticket/adv');

        }

    }

    public function editSla($id)
    {
        //
        $ticketSla = TicketSla::findOrFail($id);

        $ticketSla['react_low_1'] = floor(($ticketSla->reaction_time_low_prio % 2592000) / 86400);
        $ticketSla['react_low_2'] = floor(($ticketSla->reaction_time_low_prio % 86400) / 3600);
        $ticketSla['react_low_3'] = floor(($ticketSla->reaction_time_low_prio % 3600) / 60);
        $ticketSla['react_low_4'] = $ticketSla->reaction_time_low_prio % 60;

        $ticketSla['react_def_1'] = floor(($ticketSla->reaction_time_def % 2592000) / 86400);
        $ticketSla['react_def_2'] = floor(($ticketSla->reaction_time_def % 86400) / 3600);
        $ticketSla['react_def_3'] = floor(($ticketSla->reaction_time_def % 3600) / 60);
        $ticketSla['react_def_4'] = $ticketSla->reaction_time_def % 60;

        $ticketSla['react_high_1'] = floor(($ticketSla->reaction_time_high_prio % 2592000) / 86400);
        $ticketSla['react_high_2'] = floor(($ticketSla->reaction_time_high_prio % 86400) / 3600);
        $ticketSla['react_high_3'] = floor(($ticketSla->reaction_time_high_prio % 3600) / 60);
        $ticketSla['react_high_4'] = $ticketSla->reaction_time_high_prio % 60;

        $ticketSla['work_low_1'] = floor(($ticketSla->work_time_low_prio % 2592000) / 86400);
        $ticketSla['work_low_2'] = floor(($ticketSla->work_time_low_prio % 86400) / 3600);
        $ticketSla['work_low_3'] = floor(($ticketSla->work_time_low_prio % 3600) / 60);
        $ticketSla['work_low_4'] = $ticketSla->work_time_low_prio % 60;

        $ticketSla['work_def_1'] = floor(($ticketSla->work_time_def % 2592000) / 86400);
        $ticketSla['work_def_2'] = floor(($ticketSla->work_time_def % 86400) / 3600);
        $ticketSla['work_def_3'] = floor(($ticketSla->work_time_def % 3600) / 60);
        $ticketSla['work_def_4'] = $ticketSla->work_time_def % 60;

        $ticketSla['work_high_1'] = floor(($ticketSla->work_time_high_prio % 2592000) / 86400);
        $ticketSla['work_high_2'] = floor(($ticketSla->work_time_high_prio % 86400) / 3600);
        $ticketSla['work_high_3'] = floor(($ticketSla->work_time_high_prio % 3600) / 60);
        $ticketSla['work_high_4'] = $ticketSla->work_time_high_prio % 60;

        $ticketSla['deadline_low_1'] = floor(($ticketSla->deadline_time_low_prio % 2592000) / 86400);
        $ticketSla['deadline_low_2'] = floor(($ticketSla->deadline_time_low_prio % 86400) / 3600);
        $ticketSla['deadline_low_3'] = floor(($ticketSla->deadline_time_low_prio % 3600) / 60);
        $ticketSla['deadline_low_4'] = $ticketSla->deadline_time_low_prio % 60;

        $ticketSla['deadline_def_1'] = floor(($ticketSla->deadline_time_def % 2592000) / 86400);
        $ticketSla['deadline_def_2'] = floor(($ticketSla->deadline_time_def % 86400) / 3600);
        $ticketSla['deadline_def_3'] = floor(($ticketSla->deadline_time_def % 3600) / 60);
        $ticketSla['deadline_def_4'] = $ticketSla->deadline_time_def % 60;

        $ticketSla['deadline_high_1'] = floor(($ticketSla->deadline_time_high_prio % 2592000) / 86400);
        $ticketSla['deadline_high_2'] = floor(($ticketSla->deadline_time_high_prio % 86400) / 3600);
        $ticketSla['deadline_high_3'] = floor(($ticketSla->deadline_time_high_prio % 3600) / 60);
        $ticketSla['deadline_high_4'] = $ticketSla->deadline_time_high_prio % 60;

        $data = ['sla' => $ticketSla];

        return view('admin.ticket.editSla')->with($data);
    }

    public function updateSla(Request $request, $id)
    {
        //

        $ticketSla = TicketSla::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
        ]);

        if ($validator->fails()) {

            $request->session()->flash('alert-warning', trans('handler.msgFixErrors'));
            return back()->withErrors($validator)->withInput();

        } else {

            $react_low = ((($request->react_low_1 * 24 + $request->react_low_2) * 60 + $request->react_low_3) * 60) + $request->react_low_4;

            $react_def = ((($request->react_def_1 * 24 + $request->react_def_2) * 60 + $request->react_def_3) * 60) + $request->react_def_4;

            $react_high = ((($request->react_high_1 * 24 + $request->react_high_2) * 60 + $request->react_high_3) * 60) + $request->react_high_4;

            $work_low = ((($request->work_low_1 * 24 + $request->work_low_2) * 60 + $request->work_low_3) * 60) + $request->work_low_4;

            $work_def = ((($request->work_def_1 * 24 + $request->work_def_2) * 60 + $request->work_def_3) * 60) + $request->work_def_4;

            $work_high = ((($request->work_high_1 * 24 + $request->work_high_2) * 60 + $request->work_high_3) * 60) + $request->work_high_4;

            $deadline_low = ((($request->deadline_low_1 * 24 + $request->deadline_low_2) * 60 + $request->deadline_low_3) * 60) + $request->deadline_low_4;

            $deadline_def = ((($request->deadline_def_1 * 24 + $request->deadline_def_2) * 60 + $request->deadline_def_3) * 60) + $request->deadline_def_4;

            $deadline_high = ((($request->deadline_high_1 * 24 + $request->deadline_high_2) * 60 + $request->deadline_high_3) * 60) + $request->deadline_high_4;

            $ticketSla->update([
                'name' => $request->name,
                'reaction_time_def' => $react_def,
                'reaction_time_low_prio' => $react_low,
                'reaction_time_high_prio' => $react_high,
                'work_time_def' => $work_def,
                'work_time_low_prio' => $work_low,
                'work_time_high_prio' => $work_high,
                'deadline_time_def' => $deadline_def,
                'deadline_time_low_prio' => $deadline_low,
                'deadline_time_high_prio' => $deadline_high,
            ]);

            $request->session()->flash('alert-success', trans('handler.SlaSuccessUpdated'));
            return redirect('/admin/ticket/sla');

        }

    }

    public function editSubj($id)
    {
        //
        $ticketSubj = TicketSubj::findOrFail($id);

        $data = ['subj' => $ticketSubj];

        return view('admin.ticket.editSubj')->with($data);
    }

    public function updateSubj(Request $request, $id)
    {
        //

        $ticketSubj = TicketSubj::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
        ]);

        if ($validator->fails()) {

            $request->session()->flash('alert-warning', trans('handler.msgFixErrors'));
            return back()->withErrors($validator)->withInput();

        } else {

            $ticketSubj->update([
                'name' => $request->name,
            ]);

            $request->session()->flash('alert-success', trans('handler.SubjectSuccessUpdated'));
            return redirect('/admin/ticket/subj');

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

/*ticketCode
autoinc
code

ticketCodeCount*/
/*ticketDays2arch
ticketDays2Del*/

        Setting::set('ticket.days2arch', $request->ticketDays2arch);
        Setting::set('ticket.days2del', $request->ticketDays2Del);
        Setting::set('ticket.code', $request->ticketCode);
        Setting::set('ticket.codeCount', $request->ticketCodeCount);
        Setting::set('ticket.deadlineNotifyStatus', $request->ticketDeadlineNotifyStatus);
        Setting::set('ticket.deadlineNotify', $request->ticketDeadlineNotify);

        Setting::set('ticket.overtimeNotifyStatus', $request->ticketOvertimeNotifyStatus);

//ticketDeadlineNotifyStatus
        Setting::save();

        $request->session()->flash('alert-success', trans('handler.PerfSaved'));
        return redirect('/admin/ticket/config');

    }

//indexTicketMail
    public function indexTicketMail()
    {

        $tG = Groups::all();

        $tGArr = [];
        $tGArr[null] = 'Select item';
        foreach ($tG as $key => $value) {
            $tGArr[$value->id] = $value->name;
        }

        $tU = User::all();

        $tUArr = [];
        foreach ($tU as $key => $value) {
            $tUArr[$value->id] = $value->name;
        }

        $data = [
            'tG' => $tGArr,
            'tU' => $tUArr,
        ];

        return view('admin.ticket.configMail')->with($data);

    }

//updateTicketMailTest
    public function updateTicketMailTest(Request $request)
    {

        $serverAddr = Setting::get('ticket.ReceiveMail.AuthAddr');
        $serverPort = Setting::get('ticket.ReceiveMail.AuthPort');
        $serverSecurity = Setting::get('ticket.ReceiveMail.AuthSecurity');
        $serverLogin = Setting::get('ticket.ReceiveMail.AuthLogin');
        $serverPass = Setting::get('ticket.ReceiveMail.AuthPass');
        $serverDirectory = Setting::get('ticket.ReceiveMail.ServerDirectory');
        $serverFilter = Setting::get('ticket.ReceiveMail.filter');

        try {

            $mailbox = new ImapMailbox('{' . $serverAddr . ':' . $serverPort . '' . $serverSecurity . '}' . $serverDirectory . '', $serverLogin, $serverPass, storage_path('/tmp/'));
            $mailbox->searchMailbox($serverFilter);
        } catch (\Exception $e) {
            // fail
            return '<pre>' . $e . '</pre>';
        }

        return 'ok';

    }

    //updateTicketMail
    public function updateTicketMail(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'AuthMail' => 'required|email',
            'targetUsers' => 'required_without:targetGroup',
            'targetGroup' => 'required_without:targetUsers',
        ]);

        if ($validator->fails()) {

            $request->session()->flash('alert-warning', trans('handler.msgFixErrors'));
            return back()->withErrors($validator)->withInput();

        } else {

            (count($request->targetUsers) > 0) ? $targetUsers = implode(',', $request->targetUsers) : $targetUsers = '';
            (count($request->clients) > 0) ? $clients = implode(',', $request->clients) : $clients = '';
            (count($request->watching) > 0) ? $watching = implode(',', $request->watching) : $watching = '';
            (count($request->tags) > 0) ? $tags = implode(',', $request->tags) : $tags = '';
            (count($request->upload_files_types) > 0) ? $upload_files_types = implode(',', $request->upload_files_types) : $upload_files_types = null;

//dd($request->all());

            Setting::set('ticket.ReceiveMail.status', $request->status);
            Setting::set('ticket.ReceiveMail.receiveAnon', $request->receiveAnon);
            Setting::set('ticket.ReceiveMail.ServerDirectory', $request->ServerDirectory);
            Setting::set('ticket.ReceiveMail.AuthMail', $request->AuthMail);
            Setting::set('ticket.ReceiveMail.AuthAddr', $request->AuthAddr);
            Setting::set('ticket.ReceiveMail.AuthPort', $request->AuthPort);
            Setting::set('ticket.ReceiveMail.AuthLogin', $request->AuthLogin);
            if (!empty($request->AuthPass)) {Setting::set('ticket.ReceiveMail.AuthPass', $request->AuthPass);}
            Setting::set('ticket.ReceiveMail.AuthSecurity', $request->AuthSecurity);
            Setting::set('ticket.ReceiveMail.filter', $request->filter);
            Setting::set('ticket.ReceiveMail.targetGroup', $request->targetGroup);
            Setting::set('ticket.ReceiveMail.targetUsers', $targetUsers);
            Setting::set('ticket.ReceiveMail.clients', $clients);
            Setting::set('ticket.ReceiveMail.watching', $watching);
            Setting::set('ticket.ReceiveMail.tags', $tags);
            Setting::set('ticket.ReceiveMail.upload_files', $request->status);
            Setting::set('ticket.ReceiveMail.upload_files_types', $upload_files_types);
            Setting::set('ticket.ReceiveMail.upload_files_count', $request->upload_files_count);
            Setting::set('ticket.ReceiveMail.upload_files_size', $request->upload_files_size);
            Setting::save();

            $request->session()->flash('alert-success', 'Настройки сохранены!');
            return redirect('/admin/ticket/mail');

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

    public function destroySubj($id)
    {
        //
        $subj = TicketSubj::findOrFail($id);
        $subj->delete();

    }
//destroySla
    public function destroySla($id)
    {
        //
        $sla = TicketSla::findOrFail($id);

        Ticket::where('sla_id', $id)->update([
            'sla_id' => null,
        ]);

        $sla->delete();

    }

    //destroyAdv
    public function destroyAdv($id)
    {
        //
        $adv = TicketAdv::findOrFail($id);
        $adv->delete();

    }

//destroyForm
    public function destroyForm($id)
    {
        //
        $form = TicketForms::findOrFail($id);

        UserTicketConf::where('ticket_form_id', $id)->update([
            'ticket_form_id' => '1',
        ]);

        GroupTicketConf::where('ticket_form_id', $id)->update([
            'ticket_form_id' => '1',
        ]);

        $form->delete();

    }

}
