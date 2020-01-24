<?php

namespace zenlix\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use zenlix\Calendar;
use zenlix\Http\Controllers\Controller;

class CalendarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        $user = Auth::user();

        $myGroups = [];
        $groupsSel = [];
        foreach ($user->GroupUser() as $value) {
            $myGroups[$value->id] = $value->name;
            array_push($groupsSel, $value->id);
        }

        $data = [

            'groups'    => $myGroups,
            'groupsSel' => $groupsSel,

        ];

        return view('user.calendar.page')->with($data);
    }

    public function indexEvents(Request $request)
    {
        $user = Auth::user();
        //
        /*personal
        group
        groupArr*/
//dd($request->groupArr);
        $groupsReq = $request->groupArr;
        $calendarPersonal = [];
        $calendarGroup = [];

        if ($request->personal == "true") {

            $calendarPersonal = Calendar::where('user_id', $user->id)->where('personal', 'true')->lists('id')->toArray();

        }
        if ($request->group == "true") {

            $calendarGroup = Calendar::where('personal', 'false')
                ->whereHas('groups', function ($q) use ($groupsReq) {
                    $q->whereIn('group_id', $groupsReq);
                })
                ->lists('id')->toArray();

        }

//$calendar=collect();
        //
        $eventIds = array_merge($calendarPersonal, $calendarGroup);

        $calendar = Calendar::whereIn('id', $eventIds)->get();
        /*$calendar=Calendar::where(function($query) use ($user) {
        return $query->where('user_id', $user->id)->where('personal', 'true');

        })->orWhere(function($query) use($groupsReq) {
        return $query->where('personal', 'false')
        ->whereHas('groups', function($q) use($groupsReq) {
        $q->whereIn('group_id', $groupsReq);
        });

        })->orderBy('dtStart', 'desc')->get();*/

        $data = [];

        foreach ($calendar as $event) {

            $period = $event->dtStart . ' - ' . $event->dtStop;

            ($event->allday == 'true') ? $allDay = true : $allDay = false;

            $editable = false;
            if ($event->user_id == $user->id) {
                $editable = true;
            }

            array_push($data, [

                'id'              => $event->uniq_hash,
                'title'           => $event->title,
                'description'     => $event->description,

                //'url' => '/ticket?'.$row['hash_name'],
                'start'           => $event->dtStart->format('Y-m-d H:i:s'),
                'end'             => $event->dtStop->format('Y-m-d H:i:s'),
                'allDay'          => $allDay,
                'backgroundColor' => $event->backgroundColor,
                'borderColor'     => $event->borderColor,
                'editable'        => $editable,
                'period'          => $period,

            ]);
        }

        return response()->json($data);
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

//storeEvent
    public function storeEvent(Request $request)
    {
        //

        /*event_name
        event_info
        event_allday
        startDate
        endDate
        event_personal
        groups
        current_backgroundColor
        current_borderColor*/
        $user = Auth::user();

        ($request->event_personal == 'true') ? $event_personal = 'true' : $event_personal = 'false';
        ($request->event_allday == 'true') ? $event_allday = 'true' : $event_allday = 'false';

//dd($request);

        $calendar = Calendar::create([

            'title'           => $request->event_name,
            'dtStart'         => $request->startDate,
            'dtStop'          => $request->endDate,
            'allday'          => $event_allday,
            'backgroundColor' => $request->current_backgroundColor,
            'borderColor'     => $request->current_borderColor,
            'description'     => $request->event_info,
            'user_id'         => $user->id,
            'uniq_hash'       => str_random(20),
            'personal'        => $event_personal,

        ]);

        if (count($request->groups) > 0) {
            $calendar->groups()->attach($request->groups);
        }

        $msgSuccess = trans('handler.eventSuccessAdded');

        $request->session()->flash('alert-success', $msgSuccess);

        return redirect('/calendar');

    }

//updateEventResize
    public function updateEventResize(Request $request)
    {

        ($request->allday == 'true') ? $event_allday = 'true' : $event_allday = 'false';

        $event = Calendar::where('uniq_hash', $request->id)->firstOrFail();
        $event->update([

            'dtStart' => $request->start,
            'dtStop'  => $request->end,
            'allday'  => $event_allday,

        ]);

        //
    }

    public function updateEventDrop(Request $request)
    {

        ($request->allday == 'true') ? $event_allday = 'true' : $event_allday = 'false';

        $event = Calendar::where('uniq_hash', $request->id)->firstOrFail();
        $event->update([

            'dtStart' => $request->start,
            'dtStop'  => $request->end,
            'allday'  => $event_allday,

        ]);

        //
    }

//updateEvent
    public function updateEvent(Request $request)
    {
        //
        //dd($request->all());

        $user = Auth::user();
        $event = Calendar::where('uniq_hash', $request->event_code)->firstOrFail();
        ($request->eventPersonal == 'true') ? $event_personal = 'true' : $event_personal = 'false';
        ($request->eventAllday == 'true') ? $event_allday = 'true' : $event_allday = 'false';

        $event->update([

            'title'           => $request->eventTitle,
            'dtStart'         => $request->startDateEdit,
            'dtStop'          => $request->endDateEdit,
            'allday'          => $event_allday,
            'backgroundColor' => $request->current_backgroundColor,
            'borderColor'     => $request->current_borderColor,
            'description'     => $request->eventDescription,
            'user_id'         => $user->id,
            //'uniq_hash'=>str_random(20),
            'personal'        => $event_personal,

        ]);

        $event->groups()->detach();
        if (count($request->groupsEdit) > 0) {
            $event->groups()->attach($request->groupsEdit);
        }

        $msgSuccess = trans('handler.eventSuccessAdded');

        $request->session()->flash('alert-success', $msgSuccess);

        return redirect('/calendar');

    }

//showEvent


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function showEvent(Request $request)
    {
        //

        $event = Calendar::where('uniq_hash', $request->uniq_code)->firstOrFail();

        ($event->allday == 'true') ? $event_allday = true : $event_allday = false;
        ($event->personal == 'true') ? $event_personal = true : $event_personal = false;

        $groupArr = [];
        foreach ($event->groups as $group) {
            # code...
            array_push($groupArr, $group->id);
        }

        $data[] = [

            'title'               => $event->title,
            'description'         => $event->description,
            'eventAllday'         => $event_allday,
            'reservationtimeEdit' => $event->dtStart->format('Y-m-d H:i:s') . ' - ' . $event->dtStop->format('Y-m-d H:i:s'),
            'startDateEdit'       => $event->dtStart->format('Y-m-d H:i:s'),
            'endDateEdit'         => $event->dtStop->format('Y-m-d H:i:s'),
            'eventPersonal'       => $event_personal,
            'groupsEdit'          => $groupArr,
            'userName'            => $event->user->name,
            'backgroundColor'     => $event->backgroundColor,
            'borderColor'         => $event->borderColor,
            'code'                => $request->uniq_code,

        ];

        return response()->json($data);

    }

    public function store(Request $request)
    {
        //
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $code
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function destroyEvent($code)
    {
        //

        $event = Calendar::where('uniq_hash', $code)->firstOrFail();
        $event->groups()->detach();
        $event->delete();

    }
}
