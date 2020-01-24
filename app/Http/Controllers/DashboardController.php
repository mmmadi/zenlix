<?php

namespace zenlix\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Session;
use zenlix\GroupFeed;
use zenlix\Groups;
use zenlix\Help;
use zenlix\HelpAccess;
use zenlix\Http\Controllers\Controller;
use zenlix\NotificationMenu;
use zenlix\Ticket;
use zenlix\TicketComments;
use zenlix\User;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

//updateSidebarMenuState
    public function updateSidebarMenuState()
    {

        if (Session::has('sidebarMenuState')) {
            Session::forget('sidebarMenuState');

        } else {
            Session::put('sidebarMenuState', 'true');
        }

    }

//updateNotifyMenu
    public function updateNotifyMenu(Request $request)
    {

        $user = Auth::user();
        $ticket = Ticket::where('code', $request->code)->firstOrFail();

        NotificationMenu::where('user_id', $user->id)
            ->where('ticket_id', $ticket->id)
            ->delete();

    }

    //showNotifyMenu
    public function showNotifyMenu()
    {

        $user = Auth::user();

        $notifyMenu = NotificationMenu::where('user_id', $user->id)
            ->orderBy('id', 'desc')
            ->take(10)
            ->get();

        $data = [

            'notifyMenu' => $notifyMenu,

        ];

        return view('user.notification.menu')->with($data);
    }

    public function index()
    {
        //

        $ticketsCountAll = Ticket::count();
        $ticketsCountOk = Ticket::whereIn('status', ['success', 'arch'])->count();

        $usersTotal = User::count();

        $clientsTotal = User::whereHas('roles', function ($q) {
            $q->where('role', 'client');
        })
            ->count();

        $groupsTotal = Groups::count();
        $commentsTotal = TicketComments::count();
        $user = Auth::user();
        $userCommentsTotal = $user->comments->count();
        $userGroupsAdmin = $user->GroupAdmin()->count();

        $myGroups = [];
        foreach ($user->groups as $value) {
            # code...
            array_push($myGroups, $value->id);
        }

        if ($user->role == 'admin') {
            $help = Help::orderBy('updated_at', 'DESC')->take(5)->get();
            $groupFeed = GroupFeed::where('mark', 'true')->orderBy('updated_at', 'DESC')->take(5)->get();

        } else {
            $helps = HelpAccess::whereIn('group_id', $myGroups)->get();

            $m = [];
            foreach ($helps as $value) {
                array_push($m, $value->help[0]->id);
            }

            $help = Help::whereIn('id', $m)
                ->orWhere('access_all', 'true')
                ->orderBy('updated_at', 'DESC')->take(5)->get();

            $groupFeed = GroupFeed::whereIn('group_id', $myGroups)
                ->where('mark', 'true')
                ->orderBy('updated_at', 'DESC')->take(5)->get();

        }

        $data = [
            'PageTittle' => 'Панель приборов',
            'ticketsCountAll' => $ticketsCountAll,
            'ticketsCountOk' => $ticketsCountOk,
            'usersTotal' => $usersTotal,
            'clientsTotal' => $clientsTotal,
            'groupsTotal' => $groupsTotal,
            'commentsTotal' => $commentsTotal,
            'userCommentsTotal' => $userCommentsTotal,
            'userGroupsAdmin' => $userGroupsAdmin,
            'helps' => $help,
            'groupFeed' => $groupFeed,
        ];

        return view('user.dashboard')->with($data);

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
