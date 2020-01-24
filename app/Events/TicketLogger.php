<?php

namespace zenlix\Events;

use Illuminate\Queue\SerializesModels;
use zenlix\Events\Event;

class TicketLogger extends Event
{
    use SerializesModels;

    public $ticketId;
    public $userId;
    public $actionType;
    public $description;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($ticketId, $userId, $actionType, $description = null)
    {
        $this->ticketId = $ticketId;
        $this->userId = $userId;
        $this->actionType = $actionType;
        $this->description = $description;

    }

/*public function getData()
{
return [
'first' => $this->first,
'second' => $this->second,
'third' => $this->third,
];
}*/

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
