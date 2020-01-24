<?php

namespace zenlix\Http\Controllers;

use Auth;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Event;
use Illuminate\Http\Request;
use LocalizedCarbon;
use Setting;
use Storage;
use URL;
use Validator;
use zenlix\Classes\Zen;
use zenlix\Events\TicketLogger;
use zenlix\Events\TicketNotify;
use zenlix\Events\UserNotify;
use zenlix\Files;
use zenlix\Http\Controllers\Controller;
use zenlix\NotificationMenu;
use zenlix\Ticket;
use zenlix\TicketAdv;
use zenlix\TicketComments;
use zenlix\TicketForms;
use zenlix\TicketLog;
use zenlix\TicketPlanner;
use zenlix\TicketPlannerList;
use zenlix\TicketSla;
use zenlix\TicketSlaLog;
use zenlix\User;
use zenlix\UserTicketConf;

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

        return view('user.ticket.listIn');

    }

    public function indexPlanner(Request $request)
    {

        return view('user.ticket.listPlanner');
    }

    public function showPlanner(Request $request)
    {

        $user = Auth::user();

        $ticketArr = [];

/*$request->start;
$request->length;*/

        $orderColumn = $request->order['0']['column'];
        $orderDir = $request->order['0']['dir'];

        switch ($orderColumn) {
            case '0':$orderColumn = 'code';
                break;
            case '1':$orderColumn = 'prio';
                break;
            case '2':$orderColumn = 'subject';
                break;
            case '3':$orderColumn = 'author_id';
                break;
            case '4':$orderColumn = 'created_at';
                break;
            case '5':$orderColumn = 'deleted_at';
                break;
            //case '6': $orderColumn='CLIENTS'; break;
            //case '7': $orderColumnN='pivot.targetUsers'; break; //TARGET
            //case '8': $orderColumn='status'; break; //STATUS
            default:$orderColumn = 'code';
                break;
        }
/*
все заявки в которых:
- я исполнитель
- исполнитель == 0 И отдел == мои отделы
- исполнитель == кто-то И отдел == мои отделы где я супер админ

 */

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

//dd($myGroups);
        if ($request->search['value']) {

            $searchSlug = $request->search['value'];

//targetUsers or clients
            // ->:          =:
            //

            $tickets = TicketPlannerList::limit($request->length)
                ->offset($request->start)

                ->where(function ($query) use ($searchSlug) {
                    return $query

                        ->where('subject', 'LIKE', '%' . $searchSlug . '%')
                        ->orWhere('code', 'LIKE', '%' . $searchSlug . '%')

                        ->orWhereHas('clients', function ($q) use ($searchSlug) {
                            $q->where('name', 'LIKE', '%' . $searchSlug . '%');
                        })

                        ->orWhereHas('targetGroup', function ($q) use ($searchSlug) {
                            $q->where('name', 'LIKE', '%' . $searchSlug . '%');
                        });
                })
                ->planner()
                ->where('author_id', $user->id)
                ->orderBy($orderColumn, $orderDir)
                ->get();
            $ticketsAll = $tickets->count();
        } else {

//что бы:
            // targetGroups-мои И я в них был targetUsers
            // targetGroups-мои

            $tickets = TicketPlannerList::limit($request->length)
                ->offset($request->start)
                ->planner()
                ->where('author_id', $user->id)
                ->orderBy($orderColumn, $orderDir)
                ->get();

            $ticketsAll = TicketPlannerList::planner()->where('author_id', $user->id)->count();

        }

//{!! LocalizedCarbon::instance($ticket->created_at)->formatLocalized('%d %f %Y, %H:%M') !!}

//dd($tickets->count());

        foreach ($tickets as $ticket) {

            $Clients = $ticket->clients;
            $C = [];
            foreach ($Clients as $Client) {
                $cRes = '<a href=\'' . url('/user/') . '/' . $Client->profile->user_urlhash . '\'>' . Zen::showShortName($Client->name) . '</a>';
                array_push($C, $cRes);
            }

            $ts = view("user.ticket.ticketStatus")->with(['ticket' => $ticket])->render();
            $tp = view("user.ticket.ticketPrioShort")->with(['ticket' => $ticket])->render();

            if ($ticket->target_group_id == null) {
                if ($ticket->targetUsers->count() > 0) {
                    $tU = [];
                    foreach ($ticket->targetUsers as $targetUser) {
                        $targRes = '<a href=\'' . url('/user/') . '/' . $targetUser->profile->user_urlhash . '\'>' . Zen::showShortName($targetUser->name) . '</a>';
                        array_push($tU, $targRes);
                        //array_push($tU, $targetUser->name);
                    }
                }
                $targetString = implode(', ', $tU);
            } else {
                $targetStringGroup = str_limit($ticket->targetGroup->name, 20);
                $targetStringUser = '';
                if ($ticket->targetUsers->count() > 0) {
                    $tU = [];
                    foreach ($ticket->targetUsers as $targetUser) {
                        $targRes = '<a href=\'' . url('/user/') . '/' . $targetUser->profile->user_urlhash . '\'>' . Zen::showShortName($targetUser->name) . '</a>';
                        array_push($tU, $targRes);
                    }
                    $targetStringUser = ' (' . implode(', ', $tU) . ')';
                }

                $targetString = $targetStringGroup . $targetStringUser;

            }

            $ticketClass = null;

/*free
lock
waitsuccess
success
arch*/

//dd($ticket->planners->period);

            $period = $ticket->planners->period;

            if ($period == 'day') {
                $periodName = trans('handler.everyAt') . $ticket->planners->dayHour . ':' . $ticket->planners->dayMinute;
            } else if ($period == 'week') {
                $periodName = trans('handler.every') . $ticket->planners->weekDay . trans('handler.dayOfWeekAt') . $ticket->planners->dayHour . ':' . $ticket->planners->dayMinute;
            } else if ($period == 'month') {
                $periodName = trans('handler.every2') . $ticket->planners->weekMonth . trans('handler.dayOfMonth') . $ticket->planners->dayHour . ':' . $ticket->planners->dayMinute;
            }

//dd('ok');

            array_push($ticketArr,
                ['1' => '<strong><a href=\'' . url('ticket/planner') . '/' . $ticket->code . '\'>' . $ticket->code . '</a></strong>',
                    '2' => $tp,
                    '3' => str_limit($ticket->subject, 20),
                    '4' => '<a href=\'' . url('/user/' . $ticket->authorUser->profile->user_urlhash) . '\'>' . Zen::showShortName($ticket->authorUser->name) . '</a>',
                    '5' => $periodName,
                    '6' => 'c ' . LocalizedCarbon::instance($ticket->planners->startWork)->formatLocalized('%d %f %Y, %H:%M') . ' по ' . LocalizedCarbon::instance($ticket->planners->endWork)->formatLocalized('%d %f %Y, %H:%M'),
                    '7' => implode(', ', $C),
                    '8' => $targetString,
                    //'9'=>'action',
                    // "DT_RowClass"=> $ticketClass,
                ]);

        }

//dd('ok');

        $data = ['draw' => intval($request->draw),
            'recordsTotal' => $ticketsAll,
            'recordsFiltered' => $ticketsAll,
            'data' => $ticketArr,

        ];

        return response()->json($data);
    }

    public function indexDeleted(Request $request)
    {

        return view('user.ticket.listDeleted');
    }

    public function showDeleted(Request $request)
    {

        $user = Auth::user();

        $ticketArr = [];

/*$request->start;
$request->length;*/

        $orderColumn = $request->order['0']['column'];
        $orderDir = $request->order['0']['dir'];

        switch ($orderColumn) {
            case '0':$orderColumn = 'code';
                break;
            case '1':$orderColumn = 'prio';
                break;
            case '2':$orderColumn = 'subject';
                break;
            case '3':$orderColumn = 'author_id';
                break;
            case '4':$orderColumn = 'created_at';
                break;
            case '5':$orderColumn = 'deleted_at';
                break;
            //case '6': $orderColumn='CLIENTS'; break;
            //case '7': $orderColumnN='pivot.targetUsers'; break; //TARGET
            //case '8': $orderColumn='status'; break; //STATUS
            default:$orderColumn = 'code';
                break;
        }
/*
все заявки в которых:
- я исполнитель
- исполнитель == 0 И отдел == мои отделы
- исполнитель == кто-то И отдел == мои отделы где я супер админ

 */

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

//dd($myGroups);
        if ($request->search['value']) {

            $searchSlug = $request->search['value'];

//targetUsers or clients
            // ->:          =:
            //

            $tickets = Ticket::limit($request->length)
                ->offset($request->start)

                ->where(function ($query) use ($searchSlug) {
                    return $query

                        ->where('subject', 'LIKE', '%' . $searchSlug . '%')
                        ->orWhere('code', 'LIKE', '%' . $searchSlug . '%')

                        ->orWhereHas('clients', function ($q) use ($searchSlug) {
                            $q->where('name', 'LIKE', '%' . $searchSlug . '%');
                        })

                        ->orWhereHas('targetGroup', function ($q) use ($searchSlug) {
                            $q->where('name', 'LIKE', '%' . $searchSlug . '%');
                        });
                })
                ->onlyTrashed()
                ->orderBy($orderColumn, $orderDir)
                ->get();
            $ticketsAll = $tickets->count();
        } else {

//что бы:
            // targetGroups-мои И я в них был targetUsers
            // targetGroups-мои

            $tickets = Ticket::limit($request->length)
                ->offset($request->start)
                ->onlyTrashed()
                ->orderBy($orderColumn, $orderDir)
                ->get();

            $ticketsAll = Ticket::onlyTrashed()->count();

        }

//{!! LocalizedCarbon::instance($ticket->created_at)->formatLocalized('%d %f %Y, %H:%M') !!}

        foreach ($tickets as $ticket) {

            $Clients = $ticket->clients;
            $C = [];
            foreach ($Clients as $Client) {
                $cRes = '<a href=\'' . url('/user/') . '/' . $Client->profile->user_urlhash . '\'>' . Zen::showShortName($Client->name) . '</a>';
                array_push($C, $cRes);
            }

            $ts = view("user.ticket.ticketStatus")->with(['ticket' => $ticket])->render();
            $tp = view("user.ticket.ticketPrioShort")->with(['ticket' => $ticket])->render();

            $tU = [];
            if ($ticket->target_group_id == null) {
                if ($ticket->targetUsers->count() > 0) {

                    foreach ($ticket->targetUsers as $targetUser) {
                        $targRes = '<a href=\'' . url('/user/') . '/' . $targetUser->profile->user_urlhash . '\'>' . Zen::showShortName($targetUser->name) . '</a>';
                        array_push($tU, $targRes);
                        //array_push($tU, $targetUser->name);
                    }
                }
                $targetString = implode(', ', $tU);
            } else {
                $targetStringGroup = str_limit($ticket->targetGroup->name, 20);
                $targetStringUser = '';
                if ($ticket->targetUsers->count() > 0) {
                    $tU = [];
                    foreach ($ticket->targetUsers as $targetUser) {
                        $targRes = '<a href=\'' . url('/user/') . '/' . $targetUser->profile->user_urlhash . '\'>' . Zen::showShortName($targetUser->name) . '</a>';
                        array_push($tU, $targRes);
                    }
                    $targetStringUser = ' (' . implode(', ', $tU) . ')';
                }

                $targetString = $targetStringGroup . $targetStringUser;

            }

            $ticketClass = null;

/*free
lock
waitsuccess
success
arch*/

            array_push($ticketArr,
                ['1' => '<strong><a href=\'' . url('ticket/deleted') . '/' . $ticket->code . '\'>' . $ticket->code . '</a></strong>',
                    '2' => $tp,
                    '3' => str_limit($ticket->subject, 20),
                    '4' => '<a href=\'' . url('/user/' . $ticket->authorUser->profile->user_urlhash) . '\'>' . Zen::showShortName($ticket->authorUser->name) . '</a>',
                    '5' => LocalizedCarbon::instance($ticket->created_at)->formatLocalized('%d %f %Y, %H:%M'),
                    '6' => LocalizedCarbon::instance($ticket->deleted_at)->formatLocalized('%d %f %Y, %H:%M'),
                    '7' => implode(', ', $C),
                    '8' => $targetString,
                    //'9'=>'action',
                    // "DT_RowClass"=> $ticketClass,
                ]);

        }

        $data = ['draw' => intval($request->draw),
            'recordsTotal' => $ticketsAll,
            'recordsFiltered' => $ticketsAll,
            'data' => $ticketArr,

        ];

        return response()->json($data);
    }

//showDeletedTicket

