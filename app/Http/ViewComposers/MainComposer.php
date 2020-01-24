<?php

namespace zenlix\Http\ViewComposers;

use Auth;
use Carbon\Carbon;
use Setting;
use View;
use zenlix\Calendar;
use zenlix\Classes\Zen;
use zenlix\Messages;
use zenlix\NotificationMenu;
use zenlix\Ticket;
use zenlix\User;

//use Illuminate\View\View;

class MainComposer
{

    /**
     * Create a movie composer.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose()
    {

        if (Auth::check()) {

            $user = Auth::user();

            $sitelogo = Setting::get('sitelogo', 'false');
            ($sitelogo == 'false') ? $sitelogo = asset('dist/img/ZENLIX_small.png') : $sitelogo = asset('/files/uploads/' . Setting::get('sitelogo'));

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

            if ($user->roles->role == "client") {
                $ticketsInFree = Ticket::where(function ($query) use ($user, $myGroupsAdmin) {
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
            } else {

                $ticketsInFree = Ticket::where(function ($query) use ($user, $myGroups, $myGroupsAdmin) {
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
                    ->where('status', 'free')
                    ->where('planner_flag', 'false')
                    ->where('merge_flag', 'false')
                    ->count();

            }

            if ($user->profile->user_img == null) {
                $userImg = asset('dist/img/def_usr.png');
                $userImgSmall = asset('dist/img/def_usr_small.png');
            } else {
                $file_name = pathinfo('files/users/img/' . $user->profile->user_img, PATHINFO_FILENAME);
                $extension = pathinfo('files/users/img/' . $user->profile->user_img, PATHINFO_EXTENSION);
                $userImgSmall = asset('files/users/img/' . $file_name . '_small.' . $extension);
                $userImg = asset('files/users/img/' . $user->profile->user_img);
            }

            $messagesCount = Messages::where('to_user_id', $user->id)
                ->where('read_flag', 'true')
                ->count();

            $onlineUsers = User::orderBy('name', 'asc')
                ->whereHas('groups', function ($q) use ($myGroups) {
                    $q->whereIn('id', $myGroups);
                })
                ->where('last_login', '>=', Carbon::now()->subMinutes(7)->toDateTimeString())
                ->where('id', '!=', $user->id)
                ->take(30)->get();

            $chatResponces = User::orderBy('id', 'desc')
                ->whereHas('groups', function ($q) use ($myGroups) {
                    $q->whereIn('id', $myGroups);
                })
                ->whereHas('roles', function ($q) {
                    $q->where('role', 'client');
                })
                ->whereHas('chatRequest', function ($q) {
                    $q->where('chatWith_id', null);
                })
                ->where('last_login', '>=', Carbon::now()->subMinutes(15)->toDateTimeString())
                ->where('id', '!=', $user->id)
                ->take(10)->get();

            $notifyMenu = NotificationMenu::where('user_id', $user->id)
                ->orderBy('id', 'desc')
                ->take(10)
                ->get();

            $calendarMenu = Calendar::where(function ($query) use ($user, $myGroups) {
                return $query
                    ->where(function ($query) use ($user) {
                        return $query->where('user_id', $user->id)->where('personal', 'true');

                    })->orWhere(function ($query) use ($myGroups) {
                    return $query->where('personal', 'false')
                        ->whereHas('groups', function ($q) use ($myGroups) {
                            $q->whereIn('group_id', $myGroups);
                        });

                });
            })
                ->whereDate('dtStart', '=', date('Y-m-d'))
//->whereDate('dtStop', '=', date('Y-m-d'))

                ->orderBy('dtStart', 'asc')->get();
/*https://ru.zenlix.com/support/v295/opisanie-api
https://ru.zenlix.com/support/opisanie-api-sistemy*/
//return false;

//($ticketsInFree != 0) ? $ticketsInFree='' : $ticketsInFree='';

            View::share([
                'SiteName' => Setting::get('sitename', 'ZENLIX'),
                'SiteNameShort'=>Setting::get('sitenameShort', 'ZEN'),
                'SiteLogo' => $sitelogo,
                'PageTittle' => Setting::get('sitename', 'ZENLIX'),
                'WPURL' => Setting::get('WPURL'),
                'CurUser' => $user,
                'ticketsInFree' => $ticketsInFree,
                'userImg' => $userImg,
                'userImgSmall' => $userImgSmall,
                'messagesCount' => $messagesCount,
                'onlineUsers' => $onlineUsers,
                'totalUnreadChatMsg' => Zen::checkUnreadChatAll(),
                'chatResponces' => $chatResponces,
                'notifyMenu' => $notifyMenu,
                'calendarMenu' => $calendarMenu,
            ]);

        }
    }
}
