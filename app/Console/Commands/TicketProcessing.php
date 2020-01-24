<?php

namespace zenlix\Console\Commands;

use Carbon\Carbon;
use Event;
use Illuminate\Console\Command;
use Mail;
use Setting;
use Storage;
use zenlix\Events\TicketLogger;
use zenlix\Events\TicketNotify;
use zenlix\Files;
use zenlix\Ticket;
use zenlix\TicketNotifyStatus;
use zenlix\TicketPlanner;
use zenlix\TicketPlannerList;
use zenlix\TicketPlannerLog;
use zenlix\TicketSlaLog;

class TicketProcessing extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ticket:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for move tickets to archive, delete old and run planners...';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function update2Archive()
    {
//Setting::set('ticket.days2arch', $request->ticketDays2arch);
        //Setting::set('ticket.days2del', $request->ticketDays2Del);

        $days2Archive = Setting::get('ticket.days2arch', '2');
        $days2Archive = $days2Archive * 24 * 60 * 60;

        $tickets = Ticket::where('status', 'success')->get();

        foreach ($tickets as $ticket) {
            $dt = Carbon::now();
            $tc = Carbon::parse($ticket->updated_at);
            $diffSec = $dt->diffInSeconds($tc);

//dd($diffSec.' >= '.$days2Archive);

            if ($diffSec >= $days2Archive) {
                $ticket->update([
                    'status' => 'arch',
                ]);
                Event::fire(new TicketLogger($ticket->id, '1', 'arch', null));

            }

            # code...
        }

    }

    public function showTicketSla($ticketId)
    {

        $ticket = Ticket::findOrFail($ticketId);

        $prio = $ticket->prio;

        $slaDeadline = null;
        if ($prio == "low") {
            $slaDeadline = $ticket->sla->deadline_time_low_prio;
        } else if ($prio == "normal") {
            $slaDeadline = $ticket->sla->deadline_time_def;
        } else if ($prio == "high") {
            $slaDeadline = $ticket->sla->deadline_time_high_prio;
        }

        $dt = Carbon::parse($ticket->created_at);
        $slaDeadline = $dt->addSeconds($slaDeadline);
        $slaDeadline->toDateTimeString();

        return $slaDeadline;
    }

    public function ticketInfo($ticketID)
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
            'code' => $ticket->code,
            'ticket' => $ticket,
            'author' => $ticket->authorUser->name,
            'authorID' => $ticket->authorUser->id,
            'clients' => implode(' ,', $clients),
            'targets' => $targets,
            'canReply' => true,
