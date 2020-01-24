<?php

namespace zenlix\Http\Controllers\API;

use Illuminate\Http\Request;
use JWTAuth;
use zenlix\Http\Controllers\Controller;
use zenlix\Ticket;
use zenlix\User;

class TicketController extends Controller
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

    public function indexIn()
    {
        //
        $user = JWTAuth::parseToken()->authenticate();

        $myGroups = [];
        foreach ($user->GroupUser() as $value) {
            # code...
            array_push($myGroups, $value->id);
        }

        $myGroupsAdmin = [];
        foreach ($user->GroupAdmin() as $value) {
            # code...
            array_push($myGroupsAdmin, $value->id);
        }

        $tickets = Ticket::with('targetUsers', 'clients')
        //targetuser == I
            ->where(function ($query) use ($user, $myGroups, $myGroupsAdmin) {
                return $query
                    ->whereHas('targetUsers', function ($q) use ($user) {
                        $q->where('user_id', $user->id);
                    })
                    //OR
                    //targetuser==Null AND target_group_id==mygroups
                    ->orWhereIn('target_group_id', $myGroups)
                    ->has('targetUsers', '=', 0)

                    ->orWhereHas('targetUsers', function ($q) use ($myGroupsAdmin) {
                        $q->whereHas('groups', function ($q) use ($myGroupsAdmin) {
                            $q->whereIn('id', $myGroupsAdmin);
                        });
                    })
                ;
            })
        //при этом targetUsers только я
            ->where('status', '!=', 'arch')
            ->orderBy('id', 'desc')
            ->get();

        $ticketArr = [];

        foreach ($tickets as $ticket) {

            $Clients = $ticket->clients;
            $clientsArr = [];
            foreach ($Clients as $Client) {

                array_push($clientsArr, [

                    'full_name' => $Client->profile->full_name,
                    'user_urlhash' => $Client->profile->user_urlhash,

                ]);
            }

            if ($ticket->target_group_id == null) {
                $targetGroup = null;
            } else {
                $targetGroup = [
                    'name' => $ticket->targetGroup->name,
                    'group_urlhash' => $ticket->targetGroup->group_urlhash,
                ];
            }

            $tU = [];
            if ($ticket->targetUsers->count() > 0) {

                foreach ($ticket->targetUsers as $targetUser) {
                    array_push($tU, [

                        'full_name' => $targetUser->profile->full_name,
                        'user_urlhash' => $targetUser->profile->user_urlhash,

                    ]);
                }

            }

            array_push($ticketArr,
                ['code' => $ticket->code,
                    'prio' => $ticket->prio,
                    'subject' => strip_tags($ticket->subject),
                    'author' => ['full_name' => $ticket->authorUser->profile->full_name,
                        'user_urlhash' => $ticket->authorUser->profile->user_urlhash],
                    'created_at' => $ticket->created_at,
                    'clients' => $clientsArr,
                    'targets' => ['group' => $targetGroup,
                        'users' => $tU,
                    ],
                    'ticket_status' => $ticket->status,
                    'text' => strip_tags($ticket->text),
                    'tags' => $ticket->tags,
                    'urlhash' => $ticket->urlhash,
                    'overtime' => $ticket->overtime,
                    'planner_flag' => $ticket->planner_flag,

                ]);

/*$ticketArr=[

'author'=>[
'full_name'=>$ticket->authorUser->profile->full_name,
'user_urlhash'=>$ticket->authorUser->profile->user_urlhash
],
'code'=>$ticket->code,
'client'=>$clientsArr,
'prio'=>$ticket->prio,
'text'=>$ticket->text,
'subject'=>$ticket->subject,
'tags'=>$ticket->tags,
'urlhash'=>$ticket->urlhash,
'sla'=>$ticketSlaRes,
'target_group'=>$targetGroup,
'target_users'=>$tU,
'watching_users'=>$wU,
'deadline_time'=>$ticket->deadline_time,
'inspect_after_ok'=>$ticket->inspect_after_ok,
'individual_ok'=>$ticket->individual_ok,
'created_at'=>$ticket->created_at,
'status'=>$ticket->status,
'overtime'=>$ticket->overtime,
'planner_flag'=>$ticket->planner_flag,
'comments'=>$commentsArr

];*/

        }

        $data = [
            'tickets' => $ticketArr,
            'total' => $tickets->count(),
        ];

        $res = [
            'status_code' => 200,
            'data' => $data,
        ];

        return $res;
    }

    public function indexOut()
    {
        //

        $user = JWTAuth::parseToken()->authenticate();

        $myGroups = [];
        foreach ($user->GroupUser() as $value) {
            # code...
            array_push($myGroups, $value->id);
        }

        $myGroupsAdmin = [];
        foreach ($user->GroupAdmin() as $value) {
            # code...
            array_push($myGroupsAdmin, $value->id);
        }

        $tickets = Ticket::where(function ($query) use ($user, $myGroupsAdmin) {
            return $query
                ->where('author_id', $user->id)
                ->orWhereHas('authorUser', function ($q) use ($myGroupsAdmin) {
                    $q->whereHas('groups', function ($q) use ($myGroupsAdmin) {
                        $q->whereIn('id', $myGroupsAdmin);
                    });
                })
            ;
        })

        //targetuser == I

            ->where('status', '!=', 'arch')
        //при этом targetUsers только я
            ->orderBy('id', 'desc')
            ->get();

        $ticketArr = [];

        foreach ($tickets as $ticket) {

            $Clients = $ticket->clients;
            $clientsArr = [];
            foreach ($Clients as $Client) {

                array_push($clientsArr, [

                    'full_name' => $Client->profile->full_name,
                    'user_urlhash' => $Client->profile->user_urlhash,

                ]);
            }

            if ($ticket->target_group_id == null) {
                $targetGroup = null;
            } else {
                $targetGroup = [
                    'name' => $ticket->targetGroup->name,
                    'group_urlhash' => $ticket->targetGroup->group_urlhash,
                ];
            }

            $tU = [];
            if ($ticket->targetUsers->count() > 0) {

                foreach ($ticket->targetUsers as $targetUser) {
                    array_push($tU, [

                        'full_name' => $targetUser->profile->full_name,
                        'user_urlhash' => $targetUser->profile->user_urlhash,

                    ]);
                }

            }

            array_push($ticketArr,
                ['code' => $ticket->code,
                    'prio' => $ticket->prio,
                    'subject' => strip_tags($ticket->subject),
                    'author' => ['full_name' => $ticket->authorUser->profile->full_name,
                        'user_urlhash' => $ticket->authorUser->profile->user_urlhash],
                    'created_at' => $ticket->created_at,
                    'clients' => $clientsArr,
                    'targets' => ['group' => $targetGroup,
                        'users' => $tU,
                    ],
                    'ticket_status' => $ticket->status,
                    'text' => strip_tags($ticket->text),
                    'tags' => $ticket->tags,
                    'urlhash' => $ticket->urlhash,
                    'overtime' => $ticket->overtime,
                    'planner_flag' => $ticket->planner_flag,
                ]);

        }

        $data = [
            'tickets' => $ticketArr,
            'total' => $tickets->count(),
        ];

        $res = [
            'status_code' => 200,
            'data' => $data,
        ];

        return $res;

    }

    public function indexArch()
    {
        //

        $user = JWTAuth::parseToken()->authenticate();

        $myGroups = [];
        foreach ($user->GroupUser() as $value) {
            # code...
            array_push($myGroups, $value->id);
        }

        $myGroupsAdmin = [];
        foreach ($user->GroupAdmin() as $value) {
            # code...
            array_push($myGroupsAdmin, $value->id);
        }

        $tickets = Ticket::where(function ($query) use ($user, $myGroups, $myGroupsAdmin) {
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
                })

                ->orWhere('author_id', $user->id)

                ->orWhereHas('authorUser', function ($q) use ($myGroupsAdmin) {
                    $q->whereHas('groups', function ($q) use ($myGroupsAdmin) {
                        $q->whereIn('id', $myGroupsAdmin);
                    });
                });
        })
            ->where('status', 'arch')
            ->orderBy('id', 'desc')
            ->get();

        $ticketArr = [];

        foreach ($tickets as $ticket) {

            $Clients = $ticket->clients;
            $clientsArr = [];
            foreach ($Clients as $Client) {

                array_push($clientsArr, [

                    'full_name' => $Client->profile->full_name,
                    'user_urlhash' => $Client->profile->user_urlhash,

                ]);
            }

            if ($ticket->target_group_id == null) {
                $targetGroup = null;
            } else {
                $targetGroup = [
                    'name' => $ticket->targetGroup->name,
                    'group_urlhash' => $ticket->targetGroup->group_urlhash,
                ];
            }

            $tU = [];
            if ($ticket->targetUsers->count() > 0) {

                foreach ($ticket->targetUsers as $targetUser) {
                    array_push($tU, [

                        'full_name' => $targetUser->profile->full_name,
                        'user_urlhash' => $targetUser->profile->user_urlhash,

                    ]);
                }

            }

            array_push($ticketArr,
                ['code' => $ticket->code,
                    'prio' => $ticket->prio,
                    'subject' => strip_tags($ticket->subject),
                    'author' => ['full_name' => $ticket->authorUser->profile->full_name,
                        'user_urlhash' => $ticket->authorUser->profile->user_urlhash],
                    'created_at' => $ticket->created_at,
                    'clients' => $clientsArr,
                    'targets' => ['group' => $targetGroup,
                        'users' => $tU,
                    ],
                    'text' => strip_tags($ticket->text),
                    'ticket_status' => $ticket->status,
                    'text' => $ticket->text,
                    'tags' => $ticket->tags,
                    'urlhash' => $ticket->urlhash,
                    'overtime' => $ticket->overtime,
                    'planner_flag' => $ticket->planner_flag,
                ]);

        }

        $data = [
            'tickets' => $ticketArr,
            'total' => $tickets->count(),
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

    public function middlewareViewTicket($ticketCode, $userID)
    {

//return false;
        $ticket = Ticket::whereCode($ticketCode)->firstOrFail();
        $user = User::findOrFail($userID);

        //автор?
        if ($ticket->author_id == $user->id) {
            return true;
        }

        //в списке следящих?
        foreach ($ticket->watchingUsers as $value) {
            if ($value->id == $user->id) {return true;}
        }

        //заявка мне назначена?
        foreach ($ticket->targetUsers as $value) {
            if ($value->id == $user->id) {return true;}
        }

        //заявка моему отделу и никому конкретно?
        if (($ticket->targetUsers->count() == 0) && ($ticket->target_group_id != null)) {
            //return $ticket->target_group_id;
            foreach ($user->groups as $value) {
                if ($value->id == $ticket->target_group_id) {return true;}
                # code...
            }
        }

        //я клиент заявки?
        foreach ($ticket->watchingUsers as $value) {
            if ($value->id == $user->id) {return true;}
        }

//если заявка на отдел то проверить, я ли суперполльзователь отдела?
        if ($ticket->target_group_id != null) {
            foreach ($user->groups()->wherePivot('priviliges', 'admin')->get() as $value) {
                if ($value->id == $ticket->target_group_id) {
                    return true;
                }
            }
        }

//если заявка на пользователей конкретно, (у каждого пользователя отдел, и я ли в том отделе суперпользователь)
        if ($ticket->targetUsers->count() > 0) {
            $targetUsersGroups = [];
            foreach ($ticket->targetUsers as $targetUser) {
                # code...
                # у каждого пользователя берём группу
                foreach ($targetUser->groups as $group) {
                    # code...
                    array_push($targetUsersGroups, $group->id);

                }

            }

            $targetUsersGroups = array_unique($targetUsersGroups);

            foreach ($user->groups()->wherePivot('priviliges', 'admin')->get() as $value) {
                //if ($value->id == $ticket->target_group_id)
                if (in_array($value->id, $targetUsersGroups)) {
                    return true;
                }
            }

        }

        return false;
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
        $user = JWTAuth::parseToken()->authenticate();

        $ticket = Ticket::where('code', $request->code)->firstOrFail();

        $Clients = $ticket->clients;
        $clientsArr = [];
        foreach ($Clients as $Client) {

            array_push($clientsArr, [

                'full_name' => $Client->profile->full_name,
                'user_urlhash' => $Client->profile->user_urlhash,

            ]);
        }

        if ($ticket->target_group_id == null) {
            $targetGroup = null;
        } else {
            $targetGroup = [
                'name' => $ticket->targetGroup->name,
                'group_urlhash' => $ticket->targetGroup->group_urlhash,
            ];
        }

        $tU = [];
        if ($ticket->targetUsers->count() > 0) {

            foreach ($ticket->targetUsers as $targetUser) {
                array_push($tU, [

                    'full_name' => $targetUser->profile->full_name,
                    'user_urlhash' => $targetUser->profile->user_urlhash,

                ]);
            }

        }

        $wU = [];
        if ($ticket->watchingUsers->count() > 0) {

            foreach ($ticket->watchingUsers as $watchingUser) {
                array_push($wU, [

                    'full_name' => $watchingUser->profile->full_name,
                    'user_urlhash' => $watchingUser->profile->user_urlhash,

                ]);
            }

        }

        $ticketSlaRes = null;
        if ($ticket->sla_id != null) {

            $ticketSla = [];

            if ($ticket->prio == "low") {
                $ticketSla['reaction'] = $ticket->sla->reaction_time_low_prio;
                $ticketSla['work'] = $ticket->sla->work_time_low_prio;
                $ticketSla['deadline'] = $ticket->sla->deadline_time_low_prio;
            } else if ($ticket->prio == "normal") {
                $ticketSla['reaction'] = $ticket->sla->reaction_time_def;
                $ticketSla['work'] = $ticket->sla->work_time_def;
                $ticketSla['deadline'] = $ticket->sla->deadline_time_def;
            } else if ($ticket->prio == "high") {
                $ticketSla['reaction'] = $ticket->sla->reaction_time_high_prio;
                $ticketSla['work'] = $ticket->sla->work_time_high_prio;
                $ticketSla['deadline'] = $ticket->sla->deadline_time_high_prio;
            }

            $ticketSlaRes = [

                'reaction_time' => $ticketSla['reaction'],
                'work_time' => $ticketSla['work'],
                'deadline_time' => $ticketSla['deadline'],

                'log_reaction_time' => $ticket->slaLog->reaction_time,
                'log_work_time' => $ticket->slaLog->work_time,
                'log_deadline_time' => $ticket->slaLog->deadline_time,

            ];

        }

        $commentsArr = [];

        if ($ticket->comments->count() > 0) {

            foreach ($ticket->comments as $comment) {
                array_push($commentsArr, [
                    'author' => [
                        'full_name' => $comment->author->profile->full_name,
                        'user_urlhash' => $comment->author->profile->user_urlhash,
                    ],
                    'message' => $comment->text,
                    'created_at' => $comment->created_at,

                ]);
            }

        }

        $ticketArr = [

            'author' => [
                'full_name' => $ticket->authorUser->profile->full_name,
                'user_urlhash' => $ticket->authorUser->profile->user_urlhash,
            ],
            'code' => $ticket->code,
            'client' => $clientsArr,
            'prio' => $ticket->prio,
            'text' => $ticket->text,
            'subject' => $ticket->subject,
            'tags' => $ticket->tags,
            'urlhash' => $ticket->urlhash,
            'sla' => $ticketSlaRes,
            'target_group' => $targetGroup,
            'target_users' => $tU,
            'watching_users' => $wU,
            'deadline_time' => $ticket->deadline_time,
            'inspect_after_ok' => $ticket->inspect_after_ok,
            'individual_ok' => $ticket->individual_ok,
            'created_at' => $ticket->created_at,
            'status' => $ticket->status,
            'overtime' => $ticket->overtime,
            'planner_flag' => $ticket->planner_flag,
            'comments' => $commentsArr,

        ];

        if ($this->middlewareViewTicket($request->code, $user->id)) {
            $data = [
                'ticket' => $ticketArr,
            ];

            $res = [
                'status_code' => 200,
                'data' => $data,
            ];
            return $res;
        } else {

            throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException('You have not priviliges to view ticket.');

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
