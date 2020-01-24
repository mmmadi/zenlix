<?php
namespace zenlix\Events;

use zenlix\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TicketNotify extends Event
{
    use SerializesModels;
    
    public $ticketId;
    public $authorId;
    public $actionType;
    public $description;
    
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($ticketId, $authorId, $actionType, $description = null) {
        
        //
        
        $this->ticketId = $ticketId;
        $this->authorId = $authorId;
        $this->actionType = $actionType;
        $this->description = $description;
        
        //dd($actionType);
        
        
    }
    
    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn() {
        return [];
    }
}
