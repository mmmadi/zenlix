<?php

namespace zenlix\Jobs;

use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Redis;
use zenlix\Jobs\Job;
use zenlix\NotificationMenu;
use zenlix\User;

class SendNotifyMenu extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    protected $authorID, $action, $userID, $ticketID;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($authorID, $action, $userID, $ticketID)
    {
        //

        $this->action = $action;
        $this->userID = $userID;
        $this->authorID = $authorID;
        $this->ticketID = $ticketID;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //

        $user = User::find($this->userID);

        if ($this->userID != $this->authorID) {

            NotificationMenu::create([
                'user_id' => $this->userID,
                'author_id' => $this->authorID,
                'ticket_id' => $this->ticketID,
                'action' => $this->action,
            ]);

            Redis::publish('ZEN-channel', json_encode([

                'msgType' => 'NotifyMenuMsg',
                'login' => $user->email,

            ]));
        }

    }
}
