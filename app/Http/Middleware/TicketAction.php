<?php

namespace zenlix\Http\Middleware;

use Auth;
use Closure;
use zenlix\Ticket;
use zenlix\User;

class TicketAction
{

/*
2. кто может управлять заявкой? (блок/разблок и тд) (ACTION)
- автор заявки
- любой из отдела, если на весь отдел, но не конкретному
- конкретный назначенный пользователь
- суперпользователь из отделов, где:
- заявка адресована на отдел
- заявка адресована конкретному пользователю

 */
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $ticket = Ticket::whereCode($request->route('id'))->firstOrFail();
        $user = Auth::user();

        //автор?
        if ($ticket->author_id == $user->id) {
            return $next($request);
        }

        //заявка мне назначена?
        foreach ($ticket->targetUsers as $value) {
            if ($value->id == $user->id) {return $next($request);}
        }

        //заявка моему отделу и никому конкретно?
        if (($ticket->targetUsers->count() == 0) && ($ticket->target_group_id != null)) {
            foreach ($user->groups as $value) {
                if ($value->id == $ticket->target_group_id) {return $next($request);}
                # code...
            }
        }

//если заявка на отдел то проверить, я ли суперполльзователь отдела?
        if ($ticket->target_group_id != null) {
            foreach ($user->groups()->wherePivot('priviliges', 'admin')->get() as $value) {
                if ($value->id == $ticket->target_group_id) {
                    return $next($request);
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
                    return $next($request);
                }
            }

        }

        return redirect('/ticket/error');
    }
}
