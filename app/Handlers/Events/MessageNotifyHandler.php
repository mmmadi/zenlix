<?php

namespace zenlix\Handlers\Events;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Mail;
use Setting;
use zenlix\Events\MessageNotify;
use zenlix\Jobs\SendWebPush;
use zenlix\Messages;

//use zenlix\TicketLog;

class MessageNotifyHandler
{
    use DispatchesJobs;
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
    public function handle(MessageNotify $event)
    {
//dd($event->actionType);

        $messageID = $event->messageID;

        $setting = config('mail');
//dd('ok');
        $dataFrom = [
            'mailFromMail' => $setting['from']['address'],
            'mailFromName' => $setting['from']['name'],
        ];

        $message = Messages::findOrFail($messageID);
        $userArr = [
            'email' => $message->toUser->profile->email,
        ];

        $data = [

            'message_urlhash' => $message->message_urlhash,
            'author' => $message->fromUser->name,
            'text' => $message->text,
            'subject' => $message->subject,
            'locale' => $message->toUser->profile->lang,
            'appURL' => config('app.url'),

        ];

//dd('ok');

        Mail::queue(['text' => 'emails.user.message'], $data, function ($message) use ($data, $userArr, $dataFrom) {
            $message->from($dataFrom['mailFromMail'], $dataFrom['mailFromName']);
            $message->subject(trans('handler.newPM') . Setting::get('sitename'));
            $message->to($userArr['email']);
        });

//$webPushMsg=view('sms.ticket.create')->with($data)->render();
        $jobWebPush = (new SendWebPush($message->toUser->email, trans('handler.newMsg'), str_limit($data['text'], 20), url('message/' . $data['message_urlhash'])));
        $this->dispatch($jobWebPush);

    }

}