//'msg'=>$ticket->text
        ];

        return $data;

    }

    public function showMailDeadline($ticket_id)
    {
        $setting = config('mail');

        $dataFrom = [
            'mailFromMail' => $setting['from']['address'],
            'mailFromName' => $setting['from']['name'],
        ];
        $data = $this->ticketInfo($ticket_id);
        $ticket = Ticket::findOrFail($ticket_id);

        $users = $ticket->watchingUsers;

        foreach ($users as $user) {
            $userMail = $user->profile->email;
            Mail::queue(['text' => 'emails.ticket.deadline'], $data, function ($message) use ($data, $userMail, $dataFrom) {
                $message->from($dataFrom['mailFromMail'], $dataFrom['mailFromName']);
                $message->subject('Истекает срок выполнения заявки - ' . Setting::get('sitename') . ' [CODE:#' . $data['code'] . ']');
                $message->to($userMail);
            });

        }

    }

    public function showMailOverime($ticket_id)
    {
        $setting = config('mail');

        $dataFrom = [
            'mailFromMail' => $setting['from']['address'],
            'mailFromName' => $setting['from']['name'],
        ];
        $data = $this->ticketInfo($ticket_id);
        $ticket = Ticket::findOrFail($ticket_id);

        $users = $ticket->watchingUsers;

        foreach ($users as $user) {
            $userMail = $user->profile->email;
            Mail::queue(['text' => 'emails.ticket.overtime'], $data, function ($message) use ($data, $userMail, $dataFrom) {
                $message->from($dataFrom['mailFromMail'], $dataFrom['mailFromName']);
                $message->subject('Истёк срок выполнения заявки - ' . Setting::get('sitename') . ' [CODE:#' . $data['code'] . ']');
                $message->to($userMail);
            });

        }

    }

    public function update2Destroy()
    {

        $days2Destroy = Setting::get('ticket.days2del', '2');
        $days2Destroy = $days2Destroy * 24 * 60 * 60;

        $tickets = Ticket::onlyTrashed()->get();
        foreach ($tickets as $ticket) {

            $dt = Carbon::now();
            $tc = Carbon::parse($ticket->updated_at);
            $diffSec = $dt->diffInSeconds($tc);

            if ($diffSec >= $days2Destroy) {

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

            }

        }

    }

    public function storeNotifyDeadline()
    {
        //скоро будет просрочено

        if (Setting::get('ticket.deadlineNotifyStatus') == "true") {

            $days2Notify = Setting::get('ticket.deadlineNotify');
            $days2Notify = $days2Notify * 24 * 60 * 60;

            $tickets = Ticket::whereIn('status', ['free', 'lock', 'waitsuccess'])
                ->where(function ($query) {
                    return $query
                        ->whereNotNull('deadline_time')
                        ->orWhereNotNull('sla_id')
                    ;
                })
                ->get();

            foreach ($tickets as $ticket) {
                $TicketNotifyStatus = TicketNotifyStatus::where('ticket_id', $ticket->id)->firstOrCreate([
                    'ticket_id' => $ticket->id,
                ]);

                if ($TicketNotifyStatus->deadline_flag == 'false') {

                    if ($ticket->sla_id != null) {

                        if ($TicketNotifyStatus->deadline_flag == 'false') {

//узнать время SLA

                            $ticketSlaDeadline = $this->showTicketSla($ticket->id);

                            $dt = Carbon::now();
                            $tc = Carbon::parse($ticketSlaDeadline);

                            $diffSec = $dt->diffInSeconds($tc);

                            if ($diffSec <= $days2Notify) {

                                //отправить сообщение что скоро будет просрочено
                                $this->showMailDeadline($ticket->id);

                                $TicketNotifyStatus->update([
                                    'deadline_flag' => 'true',
                                ]);

                            }
                        }

                    } else if ($ticket->deadline_time != null) {

                        if ($TicketNotifyStatus->deadline_flag == 'false') {

                            $dt = Carbon::now();
                            $tc = Carbon::parse($ticket->deadline_time);
                            $diffSec = $dt->diffInSeconds($tc);
                            if ($diffSec <= $days2Notify) {

                                $this->showMailDeadline($ticket->id);
                                //отправить сообщение что скоро будет просрочено

                                $TicketNotifyStatus->update([
                                    'deadline_flag' => 'true',
                                ]);

                            }

                        }

                    }

                }

/*дата окончания - сейчас = осталось
если осталось <= $days2Notify то нотификация

$dt = Carbon::now();
$tc = Carbon::parse($ticket->updated_at);
$diffSec = $dt->diffInSeconds($tc);*/

            }

        }

    }

    public function storeNotifyOvertime()
    {
        //уже просрочено

        if (Setting::get('ticket.overtimeNotifyStatus') == "true") {

            $tickets = Ticket::whereIn('status', ['free', 'lock', 'waitsuccess'])
                ->where(function ($query) {
                    return $query
                        ->whereNotNull('deadline_time')
                        ->orwhereNotNull('sla_id');

                })
                ->get();

            foreach ($tickets as $ticket) {

                $TicketNotifyStatus = TicketNotifyStatus::where('ticket_id', $ticket->id)->firstOrCreate([
                    'ticket_id' => $ticket->id,
                ]);

                if ($TicketNotifyStatus->overtime_flag == 'false') {

                    if ($ticket->sla_id != null) {

                        $ticketSlaOvertime = $this->showTicketSla($ticket->id);
                        $dt = Carbon::now();
                        $tc = Carbon::parse($ticketSlaOvertime);

                        $diffSec = $dt->diffInSeconds($tc, false);

                        if ($diffSec < 0) {
                            $this->showMailOverime($ticket->id);
                            $TicketNotifyStatus->update([
                                'overtime_flag' => 'true',
                            ]);

                        }

                    } else if ($ticket->deadline_time != null) {

                        $dt = Carbon::now();
                        $tc = Carbon::parse($ticket->deadline_time);
                        $diffSec = $dt->diffInSeconds($tc, false);

                        if ($diffSec < 0) {
                            $this->showMailOverime($ticket->id);
                            $TicketNotifyStatus->update([
                                'overtime_flag' => 'true',
                            ]);

                        }

                    }

/*        */

                }

            }

        }

/*    проверить все заявки.
если есть deadline, и осталось меньше 2 дней, то уведомить!
если есть SLA и осталось меньше 2 дней, то уведомить!

если просрочена заявка то уведомить всех!
//поставить флаг просрочена*/

/*ticket_notify_status
ticket_id
overtime_flag
deadline_flag*/

    }

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

    public function createPlannerTicketProcess($ticket_id, $planner_id)
    {

        $ticket = TicketPlannerList::where('id', $ticket_id)->firstOrFail();

        $new_ticket = $ticket->replicate();
        $new_ticket->code = $this->ticketCodeGenerate();
        $new_ticket->planner_flag = false;
        $new_ticket->urlhash = str_random(10);
        $new_ticket->save();

        foreach ($ticket->targetUsers as $targetUser) {
            $new_ticket->targetUsers()->attach($targetUser);
        }

        foreach ($ticket->clients as $client) {
            $new_ticket->clients()->attach($client);
        }

        foreach ($ticket->watchingUsers as $watchingUser) {
            $new_ticket->watchingUsers()->attach($watchingUser);
        }

        if ($ticket->fields->count() > 0) {
            foreach ($ticket->fields as $field) {
                # code...
                $fh = 'field' . $field->id;

                if ($field->f_type == 'multiselect') {
                    $new_ticket->fields()->attach($field->id, ['field_data' => implode(',', $field->pivot->field_data)]);
                } else {
                    $new_ticket->fields()->attach($field->id, ['field_data' => $field->pivot->field_data]);

                }

            }
        }

        foreach ($ticket->files as $file) {
            Files::create([

                'user_id' => $file->user_id,
                'target_id' => $new_ticket->id,
                'target_type' => 'ticket',
                'name' => $file->name,
                'hash' => $file->hash,
                'mime' => $file->mime,
                'extension' => $file->extension,
                'status' => 'success',
                'image' => $file->image,

            ]);
        }

        Event::fire(new TicketLogger($new_ticket->id, $new_ticket->author_id, 'create'));
        Event::fire(new TicketNotify($new_ticket->id, $new_ticket->author_id, 'create'));

        if ($new_ticket->sla_id != null) {
            TicketSlaLog::create([
                'ticket_id' => $new_ticket->id,
            ]);
        }

//dublicate model

        TicketPlannerLog::create([

            'ticket_id' => $new_ticket->id,
            'planner_id' => $planner_id,

        ]);

    }

    public function createPlannerTicket()
    {

        $ticketPlanners = TicketPlanner::all();

        foreach ($ticketPlanners as $ticketPlanner) {
            # code...

            //$lastActionDate = $ticketPlanner->log->created_at;
            if ($ticketPlanner->log->count() > 0) {
                $lastActionDateCollect = $ticketPlanner->log()->orderBy('id', 'desc')->first();
                //dd($lastActionDateCollect);
                $lastActionDate = $lastActionDateCollect->created_at;
            } else {
                $lastActionDate = null;
            }

            $startWork = Carbon::parse($ticketPlanner->startWork);
            $endWork = Carbon::parse($ticketPlanner->endWork);
            $now = Carbon::now();

            //если сегодня дата работы скрипта
            //Carbon::now()->between($startWork, $endWork);

            //если это в этот период можно выполнять задачу?
            if (Carbon::now()->between($startWork, $endWork)) {
                //если период - день
                if ($ticketPlanner->period == "day") {

//dd($lastActionDate);

                    ($lastActionDate == null) ? $diffDays = 0 : $diffDays = $now->diffInDays(Carbon::parse($lastActionDate));

                    //если больше одного дня прошло

                    //dd($lastActionDate);

                    if (($diffDays > 0) || ($lastActionDate == null)) {

                        //dd('yeep!');

                        $diffMinutes = $now->diffInMinutes(Carbon::createFromTime($ticketPlanner->dayHour, $ticketPlanner->dayMinute, 0));
                        //dd($diffMinutes);

                        //если время выполнения настало (разница в 15 минут)
                        if ($diffMinutes < 15) {
//dd($ticketPlanner->id);
                            //fire create ticket
                            $this->createPlannerTicketProcess($ticketPlanner->ticket_id, $ticketPlanner->id);
                        }
                    }
                }

                //если период - неделя
                else if ($ticketPlanner->period == "week") {

                    ($lastActionDate == null) ? $diffDays = 0 : $diffDays = $now->diffInDays(Carbon::parse($lastActionDate));

                    //$diffDays=$now->diffInDays(Carbon::parse($lastActionDate));
                    //если прошла неделя
                    if (($diffDays > 6) || ($lastActionDate == null)) {

                        //если сегодня этот день недели
                        if ($ticketPlanner->weekDay == $now->dayOfWeek) {
                            $diffMinutes = $now->diffInMinutes(Carbon::createFromTime($ticketPlanner->dayHour, $ticketPlanner->dayMinute, 0));
                            //если время выполнения настало (разница в 15 минут)
                            if ($diffMinutes < 15) {

                                //fire create ticket
                                $this->createPlannerTicketProcess($ticketPlanner->ticket_id, $ticketPlanner->id);
                            }
                        }

                    }

                }
                //если период месяц
                else if ($ticketPlanner->period == "month") {

                    ($lastActionDate == null) ? $diffDays = 0 : $diffDays = $now->diffInDays(Carbon::parse($lastActionDate));

                    // $diffDays=$now->diffInDays(Carbon::parse($lastActionDate));
                    //если прошёл месяц
                    if (($diffDays > 29) || ($lastActionDate == null)) {
                        //если сегодня день месяца
                        if ($ticketPlanner->monthDay == $now->day) {

                            $diffMinutes = $now->diffInMinutes(Carbon::createFromTime($ticketPlanner->dayHour, $ticketPlanner->dayMinute, 0));
                            //если время выполнения настало (разница в 15 минут)
                            if ($diffMinutes < 15) {

                                //fire create ticket
                                $this->createPlannerTicketProcess($ticketPlanner->ticket_id, $ticketPlanner->id);
                            }

                        }
                    }

                }

            }

        }

/*
'name',
'ticket_id',
'author_id',
'period',
'dayHour',
'dayMinute',
'weekDay',
'monthDay',
'startWork',
'endWork',
 */

    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $this->update2Archive();
        $this->update2Destroy();

        $this->storeNotifyOvertime();
        $this->storeNotifyDeadline();

        $this->createPlannerTicket();

        //перенести в архив
        //удалить заявку
        $this->comment('Successfull!');
    }
}
