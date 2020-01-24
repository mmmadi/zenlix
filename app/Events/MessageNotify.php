<?php

namespace zenlix\Events;

use zenlix\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MessageNotify extends Event
{
    use SerializesModels;

public $messageID;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($messageID)
    {
        //
        $this->messageID=$messageID;
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
