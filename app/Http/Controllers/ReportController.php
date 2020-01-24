<?php

namespace zenlix\Http\Controllers;

use Auth;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use LocalizedCarbon;
use zenlix\Classes\Zen;
use zenlix\Groups;
use zenlix\Http\Controllers\Controller;
use zenlix\Ticket;
use zenlix\TicketSla;
use zenlix\User;

class ReportController extends Controller
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

    public function showUser()
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
        //->where('id', '!=', $iam->id)
            ->orderBy('id', 'DESC')->get();
        $usersArr = [];
        foreach ($users as $user) {
            $usersArr[$user->id] = $user->name;
            # code...
        }

        $data = ['users' => $usersArr];

        return view('user.report.user')->with($data);
    }

    public static function prepareTicket($createdTickets)
    {

        foreach ($createdTickets as $createdTicket) {
            # code...

            $Clients = $createdTicket->clients;
            $C = [];
            foreach ($Clients as $Client) {
                $cRes = '<a href=\'' . url('/user/') . '/' . $Client->profile->user_urlhash . '\'>' . Zen::showShortName($Client->name) . '</a>';
                array_push($C, $cRes);
            }
            $createdTicket->clientGen = implode(', ', $C);
            if ($createdTicket->logs()->where('action', 'ok')->exists()) {
                $dateOk = $createdTicket->logs()->where('action', 'ok')->orderBy('id', 'desc')->first();
                $createdTicket->dateOk = LocalizedCarbon::instance($dateOk->created_at)->formatLocalized('%e %f %Y, %H:%M');
            } else {
                $createdTicket->dateOk = '-';
            }

            if ($createdTicket->target_group_id == null) {
                if ($createdTicket->targetUsers->count() > 0) {
                    $tU = [];
                    foreach ($createdTicket->targetUsers as $targetUser) {
                        $targRes = '<a href=\'' . url('/user/') . '/' . $targetUser->profile->user_urlhash . '\'>' . Zen::showShortName($targetUser->name) . '</a>';
                        array_push($tU, $targRes);
                        //array_push($tU, $targetUser->name);
                    }
                }
                $targetString = implode(', ', $tU);
            } else {
                $targetStringGroup = str_limit($createdTicket->targetGroup->name, 20);
                $targetStringUser = '';
                if ($createdTicket->targetUsers->count() > 0) {
                    $tU = [];
                    foreach ($createdTicket->targetUsers as $targetUser) {
                        $targRes = '<a href=\'' . url('/user/') . '/' . $targetUser->profile->user_urlhash . '\'>' . Zen::showShortName($targetUser->name) . '</a>';
                        array_push($tU, $targRes);
                    }
                    $targetStringUser = ' (' . implode(', ', $tU) . ')';
                }

                $targetString = $targetStringGroup . $targetStringUser;

            }

            $createdTicket->targetString = $targetString;
            $createdTicket->tp = $tp = view("user.ticket.ticketPrioShort")->with(['ticket' => $createdTicket])->render();

        }

        return $createdTickets;
    }

    public static function showSlaReglamentRaw($id, $prio)
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

    public static function prepareSlaTicket($createdTickets)
    {

        $countReactionOk = 0;
        $countReactionNo = 0;
        $countWorkOk = 0;
        $countWorkNo = 0;
        $countDeadlineOk = 0;
        $countDeadlineNo = 0;

        foreach ($createdTickets as $createdTicket) {

            $ct = static::showSlaReglamentRaw($createdTicket->sla_id, $createdTicket->prio);

            if ($ct['reaction'] > $createdTicket->slaLog->reaction_time) {
                $createdTicket->slaReactionStatus = true;
                $countReactionOk++;} else {
                $createdTicket->slaReactionStatus = false;
                $countReactionNo++;}

            if ($ct['work'] > $createdTicket->slaLog->work_time) {
                $createdTicket->slaWorkStatus = true;
                $countWorkOk++;} else {
                $createdTicket->slaWorkStatus = false;
                $countWorkNo++;}

            if ($ct['deadline'] > $createdTicket->slaLog->deadline_time) {
                $createdTicket->slaDeadlineStatus = true;
                $countDeadlineOk++;} else {
                $createdTicket->slaDeadlineStatus = false;
                $countDeadlineNo++;}

            $createdTicket->slaReactionRegl = static::showSecToHT($ct['reaction']);
            $createdTicket->slaReactionFact = static::showSecToHT($createdTicket->slaLog->reaction_time);

            $createdTicket->slaWorkRegl = static::showSecToHT($ct['work']);
            $createdTicket->slaWorkFact = static::showSecToHT($createdTicket->slaLog->work_time);

            $createdTicket->slaDeadlineRegl = static::showSecToHT($ct['deadline']);
            $createdTicket->slaDeadlineFact = static::showSecToHT($createdTicket->slaLog->deadline_time);

        }

        return $createdTickets;
    }

    public static function showSecToHT($sec)
    {
        //dd(Config::get('app.locale'));

        CarbonInterval::setLocale('en');

        $ticketSla = [];

        $ticketSla['react_1'] = floor(($sec % 2592000) / 86400);
        $ticketSla['react_2'] = floor(($sec % 86400) / 3600);
        $ticketSla['react_3'] = floor(($sec % 3600) / 60);
        $ticketSla['react_4'] = $sec % 60;

        return CarbonInterval::create(0, 0, 0, $ticketSla['react_1'], $ticketSla['react_2'], $ticketSla['react_3'], $ticketSla['react_4']);

    }

