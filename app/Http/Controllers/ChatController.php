<?php

namespace zenlix\Http\Controllers;

use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Redis;
use Session;
use zenlix\Chat;
use zenlix\ChatRequest;
use zenlix\Classes\Zen;
use zenlix\Http\Controllers\Controller;
use zenlix\User;

class ChatController extends Controller
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

    public function storeChat(Request $request)
    {
        //

        $user = Auth::user();

        $toUser = User::where('id', $request->toUser)->firstOrFail();

        $text = $request->text;

        Chat::create([

            'text' => $text,
            'from_user_id' => $user->id,
            'to_user_id' => $toUser->id,

        ]);

        Chat::where('to_user_id', $user->id)
            ->where('from_user_id', $toUser->id)->where('read_flag', 'true')->update([

            'read_flag' => 'false',

        ]);

        $totalC = Chat::where(function ($query) use ($user, $toUser) {
            return $query->where('to_user_id', $user->id)->where('from_user_id', $toUser->id);
        })
            ->orWhere(function ($query) use ($user, $toUser) {
                return $query->where('to_user_id', $toUser->id)->where('from_user_id', $user->id);
            })->count();

        try {
            Redis::publish('ZEN-channel', json_encode([
                'msgType' => 'chatPush',
                'login' => $toUser->email,
                'fromName' => $user->name,
                'fromid' => $user->id,
                'from' => $user->email,
                'message' => str_limit($text, 40),
                'total' => $totalC,

            ]));
        } catch (\Exception $e) {
        }

/*login
message
from
total*/

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

    public function showChat(Request $request)
    {
        //
        $user = Auth::user();
        $toUser = User::where('id', $request->toUser)->firstOrFail();

        $totalC = Chat::where(function ($query) use ($user, $toUser) {
            return $query->where('to_user_id', $user->id)->where('from_user_id', $toUser->id);
        })
            ->orWhere(function ($query) use ($user, $toUser) {
                return $query->where('to_user_id', $toUser->id)->where('from_user_id', $user->id);
            })->count();
        $total = $totalC - 30;

        $chatMessages = Chat::where(function ($query) use ($user, $toUser) {
            return $query->where('to_user_id', $user->id)->where('from_user_id', $toUser->id);
        })
            ->orWhere(function ($query) use ($user, $toUser) {
                return $query->where('to_user_id', $toUser->id)->where('from_user_id', $user->id);
            })
        //where('to_user_id', $toUser->id)->('from_user_id', $user->id)
            ->limit(30)->offset($total)->orderBy('id', 'asc')->get();

        Session::put('chatWith', $request->toUser);
        Session::put('chatWithName', $toUser->name);

/*Chat::where('to_user_id', $user->id)
->where('from_user_id', $toUser->id)->where('read_flag', 'true')->update([

'read_flag'=>'false'

]);*/

        $data = [

            'chatMessages' => $chatMessages,

        ];

        $html = view('user.chat.chat')->with($data)->render();

        return response()->json([[
            'html' => $html,
            'userName' => $toUser->name,
            'totalMsg' => $totalC,
        ]]);

    }

//storeChatRequestAccept
    public function storeChatRequestAccept(Request $request)
    {
        $userI = Auth::user();
        $user = User::findOrFail($request->user);

        $user->chatRequest->update([
            'chatWith_id' => $userI->id,
        ]);

        $totalC = Chat::where(function ($query) use ($user, $userI) {
            return $query->where('to_user_id', $user->id)->where('from_user_id', $userI->id);
        })
            ->orWhere(function ($query) use ($user, $userI) {
                return $query->where('to_user_id', $userI->id)->where('from_user_id', $user->id);
            })->count();

        try {
            Redis::publish('ZEN-channel', json_encode([
                'msgType' => 'chatReqAccept',
                'login' => $user->email,
                'fromName' => $userI->name,
                'fromid' => $userI->id,
                'from' => $userI->email,

            ]));

            Redis::publish('ZEN-channel', json_encode([
                'msgType' => 'chatReq',

            ]));

        } catch (\Exception $e) {
        }

    }

//storeChatRequest
    public function storeChatRequest(Request $request)
    {

        $user = Auth::user();
        ChatRequest::create([

            'user_id' => $user->id,

        ]);

        try {
            Redis::publish('ZEN-channel', json_encode([
                'msgType' => 'chatReq',

            ]));
        } catch (\Exception $e) {
        }

        return response()->json([[
            'msg' => trans('handler.reqSended'),

        ]]);
    }

//showChatRequestMenu
    public function showChatRequestMenu(Request $request)
    {

        $user = Auth::user();
        $myGroups = [];
        foreach ($user->GroupUser() as $value) {
            # code...
            array_push($myGroups, $value->id);
        }

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

        $data = [

            'CurUser' => $user,
            'chatResponces' => $chatResponces,

        ];

        return view('user.chat.menuReq')->with($data);

    }

//updateChatSetAsRead
    public function updateChatSetAsRead(Request $request)
    {
        $toUser = User::where('id', $request->user)->firstOrFail();
        $user = Auth::user();

        Chat::where('to_user_id', $user->id)
            ->where('from_user_id', $toUser->id)->where('read_flag', 'true')->update([

            'read_flag' => 'false',

        ]);
        if ($toUser->roles->role == "client") {

            try {
                Redis::publish('ZEN-channel', json_encode([
                    'msgType' => 'chatReqAccept',
                    'login' => $toUser->email,
                    'fromName' => $user->name,
                    'fromid' => $user->id,
                    'from' => $user->email,

                ]));

                Redis::publish('ZEN-channel', json_encode([
                    'msgType' => 'chatReq',

                ]));

            } catch (\Exception $e) {
            }

        }

        return response()->json([[
            'countTotalUnread' => Zen::checkUnreadChatAll(),

        ]]);

    }

//updateChatCloseCurrent
    public function updateChatCloseCurrent(Request $request)
    {
        Session::put('chatStateView', 'false');
        Session::forget('chatWith');

        $userID = $request->user;
        $user = User::find($userID);
        $userI = Auth::user();
//dd($user);

        Chat::where('to_user_id', $user->id)
            ->where('from_user_id', $userI->id)->where('read_flag', 'true')->update([

            'read_flag' => 'false',

        ]);

        ChatRequest::where('user_id', $user->id)
            ->delete();

        try {
            Redis::publish('ZEN-channel', json_encode([
                'msgType' => 'chatClose',
                'login' => $user->email,

            ]));
        } catch (\Exception $e) {
        }

    }

    public function updateChatToggle()
    {
        //
        //Session::forget('chatStateView');
        //dd('ok');
        if (Session::has('chatStateView')) {
            Session::forget('chatStateView');
        } else {
            Session::put('chatStateView', 'true');
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
