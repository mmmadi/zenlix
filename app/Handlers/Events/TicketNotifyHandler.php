<?php

namespace zenlix\Handlers\Events;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Mail;
use Setting;
use zenlix\Events\TicketNotify;
use zenlix\Jobs\SendDevicePush;
use zenlix\Jobs\SendNotifyMenu;
use zenlix\Jobs\SendPB;
use zenlix\Jobs\SendSMS;
use zenlix\Jobs\SendWebPush;
use zenlix\Ticket;
use zenlix\User;

//use zenlix\TicketLog;

class TicketNotifyHandler
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
     * @param  TicketLogger $event
     * @return void
     */
    public function handle(TicketNotify $event)
    {

//dd('ok');

        $actionType = $event->actionType;
        $ticketId = $event->ticketId;
        $authorId = $event->authorId;
        $description = $event->description;

//return $this->$actionType($ticketId, $authorId, $description);

        switch ($actionType) {

            case 'create':
                $this->ticketCreate($ticketId, $authorId, $description);
                break;

            case 'refer':
                $this->ticketRefer($ticketId, $authorId, $description);
                break;

            case 'lock':
                $this->ticketLock($ticketId, $authorId, $description);
                break;

            case 'unlock':
                $this->ticketUnlock($ticketId, $authorId, $description);
                break;

            case 'ok':
                $this->ticketOk($ticketId, $authorId, $description);
                break;

            case 'unok':
                $this->ticketUnok($ticketId, $authorId, $description);
                break;

            case 'waitok':
                $this->ticketWaitok($ticketId, $authorId, $description);
                break;

            case 'aprrove':
                $this->ticketApprove($ticketId, $authorId, $description);
                break;

            case 'noapprove':
                $this->ticketNoapprove($ticketId, $authorId, $description);
                break;

            case 'delete':
                $this->ticketDelete($ticketId, $authorId, $description);
                break;

            case 'restore':
                $this->ticketRestore($ticketId, $authorId, $description);
                break;

            case 'comment':
                $this->ticketComment($ticketId, $authorId, $description);
                break;

            case 'edit':
                $this->ticketEdit($ticketId, $authorId, $description);
                break;

            default:
                # code...
                break;
        }

    }

    /*

    ticketCreate
    ticketRefer
    ticketLock
    ticketUnlock
    ticketOk
    ticketUnok
    ticketWaitok
    ticketApprove
    ticketNoapprove
    ticketDelete
    ticketRestore
    ticketComment

    по заявкам:
    - новая заявка
    - переадресована заявка
    - взята в работу
    - снята с работы
    - выполнена
    - не выполнена
    - удалена
    - перенесена в архив
    - отредактирована

    - ожидает проверки
    - проверено
    - возвращено

    - прокомментировано

    по пользователям:
    -создан пользователь
    -восстановлен пароль

     */

    /**
     * @param $ticketID
     * @return mixed
     */
    public static function ticketInfo($ticketID)
    {

        $ticket = Ticket::findOrFail($ticketID);
        $clients = [];
        foreach ($ticket->clients as $clientsEl) {
            array_push($clients, $clientsEl->name);
            # code...
        }

        $targetUsers = [];
        foreach ($ticket->targetUsers as $targetUsersEl) {
            array_push($targetUsers, $targetUsersEl->name);
            # code...
        }

        ($ticket->target_group_id != null) ? $targets = $ticket->targetGroup->name : $targets = '';

        (count($targetUsers) > 0) ? $targets = $targets . ' (' . implode(' ,', $targetUsers) . ')' : $targets = $targets;

        $data = [
            'code'     => $ticket->code,
            'ticket'   => $ticket,
            'author'   => $ticket->authorUser->name,
            'authorID' => $ticket->authorUser->id,
            'clients'  => implode(' ,', $clients),
            'targets'  => $targets,
            'canReply' => true,
            'appURL'   => config('app.url'),
            //'msg'=>$ticket->text
        ];

        return $data;

    }

    /**
     * @param $user
     * @param $target
     * @param $type
     */
    public static function checkUserNotify($user, $target, $type)
    {

        if ($type == 'pb') {

            if (empty($user->profile->pb)) {
                return false;
            } else {
                return true;
            }

        }

        if ($type == 'mail') {
            if (Setting::get('mailStatus') == 'false') {
                return false;
            }
        }

        if ($type == 'sms') {
            if (Setting::get('smsStatus') == 'false') {
                return false;
            }
            if (empty($user->profile->sms)) {
                return false;
            }
        }

        if ($user->NotifyConfigCount($target)->count() == 0) {
            return false;
        }

        if (($user->NotifyConfig($target, $type)->isEmpty() == false)) {

            if (!empty($user->profile->email)) {
                return true;
            } else {
                return false;
            }

        } else {
            return false;
        }

    }

    /**
     * @param $ticketId
     * @param $authorId
     * @param $description
     */
    public function ticketCreate($ticketId, $authorId, $description = null)
    {
        $setting = config('mail');

        $dataFrom = [
            'mailFromMail' => $setting['from']['address'],
            'mailFromName' => $setting['from']['name'],
        ];
        $data = $this->ticketInfo($ticketId);

        $ticket = Ticket::findOrFail($ticketId);

//Рассылка всем
        //если выбран только отдел
        if (count($ticket->targetUsers) == 0) {
            if ($ticket->target_group_id != null) {
                $GroupUsers = $ticket->targetGroup->users;
                //все пользователи отдела



                foreach ($GroupUsers as $GroupUser) {
                    $data['locale'] = $GroupUser->profile->locale;
//if ($GroupUser->NotifyConfig('mail', 'create')->isEmpty() == false)

                    if ($this->checkUserNotify($GroupUser, 'sms', 'create')) {
                        if ($authorId != $GroupUser->id) {
                            $messageSms = view('sms.ticket.create')->with($data)->render();
                            $job = (new SendSMS($GroupUser->profile->sms, $messageSms));
                            $this->dispatch($job);
                        }
                    }
                    if ($authorId != $GroupUser->id) {
                        $webPushMsg = view('sms.ticket.create')->with($data)->render();
                        $jobWebPush = (new SendWebPush($GroupUser->email, trans('handler.newTicket'), $webPushMsg, url('ticket/' . $data['code'])));
                        $this->dispatch($jobWebPush);
                    }

                    if ($authorId != $GroupUser->id) {
                        $deviceMsgData = view('sms.ticket.create')->with($data)->render();
                        //if (count($GroupUser->devices))
                        //dd(is_array($GroupUser->devices));
                        foreach ($GroupUser->devices as $uDevice) {

                            $devicePushMsg = (new SendDevicePush($uDevice->device_hash, trans('handler.newTicket'), $deviceMsgData));
                            $this->dispatch($devicePushMsg);

                        }
                    }

                    $jobNotifyMenu = (new SendNotifyMenu($authorId, 'create', $GroupUser->id, $ticket->id));
                    $this->dispatch($jobNotifyMenu);

                    if ($this->checkUserNotify($GroupUser, 'pb', 'create')) {
                        if ($authorId != $GroupUser->id) {
                            $messagePB = view('pb.ticket.create')->with($data)->render();
                            $jobPB = (new SendPB($GroupUser->profile->pb, trans('handler.newTicketCreated'), $messagePB));
                            $this->dispatch($jobPB);
                        }
                    }

                    if ($this->checkUserNotify($GroupUser, 'mail', 'create')) {

//->bcc(array('TEST@example.com','TESsdT@example.com','TESjxfjT@example.com','TESfssdT@example.com'))

                        $userMail = $GroupUser->profile->email;

                        Mail::queue(['text' => 'emails.ticket.create'], $data, function ($message) use ($data, $userMail, $dataFrom) {
                            $message->from($dataFrom['mailFromMail'], $dataFrom['mailFromName']);
                            $message->subject(trans('handler.newTicketCreated') . Setting::get('sitename') . ' [CODE:#' . $data['code'] . ']');
                            $message->to($userMail);
                            //$message->bcc(['info@rustem.com.ua']);
                        });

                    }

                    # code...
                }

            }
        }

//Рассылка наблюдающим
        $users = $ticket->watchingUsers;

        foreach ($users as $user) {
            $data['locale'] = $user->profile->lang;

            if ($this->checkUserNotify($user, 'sms', 'create')) {
                if ($authorId != $user->id) {
                    $messageSms = view('sms.ticket.create')->with($data)->render();
                    $job = (new SendSMS($user->profile->sms, $messageSms));
                    $this->dispatch($job);
                }
            }
            if ($authorId != $user->id) {
                $webPushMsg = view('sms.ticket.create')->with($data)->render();
                $jobWebPush = (new SendWebPush($user->email, trans('handler.newTicket'), $webPushMsg, url('ticket/' . $data['code'])));
                $this->dispatch($jobWebPush);
            }

            $jobNotifyMenu = (new SendNotifyMenu($authorId, 'create', $user->id, $ticket->id));
            $this->dispatch($jobNotifyMenu);

            if ($this->checkUserNotify($user, 'pb', 'create')) {
                if ($authorId != $user->id) {
                    $messagePB = view('pb.ticket.create')->with($data)->render();
                    $jobPB = (new SendPB($user->profile->pb, trans('handler.newTicketCreated'), $messagePB));
                    $this->dispatch($jobPB);
                }
            }

            if ($authorId != $user->id) {
                $deviceMsgData = view('sms.ticket.create')->with($data)->render();
                foreach ($user->devices as $uDevice) {

                    $devicePushMsg = (new SendDevicePush($uDevice->device_hash, trans('handler.newTicket'), $deviceMsgData));
                    $this->dispatch($devicePushMsg);

                }
            }

            if ($this->checkUserNotify($user, 'mail', 'create')) //if ($user->NotifyConfig('mail', 'create')->isEmpty() == false)
            {
                $userMail = $user->profile->email;

                Mail::queue(['text' => 'emails.ticket.create'], $data, function ($message) use ($data, $userMail, $dataFrom) {
                    $message->from($dataFrom['mailFromMail'], $dataFrom['mailFromName']);
                    $message->subject(trans('handler.newTicket') . Setting::get('sitename') . ' [CODE:#' . $data['code'] . ']');
                    $message->to($userMail);

                });

            }

            # code...
        }

    }

    /**
     * @param $ticketId
     * @param $authorId
     * @param $description
     */
    public function ticketRefer($ticketId, $authorId, $description = null)
    {
        $setting = config('mail');

        $dataFrom = [
            'mailFromMail' => $setting['from']['address'],
            'mailFromName' => $setting['from']['name'],
        ];
        $data = $this->ticketInfo($ticketId);

        $ticket = Ticket::findOrFail($ticketId);
        $initUser = User::findOrFail($authorId);

        $data['initUser'] = $initUser->name;

//Рассылка всем
        //если выбран только отдел
        if (count($ticket->targetUsers) == 0) {
            if ($ticket->target_group_id != null) {
                $GroupUsers = $ticket->targetGroup->users()->wherePivot('id', '!=', $authorId)->get();

                foreach ($GroupUsers as $GroupUser) {
                    $data['locale'] = $GroupUser->profile->locale;

                    if ($this->checkUserNotify($GroupUser, 'sms', 'refer')) {
                        if ($authorId != $GroupUser->id) {
                            $messageSms = view('sms.ticket.refer')->with($data)->render();
                            $job = (new SendSMS($GroupUser->profile->sms, $messageSms));
                            $this->dispatch($job);
                        }
                    }

                    if ($authorId != $GroupUser->id) {
                        $webPushMsg = view('sms.ticket.refer')->with($data)->render();
                        $jobWebPush = (new SendWebPush($GroupUser->email, trans('handler.forwardTicket'), $webPushMsg, url('ticket/' . $data['code'])));
                        $this->dispatch($jobWebPush);
                    }

                    $jobNotifyMenu = (new SendNotifyMenu($authorId, 'refer', $GroupUser->id, $ticket->id));
                    $this->dispatch($jobNotifyMenu);

                    if ($this->checkUserNotify($GroupUser, 'pb', 'refer')) {
                        if ($authorId != $GroupUser->id) {
                            $messagePB = view('pb.ticket.refer')->with($data)->render();
                            $jobPB = (new SendPB($GroupUser->profile->pb, trans('handler.ticketForwarded'), $messagePB));
                            $this->dispatch($jobPB);
                        }
                    }

                    if ($authorId != $GroupUser->id) {
                        $deviceMsgData = view('sms.ticket.refer')->with($data)->render();
                        foreach ($GroupUser->devices as $uDevice) {

                            $devicePushMsg = (new SendDevicePush($uDevice->device_hash, trans('handler.ticketForwarded'), $deviceMsgData));
                            $this->dispatch($devicePushMsg);

                        }
                    }

//if ($GroupUser->NotifyConfig('mail', 'refer')->isEmpty() == false)
                    if ($this->checkUserNotify($GroupUser, 'mail', 'refer')) {
                        $userMail = $GroupUser->profile->email;

                        Mail::queue(['text' => 'emails.ticket.refer'], $data, function ($message) use ($data, $userMail, $dataFrom) {
                            $message->from($dataFrom['mailFromMail'], $dataFrom['mailFromName']);
                            $message->subject(trans('handler.forwardTicket') . Setting::get('sitename') . ' [CODE:#' . $data['code'] . ']');
                            $message->to($userMail);
                        });

                    }

                    # code...
                }

            }
        }

//Рассылка наблюдающим
        $users = $ticket->watchingUsers()->wherePivot('id', '!=', $authorId)->get();

        foreach ($users as $user) {
            $data['locale'] = $user->profile->lang;
            if ($this->checkUserNotify($user, 'sms', 'refer')) {
                if ($authorId != $user->id) {
                    $messageSms = view('sms.ticket.refer')->with($data)->render();
                    $job = (new SendSMS($user->profile->sms, $messageSms));
                    $this->dispatch($job);
                }
            }
            if ($authorId != $user->id) {
                $webPushMsg = view('sms.ticket.refer')->with($data)->render();
                $jobWebPush = (new SendWebPush($user->email, trans('handler.forwardTicket'), $webPushMsg, url('ticket/' . $data['code'])));
                $this->dispatch($jobWebPush);
            }

            $jobNotifyMenu = (new SendNotifyMenu($authorId, 'refer', $user->id, $ticket->id));
            $this->dispatch($jobNotifyMenu);

            if ($this->checkUserNotify($user, 'pb', 'refer')) {
                if ($authorId != $user->id) {
                    $messagePB = view('pb.ticket.refer')->with($data)->render();
                    $jobPB = (new SendPB($user->profile->pb, trans('handler.forwardTicket'), $messagePB));
                    $this->dispatch($jobPB);
                }
            }
            if ($authorId != $user->id) {
                $deviceMsgData = view('sms.ticket.refer')->with($data)->render();
                foreach ($user->devices as $uDevice) {

                    $devicePushMsg = (new SendDevicePush($uDevice->device_hash, trans('handler.forwardTicket'), $deviceMsgData));
                    $this->dispatch($devicePushMsg);

                }
            }
            if ($this->checkUserNotify($user, 'mail', 'refer')) {
                $userMail = $user->profile->email;

                Mail::queue(['text' => 'emails.ticket.refer'], $data, function ($message) use ($data, $userMail, $dataFrom) {
                    $message->from($dataFrom['mailFromMail'], $dataFrom['mailFromName']);
                    $message->subject(trans('handler.forwardTicket') . Setting::get('sitename') . ' [CODE:#' . $data['code'] . ']');
                    $message->to($userMail);
                });

            }

            # code...
        }

    }

    /**
     * @param $ticketId
     * @param $authorId
     * @param $description
     */
    public function ticketLock($ticketId, $authorId, $description = null)
    {
        $setting = config('mail');

        $dataFrom = [
            'mailFromMail' => $setting['from']['address'],
            'mailFromName' => $setting['from']['name'],
        ];
        $data = $this->ticketInfo($ticketId);

        $ticket = Ticket::findOrFail($ticketId);
        $initUser = User::findOrFail($authorId);

        $data['initUser'] = $initUser->name;

//Рассылка наблюдающим
        $users = $ticket->watchingUsers()->wherePivot('id', '!=', $authorId)->get();

        foreach ($users as $user) {
            $data['locale'] = $user->profile->lang;
            if ($this->checkUserNotify($user, 'sms', 'lock')) {
                if ($authorId != $user->id) {
                    $messageSms = view('sms.ticket.lock')->with($data)->render();
                    $job = (new SendSMS($user->profile->sms, $messageSms));
                    $this->dispatch($job);
                }

            }

            $jobNotifyMenu = (new SendNotifyMenu($authorId, 'lock', $user->id, $ticket->id));
            $this->dispatch($jobNotifyMenu);

            if ($this->checkUserNotify($user, 'pb', 'lock')) {
                if ($authorId != $user->id) {
                    $messagePB = view('pb.ticket.lock')->with($data)->render();
                    $jobPB = (new SendPB($user->profile->pb, trans('handler.ticketLocked'), $messagePB));
                    $this->dispatch($jobPB);
                }
            }

            if ($authorId != $user->id) {
                $deviceMsgData = view('sms.ticket.lock')->with($data)->render();
                foreach ($user->devices as $uDevice) {

                    $devicePushMsg = (new SendDevicePush($uDevice->device_hash, trans('handler.ticketLocked'), $deviceMsgData));
                    $this->dispatch($devicePushMsg);

                }
            }

            if ($this->checkUserNotify($user, 'mail', 'lock')) {
                $userMail = $user->profile->email;

                Mail::queue(['text' => 'emails.ticket.lock'], $data, function ($message) use ($data, $userMail, $dataFrom) {
                    $message->from($dataFrom['mailFromMail'], $dataFrom['mailFromName']);
                    $message->subject(trans('handler.ticketLocked') . Setting::get('sitename') . ' [CODE:#' . $data['code'] . ']');
                    $message->to($userMail);
                });

            }

            # code...
        }

    }

    /**
     * @param $ticketId
     * @param $authorId
     * @param $description
     */
    public function ticketUnlock($ticketId, $authorId, $description = null)
    {
        $setting = config('mail');

        $dataFrom = [
            'mailFromMail' => $setting['from']['address'],
            'mailFromName' => $setting['from']['name'],
        ];
        $data = $this->ticketInfo($ticketId);

        $ticket = Ticket::findOrFail($ticketId);
        $initUser = User::findOrFail($authorId);

        $data['initUser'] = $initUser->name;

//Рассылка наблюдающим
        $users = $ticket->watchingUsers()->wherePivot('id', '!=', $authorId)->get();

        foreach ($users as $user) {
            $data['locale'] = $user->profile->lang;

            if ($this->checkUserNotify($user, 'sms', 'unlock')) {
                if ($authorId != $user->id) {
                    $messageSms = view('sms.ticket.unlock')->with($data)->render();
                    $job = (new SendSMS($user->profile->sms, $messageSms));
                    $this->dispatch($job);
                }

            }

            if ($this->checkUserNotify($user, 'pb', 'unlock')) {
                if ($authorId != $user->id) {
                    $messagePB = view('pb.ticket.unlock')->with($data)->render();
                    $jobPB = (new SendPB($user->profile->pb, trans('handler.ticketUnlocked'), $messagePB));
                    $this->dispatch($jobPB);
                }
            }

            if ($authorId != $user->id) {
                $deviceMsgData = view('sms.ticket.unlock')->with($data)->render();
                foreach ($user->devices as $uDevice) {

                    $devicePushMsg = (new SendDevicePush($uDevice->device_hash, trans('handler.ticketUnlocked'), $deviceMsgData));
                    $this->dispatch($devicePushMsg);

                }
            }

            $jobNotifyMenu = (new SendNotifyMenu($authorId, 'unlock', $user->id, $ticket->id));
            $this->dispatch($jobNotifyMenu);

            if ($this->checkUserNotify($user, 'mail', 'unlock')) {
                $userMail = $user->profile->email;

                Mail::queue(['text' => 'emails.ticket.unlock'], $data, function ($message) use ($data, $userMail, $dataFrom) {
                    $message->from($dataFrom['mailFromMail'], $dataFrom['mailFromName']);
                    $message->subject(trans('handler.ticketUnlocked') . Setting::get('sitename') . ' [CODE:#' . $data['code'] . ']');
                    $message->to($userMail);
                });

            }

            # code...
        }

    }

    /**
     * @param $ticketId
     * @param $authorId
     * @param $description
     */
    public function ticketOk($ticketId, $authorId, $description = null)
    {
        $setting = config('mail');

        $dataFrom = [
            'mailFromMail' => $setting['from']['address'],
            'mailFromName' => $setting['from']['name'],
        ];
        $data = $this->ticketInfo($ticketId);

        $ticket = Ticket::findOrFail($ticketId);
        $initUser = User::findOrFail($authorId);

        $data['initUser'] = $initUser->name;

//Рассылка наблюдающим
        $users = $ticket->watchingUsers()->wherePivot('id', '!=', $authorId)->get();

        foreach ($users as $user) {
            $data['locale'] = $user->profile->lang;
            if ($this->checkUserNotify($user, 'sms', 'ok')) {
                if ($authorId != $user->id) {
                    $messageSms = view('sms.ticket.ok')->with($data)->render();
                    $job = (new SendSMS($user->profile->sms, $messageSms));
                    $this->dispatch($job);
                }

            }

            if ($authorId != $user->id) {
                $webPushMsg = view('sms.ticket.ok')->with($data)->render();
                $jobWebPush = (new SendWebPush($user->email, trans('handler.ticketSuccess'), $webPushMsg, url('ticket/' . $data['code'])));
                $this->dispatch($jobWebPush);
            }

            if ($authorId != $user->id) {
                $jobNotifyMenu = (new SendNotifyMenu($authorId, 'ok', $user->id, $ticket->id));
                $this->dispatch($jobNotifyMenu);
            }

            if ($this->checkUserNotify($user, 'pb', 'ok')) {
                if ($authorId != $user->id) {
                    $messagePB = view('pb.ticket.ok')->with($data)->render();
                    $jobPB = (new SendPB($user->profile->pb, trans('handler.ticketSuccess'), $messagePB));
                    $this->dispatch($jobPB);
                }
            }

            if ($authorId != $user->id) {
                $deviceMsgData = view('sms.ticket.ok')->with($data)->render();
                foreach ($user->devices as $uDevice) {

                    $devicePushMsg = (new SendDevicePush($uDevice->device_hash, trans('handler.ticketSuccess'), $deviceMsgData));
                    $this->dispatch($devicePushMsg);

                }
            }

            if ($this->checkUserNotify($user, 'mail', 'ok')) {
                $userMail = $user->profile->email;

                Mail::queue(['text' => 'emails.ticket.ok'], $data, function ($message) use ($data, $userMail, $dataFrom) {
                    $message->from($dataFrom['mailFromMail'], $dataFrom['mailFromName']);
                    $message->subject(trans('handler.ticketSuccess') . Setting::get('sitename') . ' [CODE:#' . $data['code'] . ']');
                    $message->to($userMail);
                });

            }

            # code...
        }

    }

    /**
     * @param $ticketId
     * @param $authorId
     * @param $description
     */
    public function ticketUnok($ticketId, $authorId, $description = null)
    {
        $setting = config('mail');

        $dataFrom = [
            'mailFromMail' => $setting['from']['address'],
            'mailFromName' => $setting['from']['name'],
        ];
        $data = $this->ticketInfo($ticketId);

        $ticket = Ticket::findOrFail($ticketId);
        $initUser = User::findOrFail($authorId);

        $data['initUser'] = $initUser->name;

//Рассылка наблюдающим
        $users = $ticket->watchingUsers()->wherePivot('id', '!=', $authorId)->get();

        foreach ($users as $user) {
            $data['locale'] = $user->profile->lang;
            if ($this->checkUserNotify($user, 'sms', 'unok')) {
                if ($authorId != $user->id) {
                    $messageSms = view('sms.ticket.unok')->with($data)->render();
                    $job = (new SendSMS($user->profile->sms, $messageSms));
                    $this->dispatch($job);
                }

            }
            if ($authorId != $user->id) {
                $webPushMsg = view('sms.ticket.unok')->with($data)->render();
                $jobWebPush = (new SendWebPush($user->email, trans('handler.ticketUnOk'), $webPushMsg, url('ticket/' . $data['code'])));
                $this->dispatch($jobWebPush);
            }

            $jobNotifyMenu = (new SendNotifyMenu($authorId, 'unok', $user->id, $ticket->id));
            $this->dispatch($jobNotifyMenu);

            if ($this->checkUserNotify($user, 'pb', 'unok')) {
                if ($authorId != $user->id) {
                    $messagePB = view('pb.ticket.unok')->with($data)->render();
                    $jobPB = (new SendPB($user->profile->pb, trans('handler.ticketUnOk'), $messagePB));
                    $this->dispatch($jobPB);
                }
            }

            if ($authorId != $user->id) {
                $deviceMsgData = view('sms.ticket.unok')->with($data)->render();
                foreach ($user->devices as $uDevice) {

                    $devicePushMsg = (new SendDevicePush($uDevice->device_hash, trans('handler.ticketUnOk'), $deviceMsgData));
                    $this->dispatch($devicePushMsg);

                }
            }

            if ($this->checkUserNotify($user, 'mail', 'unok')) {
                $userMail = $user->profile->email;

                Mail::queue(['text' => 'emails.ticket.unok'], $data, function ($message) use ($data, $userMail, $dataFrom) {
                    $message->from($dataFrom['mailFromMail'], $dataFrom['mailFromName']);
                    $message->subject(trans('handler.ticketUnOk') . Setting::get('sitename') . ' [CODE:#' . $data['code'] . ']');
                    $message->to($userMail);
                });

            }

            # code...
        }

    }

    /**
     * @param $ticketId
     * @param $authorId
     * @param $description
     */
    public function ticketWaitok($ticketId, $authorId, $description = null)
    {
        $setting = config('mail');

        $dataFrom = [
            'mailFromMail' => $setting['from']['address'],
            'mailFromName' => $setting['from']['name'],
        ];
        $data = $this->ticketInfo($ticketId);

        $ticket = Ticket::findOrFail($ticketId);

//Рассылка наблюдающим
        $users = $ticket->watchingUsers()->wherePivot('id', '!=', $authorId)->get();

        foreach ($users as $user) {
            $data['locale'] = $user->profile->lang;
            if ($this->checkUserNotify($user, 'sms', 'ok')) {
                if ($authorId != $user->id) {
                    $messageSms = view('sms.ticket.waitok')->with($data)->render();
                    $job = (new SendSMS($user->profile->sms, $messageSms));
                    $this->dispatch($job);
                }

            }
            if ($authorId != $user->id) {
                $webPushMsg = view('sms.ticket.waitok')->with($data)->render();
                $jobWebPush = (new SendWebPush($user->email, trans('handler.ticketWait'), $webPushMsg, url('ticket/' . $data['code'])));
                $this->dispatch($jobWebPush);
            }

            $jobNotifyMenu = (new SendNotifyMenu($authorId, 'waitok', $user->id, $ticket->id));
            $this->dispatch($jobNotifyMenu);

            if ($this->checkUserNotify($user, 'pb', 'ok')) {
                if ($authorId != $user->id) {
                    $messagePB = view('pb.ticket.waitok')->with($data)->render();
                    $jobPB = (new SendPB($user->profile->pb, trans('handler.ticketOkAndWait'), $messagePB));
                    $this->dispatch($jobPB);
                }
            }

            if ($authorId != $user->id) {
                $deviceMsgData = view('sms.ticket.waitok')->with($data)->render();
                foreach ($user->devices as $uDevice) {

                    $devicePushMsg = (new SendDevicePush($uDevice->device_hash, trans('handler.ticketOkAndWait'), $deviceMsgData));
                    $this->dispatch($devicePushMsg);

                }
            }

            if ($this->checkUserNotify($user, 'mail', 'ok')) {
                $userMail = $user->profile->email;

                Mail::queue(['text' => 'emails.ticket.waitok'], $data, function ($message) use ($data, $userMail, $dataFrom) {
                    $message->from($dataFrom['mailFromMail'], $dataFrom['mailFromName']);
                    $message->subject(trans('handler.ticketOkAndWait') . Setting::get('sitename') . ' [CODE:#' . $data['code'] . ']');
                    $message->to($userMail);
                });

            }

            # code...
        }

    }

    /**
     * @param $ticketId
     * @param $authorId
     * @param $description
     */
    public function ticketApprove($ticketId, $authorId, $description = null)
    {
        $setting = config('mail');

        $dataFrom = [
            'mailFromMail' => $setting['from']['address'],
            'mailFromName' => $setting['from']['name'],
        ];
        $data = $this->ticketInfo($ticketId);

        $ticket = Ticket::findOrFail($ticketId);

//Рассылка наблюдающим
        $users = $ticket->watchingUsers()->wherePivot('id', '!=', $authorId)->get();

        foreach ($users as $user) {
            $data['locale'] = $user->profile->lang;
            if ($this->checkUserNotify($user, 'sms', 'approve')) {
                if ($authorId != $user->id) {
                    $messageSms = view('sms.ticket.approve')->with($data)->render();
                    $job = (new SendSMS($user->profile->sms, $messageSms));
                    $this->dispatch($job);
                }

            }
            if ($authorId != $user->id) {
                $webPushMsg = view('sms.ticket.approve')->with($data)->render();
                $jobWebPush = (new SendWebPush($user->email, trans('handler.ticketApprove'), $webPushMsg, url('ticket/' . $data['code'])));
                $this->dispatch($jobWebPush);
            }

            $jobNotifyMenu = (new SendNotifyMenu($authorId, 'approve', $user->id, $ticket->id));
            $this->dispatch($jobNotifyMenu);

            if ($this->checkUserNotify($user, 'pb', 'approve')) {
                if ($authorId != $user->id) {
                    $messagePB = view('pb.ticket.approve')->with($data)->render();
                    $jobPB = (new SendPB($user->profile->pb, trans('handler.ticketApprove2'), $messagePB));
                    $this->dispatch($jobPB);
                }
            }

            if ($authorId != $user->id) {
                $deviceMsgData = view('sms.ticket.approve')->with($data)->render();
                foreach ($user->devices as $uDevice) {

                    $devicePushMsg = (new SendDevicePush($uDevice->device_hash, trans('handler.ticketApprove2'), $deviceMsgData));
                    $this->dispatch($devicePushMsg);

                }
            }

            if ($this->checkUserNotify($user, 'mail', 'approve')) {
                $userMail = $user->profile->email;

                Mail::queue(['text' => 'emails.ticket.approve'], $data, function ($message) use ($data, $userMail, $dataFrom) {
                    $message->from($dataFrom['mailFromMail'], $dataFrom['mailFromName']);
                    $message->subject(trans('handler.ticketApprove2') . Setting::get('sitename') . ' [CODE:#' . $data['code'] . ']');
                    $message->to($userMail);
                });

            }

            # code...
        }

    }

    /**
     * @param $ticketId
     * @param $authorId
     * @param $description
     */
    public function ticketNoapprove($ticketId, $authorId, $description = null)
    {
        $setting = config('mail');

        $dataFrom = [
            'mailFromMail' => $setting['from']['address'],
            'mailFromName' => $setting['from']['name'],
        ];
        $data = $this->ticketInfo($ticketId);

        $ticket = Ticket::findOrFail($ticketId);

//Рассылка наблюдающим
        $users = $ticket->watchingUsers()->wherePivot('id', '!=', $authorId)->get();

        foreach ($users as $user) {
            $data['locale'] = $user->profile->lang;
            if ($this->checkUserNotify($user, 'sms', 'noapprove')) {
                if ($authorId != $user->id) {
                    $messageSms = view('sms.ticket.noapprove')->with($data)->render();
                    $job = (new SendSMS($user->profile->sms, $messageSms));
                    $this->dispatch($job);
                }

            }

            if ($authorId != $user->id) {
                $webPushMsg = view('sms.ticket.noapprove')->with($data)->render();
                $jobWebPush = (new SendWebPush($user->email, trans('handler.ticketNoApprove'), $webPushMsg, url('ticket/' . $data['code'])));
                $this->dispatch($jobWebPush);
            }

            $jobNotifyMenu = (new SendNotifyMenu($authorId, 'noapprove', $user->id, $ticket->id));
            $this->dispatch($jobNotifyMenu);

            if ($this->checkUserNotify($user, 'pb', 'noapprove')) {
                if ($authorId != $user->id) {
                    $messagePB = view('pb.ticket.noapprove')->with($data)->render();
                    $jobPB = (new SendPB($user->profile->pb, trans('handler.ticketNoApprove'), $messagePB));
                    $this->dispatch($jobPB);
                }
            }

            if ($authorId != $user->id) {
                $deviceMsgData = view('sms.ticket.noapprove')->with($data)->render();
                foreach ($user->devices as $uDevice) {

                    $devicePushMsg = (new SendDevicePush($uDevice->device_hash, trans('handler.ticketNoApprove'), $deviceMsgData));
                    $this->dispatch($devicePushMsg);

                }
            }

            if ($this->checkUserNotify($user, 'mail', 'noapprove')) {
                $userMail = $user->profile->email;

                Mail::queue(['text' => 'emails.ticket.noapprove'], $data, function ($message) use ($data, $userMail, $dataFrom) {
                    $message->from($dataFrom['mailFromMail'], $dataFrom['mailFromName']);
                    $message->subject(trans('handler.ticketNoApprove') . Setting::get('sitename') . ' [CODE:#' . $data['code'] . ']');
                    $message->to($userMail);
                });

            }

            # code...
        }

    }

    /**
     * @param $ticketId
     * @param $authorId
     * @param $description
     */
    public function ticketDelete($ticketId, $authorId, $description = null)
    {
        $setting = config('mail');

        $dataFrom = [
            'mailFromMail' => $setting['from']['address'],
            'mailFromName' => $setting['from']['name'],
        ];
        $data = $this->ticketInfo($ticketId);

        $ticket = Ticket::findOrFail($ticketId);
        $initUser = User::findOrFail($authorId);

        $data['initUser'] = $initUser->name;

//Рассылка наблюдающим
        $users = $ticket->watchingUsers()->wherePivot('id', '!=', $authorId)->get();

        foreach ($users as $user) {
            $data['locale'] = $user->profile->lang;
            if ($this->checkUserNotify($user, 'sms', 'delete')) {
                if ($authorId != $user->id) {
                    $messageSms = view('sms.ticket.delete')->with($data)->render();
                    $job = (new SendSMS($user->profile->sms, $messageSms));
                    $this->dispatch($job);
                }

            }

//$webPushMsg=view('sms.ticket.delete')->with($data)->render();
            //$jobWebPush = (new SendWebPush($user->email, 'Заявка удалена', $webPushMsg, url('ticket/'.$data['code'])));
            //$this->dispatch($jobWebPush);

            if ($this->checkUserNotify($user, 'pb', 'delete')) {
                if ($authorId != $user->id) {
                    $messagePB = view('pb.ticket.delete')->with($data)->render();
                    $jobPB = (new SendPB($user->profile->pb, trans('handler.ticketDeleted'), $messagePB));
                    $this->dispatch($jobPB);
                }
            }

            if ($authorId != $user->id) {
                $deviceMsgData = view('sms.ticket.delete')->with($data)->render();
                foreach ($user->devices as $uDevice) {

                    $devicePushMsg = (new SendDevicePush($uDevice->device_hash, trans('handler.ticketDeleted'), $deviceMsgData));
                    $this->dispatch($devicePushMsg);

                }
            }

            if ($this->checkUserNotify($user, 'mail', 'delete')) {
                $userMail = $user->profile->email;

                Mail::queue(['text' => 'emails.ticket.delete'], $data, function ($message) use ($data, $userMail, $dataFrom) {
                    $message->from($dataFrom['mailFromMail'], $dataFrom['mailFromName']);
                    $message->subject(trans('handler.ticketDeleted') . Setting::get('sitename') . ' [CODE:#' . $data['code'] . ']');
                    $message->to($userMail);
                });

            }

            # code...
        }

    }

    /**
     * @param $ticketId
     * @param $authorId
     * @param $description
     */
    public function ticketRestore($ticketId, $authorId, $description = null)
    {
        $setting = config('mail');

        $dataFrom = [
            'mailFromMail' => $setting['from']['address'],
            'mailFromName' => $setting['from']['name'],
        ];
        $data = $this->ticketInfo($ticketId);

        $ticket = Ticket::findOrFail($ticketId);
        $initUser = User::findOrFail($authorId);

        $data['initUser'] = $initUser->name;

//Рассылка наблюдающим
        $users = $ticket->watchingUsers()->wherePivot('id', '!=', $authorId)->get();

        foreach ($users as $user) {
            $data['locale'] = $user->profile->lang;
            if ($this->checkUserNotify($user, 'sms', 'restore')) {
                if ($authorId != $user->id) {
                    $messageSms = view('sms.ticket.restore')->with($data)->render();
                    $job = (new SendSMS($user->profile->sms, $messageSms));
                    $this->dispatch($job);
                }

            }
            if ($authorId != $user->id) {
                $webPushMsg = view('sms.ticket.restore')->with($data)->render();
                $jobWebPush = (new SendWebPush($user->email, trans('handler.ticketRestored'), $webPushMsg, url('ticket/' . $data['code'])));
                $this->dispatch($jobWebPush);
            }

            $jobNotifyMenu = (new SendNotifyMenu($authorId, 'restore', $user->id, $ticket->id));
            $this->dispatch($jobNotifyMenu);

            if ($this->checkUserNotify($user, 'pb', 'restore')) {
                if ($authorId != $user->id) {
                    $messagePB = view('pb.ticket.restore')->with($data)->render();
                    $jobPB = (new SendPB($user->profile->pb, trans('handler.ticketRestored'), $messagePB));
                    $this->dispatch($jobPB);
                }
            }

            if ($authorId != $user->id) {
                $deviceMsgData = view('sms.ticket.restore')->with($data)->render();
                foreach ($user->devices as $uDevice) {

                    $devicePushMsg = (new SendDevicePush($uDevice->device_hash, trans('handler.ticketRestored'), $deviceMsgData));
                    $this->dispatch($devicePushMsg);

                }
            }

            if ($this->checkUserNotify($user, 'mail', 'restore')) {

                $userMail = $user->profile->email;

                Mail::queue(['text' => 'emails.ticket.restore'], $data, function ($message) use ($data, $userMail, $dataFrom) {
                    $message->from($dataFrom['mailFromMail'], $dataFrom['mailFromName']);
                    $message->subject(trans('handler.ticketRestored') . Setting::get('sitename') . ' [CODE:#' . $data['code'] . ']');
                    $message->to($userMail);
                });

            }

            # code...
        }

    }

    /**
     * @param $ticketId
     * @param $authorId
     * @param $description
     */
    public function ticketComment($ticketId, $authorId, $description = null)
    {
        $setting = config('mail');

        $dataFrom = [
            'mailFromMail' => $setting['from']['address'],
            'mailFromName' => $setting['from']['name'],
        ];
        $data = $this->ticketInfo($ticketId);

        $ticket = Ticket::findOrFail($ticketId);
        $initUser = User::findOrFail($authorId);

        $lastComment = $ticket->comments()->orderBy('id', 'desc')->firstOrFail();

        $data['initUser'] = $initUser->name;
        $data['initComment'] = $lastComment->text;

//dd($lastComment->visible_client);

        if ($lastComment->visible_client == 'false') {
            $users = $ticket->watchingUsers()->whereHas('roles', function ($q) {
                $q->where('role', '!=', 'client');
            })->get();

            //dd('ok');
            //$users=$ticket->watchingUsers()->where('id', '!=', $authorId)->get();
        } else {
            $users = $ticket->watchingUsers()->wherePivot('id', '!=', $authorId)->get();
            //dd('no');
        }

//Рассылка наблюдающим
        //исключить тех, кто не клиенты

        foreach ($users as $user) {
            $data['locale'] = $user->profile->lang;
            //array_push($data, ['locale'=>$user->profile->lang]);

            if ($this->checkUserNotify($user, 'sms', 'comment')) {
                if ($authorId != $user->id) {
                    $messageSms = view('sms.ticket.comment')->with($data)->render();
                    $job = (new SendSMS($user->profile->sms, $messageSms));
                    $this->dispatch($job);
                }

            }
            if ($authorId != $user->id) {
                $webPushMsg = view('sms.ticket.comment')->with($data)->render();
                $jobWebPush = (new SendWebPush($user->email, trans('handler.ticketCommented'), $webPushMsg, url('ticket/' . $data['code'])));
                $this->dispatch($jobWebPush);
            }
            if ($authorId != $user->id) {
                $jobNotifyMenu = (new SendNotifyMenu($authorId, 'comment', $user->id, $ticket->id));
                $this->dispatch($jobNotifyMenu);
            }

            if ($this->checkUserNotify($user, 'pb', 'comment')) {
                if ($authorId != $user->id) {
                    $messagePB = view('pb.ticket.comment')->with($data)->render();
                    $jobPB = (new SendPB($user->profile->pb, trans('handler.ticketCommented'), $messagePB));
                    $this->dispatch($jobPB);
                }
            }

            if ($authorId != $user->id) {
                $deviceMsgData = view('sms.ticket.comment')->with($data)->render();
                foreach ($user->devices as $uDevice) {

                    $devicePushMsg = (new SendDevicePush($uDevice->device_hash, trans('handler.ticketCommented'), $deviceMsgData));
                    $this->dispatch($devicePushMsg);

                }
            }

            if ($this->checkUserNotify($user, 'mail', 'comment')) {
//if ($user->NotifyConfig('mail', 'comment')->isEmpty() == false) {
                $userMail = $user->profile->email;

                Mail::queue(['text' => 'emails.ticket.comment'], $data, function ($message) use ($data, $userMail, $dataFrom) {
                    $message->from($dataFrom['mailFromMail'], $dataFrom['mailFromName']);
                    $message->subject(trans('handler.ticketCommented') . Setting::get('sitename') . ' [CODE:#' . $data['code'] . ']');
                    $message->to($userMail);
                });

            }

            # code...
        }

    }

    /**
     * @param $ticketId
     * @param $authorId
     * @param $description
     */
    public function ticketEdit($ticketId, $authorId, $description = null)
    {
        $setting = config('mail');

        $dataFrom = [
            'mailFromMail' => $setting['from']['address'],
            'mailFromName' => $setting['from']['name'],
        ];
        $data = $this->ticketInfo($ticketId);

        $ticket = Ticket::findOrFail($ticketId);
        $initUser = User::findOrFail($authorId);

        $data['initUser'] = $initUser->name;

//Рассылка наблюдающим
        $users = $ticket->watchingUsers()->wherePivot('id', '!=', $authorId)->get();

        foreach ($users as $user) {
            $data['locale'] = $user->profile->lang;
            if ($this->checkUserNotify($user, 'sms', 'edit')) {
                if ($authorId != $user->id) {
                    $messageSms = view('sms.ticket.edit')->with($data)->render();
                    $job = (new SendSMS($user->profile->sms, $messageSms));
                    $this->dispatch($job);
                }

            }
            if ($authorId != $user->id) {
                $webPushMsg = view('sms.ticket.edit')->with($data)->render();
                $jobWebPush = (new SendWebPush($user->email, trans('handler.ticketEdited'), $webPushMsg, url('ticket/' . $data['code'])));
                $this->dispatch($jobWebPush);
            }

            $jobNotifyMenu = (new SendNotifyMenu($authorId, 'edit', $user->id, $ticket->id));
            $this->dispatch($jobNotifyMenu);

            if ($this->checkUserNotify($user, 'pb', 'edit')) {
                if ($authorId != $user->id) {
                    $messagePB = view('pb.ticket.edit')->with($data)->render();
                    $jobPB = (new SendPB($user->profile->pb, trans('handler.ticketEdited'), $messagePB));
                    $this->dispatch($jobPB);
                }
            }

            if ($authorId != $user->id) {
                $deviceMsgData = view('sms.ticket.edit')->with($data)->render();
                foreach ($user->devices as $uDevice) {

                    $devicePushMsg = (new SendDevicePush($uDevice->device_hash, trans('handler.ticketEdited'), $deviceMsgData));
                    $this->dispatch($devicePushMsg);

                }
            }

            if ($this->checkUserNotify($user, 'mail', 'edit')) {
                $userMail = $user->profile->email;

                Mail::queue(['text' => 'emails.ticket.edit'], $data, function ($message) use ($data, $userMail, $dataFrom) {
                    $message->from($dataFrom['mailFromMail'], $dataFrom['mailFromName']);
                    $message->subject(trans('handler.ticketEdited') . Setting::get('sitename') . ' [CODE:#' . $data['code'] . ']');
                    $message->to($userMail);
                });

            }

            # code...
        }

    }

}