//showUserReport
    public function showUserReport(Request $request)
    {
        //
        $userID = $request->user;
//$userID=1;
        $user = User::findOrFail($userID);

        $startDate = $request->startDate;
        $endDate = $request->endDate;

/*user
startDate
endDate*/
        $createdTickets = Ticket::where('author_id', $userID)
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)
            ->get();

        $receivedTickets = Ticket::WhereHas('targetUsers', function ($q) use ($userID) {
            $q->where('user_id', $userID);
        })->where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)->get();
        $successTickets = Ticket::WhereHas('targetUsers', function ($q) use ($userID) {
            $q->where('user_id', $userID);
        })->where('status', 'success')->where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)
            ->get();
        $referTickets = Ticket::WhereHas('logs', function ($q) use ($userID, $startDate, $endDate) {
            $q->where('author_id', $userID)->where('action', 'refer')->where('created_at', '>=', $startDate)
                ->where('created_at', '<=', $endDate);
        })->get();

        $slaTickets = Ticket::Where('sla_id', '!=', 'Null')->WhereHas('targetUsers', function ($q) use ($userID) {
            $q->where('user_id', $userID);
        })->where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)->get();

        $logs = $user->logs;

        $createdTickets = $this->prepareTicket($createdTickets);
        $receivedTickets = $this->prepareTicket($receivedTickets);
        $successTickets = $this->prepareTicket($successTickets);
        $referTickets = $this->prepareTicket($referTickets);
        $slaTickets = $this->prepareTicket($slaTickets);
        $slaTickets = $this->prepareSlaTicket($slaTickets);

        $countReactionOk = 0;
        $countReactionNo = 0;
        $countWorkOk = 0;
        $countWorkNo = 0;
        $countDeadlineOk = 0;
        $countDeadlineNo = 0;

        foreach ($slaTickets as $slaTicket) {

            $ct = static::showSlaReglamentRaw($slaTicket->sla_id, $slaTicket->prio);

            if ($ct['reaction'] > $slaTicket->slaLog->reaction_time) {$countReactionOk++;} else { $countReactionNo++;}

            if ($ct['work'] > $slaTicket->slaLog->work_time) {$countWorkOk++;} else { $countWorkNo++;}

            if ($ct['deadline'] > $slaTicket->slaLog->deadline_time) {$countDeadlineOk++;} else { $countDeadlineNo++;}

        }

        $data = [

            'createdTickets' => $createdTickets,
            'receivedTickets' => $receivedTickets,
            'successTickets' => $successTickets,
            'referTickets' => $referTickets,
            'slaTickets' => $slaTickets,
            'user' => $user,
            'logs' => $logs,
            'countReactionOk' => $countReactionOk,
            'countReactionNo' => $countReactionNo,
            'countWorkOk' => $countWorkOk,
            'countWorkNo' => $countWorkNo,
            'countDeadlineOk' => $countDeadlineOk,
            'countDeadlineNo' => $countDeadlineNo,

        ];

        return view('user.report.userReport')->with($data);
    }

    public function showGroup()
    {
        //

        $iam = Auth::user();
        $myGroupsUser = [];
        foreach ($iam->GroupUser() as $value) {
            # code...

            $myGroupsUser[$value->id] = $value->name;
        }

        $data = [
            'groups' => $myGroupsUser,
        ];

        return view('user.report.group')->with($data);
    }

    public function showGroupReport(Request $request)
    {
        //
        $groupID = $request->group;
//$userID=1;
        $group = Groups::findOrFail($groupID);

        $groupUsers = [];

        foreach ($group->users as $gu) {
            # code...
            array_push($groupUsers, $gu->id);
        }

        $startDate = $request->startDate;
        $endDate = $request->endDate;

/*user
startDate
endDate*/
        $createdTickets = Ticket::whereHas('authorUser', function ($q) use ($groupUsers) {
            $q->whereIn('id', $groupUsers);
        })
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)
            ->get();

        $receivedTickets = Ticket::WhereHas('targetUsers', function ($q) use ($groupUsers) {
            $q->whereIn('user_id', $groupUsers);
        })->where(function ($query) use ($startDate, $endDate) {
            return $query
                ->where('created_at', '>=', $startDate)
                ->where('created_at', '<=', $endDate);

        })
            ->orWhere('target_group_id', $groupID)

            ->where(function ($query) use ($startDate, $endDate) {
                return $query
                    ->where('created_at', '>=', $startDate)
                    ->where('created_at', '<=', $endDate);

            })->get();

        $successTickets = Ticket::WhereHas('targetUsers', function ($q) use ($groupUsers) {
            $q->whereIn('user_id', $groupUsers);
        })->where('status', 'success')->where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)
            ->get();

        $referTickets = Ticket::WhereHas('logs', function ($q) use ($groupUsers, $startDate, $endDate) {
            $q->whereIn('author_id', $groupUsers)
                ->where('action', 'refer')
                ->where('created_at', '>=', $startDate)
                ->where('created_at', '<=', $endDate);
        })->get();

        $slaTickets = Ticket::Where('sla_id', '!=', 'Null')
            ->WhereHas('targetUsers', function ($q) use ($groupUsers) {
                $q->whereIn('user_id', $groupUsers);
            })->where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)->get();