/*indexOutClient
showOutClient
indexArchClient
showArchClient*/

    public function indexIn()
    {
        $data = ['ticketOutCount' => $this->ticketOutCount(),
            'ticketArchCount' => $this->ticketArchCount()];
        return view('user.ticket.listIn')->with($data);

    }

    public function showIn(Request $request)
    {
/*

 */

        $user = Auth::user();

        $ticketArr = [];

/*$request->start;
$request->length;*/

        $orderColumn = $request->order['0']['column'];
        $orderDir = $request->order['0']['dir'];

        switch ($orderColumn) {
            case '0':$orderColumn = 'code';
                break;
            case '1':$orderColumn = 'prio';
                break;
            case '2':$orderColumn = 'subject';
                break;
            case '3':$orderColumn = 'author_id';
                break;
            case '4':$orderColumn = 'created_at';
                break;
            case '5':$orderColumn = 'created_at';
                break;
            //case '6': $orderColumn='CLIENTS'; break;
            //case '7': $orderColumnN='pivot.targetUsers'; break; //TARGET
            case '7':$orderColumn = 'status';
                break; //STATUS
            default:$orderColumn = 'code';
                break;
        }
/*
все заявки в которых:
- я исполнитель
- исполнитель == 0 И отдел == мои отделы
- исполнитель == кто-то И отдел == мои отделы где я супер админ

 */

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

//dd($myGroups);
        if ($request->search['value']) {

            $searchSlug = $request->search['value'];

//targetUsers or clients
            // ->:          =:
            //

            $tickets = Ticket::limit($request->length)
                ->offset($request->start)

                ->where(function ($query) use ($searchSlug) {
                    return $query

                        ->where('subject', 'LIKE', '%' . $searchSlug . '%')
                        ->orWhere('code', 'LIKE', '%' . $searchSlug . '%')
                        ->orWhere('tags', 'LIKE', '%' . $searchSlug . '%')

                        ->orWhereHas('clients', function ($q) use ($searchSlug) {
                            $q->where('name', 'LIKE', '%' . $searchSlug . '%');
                        })

                        ->orWhereHas('targetGroup', function ($q) use ($searchSlug) {
                            $q->where('name', 'LIKE', '%' . $searchSlug . '%');
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
                ->where('status', '!=', 'arch')
                ->where('planner_flag', 'false')
                ->where('merge_flag', 'false')
                ->orderBy($orderColumn, $orderDir)
                ->get();
            $ticketsAll = $tickets->count();
        } else {

//что бы:
            // targetGroups-мои И я в них был targetUsers
            // targetGroups-мои

            $tickets = Ticket::with('targetUsers', 'clients')->limit($request->length)
                ->offset($request->start)
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
                ->where('planner_flag', 'false')
                ->where('merge_flag', 'false')
                ->orderBy($orderColumn, $orderDir)
                ->get();

            $ticketsAll = Ticket::with('targetUsers', 'clients')->where(function ($query) use ($user, $myGroups, $myGroupsAdmin) {
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
                ->where('status', '!=', 'arch')
                ->where('planner_flag', 'false')
                ->where('merge_flag', 'false')
                ->count();
        }

//{!! LocalizedCarbon::instance($ticket->created_at)->formatLocalized('%d %f %Y, %H:%M') !!}

        foreach ($tickets as $ticket) {

            if ($ticket->individual_ok == "true") {
                if (($ticket->status != "arch") || ($ticket->status != "waitsuccess")) {
                    if ($ticket->targetUsers()->wherePivot('user_id', Auth::user()->id)->exists()) {
                        $statusPivot = $ticket->targetUsers()->wherePivot('user_id', Auth::user()->id)->firstOrFail();

                        if (($statusPivot->pivot->individual_lock_status == "false") && ($statusPivot->pivot->individual_ok_status == "false")) {
                            $ticket->status = 'free';
                        } else if (($statusPivot->pivot->individual_lock_status == "true") && ($statusPivot->pivot->individual_ok_status == "false")) {
                            $ticket->status = 'lock';
                        } else if (($statusPivot->pivot->individual_lock_status == "true") && ($statusPivot->pivot->individual_ok_status == "true")) {
                            $ticket->status = 'success';
                        }
                    }
                }

            }

            $Clients = $ticket->clients;
            $C = [];
            foreach ($Clients as $Client) {
                $cRes = '<a href=\'' . url('/user/') . '/' . $Client->profile->user_urlhash . '\'>' . Zen::showShortName($Client->name) . '</a>';
                array_push($C, $cRes);
            }

            $ts = view("user.ticket.ticketStatus")->with(['ticket' => $ticket])->render();
            $tp = view("user.ticket.ticketPrioShort")->with(['ticket' => $ticket])->render();

            if ($ticket->target_group_id == null) {
                if ($ticket->targetUsers->count() > 0) {
                    $tU = [];
                    foreach ($ticket->targetUsers as $targetUser) {
                        $targRes = '<a href=\'' . url('/user/') . '/' . $targetUser->profile->user_urlhash . '\'>' . Zen::showShortName($targetUser->name) . '</a>';
                        array_push($tU, $targRes);
                    }
                }
                $targetString = implode(', ', $tU);
            } else {
                $targetStringGroup = str_limit($ticket->targetGroup->name, 20);
                $targetStringUser = '';
                if ($ticket->targetUsers->count() > 0) {
                    $tU = [];
                    foreach ($ticket->targetUsers as $targetUser) {
                        $targRes = '<a href=\'' . url('/user/') . '/' . $targetUser->profile->user_urlhash . '\'>' . Zen::showShortName($targetUser->name) . '</a>';
                        array_push($tU, $targRes);
                    }
                    $targetStringUser = ' (' . implode(', ', $tU) . ')';
                }

                $targetString = $targetStringGroup . $targetStringUser;

            }

            $ticketClass = null;

/*free
lock
waitsuccess
success
arch*/

            switch ($ticket->status) {
                case 'free':
                    $ticketClass = "";
                    break;
                case 'lock':
                    $ticketClass = "warning";
                    break;
                case 'waitsuccess':
                    $ticketClass = "warning";
                    break;
                case 'success':
                    $ticketClass = "success";
                    break;
                case 'arch':
                    $ticketClass = "active";
                    break;

                default:
                    $ticketClass = null;
                    break;
            }

            array_push($ticketArr,
                ['1' => '<strong><center><a href=\'' . url('ticket/') . '/' . $ticket->code . '\'>' . $ticket->code . '</a></center></strong>',
                    '2' => '<center>' . $tp . '</center>',
                    '3' => str_limit($ticket->subject, 20),
                    '4' => '<a href=\'' . url('/user/' . $ticket->authorUser->profile->user_urlhash) . '\'>' . Zen::showShortName($ticket->authorUser->name) . '</a>',
                    '5' => '<span data-toggle=\'tooltip\' data-placement=\'right\' title=\'' .
                    LocalizedCarbon::instance($ticket->created_at)->diffForHumans()
                    . '\'>' . LocalizedCarbon::instance($ticket->created_at)->formatLocalized('%e %f %Y, %H:%M') . '</span>',
                    //'6'=>'<small>'.LocalizedCarbon::instance($ticket->created_at)->diffForHumans().'</small>',
                    '6' => implode(', ', $C),
                    '7' => $targetString,
                    '8' => $ts,
                    "DT_RowClass" => $ticketClass,
                ]);

        }

        $data = ['draw' => intval($request->draw),
            'recordsTotal' => $ticketsAll,
            'recordsFiltered' => $ticketsAll,
            'data' => $ticketArr,

        ];

        return response()->json($data);
    }

    public function showOut(Request $request)
    {
/*

 */

        $user = Auth::user();

        $ticketArr = [];

/*$request->start;
$request->length;*/

        $orderColumn = $request->order['0']['column'];
        $orderDir = $request->order['0']['dir'];

        switch ($orderColumn) {
            case '0':$orderColumn = 'code';
                break;
            case '1':$orderColumn = 'prio';
                break;
            case '2':$orderColumn = 'subject';
                break;
            case '3':$orderColumn = 'author_id';
                break;
            case '4':$orderColumn = 'created_at';
                break;
            //case '5': $orderColumn='created_at'; break;
            //case '6': $orderColumn='CLIENTS'; break;
            //case '7': $orderColumnN='pivot.targetUsers'; break; //TARGET
            case '7':$orderColumn = 'status';
                break; //STATUS
            default:$orderColumn = 'code';
                break;
        }

        $myGroupsAdmin = [];
        foreach ($user->GroupAdmin() as $value) {
            # code...
            array_push($myGroupsAdmin, $value->id);
        }

//dd($myGroups);
        if ($request->search['value']) {

            $searchSlug = $request->search['value'];

//targetUsers or clients
            // ->:          =:
            //

            $tickets = Ticket::limit($request->length)
                ->offset($request->start)

                ->where(function ($query) use ($searchSlug) {
                    return $query

                        ->where('subject', 'LIKE', '%' . $searchSlug . '%')
                        ->orWhere('code', 'LIKE', '%' . $searchSlug . '%')
                        ->orWhere('tags', 'LIKE', '%' . $searchSlug . '%')

                        ->orWhereHas('clients', function ($q) use ($searchSlug) {
                            $q->where('name', 'LIKE', '%' . $searchSlug . '%');
                        })

                        ->orWhereHas('targetGroup', function ($q) use ($searchSlug) {
                            $q->where('name', 'LIKE', '%' . $searchSlug . '%');
                        });
                })
                ->where(function ($query) use ($user, $myGroupsAdmin) {
                    return $query
                        ->where('author_id', $user->id)
                        ->orWhereHas('authorUser', function ($q) use ($myGroupsAdmin) {
                            $q->whereHas('groups', function ($q) use ($myGroupsAdmin) {
                                $q->whereIn('id', $myGroupsAdmin);
                            });
                        });
                })
                ->where('status', '!=', 'arch')
                ->where('planner_flag', 'false')
                ->where('merge_flag', 'false')
                ->orderBy($orderColumn, $orderDir)
                ->get();
            $ticketsAll = $tickets->count();
        } else {

//что бы:
            /*
            все заявки в которых:
            - я автор
            - отдел автора == мои отделы где я супер админ

            authorUser

            ->where('author_id', $user->id)
            ->orWhereHas('authorUser', function ($q) use($myGroupsAdmin) {
            $q->whereHas('groups', function($q) use($myGroupsAdmin) {
            $q->whereIn('id', $myGroupsAdmin);
            });
            })
             */

            $tickets = Ticket::limit($request->length)
                ->offset($request->start)

                ->where(function ($query) use ($user, $myGroupsAdmin) {
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
                ->where('planner_flag', 'false')
                ->where('merge_flag', 'false')
            //при этом targetUsers только я
                ->orderBy($orderColumn, $orderDir)
                ->get();

            $ticketsAll = Ticket::where(function ($query) use ($user, $myGroupsAdmin) {
                return $query
                    ->where('author_id', $user->id)
                    ->orWhereHas('authorUser', function ($q) use ($myGroupsAdmin) {
                        $q->whereHas('groups', function ($q) use ($myGroupsAdmin) {
                            $q->whereIn('id', $myGroupsAdmin);
                        });
                    })
                ;
            })
                ->where('status', '!=', 'arch')
                ->where('planner_flag', 'false')
                ->where('merge_flag', 'false')
                ->count();
        }

//{!! LocalizedCarbon::instance($ticket->created_at)->formatLocalized('%d %f %Y, %H:%M') !!}

        foreach ($tickets as $ticket) {

            if ($ticket->individual_ok == "true") {
                if (($ticket->status != "arch") || ($ticket->status != "waitsuccess")) {
                    if ($ticket->targetUsers()->wherePivot('user_id', Auth::user()->id)->exists()) {

                        $statusPivot = $ticket->targetUsers()->wherePivot('user_id', Auth::user()->id)->firstOrFail();

                        if (($statusPivot->pivot->individual_lock_status == "false") && ($statusPivot->pivot->individual_ok_status == "false")) {
                            $ticket->status = 'free';
                        } else if (($statusPivot->pivot->individual_lock_status == "true") && ($statusPivot->pivot->individual_ok_status == "false")) {
                            $ticket->status = 'lock';
                        } else if (($statusPivot->pivot->individual_lock_status == "true") && ($statusPivot->pivot->individual_ok_status == "true")) {
                            $ticket->status = 'success';
                        }
                    }
                }

            }

            $Clients = $ticket->clients;
            $C = [];
            foreach ($Clients as $Client) {
                $cRes = '<a href=\'' . url('/user/') . '/' . $Client->profile->user_urlhash . '\'>' . Zen::showShortName($Client->name) . '</a>';
                array_push($C, $cRes);
            }

            $ts = view("user.ticket.ticketStatus")->with(['ticket' => $ticket])->render();
            $tp = view("user.ticket.ticketPrioShort")->with(['ticket' => $ticket])->render();

            $tU = [];
            if ($ticket->target_group_id == null) {
                if ($ticket->targetUsers->count() > 0) {

                    foreach ($ticket->targetUsers as $targetUser) {
                        $targRes = '<a href=\'' . url('/user/') . '/' . $targetUser->profile->user_urlhash . '\'>' . Zen::showShortName($targetUser->name) . '</a>';
                        array_push($tU, $targRes);
                    }
                }
                $targetString = implode(', ', $tU);
            } else {
                $targetStringGroup = str_limit($ticket->targetGroup->name, 20);
                $targetStringUser = '';
                if ($ticket->targetUsers->count() > 0) {
                    $tU = [];
                    foreach ($ticket->targetUsers as $targetUser) {
                        $targRes = '<a href=\'' . url('/user/') . '/' . $targetUser->profile->user_urlhash . '\'>' . Zen::showShortName($targetUser->name) . '</a>';
                        array_push($tU, $targRes);
                    }
                    $targetStringUser = ' (' . implode(', ', $tU) . ')';
                }

                $targetString = $targetStringGroup . $targetStringUser;

            }

            $ticketClass = null;

/*free
lock
waitsuccess
success
arch*/

            switch ($ticket->status) {
                case 'free':
                    $ticketClass = "";
                    break;
                case 'lock':
                    $ticketClass = "warning";
                    break;
                case 'waitsuccess':
                    $ticketClass = "warning";
                    break;
                case 'success':
                    $ticketClass = "success";
                    break;
                case 'arch':
                    $ticketClass = "active";
                    break;

                default:
                    $ticketClass = null;
                    break;
            }

            array_push($ticketArr,
                ['1' => '<strong><center><a href=\'' . url('ticket/') . '/' . $ticket->code . '\'>' . $ticket->code . '</a></center></strong>',
                    '2' => '<center>' . $tp . '</center>',
                    '3' => str_limit($ticket->subject, 20),
                    '4' => '<a href=\'' . url('/user/' . $ticket->authorUser->profile->user_urlhash) . '\'>' . Zen::showShortName($ticket->authorUser->name) . '</a>',
                    '5' => '<span data-toggle=\'tooltip\' data-placement=\'right\' title=\'' .
                    LocalizedCarbon::instance($ticket->created_at)->diffForHumans()
                    . '\'>' . LocalizedCarbon::instance($ticket->created_at)->formatLocalized('%e %f %Y, %H:%M') . '</span>',
                    //'6'=>LocalizedCarbon::instance($ticket->created_at)->diffForHumans(),
                    '6' => implode(',', $C),
                    '7' => $targetString,
                    '8' => $ts,
                    "DT_RowClass" => $ticketClass,
                ]);

        }

        $data = ['draw' => intval($request->draw),
            'recordsTotal' => $ticketsAll,
            'recordsFiltered' => $ticketsAll,
            'data' => $ticketArr,

        ];

        return response()->json($data);
    }

    public function showOutClient(Request $request)
    {
/*

 */

        $user = Auth::user();

        $ticketArr = [];

/*$request->start;
$request->length;*/

        $orderColumn = $request->order['0']['column'];
        $orderDir = $request->order['0']['dir'];

        switch ($orderColumn) {
            case '0':$orderColumn = 'code';
                break;
            case '1':$orderColumn = 'prio';
                break;
            case '2':$orderColumn = 'subject';
                break;
            case '3':$orderColumn = 'author_id';
                break;
            case '4':$orderColumn = 'created_at';
                break;
            //case '5': $orderColumn='created_at'; break;
            //case '6': $orderColumn='CLIENTS'; break;
            //case '7': $orderColumnN='pivot.targetUsers'; break; //TARGET
            case '7':$orderColumn = 'status';
                break; //STATUS
            default:$orderColumn = 'code';
                break;
        }

        $myGroupsAdmin = [];
        foreach ($user->GroupAdmin() as $value) {
            # code...
            array_push($myGroupsAdmin, $value->id);
        }

//dd($myGroups);
        if ($request->search['value']) {

            $searchSlug = $request->search['value'];

//targetUsers or clients
            // ->:          =:
            //

            $tickets = Ticket::limit($request->length)
                ->offset($request->start)

                ->where(function ($query) use ($searchSlug) {
                    return $query

                        ->where('subject', 'LIKE', '%' . $searchSlug . '%')
                        ->orWhere('code', 'LIKE', '%' . $searchSlug . '%')
                        ->orWhere('tags', 'LIKE', '%' . $searchSlug . '%')

                        ->orWhereHas('clients', function ($q) use ($searchSlug) {
                            $q->where('name', 'LIKE', '%' . $searchSlug . '%');
                        })

                        ->orWhereHas('targetGroup', function ($q) use ($searchSlug) {
                            $q->where('name', 'LIKE', '%' . $searchSlug . '%');
                        });
                })
                ->where(function ($query) use ($user, $myGroupsAdmin) {
                    return $query
                        ->where('author_id', $user->id)
                        ->orWhereHas('authorUser', function ($q) use ($myGroupsAdmin) {
                            $q->whereHas('groups', function ($q) use ($myGroupsAdmin) {
                                $q->whereIn('id', $myGroupsAdmin);
                            });
                        });
                })
                ->where('status', '!=', 'arch')
                ->where('planner_flag', 'false')
                ->where('merge_flag', 'false')
                ->orderBy($orderColumn, $orderDir)
                ->get();
            $ticketsAll = $tickets->count();
        } else {

//что бы:
            /*
            все заявки в которых:
            - я автор
            - отдел автора == мои отделы где я супер админ

            authorUser

            ->where('author_id', $user->id)
            ->orWhereHas('authorUser', function ($q) use($myGroupsAdmin) {
            $q->whereHas('groups', function($q) use($myGroupsAdmin) {
            $q->whereIn('id', $myGroupsAdmin);
            });
            })
             */

            $tickets = Ticket::limit($request->length)
                ->offset($request->start)

                ->where(function ($query) use ($user, $myGroupsAdmin) {
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

            //при этом targetUsers только я
                ->where('status', '!=', 'arch')
                ->where('planner_flag', 'false')
                ->where('merge_flag', 'false')
                ->orderBy($orderColumn, $orderDir)
                ->get();

            $ticketsAll = Ticket::where(function ($query) use ($user, $myGroupsAdmin) {
                return $query
                    ->where('author_id', $user->id)
                    ->orWhereHas('authorUser', function ($q) use ($myGroupsAdmin) {
                        $q->whereHas('groups', function ($q) use ($myGroupsAdmin) {
                            $q->whereIn('id', $myGroupsAdmin);
                        });
                    })
                ;
            })
                ->where('status', '!=', 'arch')
                ->where('planner_flag', 'false')
                ->where('merge_flag', 'false')
                ->count();
        }

//{!! LocalizedCarbon::instance($ticket->created_at)->formatLocalized('%d %f %Y, %H:%M') !!}

        foreach ($tickets as $ticket) {

            if ($ticket->individual_ok == "true") {
                if (($ticket->status != "arch") || ($ticket->status != "waitsuccess")) {
                    if ($ticket->targetUsers()->wherePivot('user_id', Auth::user()->id)->exists()) {

                        $statusPivot = $ticket->targetUsers()->wherePivot('user_id', Auth::user()->id)->firstOrFail();

                        if (($statusPivot->pivot->individual_lock_status == "false") && ($statusPivot->pivot->individual_ok_status == "false")) {
                            $ticket->status = 'free';
                        } else if (($statusPivot->pivot->individual_lock_status == "true") && ($statusPivot->pivot->individual_ok_status == "false")) {
                            $ticket->status = 'lock';
                        } else if (($statusPivot->pivot->individual_lock_status == "true") && ($statusPivot->pivot->individual_ok_status == "true")) {
                            $ticket->status = 'success';
                        }
                    }
                }

            }

            $Clients = $ticket->clients;
            $C = [];
            foreach ($Clients as $Client) {
                $cRes = '<a href=\'' . url('/user/') . '/' . $Client->profile->user_urlhash . '\'>' . Zen::showShortName($Client->name) . '</a>';
                array_push($C, $cRes);
            }

            $ts = view("user.ticket.ticketStatus")->with(['ticket' => $ticket])->render();
            $tp = view("user.ticket.ticketPrioShort")->with(['ticket' => $ticket])->render();

            if ($ticket->target_group_id == null) {
                if ($ticket->targetUsers->count() > 0) {
                    $tU = [];
                    foreach ($ticket->targetUsers as $targetUser) {
                        $targRes = '<a href=\'' . url('/user/') . '/' . $targetUser->profile->user_urlhash . '\'>' . Zen::showShortName($targetUser->name) . '</a>';
                        array_push($tU, $targRes);
                    }
                }
                $targetString = implode(', ', $tU);
            } else {
                $targetStringGroup = str_limit($ticket->targetGroup->name, 20);
                $targetStringUser = '';
                if ($ticket->targetUsers->count() > 0) {
                    $tU = [];
                    foreach ($ticket->targetUsers as $targetUser) {
                        $targRes = '<a href=\'' . url('/user/') . '/' . $targetUser->profile->user_urlhash . '\'>' . Zen::showShortName($targetUser->name) . '</a>';
                        array_push($tU, $targRes);
                    }
                    $targetStringUser = ' (' . implode(', ', $tU) . ')';
                }

                $targetString = $targetStringGroup . $targetStringUser;

            }

            $ticketClass = null;

/*free
lock
waitsuccess
success
arch*/

            switch ($ticket->status) {
                case 'free':
                    $ticketClass = "";
                    break;
                case 'lock':
                    $ticketClass = "warning";
                    break;
                case 'waitsuccess':
                    $ticketClass = "warning";
                    break;
                case 'success':
                    $ticketClass = "success";
                    break;
                case 'arch':
                    $ticketClass = "active";
                    break;

                default:
                    $ticketClass = null;
                    break;
            }

            array_push($ticketArr,
                ['1' => '<strong><center><a href=\'' . url('ticket/') . '/' . $ticket->code . '\'>' . $ticket->code . '</a></center></strong>',
                    '2' => '<center>' . $tp . '</center>',
                    '3' => str_limit($ticket->subject, 20),
                    '4' => '<a href=\'' . url('/user/' . $ticket->authorUser->profile->user_urlhash) . '\'>' . Zen::showShortName($ticket->authorUser->name) . '</a>',
                    '5' => '<span data-toggle=\'tooltip\' data-placement=\'right\' title=\'' .
                    LocalizedCarbon::instance($ticket->created_at)->diffForHumans()
                    . '\'>' . LocalizedCarbon::instance($ticket->created_at)->formatLocalized('%e %f %Y, %H:%M') . '</span>',
                    //'6'=>LocalizedCarbon::instance($ticket->created_at)->diffForHumans(),
                    '6' => implode(',', $C),
                    '7' => $targetString,
                    '8' => $ts,
                    "DT_RowClass" => $ticketClass,
                ]);

/*array_push($ticketArr,
['1'=>'<strong><a href=\''.url('ticket/').'/'.$ticket->code.'\'>'.$ticket->code.'</a></strong>',
'2'=>$tp,
'3'=>$ticket->subject,
'4'=>$ticket->authorUser->name,
'5'=>LocalizedCarbon::instance($ticket->created_at)->formatLocalized('%d %f %Y, %H:%M'),
//'6'=>LocalizedCarbon::instance($ticket->created_at)->diffForHumans(),
'6'=>implode(',', $C),
'7'=>$targetString,
'8'=>$ts,
"DT_RowClass"=> $ticketClass,
]);*/

        }

        $data = ['draw' => intval($request->draw),
            'recordsTotal' => $ticketsAll,
            'recordsFiltered' => $ticketsAll,
            'data' => $ticketArr,

        ];

        return response()->json($data);
    }

    public function ticketOutCount()
    {

        $user = Auth::user();
        $myGroupsAdmin = [];
        foreach ($user->GroupAdmin() as $value) {
            # code...
            array_push($myGroupsAdmin, $value->id);
        }

        return Ticket::where(function ($query) use ($user, $myGroupsAdmin) {
            return $query
                ->where('author_id', $user->id)
                ->orWhereHas('authorUser', function ($q) use ($myGroupsAdmin) {
                    $q->whereHas('groups', function ($q) use ($myGroupsAdmin) {
                        $q->whereIn('id', $myGroupsAdmin);
                    });
                })
            ;
        })
            ->where('status', 'free')
            ->where('planner_flag', 'false')
            ->where('merge_flag', 'false')
            ->count();

    }

    public function ticketArchCount()
    {

        $user = Auth::user();
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

        return Ticket::where(function ($query) use ($user, $myGroups, $myGroupsAdmin) {
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
            ->where('planner_flag', 'false')
            ->where('merge_flag', 'false')
            ->count();

    }

    public function indexOut()
    {

        $data = ['ticketOutCount' => $this->ticketOutCount(),
            'ticketArchCount' => $this->ticketArchCount()];

        return view('user.ticket.listOut')->with($data);

    }

    public function indexOutClient()
    {

        $data = ['ticketOutCount' => $this->ticketOutCount(),
            'ticketArchCount' => $this->ticketArchCount()];

        return view('client.ticket.listAll')->with($data);

    }

    public function indexArch()
    {
        $data = ['ticketOutCount' => $this->ticketOutCount(),
            'ticketArchCount' => $this->ticketArchCount()];
        return view('user.ticket.listArch')->with($data);

    }

    public function indexArchClient()
    {
        $data = ['ticketOutCount' => $this->ticketOutCount(),
            'ticketArchCount' => $this->ticketArchCount()];
        return view('client.ticket.listArch')->with($data);

    }

    public function showArch(Request $request)
    {

        $user = Auth::user();

        $ticketArr = [];

/*$request->start;
$request->length;*/

        $orderColumn = $request->order['0']['column'];
        $orderDir = $request->order['0']['dir'];

        switch ($orderColumn) {
            case '0':$orderColumn = 'code';
                break;
            case '1':$orderColumn = 'prio';
                break;
            case '2':$orderColumn = 'subject';
                break;
            case '3':$orderColumn = 'author_id';
                break;
            case '4':$orderColumn = 'created_at';
                break;
            case '5':$orderColumn = 'created_at';
                break;
            //case '6': $orderColumn='CLIENTS'; break;
            //case '7': $orderColumnN='pivot.targetUsers'; break; //TARGET
            case '8':$orderColumn = 'status';
                break; //STATUS
            default:$orderColumn = 'code';
                break;
        }
/*
все заявки в которых:
- я исполнитель
- исполнитель == 0 И отдел == мои отделы
- исполнитель == кто-то И отдел == мои отделы где я супер админ

 */

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

//dd($myGroups);
        if ($request->search['value']) {

            $searchSlug = $request->search['value'];

//targetUsers or clients
            // ->:          =:
            //

            $tickets = Ticket::limit($request->length)
                ->offset($request->start)

                ->where(function ($query) use ($searchSlug) {
                    return $query

                        ->where('subject', 'LIKE', '%' . $searchSlug . '%')
                        ->orWhere('code', 'LIKE', '%' . $searchSlug . '%')
                        ->orWhere('tags', 'LIKE', '%' . $searchSlug . '%')

                        ->orWhereHas('clients', function ($q) use ($searchSlug) {
                            $q->where('name', 'LIKE', '%' . $searchSlug . '%');
                        })

                        ->orWhereHas('targetGroup', function ($q) use ($searchSlug) {
                            $q->where('name', 'LIKE', '%' . $searchSlug . '%');
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
                        })

                        ->orWhere('author_id', $user->id)

                        ->orWhereHas('authorUser', function ($q) use ($myGroupsAdmin) {
                            $q->whereHas('groups', function ($q) use ($myGroupsAdmin) {
                                $q->whereIn('id', $myGroupsAdmin);
                            });
                        });
                })
                ->where('status', 'arch')
                ->where('planner_flag', 'false')
                ->where('merge_flag', 'false')

                ->orderBy($orderColumn, $orderDir)
                ->get();
            $ticketsAll = $tickets->count();
        } else {

//что бы:
            // targetGroups-мои И я в них был targetUsers
            // targetGroups-мои

            //where('status','arch')

            $tickets = Ticket::limit($request->length)
                ->offset($request->start)
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
                        })

                        ->orWhere('author_id', $user->id)

                        ->orWhereHas('authorUser', function ($q) use ($myGroupsAdmin) {
                            $q->whereHas('groups', function ($q) use ($myGroupsAdmin) {
                                $q->whereIn('id', $myGroupsAdmin);
                            });
                        });
                })
                ->where('status', 'arch')
                ->where('planner_flag', 'false')
                ->where('merge_flag', 'false')
                ->orderBy($orderColumn, $orderDir)
                ->get();

            $ticketsAll = Ticket::where(function ($query) use ($user, $myGroups, $myGroupsAdmin) {
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
                ->where('planner_flag', 'false')
                ->where('merge_flag', 'false')
                ->count();
        }

//{!! LocalizedCarbon::instance($ticket->created_at)->formatLocalized('%d %f %Y, %H:%M') !!}

        foreach ($tickets as $ticket) {

/*$targetUsers=$ticket->targetUsers;
$tU=[];
foreach ($targetUsers as $targetUser) {
array_push($tU, $targetUser->name);
}*/

            $Clients = $ticket->clients;
            $C = [];
            foreach ($Clients as $Client) {
                array_push($C, $Client->name);
            }

            $ts = view("user.ticket.ticketStatus")->with(['ticket' => $ticket])->render();
            $tp = view("user.ticket.ticketPrioShort")->with(['ticket' => $ticket])->render();

            if ($ticket->target_group_id == null) {
                if ($ticket->targetUsers->count() > 0) {
                    $tU = [];
                    foreach ($ticket->targetUsers as $targetUser) {
                        $targRes = '<a href=\'' . url('/user/') . '/' . $targetUser->profile->user_urlhash . '\'>' . Zen::showShortName($targetUser->name) . '</a>';
                        array_push($tU, $targRes);
                        //array_push($tU, $targetUser->name);
                    }
                }
                $targetString = implode(', ', $tU);
            } else {
                $targetStringGroup = str_limit($ticket->targetGroup->name, 20);
                $targetStringUser = '';
                if ($ticket->targetUsers->count() > 0) {
                    $tU = [];
                    foreach ($ticket->targetUsers as $targetUser) {
                        $targRes = '<a href=\'' . url('/user/') . '/' . $targetUser->profile->user_urlhash . '\'>' . Zen::showShortName($targetUser->name) . '</a>';
                        array_push($tU, $targRes);
                    }
                    $targetStringUser = ' (' . implode(', ', $tU) . ')';
                }

                $targetString = $targetStringGroup . $targetStringUser;

            }

/*array_push($ticketArr,
['1'=>'<strong><center><a href=\''.url('ticket/').'/'.$ticket->code.'\'>'.$ticket->code.'</a></center></strong>',
'2'=>'<center>'.$tp.'</center>',
'3'=>str_limit($ticket->subject, 20),
'4'=>'<a href=\''.url('/user/'.$ticket->authorUser->profile->user_urlhash).'\'>'.Zen::showShortName($ticket->authorUser->name).'</a>',
'5'=>'<span data-toggle=\'tooltip\' data-placement=\'right\' title=\''.
LocalizedCarbon::instance($ticket->created_at)->diffForHumans()
.'\'>'.LocalizedCarbon::instance($ticket->created_at)->formatLocalized('%e %f %Y, %H:%M').'</span>',
//'6'=>LocalizedCarbon::instance($ticket->created_at)->diffForHumans(),
'6'=>implode(',', $C),
'7'=>$targetString,
'8'=>$ts,
"DT_RowClass"=> $ticketClass,
]);*/

            array_push($ticketArr,
                ['1' => '<strong><center><a href=\'' . url('ticket/') . '/' . $ticket->code . '\'>' . $ticket->code . '</a></center></strong>',
                    '2' => '<center>' . $tp . '</center>',
                    '3' => str_limit($ticket->subject, 20),
                    '4' => '<a href=\'' . url('/user/' . $ticket->authorUser->profile->user_urlhash) . '\'>' . Zen::showShortName($ticket->authorUser->name) . '</a>',
                    '5' => '<span data-toggle=\'tooltip\' data-placement=\'right\' title=\'' .
                    LocalizedCarbon::instance($ticket->created_at)->diffForHumans()
                    . '\'>' . LocalizedCarbon::instance($ticket->created_at)->formatLocalized('%e %f %Y, %H:%M') . '</span>',
                    '6' => '<span data-toggle=\'tooltip\' data-placement=\'right\' title=\'' .
                    LocalizedCarbon::instance($ticket->updated_at)->diffForHumans()
                    . '\'>' . LocalizedCarbon::instance($ticket->updated_at)->formatLocalized('%e %f %Y, %H:%M') . '</span>',
                    '7' => implode(',', $C),
                    '8' => $targetString,
                    '9' => $ts,
                    // "DT_RowClass"=> $ticketClass,
                ]);

        }

        $data = ['draw' => intval($request->draw),
            'recordsTotal' => $ticketsAll,
            'recordsFiltered' => $ticketsAll,
            'data' => $ticketArr,

        ];

        return response()->json($data);
    }

    public function showArchClient(Request $request)
    {

        $user = Auth::user();

        $ticketArr = [];

/*$request->start;
$request->length;*/

        $orderColumn = $request->order['0']['column'];
        $orderDir = $request->order['0']['dir'];

        switch ($orderColumn) {
            case '0':$orderColumn = 'code';
                break;
            case '1':$orderColumn = 'prio';
                break;
            case '2':$orderColumn = 'subject';
                break;
            case '3':$orderColumn = 'author_id';
                break;
            case '4':$orderColumn = 'created_at';
                break;
            case '5':$orderColumn = 'created_at';
                break;
            //case '6': $orderColumn='CLIENTS'; break;
            //case '7': $orderColumnN='pivot.targetUsers'; break; //TARGET
            case '8':$orderColumn = 'status';
                break; //STATUS
            default:$orderColumn = 'code';
                break;
        }
/*
все заявки в которых:
- я исполнитель
- исполнитель == 0 И отдел == мои отделы
- исполнитель == кто-то И отдел == мои отделы где я супер админ

 */

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

//dd($myGroups);
        if ($request->search['value']) {

            $searchSlug = $request->search['value'];

//targetUsers or clients
            // ->:          =:
            //

            $tickets = Ticket::limit($request->length)
                ->offset($request->start)

                ->where(function ($query) use ($searchSlug) {
                    return $query

                        ->where('subject', 'LIKE', '%' . $searchSlug . '%')
                        ->orWhere('code', 'LIKE', '%' . $searchSlug . '%')
                        ->orWhere('tags', 'LIKE', '%' . $searchSlug . '%')

                        ->orWhereHas('clients', function ($q) use ($searchSlug) {
                            $q->where('name', 'LIKE', '%' . $searchSlug . '%');
                        })

                        ->orWhereHas('targetGroup', function ($q) use ($searchSlug) {
                            $q->where('name', 'LIKE', '%' . $searchSlug . '%');
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
                        })

                        ->orWhere('author_id', $user->id)

                        ->orWhereHas('authorUser', function ($q) use ($myGroupsAdmin) {
                            $q->whereHas('groups', function ($q) use ($myGroupsAdmin) {
                                $q->whereIn('id', $myGroupsAdmin);
                            });
                        });
                })
                ->where('status', 'arch')
                ->where('planner_flag', 'false')
                ->where('merge_flag', 'false')

                ->orderBy($orderColumn, $orderDir)
                ->get();
            $ticketsAll = $tickets->count();
        } else {

//что бы:
            // targetGroups-мои И я в них был targetUsers
            // targetGroups-мои

            //where('status','arch')

            $tickets = Ticket::limit($request->length)
                ->offset($request->start)
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
                        })

                        ->orWhere('author_id', $user->id)

                        ->orWhereHas('authorUser', function ($q) use ($myGroupsAdmin) {
                            $q->whereHas('groups', function ($q) use ($myGroupsAdmin) {
                                $q->whereIn('id', $myGroupsAdmin);
                            });
                        });
                })
                ->where('status', 'arch')
                ->where('planner_flag', 'false')
                ->where('merge_flag', 'false')
                ->orderBy($orderColumn, $orderDir)
                ->get();

            $ticketsAll = Ticket::where(function ($query) use ($user, $myGroups, $myGroupsAdmin) {
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
                ->where('planner_flag', 'false')
                ->where('merge_flag', 'false')
                ->count();
        }

//{!! LocalizedCarbon::instance($ticket->created_at)->formatLocalized('%d %f %Y, %H:%M') !!}

        foreach ($tickets as $ticket) {

/*$targetUsers=$ticket->targetUsers;
$tU=[];
foreach ($targetUsers as $targetUser) {
array_push($tU, $targetUser->name);
}*/

            $Clients = $ticket->clients;
            $C = [];
            foreach ($Clients as $Client) {
                array_push($C, $Client->name);
            }

            $ts = view("user.ticket.ticketStatus")->with(['ticket' => $ticket])->render();
            $tp = view("user.ticket.ticketPrioShort")->with(['ticket' => $ticket])->render();

            if ($ticket->target_group_id == null) {
                if ($ticket->targetUsers->count() > 0) {
                    $tU = [];
                    foreach ($ticket->targetUsers as $targetUser) {
                        $targRes = '<a href=\'' . url('/user/') . '/' . $targetUser->profile->user_urlhash . '\'>' . Zen::showShortName($targetUser->name) . '</a>';
                        array_push($tU, $targRes);
                    }
                }
                $targetString = implode(', ', $tU);
            } else {
                $targetStringGroup = str_limit($ticket->targetGroup->name, 20);
                $targetStringUser = '';
                if ($ticket->targetUsers->count() > 0) {
                    $tU = [];
                    foreach ($ticket->targetUsers as $targetUser) {
                        $targRes = '<a href=\'' . url('/user/') . '/' . $targetUser->profile->user_urlhash . '\'>' . Zen::showShortName($targetUser->name) . '</a>';
                        array_push($tU, $targRes);
                    }
                    $targetStringUser = ' (' . implode(', ', $tU) . ')';
                }

                $targetString = $targetStringGroup . $targetStringUser;

            }

            array_push($ticketArr,
                ['1' => '<strong><center><a href=\'' . url('ticket/') . '/' . $ticket->code . '\'>' . $ticket->code . '</a></center></strong>',
                    '2' => '<center>' . $tp . '</center>',
                    '3' => str_limit($ticket->subject, 20),
                    '4' => '<a href=\'' . url('/user/' . $ticket->authorUser->profile->user_urlhash) . '\'>' . Zen::showShortName($ticket->authorUser->name) . '</a>',
                    '5' => '<span data-toggle=\'tooltip\' data-placement=\'right\' title=\'' .
                    LocalizedCarbon::instance($ticket->created_at)->diffForHumans()
                    . '\'>' . LocalizedCarbon::instance($ticket->created_at)->formatLocalized('%e %f %Y, %H:%M') . '</span>',
                    '6' => '<span data-toggle=\'tooltip\' data-placement=\'right\' title=\'' .
                    LocalizedCarbon::instance($ticket->updated_at)->diffForHumans()
                    . '\'>' . LocalizedCarbon::instance($ticket->updated_at)->formatLocalized('%e %f %Y, %H:%M') . '</span>',
                    '7' => implode(',', $C),
                    '8' => $targetString,
                    '9' => $ts,
                    // "DT_RowClass"=> $ticketClass,
                ]);

/*array_push($ticketArr,
['1'=>'<strong><a href=\''.url('ticket/').'/'.$ticket->code.'\'>'.$ticket->code.'</a></strong>',
'2'=>$tp,
'3'=>$ticket->subject,
'4'=>$ticket->authorUser->name,
'5'=>LocalizedCarbon::instance($ticket->created_at)->formatLocalized('%d %f %Y, %H:%M'),
'6'=>LocalizedCarbon::instance($ticket->updated_at)->formatLocalized('%d %f %Y, %H:%M'),
'7'=>implode(',', $C),
'8'=>$targetString,
'9'=>$ts,
// "DT_RowClass"=> $ticketClass,
]);*/

        }

        $data = ['draw' => intval($request->draw),
            'recordsTotal' => $ticketsAll,
            'recordsFiltered' => $ticketsAll,
            'data' => $ticketArr,

        ];

        return response()->json($data);
    }

    public function indexMerged(Request $request)
    {

//1. ПОКАЗАТЬ ТОЛЬКО ЗАЯВКИ КОТОРЫЕ МОЖНО ОБЪЕДИНИТЬ
        //2. При объденении ставить флаг
        //3. При разъединении убирать флаг
        //4. УБРАТЬ из списка заявок с флагом объединённые

        $user = Auth::user();

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

        $ticket = Ticket::whereCode($request->ticketCode)->firstOrFail();
//dd($ticket->watchingUsers->id);
        $mergedAlreadyList = [];
        foreach ($ticket->merged as $u) {
            array_push($mergedAlreadyList, $u->id);
            # code...
        }

//dd($watchingAlreadyList);
        //ticketCode

        $TicketRes = Ticket::whereNotIn('id', $mergedAlreadyList)
            ->where('code', 'LIKE', '%' . $request->q . '%')
            ->where('merge_flag', 'false')
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
        //where can merge!
            ->get();

//EXCLUDE $ticket->watching

        $items = [];

        foreach ($TicketRes as $ticketOnce) {
            # code...
            array_push($items, ['id' => $ticketOnce->id,
                'name' => '#' . $ticketOnce->code,
                'img' => '<i class=\'fa fa-ticket\'></i>',
                'position' => $ticketOnce->subject,
                'value' => $ticketOnce->id]);
        }

        $data = ['items' => $items];

        return response()->json($data);

    }

    public function storeMerge($id, Request $request)
    {

        $ticket = Ticket::whereCode($id)->firstOrFail();

        $ticket2Merge = Ticket::whereId($request->TICKETID)->firstOrFail();
        $ticket2Merge->update([
            'merge_flag' => 'true',
        ]);

        $ticket->merged()->attach($request->TICKETID, ['author_id' => Auth::user()->id]);

    }

    public function showMerged(Request $request)
    {

        $ticket = Ticket::whereCode($request->TICKETCODE)->firstOrFail();

        $data = ['ticket' => $ticket];
//dd($ticket);
        return view('user.ticket.mergeTicketList')->with($data);

    }

    public function destroyMerge($id, Request $request)
    {

        $ticket = Ticket::whereCode($id)->firstOrFail();

        $ticket2Merge = Ticket::whereCode($request->TICKETID)->firstOrFail();
        $ticket2Merge->update([
            'merge_flag' => 'false',
        ]);

        $ticket->merged()->detach($ticket2Merge->id);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

//indexWatching
    public function indexWatching(Request $request)
    {

        $ticket = Ticket::whereCode($request->ticketCode)->firstOrFail();
//dd($ticket->watchingUsers->id);
        $watchingAlreadyList = [];
        foreach ($ticket->watchingUsers as $u) {
            array_push($watchingAlreadyList, $u->id);
            # code...
        }
//dd($watchingAlreadyList);
        //ticketCode

        $UserRes = User::whereNotIn('id', $watchingAlreadyList)->where('name', 'LIKE', '%' . $request->q . '%')->get();

//EXCLUDE $ticket->watching

        $items = [];

        foreach ($UserRes as $user) {
            # code...
            array_push($items, ['id' => $user->id,
                'name' => $user->name,
                'img' => Zen::showUserImgSmall($user->profile->user_img),
                'position' => $user->profile->position,
                'value' => $user->id]);
        }

        $data = ['items' => $items];

        return response()->json($data);

    }

//storeWatching
    public function storeWatching($id, Request $request)
    {

        $ticket = Ticket::whereCode($id)->firstOrFail();
        $ticket->watchingUsers()->attach($request->USERID);

    }

//storeWatching
    public function destroyWatching($id, Request $request)
    {

        $ticket = Ticket::whereCode($id)->firstOrFail();
        $ticket->watchingUsers()->detach($request->USERID);

    }

//showWatching
    public function showWatching(Request $request)
    {

        $ticket = Ticket::whereCode($request->TICKETCODE)->firstOrFail();

        $data = ['ticket' => $ticket];
//dd($ticket);
        return view('user.ticket.watchingList')->with($data);

    }

    public function showWatchingPanel(Request $request)
    {

        $ticket = Ticket::whereCode($request->TICKETCODE)->firstOrFail();

        $data = ['ticket' => $ticket];
//dd($ticket);
        return view('user.ticket.watchingListPanel')->with($data);

    }

//showReferPanel
    public function showReferPanel(Request $request)
    {

        $UserTicketConf = UserTicketConf::firstOrCreate(['user_id' => Auth::user()->id]);

        if ($UserTicketConf->conf_params == "group") {

            if (isset($UserTicketConf->groupTicket->ticket_form_id)) {
                $TicketForm = TicketForms::findOrFail($UserTicketConf->groupTicket->ticket_form_id);
            }
            else {
                $TicketForm = TicketForms::findOrFail(1);
            }


        } else if ($UserTicketConf->conf_params == "user") {
            $TicketForm = TicketForms::findOrFail($UserTicketConf->ticket_form_id);
        }

        $ticket = Ticket::whereCode($request->TICKETCODE)->firstOrFail();

        $tG = $TicketForm->targetGroups;

        $tGArr = [];
        $tGArr[null] = 'Select item';
        foreach ($tG as $key => $value) {
            $tGArr[$value->id] = $value->name;
        }

        $tU = $TicketForm->targetUsers;

        $tUArr = [];
        foreach ($tU as $key => $value) {
            $tUArr[$value->id] = $value->name;
        }

        $data = [
            'TicketForm' => $TicketForm,
            'ticket' => $ticket,
            'tG' => $tGArr,
            'tU' => $tUArr,
        ];

        return view('user.ticket.referForm')->with($data);
    }

//updateWorkStatus
    //updateSuccessStatus
    public function updateWorkStatus(Request $request, $id)
    {

        $user = Auth::user();
        $ticket = Ticket::whereCode($id)->firstOrFail();
//dd('ok');

/*START INDIVIDUAL*/
        if ($ticket->individual_ok == "true") {
            //IF I IN TARGET
            if ($ticket->targetUsers()
                ->wherePivot('user_id', Auth::user()->id)->exists()) {
                $statusPivot = $ticket->targetUsers()
                    ->wherePivot('user_id',
                        Auth::user()->id)->firstOrFail();

                $curStatus = $statusPivot->pivot->individual_lock_status;

                if ($curStatus == 'false') {
                    $statusPivot->pivot->update([
                        'individual_lock_status' => 'true',
                    ]);

//$ticket->targetUsers()->count()
                    if ($ticket->targetUsers()->wherePivot('individual_lock_status', 'true')->count() == 1) {
                        Event::fire(new TicketLogger($ticket->id, $user->id, 'lock'));
                        Event::fire(new TicketNotify($ticket->id, $user->id, 'lock'));
                    } else if ($ticket->targetUsers()->wherePivot('individual_lock_status', 'true')->count() > 1) {
                        Event::fire(new TicketLogger($ticket->id, $user->id, 'lockNext'));
                        Event::fire(new TicketNotify($ticket->id, $user->id, 'lock'));
                    }

//Event::fire(new TicketLogger($ticket->id, $user->id, 'lock'));
                    //беру в работу
                    if ($ticket->targetUsers()->wherePivot('individual_lock_status', 'true')->count() != 0) {
                        //если кто-то ещё работает с заявкой
                        $this->updateSlaReaction($ticket->id);
                        $ticket->update(['status' => 'lock']);
                        //dd('op');
                    }

                } else if ($curStatus == 'true') {
                    $statusPivot->pivot->update([
                        'individual_lock_status' => 'false',
                    ]);

                    Event::fire(new TicketLogger($ticket->id, $user->id, 'unlock'));
                    Event::fire(new TicketNotify($ticket->id, $user->id, 'unlock'));
//снимаю с работы

                    if ($ticket->targetUsers()->wherePivot('individual_lock_status', 'true')->count() != 0) {
//если кто-то ещё работает с заявкой
                        $ticket->update(['status' => 'lock']);
                    } else {
//если уже никто не работает с заявкой
                        $this->updateSlaWork($ticket->id);
                        $ticket->update(['status' => 'free']);
                    }

                }

            } else {
                //if im not target user?
                $ticketStatus = $ticket->status;
                if ($ticketStatus == 'free') {

                    if ($ticket->targetUsers()->wherePivot('individual_lock_status', 'true')->count() == 1) {
                        Event::fire(new TicketLogger($ticket->id, $user->id, 'lock'));
                        Event::fire(new TicketNotify($ticket->id, $user->id, 'lock'));
                    } else if ($ticket->targetUsers()->wherePivot('individual_lock_status', 'true')->count() > 1) {
                        Event::fire(new TicketLogger($ticket->id, $user->id, 'lockNext'));
                        Event::fire(new TicketNotify($ticket->id, $user->id, 'lock'));
                    }

                    $ticket->update(['status' => 'lock']);
                    $this->updateSlaReaction($ticket->id);

                } else if ($ticketStatus == 'lock') {
                    $this->updateSlaWork($ticket->id);
                    $ticket->update(['status' => 'free']);
                    Event::fire(new TicketLogger($ticket->id, $user->id, 'unlock'));
                    Event::fire(new TicketNotify($ticket->id, $user->id, 'unlock'));

                }
            }

        }

/*STOP INDIVIDUAL*/

        else {
            $ticketStatus = $ticket->status;
            if ($ticketStatus == 'free') {
                $ticket->update(['status' => 'lock']);
                $this->updateSlaReaction($ticket->id);
                Event::fire(new TicketLogger($ticket->id, $user->id, 'lock'));
                Event::fire(new TicketNotify($ticket->id, $user->id, 'lock'));

            } else if ($ticketStatus == 'lock') {
                // $this->updateSlaWork($ticket->id);
                $ticket->update(['status' => 'free']);
                Event::fire(new TicketLogger($ticket->id, $user->id, 'unlock'));
                Event::fire(new TicketNotify($ticket->id, $user->id, 'unlock'));

            }

        }

        if ($ticket->status == "free") {
            // $this->updateSlaWork($ticket->id);
        }

        $msgSuccess = "<h4><i class=\"icon fa fa-check\"></i> " . trans('handler.ticketUpdated');
        $request->session()->flash('alert-success', $msgSuccess);

        return redirect('ticket/' . $id);
    }

    public function updateSuccessStatus(Request $request, $id)
    {

        $ticket = Ticket::whereCode($id)->firstOrFail();
        $user = Auth::user();

        if ($ticket->individual_ok == "true") {
            if ($ticket->targetUsers()->wherePivot('user_id', Auth::user()->id)->exists()) {
                $statusPivot = $ticket->targetUsers()->wherePivot('user_id', Auth::user()->id)->firstOrFail();

                if ($statusPivot->pivot->individual_ok_status == 'false') {
                    $statusPivot->pivot->update([
                        'individual_ok_status' => 'true',
                    ]);
                    //Event::fire(new TicketLogger($ticket->id, $user->id, 'ok'));

//если все individual_ok_status == true
                    //status success
                    if ($ticket->targetUsers()->WherePivot('individual_ok_status', 'false')->count() == 0) {

                        if ($ticket->inspect_after_ok == 'true') {
                            $this->updateSlaWork($ticket->id);
                            $this->updateSlaDeadline($ticket->id);
                            $ticket->update(['status' => 'waitsuccess']);
                            Event::fire(new TicketLogger($ticket->id, $user->id, 'waitok'));
                            Event::fire(new TicketNotify($ticket->id, $user->id, 'waitok'));
                        } else {
                            $this->updateSlaWork($ticket->id);
                            $this->updateSlaDeadline($ticket->id);
                            $ticket->update(['status' => 'success']);
                            Event::fire(new TicketLogger($ticket->id, $user->id, 'ok'));
                            Event::fire(new TicketNotify($ticket->id, $user->id, 'ok'));
                        }

//////////
                    }

                } else if ($statusPivot->pivot->individual_ok_status == 'true') {
                    $statusPivot->pivot->update([
                        'individual_ok_status' => 'false',
                    ]);

                    if ($ticket->targetUsers()->wherePivot('individual_ok_status', 'false')->count() == 1) {
                        Event::fire(new TicketLogger($ticket->id, $user->id, 'unok'));
                        Event::fire(new TicketNotify($ticket->id, $user->id, 'unok'));
                    } else if ($ticket->targetUsers()->wherePivot('individual_ok_status', 'false')->count() > 1) {
                        Event::fire(new TicketLogger($ticket->id, $user->id, 'unokNext'));
                        Event::fire(new TicketNotify($ticket->id, $user->id, 'unok'));
                    }

                    //Event::fire(new TicketLogger($ticket->id, $user->id, 'unok'));

//если все individual_ok_status == true и individual_lock_status == true
                    //то status lock
                    //иначе status free
                    if ($ticket->targetUsers()->WherePivot('individual_ok_status', 'false')->count() != 0) {
                        $ticket->update(['status' => 'lock']);
                    }

                }
            } else {
                //if im not target user?
                if ($ticket->status == 'lock') {
                    //$ticket->update(['status'=>'success']);

                    if ($ticket->inspect_after_ok == 'true') {
                        $this->updateSlaDeadline($ticket->id);
                        $this->updateSlaWork($ticket->id);
                        $ticket->update(['status' => 'waitsuccess']);
                        Event::fire(new TicketLogger($ticket->id, $user->id, 'waitok'));
                        Event::fire(new TicketNotify($ticket->id, $user->id, 'waitok'));
                    } else {
                        $this->updateSlaDeadline($ticket->id);
                        $this->updateSlaWork($ticket->id);
                        $ticket->update(['status' => 'success']);
                        Event::fire(new TicketLogger($ticket->id, $user->id, 'ok'));
                        Event::fire(new TicketNotify($ticket->id, $user->id, 'ok'));
                    }
/////////////

//Event::fire(new TicketLogger($ticket->id, $user->id, 'ok'));
                } else if ($ticket->status == 'success') {

                    $ticket->update(['status' => 'lock']);
                    if ($ticket->targetUsers()->wherePivot('individual_ok_status', 'false')->count() == 1) {
                        Event::fire(new TicketLogger($ticket->id, $user->id, 'unok'));
                        Event::fire(new TicketNotify($ticket->id, $user->id, 'unok'));
                    } else if ($ticket->targetUsers()->wherePivot('individual_ok_status', 'false')->count() > 1) {
                        Event::fire(new TicketLogger($ticket->id, $user->id, 'unokNext'));
                        Event::fire(new TicketNotify($ticket->id, $user->id, 'unok'));
                    }

                }
            }

        } else {
            if ($ticket->status == 'lock') {
                //$ticket->update(['status'=>'success']);

                if ($ticket->inspect_after_ok == 'true') {
                    $this->updateSlaWork($ticket->id);
                    $this->updateSlaDeadline($ticket->id);
                    $ticket->update(['status' => 'waitsuccess']);
                    Event::fire(new TicketLogger($ticket->id, $user->id, 'waitok'));
                    Event::fire(new TicketNotify($ticket->id, $user->id, 'waitok'));
                } else {
                    $this->updateSlaWork($ticket->id);
                    $this->updateSlaDeadline($ticket->id);
                    $ticket->update(['status' => 'success']);
                    //dd('ook');
                    Event::fire(new TicketLogger($ticket->id, $user->id, 'ok'));
                    Event::fire(new TicketNotify($ticket->id, $user->id, 'ok'));
                }
/////////////

//Event::fire(new TicketLogger($ticket->id, $user->id, 'ok'));
            } else if ($ticket->status == 'success') {
                $ticket->update(['status' => 'lock']);
                Event::fire(new TicketLogger($ticket->id, $user->id, 'unok'));
                Event::fire(new TicketNotify($ticket->id, $user->id, 'unok'));

            }
        }

        $msgSuccess = "<h4><i class=\"icon fa fa-check\"></i> " . trans('handler.ticketUpdated');
        $request->session()->flash('alert-success', $msgSuccess);

        return redirect('ticket/' . $id);
    }

//updateSuccessStatusApprove
    public function updateSuccessStatusApprove($id, Request $request)
    {
        $ticket = Ticket::whereCode($id)->firstOrFail();
        $user = Auth::user();

        if ($ticket->status == "waitsuccess") {
//$this->updateSlaWork($ticket->id);
            $this->updateSlaDeadline($ticket->id);
            $ticket->update(['status' => 'success']);
            Event::fire(new TicketLogger($ticket->id, $user->id, 'approve'));
            Event::fire(new TicketNotify($ticket->id, $user->id, 'approve'));
//Event::fire(new TicketLogger($ticket->id, $user->id, 'ok'));

        }

        $msgSuccess = "<h4><i class=\"icon fa fa-check\"></i> " . trans('handler.ticketUpdated');
        $request->session()->flash('alert-success', $msgSuccess);

        return back();
    }

    public function updateSuccessStatusNoApprove($id, Request $request)
    {
        $ticket = Ticket::whereCode($id)->firstOrFail();
        $user = Auth::user();

        if ($ticket->status == "waitsuccess") {

            $ticket->update(['status' => 'lock']);
            Event::fire(new TicketLogger($ticket->id, $user->id, 'noapprove'));
            Event::fire(new TicketNotify($ticket->id, $user->id, 'noapprove'));

            foreach ($ticket->targetUsers as $targetUser) {
                # code...
                $targetUser->pivot->individual_ok_status = 'false';
                $targetUser->pivot->individual_lock_status = 'true';
                $targetUser->pivot->save();
            }

//$this->updateSlaWork($ticket->id);

        }

        $msgSuccess = "<h4><i class=\"icon fa fa-check\"></i> " . trans('handler.ticketUpdated');
        $request->session()->flash('alert-success', $msgSuccess);

        return back();
    }

//indexDeleted

    public function indexClients(Request $request)
    {

        $UserTicketConf = UserTicketConf::firstOrCreate(['user_id' => Auth::user()->id]);

        if ($UserTicketConf->conf_params == "group") {

            if (isset($UserTicketConf->groupTicket->ticket_form_id)) {
                $TicketForm = TicketForms::findOrFail($UserTicketConf->groupTicket->ticket_form_id);
            }
            else {
                $TicketForm = TicketForms::findOrFail(1);
            }


        } else if ($UserTicketConf->conf_params == "user") {
            $TicketForm = TicketForms::findOrFail($UserTicketConf->ticket_form_id);
        }

//$TicketForm->clientGroups;

//найти всех пользователей, которые входят в группы
        //select all users where('column', 'LIKE', '%value%') and group_id in ()

//dd($TicketForm->clientGroups);
        $clientGroups = [];
        foreach ($TicketForm->clientGroups as $value) {
            # code...
            array_push($clientGroups, $value->id);
        }
//dd($clientGroups);

        $UserRes = User::where('name', 'LIKE', '%' . $request->q . '%')
            ->where(function ($query) use ($clientGroups) {
                return $query
                    ->whereHas('groups', function ($q) use ($clientGroups) {
                        $q->whereIn('id', $clientGroups);
                    })
                    ->orHas('groups', '=', 0);
            })

            ->get();

        $items = [];

        if ($TicketForm->create_user == 'true') {
            if ($UserRes->count() == 0) {
                $newName = $request->q . trans('handler.ticketCreateNewClient');
                $newID = $request->q;
                $randId = 'new_' . str_random(5);
                array_push($items, ['id' => $newID . '[new]',
                    'img' => Zen::showUserImgSmall(null),
                    'name' => $newName,
                    'position' => trans('handler.willBeCreateNewClient'),
                    'value' => $newID . '[new]']);
            }
        }

        foreach ($UserRes as $user) {
            # code...
            array_push($items, ['id' => $user->id,
                'img' => Zen::showUserImgSmall($user->profile->user_img),
                'name' => $user->name,
                'position' => $user->profile->position,
                'value' => $user->id]);
        }

        $data = ['items' => $items];

        return response()->json($data);
    }

    public function showClients(Request $request)
    {

/*$data=[
'comments'=>$comments
];*/

//$html = view('user.ticket.singleComment')->with($data);

//dd($html);
        /*
        if (!empty($request->clients)) {

        foreach ($request->clients as $client) {
        # code...
        $user=User::whereId($client)->get();
        }

        }
        else {
        //empty
        }*/

        $clientsArr = $request->clients;
        if (!empty($clientsArr)) {
            $countClients = count($clientsArr);

            if ($countClients == 1) {

                if (strpos($request->clients[0], '[new]') !== false) {
                    $n = explode('[new]', $request->clients[0]);
                    $ciDATA = [
                        'name' => $n[0],
                    ];
                    $resHTML = view('user.ticket.clientNew')->with($ciDATA)->render();} else {
                    if (User::where('id', '=', $request->clients[0])->exists()) {
                        $ce = User::where('id', '=', $request->clients[0])->first();
                        $dataCE = ['client' => $ce];

                        $resHTML = view('user.ticket.clientExist')->with($dataCE)->render();
                    }
                }

//if (strpos($client,'[new]') !== false) { $client=Null; }

                //$client=User::where('id', '=', $request->clients[0])->exists()
                //findOrFail($clientsArr[0]);
                //$resHTML=$request->clients[0];
                // ($client) ? $resHTML='ok' : $resHTML="not found";
                //dd($client);

/*  if (User::where('id', '=', $request->clients[0])->exists()) {
$resHTML=view('user.ticket.clientExist')->render();
}
else  {
$resHTML=view('user.ticket.clientNew')->render();
}
 */

            } else if ($countClients > 1) {

/*$clientsArrCollect=collect($clientsArr);
$sorted = $clientsArrCollect->sort();
$sortedArr=$sorted->values()->all();*/

//dd($sorted->values()->all());
                $existClient = [];
                $newClient = [];
                foreach ($clientsArr as $client) {

//if $client consist new

                    if (strpos($client, '[new]') !== false) {
                        $name = explode('[new]', $client);
                        array_push($newClient, $name[0]);
                    } else {
                        if (User::whereId($client)->exists()) {
                            array_push($existClient, $client);
                        }
                    }

                }

                $dataList = [
                    'existClient' => $existClient,
                    'newClient' => $newClient,

                ];
                $resHTML = view('user.ticket.clientsList')->with($dataList)->render();

            }

//$resHTML=count($request->clients);

        } else {
            $resHTML = view('user.ticket.clientsEmpty')->render();
        }

/*если пусто - то вывести что пусто
если не пусто - то к-во элементов в массиве

если 1 - то 1 шаблон
если > - то 2 шаблон

если пользователь с таким id не найден - то создать нового?
нужно его (email, ФИО)*/

        $dataJSON = [[
            'html' => $resHTML,
        ],
        ];

        return response()->json($dataJSON);

    }

    public function create()
    {
        //

//delete tmp files

        $files = Files::where('status', 'tmp')->where('target_type', 'ticket')->where('user_id', Auth::user()->id)->get();

        $storage = Storage::disk('users');

        foreach ($files as $file) {
            # code...
            $fileName = $file->hash . '.' . $file->extension;
            $storage->delete(Auth::user()->id . '/' . $fileName);
            $file->delete();
        }

        $UserTicketConf = UserTicketConf::firstOrCreate(['user_id' => Auth::user()->id]);

        if ($UserTicketConf->conf_params == "group") {

            if (isset($UserTicketConf->groupTicket->ticket_form_id)) {
                $TicketForm = TicketForms::findOrFail($UserTicketConf->groupTicket->ticket_form_id);
            }
            else {
                $TicketForm = TicketForms::findOrFail(1);
            }


        } else if ($UserTicketConf->conf_params == "user") {
            $TicketForm = TicketForms::findOrFail($UserTicketConf->ticket_form_id);
        }

/*
client_field
target_field
prio
subj_field
upload_files
upload_files_types
upload_files_count
upload_files_size
deadline_field
watching_field
individual_ok_field
check_after_ok
 */

        $targetGroups = $TicketForm->targetGroups;

        $targetGroupsArr = [];
        $targetGroupsArr[null] = 'Select item';
        foreach ($targetGroups as $key => $value) {
            $targetGroupsArr[$value->id] = $value->name;
        }

        $targetUsers = $TicketForm->targetUsers;

        $targetUsersArr = [];
        foreach ($targetUsers as $key => $value) {
            $targetUsersArr[$value->id] = $value->name;
        }

        $subj = $TicketForm->subjs;

        $subjArr = [];
        $subjArr[null] = 'Select item';
        foreach ($subj as $key => $value) {
            $subjArr[$value->name] = $value->name;
        }

        $slas = $TicketForm->slas;
        $slasArr = [];
        $slasArr[null] = 'Select item';
        foreach ($slas as $key => $value) {
            $slasArr[$value->id] = $value->name;
        }

        $user = Auth::user();
        $myGroups = [];
        foreach ($user->groups as $value) {
            # code...
            array_push($myGroups, $value->id);
        }

        $UserWatching = User::whereHas('groups', function ($q) use ($myGroups) {
            $q->whereIn('id', $myGroups);
        })->get();

        $watchingUsersArr = [];
        foreach ($UserWatching as $key => $value) {
            $watchingUsersArr[$value->id] = $value->name;
        }

        $data = [
            'TicketForm' => $TicketForm,
            'targetGroup' => $targetGroupsArr,
            'targetUser' => $targetUsersArr,
            'subj' => $subjArr,
            'slas' => $slasArr,
            'watchingUsers' => $watchingUsersArr,

        ];

        return view('user.ticket.create')->with($data);

    }

//storeFiles
    public function storeFiles(Request $request)
    {

        //
        $user = Auth::user();

        $UserTicketConf = UserTicketConf::firstOrCreate(['user_id' => $user->id]);



        if ($UserTicketConf->conf_params == "group") {

            if (isset($UserTicketConf->groupTicket->ticket_form_id)) {
                $TicketForm = TicketForms::findOrFail($UserTicketConf->groupTicket->ticket_form_id);
            }
            else {
                $TicketForm = TicketForms::findOrFail(1);
            }


        } else if ($UserTicketConf->conf_params == "user") {
            $TicketForm = TicketForms::findOrFail($UserTicketConf->ticket_form_id);
        }

        $fileTypes = $TicketForm->upload_files_types;
        $fileCount = $TicketForm->upload_files_count;
        $fileSize = $TicketForm->upload_files_size;

        $file = $request->file('ticketfile');
        $validator = Validator::make(array('ticketfile' => $file), [
            'ticketfile' => 'mimes:' . $fileTypes . '|max:' . $fileSize . '',
        ]);

        if ($validator->fails()) {

            return response()->json([
                'status' => 'error',
                'message' => $validator->messages()->first(),
                'code' => 500,
            ]);

        } else {

            $count_tmpFiles = Files::whereUserId(Auth::user()->id)->whereStatus('tmp')->where('target_type', 'ticket')->count();
            if ($count_tmpFiles >= $fileCount) {
                return response()->json([
                    'status' => 'error',
                    'message' => trans('handler.maxFilesCount') . $fileCount,
                    'code' => 500,
                ]);
            }

            $fileHash = str_random(30);

            $extension = $file->getClientOriginalExtension();
            $mime = $file->getClientMimeType();
            $originalName = $file->getClientOriginalName();

            $isimage = 'false';
            if (substr($mime, 0, 5) == 'image') {
                $isimage = 'true';
            }

            $storage = Storage::disk('users');
            $file_name = $fileHash . '.' . strtolower($extension);

            if (!$storage->exists($user->id)) {
                $storage->makeDirectory($user->id);
            }

            $storage->put($user->id . '/' . $file_name,
                file_get_contents($request->file('ticketfile')->getRealPath()));

/*if ($isimage == 'true') {
$img=Image::make($imgPath)->fit(150, 150, function ($constraint) {
$constraint->aspectRatio();
$constraint->upsize();
});
$img->save('files/users/img/' . $string . '.' . $extension);
}*/

            Files::create([
                'user_id' => $user->id,
                'target_id' => null,
                'target_type' => 'ticket',
                'name' => $originalName,
                'hash' => $fileHash,
                'mime' => $mime,
                'extension' => strtolower($extension),
                'status' => 'tmp',
                'image' => $isimage,

            ]);

            return response()->json([
                'status' => 'success',
                'uniq_code' => $fileHash,
                'message' => '',
                'code' => 500,
            ]);

        }

    }

    //destroyFiles
    public function destroyFiles($id)
    {

        $user = Auth::user();

        $file = Files::where('hash', '=', $id)->where('user_id', $user->id)->firstOrFail();

        $storage = Storage::disk('users');

        $fileAuthor = $file->user_id;
        $fileName = $file->hash . '.' . $file->extension;

        $storage->delete($fileAuthor . '/' . $fileName);

        $file->delete();

    }

//ticketCodeGenerate
    public function ticketCodeGenerate()
    {

        $selTicketCode = Setting::get('ticket.code');
        do {
            if ($selTicketCode == "autoinc") {
//get last ticket id
                $ticket = Ticket::orderBy('created_at', 'desc')->first();
                $codeGen = $ticket->id + 1;
            } else {
                $randNum = Setting::get('ticket.codeCount', '4');

                $codeGen = strtoupper(str_random($randNum));
            }

        } while (Ticket::where('code', $codeGen)->count() != 0);

        return $codeGen;

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

        $user = Auth::user();

        $UserTicketConf = UserTicketConf::firstOrCreate(['user_id' => Auth::user()->id]);

        if ($UserTicketConf->conf_params == "group") {

            if (isset($UserTicketConf->groupTicket->ticket_form_id)) {
                $TicketForm = TicketForms::findOrFail($UserTicketConf->groupTicket->ticket_form_id);
            }
            else {
                $TicketForm = TicketForms::findOrFail(1);
            }


        } else if ($UserTicketConf->conf_params == "user") {
            $TicketForm = TicketForms::findOrFail($UserTicketConf->ticket_form_id);
        }

        $validatorRules = [];
        $ticketResultArr = [];

        if ($request->plannerStatus == "true") {
            $validatorRules['plannerName'] = 'required';

            $validatorRules['plannerStartDay'] = 'required';
            $validatorRules['plannerStartMonth'] = 'required';
            $validatorRules['plannerStartYear'] = 'required';

            $validatorRules['plannerEndDay'] = 'required';
            $validatorRules['plannerEndMonth'] = 'required';
            $validatorRules['plannerEndYear'] = 'required';

            $validatorRules['plannerTime'] = 'required';

        }

        if ($TicketForm->client_field == 'self') {
//insert into ticket_clients USER-I AM
        } else if ($TicketForm->client_field == 'group') {
//insert into ticket_clients USER-LISTS
            $validatorRules['client'] = 'required';

//For ALL new CLIENTS REQUIRED EMAIL!
            if ($request->client) {
                foreach ($request->client as $cl) {
                    if (strpos($cl, '[new]') !== false) {
                        $normalName = explode('[new]', $cl);
                        $validatorRules['clientNEW[' . $normalName[0] . '][email]'] = 'required|email|unique:users,email'; // add uniq!
                        //clientNEW[trololo][email]
                    }

                }
            }
//dd($validatorRules);

        }

        if ($TicketForm->target_field == 'user_groups') {

/*array_push($ticketResultArr, [
'target_group_id'=>$request->targetGroup
]);*/
            if (empty($request->targetUsers) && (empty($request->targetGroup))) {
                $validatorRules['targetGroup'] = 'required';
                $validatorRules['targetUsers'] = 'required';
            }

        } else if ($TicketForm->target_field == 'users') {
//insert into ticket_target_user USER=targetUsers LISTS
            $validatorRules['targetUsers'] = 'required';
        } else if ($TicketForm->target_field == 'group') {
            $validatorRules['targetGroup'] = 'required';
        }

        $validatorRules['subj'] = 'required';
        $validatorRules['msg'] = 'required|min:10';

        foreach ($TicketForm->fields as $field) {

            if ($field->required == "true") {
                $validatorRules['field' . $field->id] = 'required';
            }

        }

        $reqArr = [];
        if ($request->clientNEW) {
            foreach ($request->clientNEW as $key => $cl) {

                $reqArr['clientNEW[' . $key . '][email]'] = $cl['email'];

            }
        }

        $resReq = array_merge($request->all(), $reqArr);
//dd($resReq);

        $validator = Validator::make($resReq, $validatorRules);

        if ($validator->fails()) {

            $request->session()->flash('alert-warning', trans('handler.msgFixErrors'));
            return back()->withErrors($validator)->withInput();

        } else {

            $code = $this->ticketCodeGenerate();

            $urlhash = str_random(10);

            (!empty($request->tags)) ? $tags = implode(',', $request->tags) : $tags = null;

            ($request->sla) ? $sla = $request->sla : $sla = null;

//($TicketForm->target_field == 'users') ? $targetGroupID=Null : $targetGroupID=$request->targetGroup;
            $targetGroupID = null;
            if (!empty($request->targetGroup)) {
                $targetGroupID = $request->targetGroup;
            }

            if ($request->deadlineStatus == 'active') {
                $deadlineTime = $request->deadlineYear . '-' . $request->deadlineMonth . '-' . $request->deadlineDay . ' ' . $request->deadlineTime . ':00';
            } else {
                $deadlineTime = null;
            }

            ($request->check_after == 'true') ? $check_after = 'true' : $check_after = 'false';

            ($request->individual_ok == 'true') ? $individual_ok = 'true' : $individual_ok = 'false';

            ($request->plannerStatus == "true") ? $plannerStatus = 'true' : $plannerStatus = 'false';

            (empty($request->prio)) ? $prio = 'normal' : $prio = $request->prio;

            $ticket = Ticket::create([
                'author_id' => $user->id,
                'code' => $code,
//'client_id',
                'prio' => $prio,
                'text' => $request->msg,
                'subject' => $request->subj,
                'tags' => $tags,
                'urlhash' => $urlhash,
//'number',
                'sla_id' => $sla,
                'target_group_id' => $targetGroupID,
                'deadline_time' => $deadlineTime,
                'inspect_after_ok' => $check_after,
                'individual_ok' => $individual_ok,
                'planner_flag' => $plannerStatus,
            ]);

            if ($plannerStatus == 'true') {
/*

plannerStatus
plannerName
plannerPeriod
plannerEveryDay
plannerTime

plannerStartDay
plannerStartMonth
plannerStartYear

plannerEndDay
plannerEndMonth
plannerEndYear

 */
                $plannerTime = explode(':', $request->plannerTime);
                $plannerStartWork = $request->plannerStartYear . '-' . $request->plannerStartMonth . '-' . $request->plannerStartDay . ' 00:00:00';
                $plannerEndWork = $request->plannerEndYear . '-' . $request->plannerEndMonth . '-' . $request->plannerEndDay . ' 23:59:00';

                TicketPlanner::create([

                    'name' => $request->plannerName,
                    'ticket_id' => $ticket->id,
                    'author_id' => $user->id,
                    'period' => $request->plannerPeriod,
                    'dayHour' => $plannerTime[0],
                    'dayMinute' => $plannerTime[1],
                    'weekDay' => $request->plannerEveryDay,
                    'monthDay' => $request->plannerEveryDay,
                    'startWork' => $plannerStartWork,
                    'endWork' => $plannerEndWork,

                ]);

            }

            if ($plannerStatus == 'false') {
                Event::fire(new TicketLogger($ticket->id, $user->id, 'create'));
            }

//($TicketForm->target_field == 'users') ? $targetGroupID=Null : $targetGroupID=$request->targetGroup;

//clients
            //targets
            //adv_fields
            //ticket_watching
            if ($sla != null) {
                TicketSlaLog::create([
                    'ticket_id' => $ticket->id,
                ]);
            }

///////////////////////////////////NEW CLIENT////////////////////////////////////
            $newClintsArr = [];
            $newClientIds = [];
            if ($request->client) {
                foreach ($request->client as $cl) {
                    if (strpos($cl, '[new]') !== false) {
                        $normalName = explode('[new]', $cl);
                        //echo $normalName[0].' <br>';
                        //clientNEW['.$normalName[0].'][email]
                        array_push($newClintsArr, [
                            'name' => $normalName[0],
                            'email' => $request->clientNEW[$normalName[0]]['email'],
                            'posada' => $request->clientNEW[$normalName[0]]['posada'],
                            'address' => $request->clientNEW[$normalName[0]]['address'],
                            'pass' => str_random('8'),
                        ]);
                    }
                }

                foreach ($newClintsArr as $newClientArr) {
                    # code...
                    //dd($newClientArr[]);

                    $NewUser = Zen::storeNewUser([
                        'name' => $newClientArr['name'],
                        'email' => $newClientArr['email'],
                        'password' => $newClientArr['pass'],
                    ]);

                    $NewUser->profile->update([

                        'full_name' => $newClientArr['name'],
                        'email' => $newClientArr['email'],

                    ]);

/*

$NewUser = User::create([
'name' => $newClientArr['name'],
'email' => $newClientArr['email'],
'password' => bcrypt($newClientArr['pass']),
]);
$NewUserProfile = new UserProfile;
$NewUserProfile->full_name = $newClientArr['name'];
$NewUserProfile->email = $newClientArr['email'];*/
                    if (!empty($newClientArr['posada'])) {

                        $NewUser->profile->update([
                            'position' => $newClientArr['posada'],
                        ]);
                    }
                    if (!empty($newClientArr['address'])) {
                        $NewUser->profile->update([
                            'address' => $newClientArr['address'],
                        ]);
                    }

                    $NewUser->profile->update([
                        'user_urlhash' => str_random(25),
                    ]);

                    //$NewUser->profile()->save($NewUserProfile);

                    /*$userTicketConf= UserTicketConf::create([
                    'user_id'=>$NewUser->id,
                    'ticket_form_id'=>'1',
                    'conf_params'=>'user'
                    ]);

                    UserRole::create([
                    'user_id'=>$NewUser->id,
                    'role'=>'client'
                    ]);

                    UserLdap::create([
                    'user_id'=>$NewUser->id
                    ]);
                     */
                    //$newClientArr['user_id']=$NewUser->id;
                    array_push($newClientIds, $NewUser->id);

                }
//dd($newClientIds);

            }
///////////////////////////////////NEW CLIENT////////////////////////////////////

            ($TicketForm->client_field == 'self') ? $ClientField = [$user->id] : $ClientField = $request->client;
            $ClientField = array_merge($ClientField, $newClientIds);
            $ticket->clients()->attach($ClientField);

            if ($TicketForm->target_field != 'group') {
                if (!empty($request->targetUsers)) {
                    $ticket->targetUsers()->attach($request->targetUsers);
                }
            }

//Автор заявки наблюдает всегда
            //$ticket->watchingUsers()->attach([$user->id]);

//исполнители наблюдают тоже
            //$ticket->watchingUsers()->attach($request->targetUsers);

//notifyClient

            $notifyArr = [];
            if ($request->notifyClient == 'true') {

//$newClientIds
                //$request->client
                $notifyArr = array_unique(array_merge($newClientIds, $request->client));

//???????
                if ($plannerStatus == 'false') {
                    foreach ($newClientIds as $newClientId) {
                        # code...
                        Event::fire(new UserNotify($newClientId, null, 'create'));
                    }
                }

//Event::fire(new UserNotify($AuthorUser->id, $newPass, 'create'));

            }

            $targU = [];
            if (!empty($request->targetUsers)) {
                $targU = $request->targetUsers;
            }

            $watchU = [];
            if (!empty($request->watchingUsers)) {
                $watchU = $request->watchingUsers;
            }

            $watchResArr = array_unique(array_merge($watchU, $targU, [$user->id], $notifyArr));

            $ticket->watchingUsers()->attach($watchResArr);

/*if ($TicketForm->watching_field == "true") {
if (!empty($request->watchingUsers)) {
$ticket->watchingUsers()->attach($request->watchingUsers);
}
} */

            if ($TicketForm->fields->count() > 0) {
                foreach ($TicketForm->fields as $field) {
                    # code...
                    $fh = 'field' . $field->id;

                    if ($field->f_type == 'multiselect') {
                        if ($request->$fh) {$ticket->fields()->attach($field->id, ['field_data' => implode(',', $request->$fh)]);}
                    } else {
                        if ($request->$fh) {$ticket->fields()->attach($field->id, ['field_data' => $request->$fh]);}

                    }

                }
            }
            if ($plannerStatus == 'false') {
                Event::fire(new TicketNotify($ticket->id, $user->id, 'create'));
            }

//ADD FILES_APPROVE

            $DBfiles = Files::where('status', 'tmp')->where('target_type', 'ticket')->where('user_id', Auth::user()->id)->get();

            foreach ($DBfiles as $file) {
                $file->update(['status' => 'success', 'target_id' => $ticket->id]);
            }

            if ($plannerStatus == 'true') {
                $msgSuccess = "<h4>  <i class=\"icon fa fa-check\"></i> " . trans('ticketSuccessPlanned') . "</h4>" . trans('handler.youCan') . "  <a href=\"" . url('ticket/planner') . "\">" . trans('handler.goToEtc');
            } else {

                $msgSuccess = "<h4>  <i class=\"icon fa fa-check\"></i> " . trans('handler.ticketCreatedByNum') . " #" . $ticket->code . ", " . trans('handler.youCan') . " <a href=\"" . url('ticket/' . $ticket->code) . '/print' . "\">" . trans('handler.printOr') . " <a href=\"" . url('ticket/' . $ticket->code) . "\">" . trans('handler.goToTicketPage');

            }
            $request->session()->flash('alert-success', $msgSuccess);
            return redirect('/ticket/create');
        }

    }

//accessError
    public function accessError()
    {

        return view('user.ticket.ticketError');

    }

    public function ticketAccessAction($ticketCode)
    {

        $ticket = Ticket::whereCode($ticketCode)->firstOrFail();
        $user = Auth::user();

        //автор?
        if ($ticket->author_id == $user->id) {
            return true;
        }

        //заявка мне назначена?
        foreach ($ticket->targetUsers as $value) {
            if ($value->id == $user->id) {return true;}
        }

        //заявка моему отделу и никому конкретно?
        if (($ticket->targetUsers->count() == 0) && ($ticket->target_group_id != null)) {
            foreach ($user->groups as $value) {
                if ($value->id == $ticket->target_group_id) {return true;}
                # code...
            }
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

    public function ticketAccessModify($ticketCode)
    {

        $ticket = Ticket::whereCode($ticketCode)->firstOrFail();
        $user = Auth::user();

        //автор?
        if ($ticket->author_id == $user->id) {
            return true;
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

//showDeletedTicket
    public function showDeletedTicket($id)
    {

        $files = Files::where('status', 'tmp')->where('target_type', 'ticketComment')->where('user_id', Auth::user()->id)->get();

        $storage = Storage::disk('users');

        foreach ($files as $file) {
            # code...
            $fileName = $file->hash . '.' . $file->extension;
            $storage->delete(Auth::user()->id . '/' . $fileName);
            $file->delete();
        }

        $ticket = Ticket::where('code', $id)->onlyTrashed()->firstOrFail();
//$ticket=$ticket->trashed()->get();
        $user = Auth::user();

//dd($user->GroupAdmin());

//ticket->status

        $UserTicketConf = UserTicketConf::firstOrCreate(['user_id' => Auth::user()->id]);

        if ($UserTicketConf->conf_params == "group") {

            if (isset($UserTicketConf->groupTicket->ticket_form_id)) {
                $TicketForm = TicketForms::findOrFail($UserTicketConf->groupTicket->ticket_form_id);
            }
            else {
                $TicketForm = TicketForms::findOrFail(1);
            }


        } else if ($UserTicketConf->conf_params == "user") {
            $TicketForm = TicketForms::findOrFail($UserTicketConf->ticket_form_id);
        }

        $targetUsers = [];
        if ($ticket->targetUsers()->count() > 0) {
            foreach ($ticket->targetUsers as $targetUser) {
                # code...

                $tURes = '<a href=\'' . url('/users/') . '/' . $targetUser->profile->user_urlhash . '\'>' . $targetUser->name . '</a>';

                array_push($targetUsers, $tURes);
            }
        }

        $tG = $TicketForm->targetGroups;

        $tGArr = [];
        $tGArr[null] = 'Select item';
        foreach ($tG as $key => $value) {
            $tGArr[$value->id] = $value->name;
        }

        $tU = $TicketForm->targetUsers;

        $tUArr = [];
        foreach ($tU as $key => $value) {
            $tUArr[$value->id] = $value->name;
        }

//ticketAccessAction($ticker->id);
        //ticketAccessModify($ticket->id);

        $data = [
            'user' => $user,
            'ticket' => $ticket,
            'targetUsers' => $targetUsers,
            'TicketForm' => $TicketForm,
            'tU' => $tUArr,
            'tG' => $tGArr,
            'comments' => $ticket->comments,

        ];

        return view('user.ticket.ticketDeleted')->with($data);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, $print = null)
    {
        //

        $files = Files::where('status', 'tmp')->where('target_type', 'ticketComment')->where('user_id', Auth::user()->id)->get();

        $storage = Storage::disk('users');

        foreach ($files as $file) {
            # code...
            $fileName = $file->hash . '.' . $file->extension;
            $storage->delete(Auth::user()->id . '/' . $fileName);
            $file->delete();
        }

        $ticket = Ticket::where('code', $id)->firstOrFail();

        $user = Auth::user();

        NotificationMenu::where('user_id', $user->id)->where('ticket_id', $ticket->id)->delete();

//dd($user->GroupAdmin());

//ticket->status

        if ($ticket->individual_ok == "true") {

            if (($ticket->status != "arch") && ($ticket->status != "waitsuccess")) {
//dd($ticket->targetUsers()->wherePivot('user_id', Auth::user()->id)->exists());
                if ($ticket->targetUsers()->wherePivot('user_id', Auth::user()->id)->exists()) {

                    $statusPivot = $ticket->targetUsers()->wherePivot('user_id', Auth::user()->id)->firstOrFail();

                    if (($statusPivot->pivot->individual_lock_status == "false") && ($statusPivot->pivot->individual_ok_status == "false")) {
                        $ticket->status = 'free';
                    } else if (($statusPivot->pivot->individual_lock_status == "true") && ($statusPivot->pivot->individual_ok_status == "false")) {
                        $ticket->status = 'lock';
                    } else if (($statusPivot->pivot->individual_lock_status == "true") && ($statusPivot->pivot->individual_ok_status == "true")) {
                        $ticket->status = 'success';
                    }
                }

            } else if ($ticket->status == "waitsuccess") {

                //$ticket->status='waitsuccess';

            }

        }

        $UserTicketConf = UserTicketConf::firstOrCreate(['user_id' => Auth::user()->id]);

        if ($UserTicketConf->conf_params == "group") {

            if (isset($UserTicketConf->groupTicket->ticket_form_id)) {
                $TicketForm = TicketForms::findOrFail($UserTicketConf->groupTicket->ticket_form_id);
            }
            else {
                $TicketForm = TicketForms::findOrFail(1);
            }


        } else if ($UserTicketConf->conf_params == "user") {
            $TicketForm = TicketForms::findOrFail($UserTicketConf->ticket_form_id);
        }

        $targetUsers = [];
        if ($ticket->targetUsers()->count() > 0) {
            foreach ($ticket->targetUsers as $targetUser) {
                # code...
                $tUres = '<a href=\'' . url('/users/') . '/' . $targetUser->profile->user_urlhash . '\'>' . $targetUser->name . '</a>';
                array_push($targetUsers, $tUres);
            }
        }

        $tG = $TicketForm->targetGroups;

        $tGArr = [];
        $tGArr[null] = 'Select item';
        foreach ($tG as $key => $value) {
            $tGArr[$value->id] = $value->name;
        }

        $tU = $TicketForm->targetUsers;

        $tUArr = [];
        foreach ($tU as $key => $value) {
            $tUArr[$value->id] = $value->name;
        }

        $AccessAction = $this->ticketAccessAction($ticket->code);
        $AccessModify = $this->ticketAccessModify($ticket->code);

//ticketAccessAction($ticker->id);
        //ticketAccessModify($ticket->id);

/*($ticket->sla_id != Null) ? $SLAReglament=$this->showSlaReglament($ticket->sla_id, $ticket->prio) : $SLAReglament=Null;*/

        ($ticket->sla_id != null) ? $SLALog = $this->showSlaInfo($ticket->id, $ticket->prio) : $SLALog = null;

($ticket->tags != null) ? $ticketTags=explode(',', $ticket->tags) : $ticketTags=[];

//dd($SLAReglament);
        $this->showSlaInfo($ticket->id);
        $data = [
            'user' => $user,
            'ticket' => $ticket,
            'targetUsers' => $targetUsers,
            'TicketForm' => $TicketForm,
            'tU' => $tUArr,
            'tG' => $tGArr,
            'comments' => $ticket->comments,
            'AccessAction' => $AccessAction,
            'AccessModify' => $AccessModify,
//'SLAReglament'=>$SLAReglament,
            'SLALog' => $SLALog,
            'ticketTags'=>$ticketTags
//'ci'=>$ci

        ];

        if ($user->roles->role == "client") {

            return view('client.ticket.ticket')->with($data);
        } else {

            if ($print == 'print') {
                return view('user.ticket.print')->with($data);
            }

            return view('user.ticket.ticket')->with($data);
        }

    }

    public function updateSlaReaction($id)
    {

        $ticket = Ticket::findOrFail($id);
        if ($ticket->sla_id != 0) {
            $slaLog = $ticket->slaLog;

            if ($ticket->slaLog->reaction_time == 0) {
                $dt = Carbon::now();
                $tc = Carbon::parse($ticket->created_at);
                $slaLog->reaction_time = $dt->diffInSeconds($tc);
                $slaLog->save();
            }
        }
    }

    public function updateSlaWork($id)
    {
//if ticket - unlock/waitsuccess/success
        //dd('ok');
        $dt = Carbon::now();
        $ticket = Ticket::findOrFail($id);
        if ($ticket->sla_id != 0) {
            $slaLog = $ticket->slaLog;

//если разблокировка?
            $lockNowTime = 0;
//if ($action == 'stop') {

            if (TicketLog::where('ticket_id', $id)
                ->whereIn('action', ['lock', 'unok'])
                //->OrWhere('action', 'lock')
                ->exists()) {
                $lockRec = TicketLog::where('ticket_id', $id)->whereIn('action', ['lock', 'unok'])->orderBy('id', 'desc')->first();

                // dd($lockRec);
                //$lockRec->created_at
                $lockNowTime = $dt->diffInSeconds($lockRec->created_at);

            }

//}

            $slaLog->work_time = $slaLog->work_time + $lockNowTime;
            $slaLog->save();
        }
    }

    public function updateSlaDeadline($id)
    {
        $dt = Carbon::now();
        $ticket = Ticket::findOrFail($id);
        if ($ticket->sla_id != 0) {
            $slaLog = $ticket->slaLog;

//
            /*if (TicketLog::where('ticket_id', $id)
            ->whereIn('action', ['success','waitsuccess'])
            //->OrWhere('action', 'lock')
            ->exists()) {
            $lockRec=TicketLog::where('ticket_id', $id)->whereIn('action', ['success','waitsuccess'])->orderBy('id', 'desc')->first();

            $SuccessNowTime=$dt->diffInSeconds($lockRec->created_at);
            }
            else {*/
            $SuccessNowTime = $dt->diffInSeconds($ticket->created_at);
//}

            $slaLog->deadline_time = $SuccessNowTime;
            $slaLog->save();

        }

    }

    public function showSlaInfo($id, $prio = 'normal')
    {

//$this->updateSlaReaction($id);

        $ticket = Ticket::findOrFail($id);
        if ($ticket->sla_id != null) {
            if (!TicketSlaLog::where('ticket_id', $id)->exists()) {
                TicketSlaLog::create(['ticket_id' => $id]);
            }

            $slaLogRes = $ticket->slaLog;

            $slaInfo = $ticket->sla;

            $dt = Carbon::now();
            $tc_created = Carbon::parse($ticket->created_at);

//FOR REACTION
            $slaLogRes->reaction_time_status = 'false';
            if ($slaLogRes->reaction_time == 0) {
                $slaLogRes->reaction_time = $dt->diffInSeconds($tc_created);
                $slaLogRes->reaction_time_status = 'true';
            }

//FOR WORK
            $slaLogRes->work_time_status = 'false';
            if ($ticket->status == 'lock') {
                if (TicketLog::where('ticket_id', $id)
                    ->whereIn('action', ['lock', 'unok'])
                    ->exists()) {
                    $lockRec = TicketLog::where('ticket_id', $id)->whereIn('action', ['lock', 'unok', 'noapprove'])->orderBy('id', 'desc')->first();
                    //$lockRec->created_at
                    $lockNowTime = $dt->diffInSeconds($lockRec->created_at);

                } else { $lockNowTime = 0;}

                $slaLogRes->work_time = $slaLogRes->work_time + $lockNowTime;
                $slaLogRes->work_time_status = 'true';
            }

//FOR DEADLINE
            $slaLogRes->deadline_time_status = 'false';
            if (($ticket->status != 'success') && ($ticket->status != 'arch')) {

                $SuccessNowTime = $dt->diffInSeconds($ticket->created_at);

                $slaLogRes->deadline_time = $SuccessNowTime;
                $slaLogRes->deadline_time_status = 'true';
            }
//$slaLogRes->deadline_time=

            $slaReglament = $this->showSlaReglament($ticket->sla_id, $prio);
            $slaLogRes->reglamentReaction = $slaReglament['reaction'];
            $slaLogRes->reglamentWork = $slaReglament['work'];
            $slaLogRes->reglamentDeadline = $slaReglament['deadline'];

            $slaLog_reaction_time = $ticket->slaLog->reaction_time;
            $slaLog_work_time = $ticket->slaLog->work_time;
            $slaLog_deadline_time = $ticket->slaLog->deadline_time;

//dd($slaReglament['reaction']->diffInSeconds(0));
            $slaRegl = $this->showSlaReglamentRaw($ticket->sla_id, $ticket->prio);
//dd($slaRegl);

            if ($slaRegl['reaction'] == 0) {$slaRegl['reaction'] = 1;}
            if ($slaRegl['work'] == 0) {$slaRegl['work'] = 1;}
            if ($slaRegl['deadline'] == 0) {$slaRegl['deadline'] = 1;}

            $slaLogRes->reglamentReactionPercent = ($slaLog_reaction_time * 100) / $slaRegl['reaction'];
            $slaLogRes->reglamentWorkPercent = ($slaLog_work_time * 100) / $slaRegl['work'];
            $slaLogRes->reglamentDeadlinePercent = ($slaLog_deadline_time * 100) / $slaRegl['deadline'];

            return $slaLogRes;
        } else {
            return null;
        }

    }

    public function showSlaReglamentRaw($id, $prio)
    {

        $sla = TicketSla::findOrFail($id);
        $ticketSla = [];

        if ($prio == "low") {
            $ticketSla['reaction'] = $sla->reaction_time_low_prio;
            $ticketSla['work'] = $sla->work_time_low_prio;
            $ticketSla['deadline'] = $sla->deadline_time_low_prio;
        } else if ($prio == "normal") {
            $ticketSla['reaction'] = $sla->reaction_time_def;
            $ticketSla['work'] = $sla->work_time_def;
            $ticketSla['deadline'] = $sla->deadline_time_def;
        } else if ($prio == "high") {
            $ticketSla['reaction'] = $sla->reaction_time_high_prio;
            $ticketSla['work'] = $sla->work_time_high_prio;
            $ticketSla['deadline'] = $sla->deadline_time_high_prio;
        }

        return $ticketSla;
    }

    public function showSlaReglament($id, $prio)
    {
        //dd(Config::get('app.locale'));

        CarbonInterval::setLocale('en');

        $sla = TicketSla::findOrFail($id);

        $ticketSla = [];

        if ($prio == "low") {
            $ticketSla['react_1'] = floor(($sla->reaction_time_low_prio % 2592000) / 86400);
            $ticketSla['react_2'] = floor(($sla->reaction_time_low_prio % 86400) / 3600);
            $ticketSla['react_3'] = floor(($sla->reaction_time_low_prio % 3600) / 60);
            $ticketSla['react_4'] = $sla->reaction_time_low_prio % 60;
            $ticketSla['work_1'] = floor(($sla->work_time_low_prio % 2592000) / 86400);
            $ticketSla['work_2'] = floor(($sla->work_time_low_prio % 86400) / 3600);
            $ticketSla['work_3'] = floor(($sla->work_time_low_prio % 3600) / 60);
            $ticketSla['work_4'] = $sla->work_time_low_prio % 60;
            $ticketSla['deadline_1'] = floor(($sla->deadline_time_low_prio % 2592000) / 86400);
            $ticketSla['deadline_2'] = floor(($sla->deadline_time_low_prio % 86400) / 3600);
            $ticketSla['deadline_3'] = floor(($sla->deadline_time_low_prio % 3600) / 60);
            $ticketSla['deadline_4'] = $sla->deadline_time_low_prio % 60;
        } else if ($prio == "normal") {
            $ticketSla['react_1'] = floor(($sla->reaction_time_def % 2592000) / 86400);
            $ticketSla['react_2'] = floor(($sla->reaction_time_def % 86400) / 3600);
            $ticketSla['react_3'] = floor(($sla->reaction_time_def % 3600) / 60);
            $ticketSla['react_4'] = $sla->reaction_time_def % 60;
            $ticketSla['work_1'] = floor(($sla->work_time_def % 2592000) / 86400);
            $ticketSla['work_2'] = floor(($sla->work_time_def % 86400) / 3600);
            $ticketSla['work_3'] = floor(($sla->work_time_def % 3600) / 60);
            $ticketSla['work_4'] = $sla->work_time_def % 60;
            $ticketSla['deadline_1'] = floor(($sla->deadline_time_def % 2592000) / 86400);
            $ticketSla['deadline_2'] = floor(($sla->deadline_time_def % 86400) / 3600);
            $ticketSla['deadline_3'] = floor(($sla->deadline_time_def % 3600) / 60);
            $ticketSla['deadline_4'] = $sla->deadline_time_def % 60;
        } else if ($prio == "high") {
            $ticketSla['react_1'] = floor(($sla->reaction_time_high_prio % 2592000) / 86400);
            $ticketSla['react_2'] = floor(($sla->reaction_time_high_prio % 86400) / 3600);
            $ticketSla['react_3'] = floor(($sla->reaction_time_high_prio % 3600) / 60);
            $ticketSla['react_4'] = $sla->reaction_time_high_prio % 60;
            $ticketSla['work_1'] = floor(($sla->work_time_high_prio % 2592000) / 86400);
            $ticketSla['work_2'] = floor(($sla->work_time_high_prio % 86400) / 3600);
            $ticketSla['work_3'] = floor(($sla->work_time_high_prio % 3600) / 60);
            $ticketSla['work_4'] = $sla->work_time_high_prio % 60;
            $ticketSla['deadline_1'] = floor(($sla->deadline_time_high_prio % 2592000) / 86400);
            $ticketSla['deadline_2'] = floor(($sla->deadline_time_high_prio % 86400) / 3600);
            $ticketSla['deadline_3'] = floor(($sla->deadline_time_high_prio % 3600) / 60);
            $ticketSla['deadline_4'] = $sla->deadline_time_high_prio % 60;
        }

        $res = [];
        $res['reaction'] = CarbonInterval::create(0, 0, 0, $ticketSla['react_1'], $ticketSla['react_2'], $ticketSla['react_3'], $ticketSla['react_4']);

        $res['work'] = CarbonInterval::create(0, 0, 0, $ticketSla['work_1'], $ticketSla['work_2'], $ticketSla['work_3'], $ticketSla['work_4']);
        $res['deadline'] = CarbonInterval::create(0, 0, 0, $ticketSla['deadline_1'], $ticketSla['deadline_2'], $ticketSla['deadline_3'], $ticketSla['deadline_4']);

        return $res;

    }

//updateRefer
    public function updateRefer(Request $request, $id)
    {
        $ticket = Ticket::whereCode($id)->firstOrFail();
        $user = Auth::user();
        $UserTicketConf = UserTicketConf::firstOrCreate(['user_id' => Auth::user()->id]);

        if ($UserTicketConf->conf_params == "group") {

            if (isset($UserTicketConf->groupTicket->ticket_form_id)) {
                $TicketForm = TicketForms::findOrFail($UserTicketConf->groupTicket->ticket_form_id);
            }
            else {
                $TicketForm = TicketForms::findOrFail(1);
            }


        } else if ($UserTicketConf->conf_params == "user") {
            $TicketForm = TicketForms::findOrFail($UserTicketConf->ticket_form_id);
        }

        $validatorRules = [];

        if ($TicketForm->target_field == 'user_groups') {

/*array_push($ticketResultArr, [
'target_group_id'=>$request->targetGroup
]);*/
            $validatorRules['targetGroup'] = 'required';
        } else if ($TicketForm->target_field == 'users') {
//insert into ticket_target_user USER=targetUsers LISTS
            $validatorRules['targetUsers'] = 'required';
        } else if ($TicketForm->target_field == 'group') {
            $validatorRules['targetGroup'] = 'required';
        }

        $validator = Validator::make($request->all(), $validatorRules);

        if ($validator->fails()) {

            $request->session()->flash('alert-warning', trans('handler.msgFixErrors'));
            return back()->withErrors($validator)->withInput();

        } else {

            ($TicketForm->target_field == 'users') ? $targetGroupID = null : $targetGroupID = $request->targetGroup;

            if ($TicketForm->target_field != 'group') {
                $ticket->targetUsers()->detach();
                if (!empty($request->targetUsers)) {

                    $ticket->targetUsers()->attach($request->targetUsers);
                }
            }

            (empty($request->msg)) ? $ReferMsg = $ticket->text : $ReferMsg = $ticket->text . '<br> ' . $user->name . ' <small>' . trans('handler.writeInForwardMsg') . '</small>: ' . $request->msg;

            $ticket->update([
                'target_group_id' => $targetGroupID,
                'text' => $ReferMsg]);

            $TicketLoggerMsg = '';

            Event::fire(new TicketLogger($ticket->id, $user->id, 'refer', $TicketLoggerMsg));

            $msgSuccess = "<i class=\"icon fa fa-check\"></i> " . trans('handler.ticketReferer');
            $request->session()->flash('alert-success', $msgSuccess);

            return redirect('/ticket/' . $id);
        }
    }

//storeComment
    public function storeComment(Request $request, $id)
    {

        $user = Auth::user();
        $ticket = Ticket::whereCode($id)->firstOrFail();

//$filesIDs=$request->fileIDS;

//dd($request->fileIDS);

        $totalCommentsPage = $request->totalComments;

        $comment = TicketComments::create([
            'text' => $request->msg,
            'author_id' => $user->id,
            'ticket_id' => $ticket->id,
            'visible_client' => $request->visible_client,
            'urlhash' => str_random(10),

        ]);

        Event::fire(new TicketLogger($ticket->id, $user->id, 'comment'));
        Event::fire(new TicketNotify($ticket->id, $user->id, 'comment'));

/*
для всех IDS
обновить аппрув
 */
        if ($request->fileIDS) {
            foreach ($request->fileIDS as $fileID) {
                Files::where('hash', $fileID)->update(['status' => 'success', 'target_id' => $comment->id]);
                # code...
            }
        }

//$request->fileIDS

/*$filesIDs=$request->fileIDS;
$DBfiles = Files::where('status', 'tmp')->where('target_type', 'commentFiles')->where('user_id', Auth::user()->id)->get();

foreach ($DBfiles as $file) {
$file->update(['status'=>'success', 'target_id'=>$ticket->id]);
}*/

        $totalCommentsDB = $ticket->comments->count();

        $LimitComments = $totalCommentsDB - $totalCommentsPage;
//return $LimitComments;

        $comments = $ticket->comments()->limit($LimitComments)->offset($totalCommentsPage)->orderBy('id', 'asc')->get();

        $data = [
            'comments' => $comments,
        ];

        $html = view('user.ticket.singleComment')->with($data);

//dd($html);

        $dataJSON = [[
            'total' => $ticket->comments->count(),
            'html' => $html->render(),
        ],
        ];

        return response()->json($dataJSON);

    }

/*    //showPrint
public function showPrint($id)
{
//

$data=[];

return view('user.ticket.print')->with($data);
}*/

    public function search($id)
    {
        //
        return $id;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

//editPlannerTicket
    public function editPlannerTicket($code)
    {

        $files = Files::where('status', 'tmp')->where('target_type', 'ticket')->where('user_id', Auth::user()->id)->get();

        $storage = Storage::disk('users');

        foreach ($files as $file) {
            # code...
            $fileName = $file->hash . '.' . $file->extension;
            $storage->delete(Auth::user()->id . '/' . $fileName);
            $file->delete();
        }

        $ticket = TicketPlannerList::whereCode($code)->firstOrFail();

        $UserTicketConf = UserTicketConf::firstOrCreate(['user_id' => Auth::user()->id]);

        if ($UserTicketConf->conf_params == "group") {

            if (isset($UserTicketConf->groupTicket->ticket_form_id)) {
                $TicketForm = TicketForms::findOrFail($UserTicketConf->groupTicket->ticket_form_id);
            }
            else {
                $TicketForm = TicketForms::findOrFail(1);
            }


        } else if ($UserTicketConf->conf_params == "user") {
            $TicketForm = TicketForms::findOrFail($UserTicketConf->ticket_form_id);
        }

        $targetGroups = $TicketForm->targetGroups;

        $targetGroupsArr = [];
        $targetGroupsArr[null] = 'Select item';
        foreach ($targetGroups as $key => $value) {
            $targetGroupsArr[$value->id] = $value->name;
        }

        $targetUsers = $TicketForm->targetUsers;

        $targetUsersArr = [];
        foreach ($targetUsers as $key => $value) {
            $targetUsersArr[$value->id] = $value->name;
        }

        $subj = $TicketForm->subjs;

        $subjArr = [];
        $subjArr[null] = 'Select item';
        foreach ($subj as $key => $value) {
            $subjArr[$value->name] = $value->name;
        }

        $slas = $TicketForm->slas;
        $slasArr = [];
        $slasArr[null] = 'Select item';
        foreach ($slas as $key => $value) {
            $slasArr[$value->id] = $value->name;
        }

        $user = Auth::user();
        $myGroups = [];
        foreach ($user->groups as $value) {
            # code...
            array_push($myGroups, $value->id);
        }

        $UserWatching = User::whereHas('groups', function ($q) use ($myGroups) {
            $q->whereIn('id', $myGroups);
        })->get();

        $watchingUsersArr = [];
        foreach ($UserWatching as $key => $value) {
            $watchingUsersArr[$value->id] = $value->name;
        }

        $watchingUsersSel = [];
        foreach ($ticket->watchingUsers as $value) {
            array_push($watchingUsersSel, $value->id);
            //$watchingUsersSel[$value->id] = $value->name;
        }

        $clients = [];
        $clientSel = [];

        foreach ($ticket->clients as $client) {
            # code...
            $clients[$client->id] = $client->name;
            array_push($clientSel, $client->id);
        }

        $targetUserSel = [];
        foreach ($ticket->targetUsers as $value) {
            # code...
            array_push($targetUserSel, $value->id);

        }

        $tags = [];
        $tagsSel = [];
        $tagsDB = explode(',', $ticket->tags);
        foreach ($tagsDB as $value) {
            # code...
            $tags[$value] = $value;
            array_push($tagsSel, $value);
        }

//$ticket->fields()->wherePivot('ticket_field_id', $field->id)->firstOrFail()->pivot->field_data

//вывести все данные формы и добавить возможные
        //СПИСОК ПОЛЕЙ В ЗАЯВКЕ

//СПИСОК ДОСТУПНЫХ ДОП ПОЛЕЙ

        $fieldsFromTicket = [];
        $fieldsFromForm = [];

        foreach ($TicketForm->fields as $field) {
            array_push($fieldsFromForm, $field->id);
        }

        foreach ($ticket->fields as $field) {
            array_push($fieldsFromTicket, $field->pivot->ticket_field_id);
            $ticketAdv = TicketAdv::whereId($field->pivot->ticket_field_id)->firstOrFail();

            $field->field_name = $ticketAdv->field_name;
            $field->field_placeholder = $ticketAdv->field_placeholder;
            $field->required = $ticketAdv->required;
            $field->f_type = $ticketAdv->f_type;
            $field->field_value = $ticketAdv->field_value;
            $field->value = $field->pivot->field_data;
        }

        $ResFieldsArr = array_diff($fieldsFromForm, $fieldsFromTicket);
//dd($ResFieldsArr);

        foreach ($ResFieldsArr as $value) {
            # code...
            $tp = TicketAdv::whereId($value)->firstOrFail();
            $ticket->fields->push($tp);
        }

        ($ticket->deadline_time) ? $deadlineStatus = true : $deadlineStatus = false;

//dd(date('i', strtotime($ticket->deadline_time)));

        if ($deadlineStatus) {

//$ticket->deadline_time

            $ticket->deadlineTime = date('H', strtotime($ticket->deadline_time)) . ':' . date('i', strtotime($ticket->deadline_time));
            $ticket->deadlineDay = date('d', strtotime($ticket->deadline_time));
            $ticket->deadlineMonth = date('m', strtotime($ticket->deadline_time));
            $ticket->deadlineYear = date('Y', strtotime($ticket->deadline_time));
        } else {
            $ticket->deadlineTime = null;
            $ticket->deadlineDay = null;
            $ticket->deadlineMonth = null;
            $ticket->deadlineYear = null;
        }

        ($ticket->individual_ok == 'true') ? $individual_ok = 'true' : $individual_ok = 'false';
//check_after
        ($ticket->inspect_after_ok == 'true') ? $check_after = true : $check_after = false;

        $TicketPlannerStart = Carbon::parse($ticket->planners->startWork);
        $TicketPlannerStop = Carbon::parse($ticket->planners->endWork);

//dd($TicketPlannerStart->day);

        $data = [
            //'TicketPlannerStatus'=>$TicketPlannerStatus,
            'TicketPlannerStart' => $TicketPlannerStart,
            'TicketPlannerStop' => $TicketPlannerStop,

            'ticket' => $ticket,
            'clients' => $clients,
            'clientSel' => $clientSel,

            'targetGroup' => $targetGroupsArr,

            'targetUser' => $targetUsersArr,
            'targetUserSel' => $targetUserSel,

            'tags' => $tags,
            'tagsSel' => $tagsSel,
            'watchingUsersSel' => $watchingUsersSel,
            //'ticketFieldsData'=>$ticketFieldsData,
            'individual_okStatus' => $individual_ok,
            'check_afterStatus' => $check_after,

            'TicketForm' => $TicketForm,
            'subj' => $subjArr,
            'slas' => $slasArr,
            'watchingUsers' => $watchingUsersArr,
            'deadlineStatus' => $deadlineStatus,

        ];

        return view('user.ticket.ticketEditPlanner')->with($data);

    }

    public function updatePlannerTicket(Request $request, $id)
    {
        //

//dd($request->all());

        $user = Auth::user();

        $UserTicketConf = UserTicketConf::firstOrCreate(['user_id' => Auth::user()->id]);

        if ($UserTicketConf->conf_params == "group") {

            if (isset($UserTicketConf->groupTicket->ticket_form_id)) {
                $TicketForm = TicketForms::findOrFail($UserTicketConf->groupTicket->ticket_form_id);
            }
            else {
                $TicketForm = TicketForms::findOrFail(1);
            }


        } else if ($UserTicketConf->conf_params == "user") {
            $TicketForm = TicketForms::findOrFail($UserTicketConf->ticket_form_id);
        }

        $validatorRules = [];
        $ticketResultArr = [];

        if ($TicketForm->client_field == 'self') {
//insert into ticket_clients USER-I AM
        } else if ($TicketForm->client_field == 'group') {
//insert into ticket_clients USER-LISTS
            $validatorRules['client'] = 'required';

//For ALL new CLIENTS REQUIRED EMAIL!
            if ($request->client) {
                foreach ($request->client as $cl) {
                    if (strpos($cl, '[new]') !== false) {
                        $normalName = explode('[new]', $cl);
                        $validatorRules['clientNEW[' . $normalName[0] . '][email]'] = 'required|email|unique:users,email';
                        //clientNEW[trololo][email]
                    }
                }

            }
//dd($validatorRules);

        }

        if ($TicketForm->target_field == 'user_groups') {

/*array_push($ticketResultArr, [
'target_group_id'=>$request->targetGroup
]);*/
            if (empty($request->targetUsers) && (empty($request->targetGroup))) {
                $validatorRules['targetGroup'] = 'required';
                $validatorRules['targetUsers'] = 'required';
            }

        } else if ($TicketForm->target_field == 'users') {
//insert into ticket_target_user USER=targetUsers LISTS
            $validatorRules['targetUsers'] = 'required';
        } else if ($TicketForm->target_field == 'group') {
            $validatorRules['targetGroup'] = 'required';
        }

        $validatorRules['subj'] = 'required';
        $validatorRules['msg'] = 'required|min:10';

        foreach ($TicketForm->fields as $field) {

            if ($field->required == "true") {
                $validatorRules['field' . $field->id] = 'required';
            }

        }

        $reqArr = [];
        if ($request->clientNEW) {
            foreach ($request->clientNEW as $key => $cl) {

                $reqArr['clientNEW[' . $key . '][email]'] = $cl['email'];

            }
        }

        $resReq = array_merge($request->all(), $reqArr);
//dd($resReq);

        $validator = Validator::make($resReq, $validatorRules);

        if ($validator->fails()) {

            $request->session()->flash('alert-warning', trans('handler.msgFixErrors'));
            return back()->withErrors($validator)->withInput();

        } else {

            $code = str_random(4);
            $urlhash = str_random(10);

            (!empty($request->tags)) ? $tags = implode(',', $request->tags) : $tags = null;

            ($request->sla) ? $sla = $request->sla : $sla = null;

//($TicketForm->target_field == 'users') ? $targetGroupID=Null : $targetGroupID=$request->targetGroup;

            $targetGroupID = null;
            if (!empty($request->targetGroup)) {
                $targetGroupID = $request->targetGroup;
            }

            if ($request->deadlineStatus == 'active') {
                $deadlineTime = $request->deadlineYear . '-' . $request->deadlineMonth . '-' . $deadlineTime = $request->deadlineDay . ' ' . $request->deadlineTime . ':00';
            } else {
                $deadlineTime = null;
            }

            ($request->check_after == 'true') ? $check_after = 'true' : $check_after = 'false';

            ($request->individual_ok == 'true') ? $individual_ok = 'true' : $individual_ok = 'false';
            $ticket = TicketPlannerList::whereCode($id)->firstOrFail();

            (empty($request->prio)) ? $prio = 'normal' : $prio = $request->prio;

            $ticket->update([
                'author_id' => $user->id,
//'code'=>$code,
                //'client_id',
                'prio' => $prio,
                'text' => $request->msg,
                'subject' => $request->subj,
                'tags' => $tags,
//'urlhash'=>$urlhash,
                //'number',
                'sla_id' => $sla,
                'target_group_id' => $targetGroupID,
                'deadline_time' => $deadlineTime,
                'inspect_after_ok' => $check_after,
                'individual_ok' => $individual_ok,
            ]);

            $plannerTime = explode(':', $request->plannerTime);
            $plannerStartWork = $request->plannerStartYear . '-' . $request->plannerStartMonth . '-' . $request->plannerStartDay . ' 00:00:00';
            $plannerEndWork = $request->plannerEndYear . '-' . $request->plannerEndMonth . '-' . $request->plannerEndDay . ' 23:59:00';

            $ticket->planners->update([

                'name' => $request->plannerName,
                'author_id' => $user->id,
                'period' => $request->plannerPeriod,
                'dayHour' => $plannerTime[0],
                'dayMinute' => $plannerTime[1],
                'weekDay' => $request->plannerEveryDay,
                'monthDay' => $request->plannerEveryDay,
                'startWork' => $plannerStartWork,
                'endWork' => $plannerEndWork,

            ]);

//Event::fire(new TicketLogger($ticket->id, $user->id, 'edit'));

///////////////////////////////////NEW CLIENT////////////////////////////////////
            $newClintsArr = [];
            $newClientIds = [];
            if ($request->client) {
                foreach ($request->client as $cl) {
                    if (strpos($cl, '[new]') !== false) {
                        $normalName = explode('[new]', $cl);
                        //echo $normalName[0].' <br>';
                        //clientNEW['.$normalName[0].'][email]
                        array_push($newClintsArr, [
                            'name' => $normalName[0],
                            'email' => $request->clientNEW[$normalName[0]]['email'],
                            'posada' => $request->clientNEW[$normalName[0]]['posada'],
                            'address' => $request->clientNEW[$normalName[0]]['address'],
                            'pass' => str_random('8'),
                        ]);
                    }
                }

                foreach ($newClintsArr as $newClientArr) {
                    # code...
                    //dd($newClientArr[]);

                    $NewUser = Zen::storeNewUser([
                        'name' => $newClientArr['name'],
                        'email' => $newClientArr['email'],
                        'password' => $newClientArr['pass'],
                    ]);

                    $NewUser->profile->update([

                        'full_name' => $newClientArr['name'],
                        'email' => $newClientArr['email'],

                    ]);
                    if (!empty($newClientArr['posada'])) {

                        $NewUser->profile->update([
                            'position' => $newClientArr['posada'],
                        ]);
                    }
                    if (!empty($newClientArr['address'])) {
                        $NewUser->profile->update([
                            'address' => $newClientArr['address'],
                        ]);
                    }

                    $NewUser->profile->update([
                        'user_urlhash' => str_random(25),
                    ]);

                    //$newClientArr['user_id']=$NewUser->id;
                    array_push($newClientIds, $NewUser->id);

                }
//dd($newClientIds);

            }
///////////////////////////////////NEW CLIENT////////////////////////////////////

            ($TicketForm->client_field == 'self') ? $ClientField = [$user->id] : $ClientField = $request->client;
            $ticket->clients()->detach();
            $ClientField = array_merge($ClientField, $newClientIds);
            $ticket->clients()->attach($ClientField);

            if ($TicketForm->target_field != 'group') {
                if (!empty($request->targetUsers)) {
                    $ticket->targetUsers()->detach();
                    $ticket->targetUsers()->attach($request->targetUsers);
                }
            }

/*if ($TicketForm->watching_field == "true") {
if (!empty($request->watchingUsers)) {

$ticket->watchingUsers()->detach();

$ticket->watchingUsers()->attach($request->targetUsers);
}
} */
            $notifyArr = [];
            if ($request->notifyClient == 'true') {

//$newClientIds
                //$request->client
                $notifyArr = array_unique(array_merge($newClientIds, $request->client));

//???????

//Event::fire(new UserNotify($AuthorUser->id, $newPass, 'create'));

            }

            $targU = [];
            if (!empty($request->targetUsers)) {
                $targU = $request->targetUsers;
            }

            $watchU = [];
            if (!empty($request->watchingUsers)) {
                $watchU = $request->watchingUsers;
            }

            $ticketwatchU = [];
            if (!empty($ticket->watchingUsers)) {
                $ticketwatchU = $ticket->watchingUsers;
            }

            $watchResArr = array_unique(array_merge($watchU, $targU, [$user->id], $ticketwatchU, $notifyArr));
            $ticket->watchingUsers()->detach();
            $ticket->watchingUsers()->attach($watchResArr);

/*    foreach ($newClientIds as $newClientId) {
# code...
Event::fire(new UserNotify($newClientId, Null, 'create'));
}*/

            if ($TicketForm->fields->count() > 0) {
                $ticket->fields()->detach();
                foreach ($TicketForm->fields as $field) {
                    # code...
                    $fh = 'field' . $field->id;

                    if ($field->f_type == 'multiselect') {
                        if ($request->$fh) {$ticket->fields()->attach($field->id, ['field_data' => implode(',', $request->$fh)]);}
                    } else {
                        if ($request->$fh) {$ticket->fields()->attach($field->id, ['field_data' => $request->$fh]);}

                    }

                }
            }

//ADD FILES_APPROVE

            $DBfiles = Files::where('status', 'tmp')->where('target_type', 'ticket')->where('user_id', Auth::user()->id)->get();

            foreach ($DBfiles as $file) {
                $file->update(['status' => 'success', 'target_id' => $ticket->id]);
            }

//Event::fire(new TicketNotify($ticket->id, $user->id, 'edit'));

            $msgSuccess = "<h4>  <i class=\"icon fa fa-check\"></i> " . trans('handler.ticketEditedOk') . "</h4> ";
            $request->session()->flash('alert-success', $msgSuccess);
            return redirect('/ticket/planner' . '/' . $id);

        }
    }

    public function edit($id)
    {
        //

        $files = Files::where('status', 'tmp')->where('target_type', 'ticket')->where('user_id', Auth::user()->id)->get();

        $storage = Storage::disk('users');

        foreach ($files as $file) {
            # code...
            $fileName = $file->hash . '.' . $file->extension;
            $storage->delete(Auth::user()->id . '/' . $fileName);
            $file->delete();
        }

        $ticket = Ticket::whereCode($id)->firstOrFail();

        $UserTicketConf = UserTicketConf::firstOrCreate(['user_id' => Auth::user()->id]);

        if ($UserTicketConf->conf_params == "group") {

            if (isset($UserTicketConf->groupTicket->ticket_form_id)) {
                $TicketForm = TicketForms::findOrFail($UserTicketConf->groupTicket->ticket_form_id);
            }
            else {
                $TicketForm = TicketForms::findOrFail(1);
            }


        } else if ($UserTicketConf->conf_params == "user") {
            $TicketForm = TicketForms::findOrFail($UserTicketConf->ticket_form_id);
        }

        $targetGroups = $TicketForm->targetGroups;

        $targetGroupsArr = [];
        $targetGroupsArr[null] = 'Select item';
        foreach ($targetGroups as $key => $value) {
            $targetGroupsArr[$value->id] = $value->name;
        }

        $targetUsers = $TicketForm->targetUsers;

        $targetUsersArr = [];
        foreach ($targetUsers as $key => $value) {
            $targetUsersArr[$value->id] = $value->name;
        }

        $subj = $TicketForm->subjs;

        $subjArr = [];
        $subjArr[null] = 'Select item';
        foreach ($subj as $key => $value) {
            $subjArr[$value->name] = $value->name;
        }

        $slas = $TicketForm->slas;
        $slasArr = [];
        $slasArr[null] = 'Select item';
        foreach ($slas as $key => $value) {
            $slasArr[$value->id] = $value->name;
        }

        $user = Auth::user();
        $myGroups = [];
        foreach ($user->groups as $value) {
            # code...
            array_push($myGroups, $value->id);
        }

        $UserWatching = User::whereHas('groups', function ($q) use ($myGroups) {
            $q->whereIn('id', $myGroups);
        })->get();

        $watchingUsersArr = [];
        foreach ($UserWatching as $key => $value) {
            $watchingUsersArr[$value->id] = $value->name;
        }

        $watchingUsersSel = [];
        foreach ($ticket->watchingUsers as $value) {
            array_push($watchingUsersSel, $value->id);
            //$watchingUsersSel[$value->id] = $value->name;
        }

        $clients = [];
        $clientSel = [];

        foreach ($ticket->clients as $client) {
            # code...
            $clients[$client->id] = $client->name;
            array_push($clientSel, $client->id);
        }

        $targetUserSel = [];
        foreach ($ticket->targetUsers as $value) {
            # code...
            array_push($targetUserSel, $value->id);

        }

        $tags = [];
        $tagsSel = [];
        $tagsDB = explode(',', $ticket->tags);
        foreach ($tagsDB as $value) {
            # code...
            $tags[$value] = $value;
            array_push($tagsSel, $value);
        }

//$ticket->fields()->wherePivot('ticket_field_id', $field->id)->firstOrFail()->pivot->field_data

//вывести все данные формы и добавить возможные
        //СПИСОК ПОЛЕЙ В ЗАЯВКЕ

//СПИСОК ДОСТУПНЫХ ДОП ПОЛЕЙ

        $fieldsFromTicket = [];
        $fieldsFromForm = [];

        foreach ($TicketForm->fields as $field) {
            array_push($fieldsFromForm, $field->id);
        }

        foreach ($ticket->fields as $field) {
            array_push($fieldsFromTicket, $field->pivot->ticket_field_id);
            $ticketAdv = TicketAdv::whereId($field->pivot->ticket_field_id)->firstOrFail();

            $field->field_name = $ticketAdv->field_name;
            $field->field_placeholder = $ticketAdv->field_placeholder;
            $field->required = $ticketAdv->required;
            $field->f_type = $ticketAdv->f_type;
            $field->field_value = $ticketAdv->field_value;
            $field->value = $field->pivot->field_data;
        }

        $ResFieldsArr = array_diff($fieldsFromForm, $fieldsFromTicket);
//dd($ResFieldsArr);

        foreach ($ResFieldsArr as $value) {
            # code...
            $tp = TicketAdv::whereId($value)->firstOrFail();
            $ticket->fields->push($tp);
        }

        ($ticket->deadline_time) ? $deadlineStatus = true : $deadlineStatus = false;

//dd(date('i', strtotime($ticket->deadline_time)));

        if ($deadlineStatus) {

//$ticket->deadline_time

            $ticket->deadlineTime = date('H', strtotime($ticket->deadline_time)) . ':' . date('i', strtotime($ticket->deadline_time));
            $ticket->deadlineDay = date('d', strtotime($ticket->deadline_time));
            $ticket->deadlineMonth = date('m', strtotime($ticket->deadline_time));
            $ticket->deadlineYear = date('Y', strtotime($ticket->deadline_time));
        } else {
            $ticket->deadlineTime = null;
            $ticket->deadlineDay = null;
            $ticket->deadlineMonth = null;
            $ticket->deadlineYear = null;
        }

        ($ticket->individual_ok == 'true') ? $individual_ok = 'true' : $individual_ok = 'false';
//check_after
        ($ticket->inspect_after_ok == 'true') ? $check_after = true : $check_after = false;

        $data = [
            'ticket' => $ticket,
            'clients' => $clients,
            'clientSel' => $clientSel,

            'targetGroup' => $targetGroupsArr,

            'targetUser' => $targetUsersArr,
            'targetUserSel' => $targetUserSel,

            'tags' => $tags,
            'tagsSel' => $tagsSel,
            'watchingUsersSel' => $watchingUsersSel,
            //'ticketFieldsData'=>$ticketFieldsData,
            'individual_okStatus' => $individual_ok,
            'check_afterStatus' => $check_after,

            'TicketForm' => $TicketForm,
            'subj' => $subjArr,
            'slas' => $slasArr,
            'watchingUsers' => $watchingUsersArr,
            'deadlineStatus' => $deadlineStatus,

        ];

        return view('user.ticket.ticketEdit')->with($data);
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

        $user = Auth::user();

        $UserTicketConf = UserTicketConf::firstOrCreate(['user_id' => Auth::user()->id]);

        if ($UserTicketConf->conf_params == "group") {

            if (isset($UserTicketConf->groupTicket->ticket_form_id)) {
                $TicketForm = TicketForms::findOrFail($UserTicketConf->groupTicket->ticket_form_id);
            }
            else {
                $TicketForm = TicketForms::findOrFail(1);
            }


        } else if ($UserTicketConf->conf_params == "user") {
            $TicketForm = TicketForms::findOrFail($UserTicketConf->ticket_form_id);
        }

        $validatorRules = [];
        $ticketResultArr = [];

        if ($TicketForm->client_field == 'self') {
//insert into ticket_clients USER-I AM
        } else if ($TicketForm->client_field == 'group') {
//insert into ticket_clients USER-LISTS
            $validatorRules['client'] = 'required';

//For ALL new CLIENTS REQUIRED EMAIL!
            if ($request->client) {
                foreach ($request->client as $cl) {
                    if (strpos($cl, '[new]') !== false) {
                        $normalName = explode('[new]', $cl);
                        $validatorRules['clientNEW[' . $normalName[0] . '][email]'] = 'required|email|unique:users,email';
                        //clientNEW[trololo][email]
                    }
                }

            }
//dd($validatorRules);

        }

        if ($TicketForm->target_field == 'user_groups') {

/*array_push($ticketResultArr, [
'target_group_id'=>$request->targetGroup
]);*/
            if (empty($request->targetUsers) && (empty($request->targetGroup))) {
                $validatorRules['targetGroup'] = 'required';
                $validatorRules['targetUsers'] = 'required';
            }

        } else if ($TicketForm->target_field == 'users') {
//insert into ticket_target_user USER=targetUsers LISTS
            $validatorRules['targetUsers'] = 'required';
        } else if ($TicketForm->target_field == 'group') {
            $validatorRules['targetGroup'] = 'required';
        }

        $validatorRules['subj'] = 'required';
        $validatorRules['msg'] = 'required|min:10';

        foreach ($TicketForm->fields as $field) {

            if ($field->required == "true") {
                $validatorRules['field' . $field->id] = 'required';
            }

        }

        $reqArr = [];
        if ($request->clientNEW) {
            foreach ($request->clientNEW as $key => $cl) {

                $reqArr['clientNEW[' . $key . '][email]'] = $cl['email'];

            }
        }

        $resReq = array_merge($request->all(), $reqArr);
//dd($resReq);

        $validator = Validator::make($resReq, $validatorRules);

        if ($validator->fails()) {

            $request->session()->flash('alert-warning', trans('handler.msgFixErrors'));
            return back()->withErrors($validator)->withInput();

        } else {

            $code = str_random(4);
            $urlhash = str_random(10);

            (!empty($request->tags)) ? $tags = implode(',', $request->tags) : $tags = null;

            ($request->sla) ? $sla = $request->sla : $sla = null;

//($TicketForm->target_field == 'users') ? $targetGroupID=Null : $targetGroupID=$request->targetGroup;

            $targetGroupID = null;
            if (!empty($request->targetGroup)) {
                $targetGroupID = $request->targetGroup;
            }

            if ($request->deadlineStatus == 'active') {
                $deadlineTime = $request->deadlineYear . '-' . $request->deadlineMonth . '-' . $deadlineTime = $request->deadlineDay . ' ' . $request->deadlineTime . ':00';
            } else {
                $deadlineTime = null;
            }

            ($request->check_after == 'true') ? $check_after = 'true' : $check_after = 'false';

            ($request->individual_ok == 'true') ? $individual_ok = 'true' : $individual_ok = 'false';
            $ticket = Ticket::whereCode($id)->firstOrFail();
(empty($request->prio)) ? $prio = 'normal' : $prio = $request->prio;
            $ticket->update([
                'author_id' => $user->id,
//'code'=>$code,
                //'client_id',
                'prio' => $prio,
                'text' => $request->msg,
                'subject' => $request->subj,
                'tags' => $tags,
//'urlhash'=>$urlhash,
                //'number',
                'sla_id' => $sla,
                'target_group_id' => $targetGroupID,
                'deadline_time' => $deadlineTime,
                'inspect_after_ok' => $check_after,
                'individual_ok' => $individual_ok,
            ]);

            Event::fire(new TicketLogger($ticket->id, $user->id, 'edit'));

///////////////////////////////////NEW CLIENT////////////////////////////////////
            $newClintsArr = [];
            $newClientIds = [];
            if ($request->client) {
                foreach ($request->client as $cl) {
                    if (strpos($cl, '[new]') !== false) {
                        $normalName = explode('[new]', $cl);
                        //echo $normalName[0].' <br>';
                        //clientNEW['.$normalName[0].'][email]
                        array_push($newClintsArr, [
                            'name' => $normalName[0],
                            'email' => $request->clientNEW[$normalName[0]]['email'],
                            'posada' => $request->clientNEW[$normalName[0]]['posada'],
                            'address' => $request->clientNEW[$normalName[0]]['address'],
                            'pass' => str_random('8'),
                        ]);
                    }
                }

                foreach ($newClintsArr as $newClientArr) {
                    # code...
                    //dd($newClientArr[]);

                    $NewUser = Zen::storeNewUser([
                        'name' => $newClientArr['name'],
                        'email' => $newClientArr['email'],
                        'password' => $newClientArr['pass'],
                    ]);

                    $NewUser->profile->update([

                        'full_name' => $newClientArr['name'],
                        'email' => $newClientArr['email'],

                    ]);
                    if (!empty($newClientArr['posada'])) {

                        $NewUser->profile->update([
                            'position' => $newClientArr['posada'],
                        ]);
                    }
                    if (!empty($newClientArr['address'])) {
                        $NewUser->profile->update([
                            'address' => $newClientArr['address'],
                        ]);
                    }

                    $NewUser->profile->update([
                        'user_urlhash' => str_random(25),
                    ]);

/*    $NewUser = User::create([
'name' => $newClientArr['name'],
'email' => $newClientArr['email'],
'password' => bcrypt($newClientArr['pass']),
]);
$NewUserProfile = new UserProfile;
$NewUserProfile->full_name = $newClientArr['name'];
$NewUserProfile->email = $newClientArr['email'];
if (!empty($newClientArr['posada'])) {
$NewUserProfile->position = $newClientArr['posada'];
}
if (!empty($newClientArr['address'])) {
$NewUserProfile->address = $newClientArr['address'];
}
$NewUserProfile->user_urlhash=str_random(25);
$NewUser->profile()->save($NewUserProfile);

$userTicketConf= UserTicketConf::create([
'user_id'=>$NewUser->id,
'ticket_form_id'=>'1',
'conf_params'=>'user'
]);

UserRole::create([
'user_id'=>$NewUser->id,
'role'=>'client'
]);*/

                    //$newClientArr['user_id']=$NewUser->id;
                    array_push($newClientIds, $NewUser->id);

                }
//dd($newClientIds);

            }
///////////////////////////////////NEW CLIENT////////////////////////////////////

            ($TicketForm->client_field == 'self') ? $ClientField = [$user->id] : $ClientField = $request->client;
            $ticket->clients()->detach();
            $ClientField = array_merge($ClientField, $newClientIds);
            $ticket->clients()->attach($ClientField);

            if ($TicketForm->target_field != 'group') {
                if (!empty($request->targetUsers)) {
                    $ticket->targetUsers()->detach();
                    $ticket->targetUsers()->attach($request->targetUsers);
                }
            }

/*if ($TicketForm->watching_field == "true") {
if (!empty($request->watchingUsers)) {

$ticket->watchingUsers()->detach();

$ticket->watchingUsers()->attach($request->targetUsers);
}
} */
            $notifyArr = [];
            if ($request->notifyClient == 'true') {

//$newClientIds
                //$request->client
                $notifyArr = array_unique(array_merge($newClientIds, $request->client));

//???????

                foreach ($newClientIds as $newClientId) {
                    # code...
                    Event::fire(new UserNotify($newClientId, null, 'create'));
                }

//Event::fire(new UserNotify($AuthorUser->id, $newPass, 'create'));

            }
//dd($request);
            $targU = [];
            if (!empty($request->targetUsers)) {
                $targU = $request->targetUsers;
            }

            $watchU = [];
            if (!empty($request->watchingUsers)) {
                $watchU = $request->watchingUsers;
            }

            $ticketwatchU = [];
            if (!empty($ticket->watchingUsers)) {

                //$ticketwatchU = $ticket->watchingUsers;
                foreach ($ticket->watchingUsers as $watchingUser) {
                   array_push($ticketwatchU, $watchingUser->id);
                }
            }
//dd($ticketwatchU);
            $watchResArr = array_unique(array_merge($watchU, $targU, [$user->id], $ticketwatchU, $notifyArr));
            $ticket->watchingUsers()->detach();
            $ticket->watchingUsers()->attach($watchResArr);

/*    foreach ($newClientIds as $newClientId) {
# code...
Event::fire(new UserNotify($newClientId, Null, 'create'));
}*/

            if ($TicketForm->fields->count() > 0) {
                $ticket->fields()->detach();
                foreach ($TicketForm->fields as $field) {
                    # code...
                    $fh = 'field' . $field->id;

                    if ($field->f_type == 'multiselect') {
                        if ($request->$fh) {$ticket->fields()->attach($field->id, ['field_data' => implode(',', $request->$fh)]);}
                    } else {
                        if ($request->$fh) {$ticket->fields()->attach($field->id, ['field_data' => $request->$fh]);}

                    }

                }
            }

//ADD FILES_APPROVE

            $DBfiles = Files::where('status', 'tmp')->where('target_type', 'ticket')->where('user_id', Auth::user()->id)->get();

            foreach ($DBfiles as $file) {
                $file->update(['status' => 'success', 'target_id' => $ticket->id]);
            }

            Event::fire(new TicketNotify($ticket->id, $user->id, 'edit'));

            $msgSuccess = "<h4>  <i class=\"icon fa fa-check\"></i> " . trans('handler.ticketEditedOk') . "</h4> ";
            $request->session()->flash('alert-success', $msgSuccess);
            return redirect('/ticket' . '/' . $id);

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
        //soft delete!

        $user = Auth::user();
        $ticket = Ticket::whereCode($id)->firstOrFail();

        $ticket->delete();

        Event::fire(new TicketLogger($ticket->id, $user->id, 'delete'));
        Event::fire(new TicketNotify($ticket->id, $user->id, 'delete'));
        return 'ok';
    }

    public function destroyRestore($id, Request $request)
    {
        $user = Auth::user();
        $ticket = Ticket::onlyTrashed()->whereCode($id)->firstOrFail();
        $ticket->restore();
        Event::fire(new TicketLogger($ticket->id, $user->id, 'restore'));
        Event::fire(new TicketNotify($ticket->id, $user->id, 'restore'));

        $msgSuccess = "<h4>  <i class=\"icon fa fa-check\"></i> " . trans('handler.ticketRestored') . "</h4> ";
        $request->session()->flash('alert-success', $msgSuccess);
        return redirect('/ticket' . '/' . $id);

    }

//destroyPlannerTicket
    public function destroyPlannerTicket($id, Request $request)
    {
        $user = Auth::user();
        $ticket = TicketPlannerList::whereCode($id)->firstOrFail();
        $storage = Storage::disk('users');

        $ticketComments = $ticket->comments;
        foreach ($ticket->comments as $comment) {
            foreach ($comment->files as $file) {
                $fileName = $file->hash . '.' . $file->extension;
                $storage->delete($file->user_id . '/' . $fileName);
                $file->delete();
                # code...
            }
        }
        $ticket->comments()->delete();

        $ticket->planners()->delete();

        $ticket->logs()->delete();

        foreach ($ticket->files as $file) {
            # code...
            $fileName = $file->hash . '.' . $file->extension;
            $storage->delete($file->user_id . '/' . $fileName);
            $file->delete();
        }
        $ticket->clients()->detach();
        $ticket->targetUsers()->detach();
        $ticket->watchingUsers()->detach();
        $ticket->fields()->detach();
        $ticket->forceDelete();
        $msgSuccess = "<h4>  <i class=\"icon fa fa-check\"></i> " . trans('handler.ticketDeleted') . "</h4> ";
        $request->session()->flash('alert-success', $msgSuccess);
        return redirect('/ticket/planner');
    }

    public function destroyApprove($id, Request $request)
    {
        //dd('ok');
        $user = Auth::user();
        $ticket = Ticket::onlyTrashed()->whereCode($id)->firstOrFail();
        $storage = Storage::disk('users');

        $ticketComments = $ticket->comments;
        foreach ($ticket->comments as $comment) {
            foreach ($comment->files as $file) {
                $fileName = $file->hash . '.' . $file->extension;
                $storage->delete($file->user_id . '/' . $fileName);
                $file->delete();
                # code...
            }
        }
        $ticket->comments()->delete();

        $ticket->logs()->delete();

        foreach ($ticket->files as $file) {
            # code...
            $fileName = $file->hash . '.' . $file->extension;
            $storage->delete($file->user_id . '/' . $fileName);
            $file->delete();
        }
        $ticket->clients()->detach();
        $ticket->targetUsers()->detach();
        $ticket->watchingUsers()->detach();
        $ticket->fields()->detach();
        $ticket->forceDelete();
        $msgSuccess = "<h4>  <i class=\"icon fa fa-check\"></i> " . trans('handler.ticketDeleted') . "</h4> ";
        $request->session()->flash('alert-success', $msgSuccess);
        return redirect('/ticket/deleted');
    }

}
