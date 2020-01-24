<?php

namespace zenlix\Events;

use zenlix\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UserNotify extends Event
{
    use SerializesModels;

    public $userId;
    public $authorId;
    public $actionType;
    public $description;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($userId, $pass, $actionType)
    {
        //

        $this->userId = $userId;
        $this->pass = $pass;
        $this->actionType = $actionType;
        //$this->description = $description;

        //dd($actionType);

    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
