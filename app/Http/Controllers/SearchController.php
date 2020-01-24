<?php

namespace zenlix\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use zenlix\Help;
use zenlix\HelpAccess;
use zenlix\Http\Controllers\Controller;
use zenlix\Ticket;
use zenlix\User;

class SearchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $searchResults = [];

        $user = Auth::user();
        $myGroups = [];
        foreach ($user->groups as $value) {
            # code...
            array_push($myGroups, $value->id);
        }

        $myGroupsAdmin = [];
        foreach ($user->GroupAdmin() as $value) {
            # code...
            array_push($myGroupsAdmin, $value->id);
        }

        if (strpos($id, ':') !== false) {

            $termIn = explode(":", $id);

            $termObj = $termIn[0];
            $termText = $termIn[1];

            if ($termObj == 'help') {

                $helps = HelpAccess::whereIn('group_id', $myGroups)->get();

                $m = [];

                foreach ($helps as $value) {
                    # code...

//dd($value->help[0]);

                    array_push($m, $value->help[0]->id);
                }

                $help = Help::where(function ($query) use ($termText) {
                    return $query
                        ->where('name', 'LIKE', '%' . $termText . '%')
                        ->orWhere('description', 'LIKE', '%' . $termText . '%');
                })

                    ->where(function ($query) use ($m) {
                        return $query
                            ->whereIn('id', $m)
                            ->orWhere('access_all', 'true')
                        ;})

                    ->orderBy('updated_at', 'DESC')->take(5)->get();

                foreach ($help as $helpValue) {
                    # code...
                    array_push($searchResults, [

                        'title' => $helpValue->name,
                        'description' => $helpValue->description,
                        'url' => url('/help') . '/' . $helpValue->slug,

                    ]);
                }

            } else if ($termObj == 'ticket') {

                $tickets = Ticket::where(function ($query) use ($termText) {
                    return $query

                        ->where('subject', 'LIKE', '%' . $termText . '%')
                        ->orWhere('code', 'LIKE', '%' . $termText . '%')
                        ->orWhere('tags', 'LIKE', '%' . $termText . '%')

                        ->orWhereHas('clients', function ($q) use ($termText) {
                            $q->where('name', 'LIKE', '%' . $termText . '%');
                        })

                        ->orWhereHas('targetGroup', function ($q) use ($termText) {
                            $q->where('name', 'LIKE', '%' . $termText . '%');
                        });
                })
                    ->where(function ($query) use ($user, $myGroups, $myGroupsAdmin) {
                        return $query
                            ->whereHas('targetUsers', function ($q) use ($user) {
                                $q->where('user_id', $user->id);
                            })
                            ->orWhereIn('target_group_id', $myGroups)
                            ->has('targetUsers', '=', 0)
                            ->orWhereHas('targetUsers', function ($q) use ($myGroupsAdmin) {
                                $q->whereHas('groups', function ($q) use ($myGroupsAdmin) {
                                    $q->whereIn('id', $myGroupsAdmin);
                                });
                            });
                    })
                    ->where('planner_flag', 'false')
                    ->take(10)->get();

                foreach ($tickets as $ticket) {
                    # code...
                    array_push($searchResults, [

                        'title' => '#' . $ticket->code,
                        'description' => $ticket->subject,
                        'url' => url('/ticket') . '/' . $ticket->code,

                    ]);
                }

            } else if ($termObj == 'user') {

                $users = User::where('name', 'LIKE', '%' . $termText . '%')
                    ->whereHas('groups', function ($q) use ($myGroups) {
                        $q->whereIn('id', $myGroups);
                    })
                    ->where('id', '!=', $user->id)
                    ->orderBy('id', 'DESC')->take(5)->get();

                foreach ($users as $usersValue) {
                    # code...
                    array_push($searchResults, [

                        'title' => $usersValue->profile->full_name,
                        'description' => $usersValue->profile->position,
                        'url' => url('/user') . '/' . $usersValue->profile->user_urlhash,

                    ]);
                }
            }
        } else {

        }

        $data = [

            'searchResults' => $searchResults,

        ];

        return view('user.search.index')->with($data);
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
