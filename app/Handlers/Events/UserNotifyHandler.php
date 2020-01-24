<?php

namespace zenlix\Handlers\Events;

use Mail;
use Setting;
use zenlix\Events\UserNotify;
use zenlix\Ticket;
use zenlix\User;

//use zenlix\TicketLog;

class UserNotifyHandler
{
    /**
     * Create the event handler.
     *
     * @return void
     */
    public function __construct()
    {

        //

    }

    /**
     * Handle the event.
     *
     * @param  TicketLogger  $event
     * @return void
     */
    public function handle(UserNotify $event)
    {
//dd($event->actionType);

        $actionType = $event->actionType;
        $userId = $event->userId;
        $pass = $event->pass;
//$description=$event->description;

//dd($actionType);

        switch ($actionType) {

            case 'create':
                $this->userCreate($userId, $pass);
                break;
            default:
                # code...
                break;
        }

    }

    public function userCreate($userID, $pass = null)
    {
        $setting = config('mail');
//dd('ok');
        $dataFrom = [
            'mailFromMail' => $setting['from']['address'],
            'mailFromName' => $setting['from']['name'],
        ];

        $user = User::findOrFail($userID);
        $userArr = [
            'email' => $user->profile->email,
        ];

//$data['locale']=$user->profile->locale;
        $data = [

            'user' => $user,
            'pass' => $pass,
            'locale' => $user->profile->lang,
            'appURL' => config('app.url'),

        ];

//dd('ok');

        Mail::queue(['text' => 'emails.user.create'], $data, function ($message) use ($data, $userArr, $dataFrom) {
            $message->from($dataFrom['mailFromMail'], $dataFrom['mailFromName']);
            $message->subject(trans('handler.OkRegisterUser') . Setting::get('sitename'));
            $message->to($userArr['email']);
        });

    }

}