//$logs=$user->logs;

        $createdTickets = $this->prepareTicket($createdTickets);
        $receivedTickets = $this->prepareTicket($receivedTickets);
        $successTickets = $this->prepareTicket($successTickets);
        $referTickets = $this->prepareTicket($referTickets);
        $slaTickets = $this->prepareTicket($slaTickets);
        $slaTickets = $this->prepareSlaTicket($slaTickets);

        $countReactionOk = 0;
        $countReactionNo = 0;
        $countWorkOk = 0;
        $countWorkNo = 0;
        $countDeadlineOk = 0;
        $countDeadlineNo = 0;

        foreach ($slaTickets as $slaTicket) {

            $ct = static::showSlaReglamentRaw($slaTicket->sla_id, $slaTicket->prio);

            if ($ct['reaction'] > $slaTicket->slaLog->reaction_time) {$countReactionOk++;} else { $countReactionNo++;}

            if ($ct['work'] > $slaTicket->slaLog->work_time) {$countWorkOk++;} else { $countWorkNo++;}

            if ($ct['deadline'] > $slaTicket->slaLog->deadline_time) {$countDeadlineOk++;} else { $countDeadlineNo++;}

        }

        $data = [

            'createdTickets' => $createdTickets,
            'receivedTickets' => $receivedTickets,
            'successTickets' => $successTickets,
            'referTickets' => $referTickets,
            'slaTickets' => $slaTickets,
            'group' => $group,
//'logs'=>$logs,
            'countReactionOk' => $countReactionOk,
            'countReactionNo' => $countReactionNo,
            'countWorkOk' => $countWorkOk,
            'countWorkNo' => $countWorkNo,
            'countDeadlineOk' => $countDeadlineOk,
            'countDeadlineNo' => $countDeadlineNo,

        ];

        return view('user.report.groupReport')->with($data);
    }

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
