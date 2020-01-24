<?php

namespace zenlix\Handlers\Events;

use zenlix\Events\TicketLogger;
use zenlix\TicketLog;

class TicketLoggerHandler
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
    public function handle(TicketLogger $event)
    {

        $actionType = $event->actionType;
        $ticketId = $event->ticketId;
        $userId = $event->userId;
        $description = $event->description;

        return $this->$actionType($ticketId, $userId, $description);

    }

    public function create($ticketId, $userId, $description = null)
    {
        //dd('create');

        TicketLog::create([
            'ticket_id' => $ticketId,
            'action' => 'create',
            'author_id' => $userId,
        ]);

    }

    public function refer($ticketId, $userId, $description = null)
    {
        TicketLog::create([
            'ticket_id' => $ticketId,
            'action' => 'refer',
            'author_id' => $userId,
            'description' => $description,
        ]);
    }

    public function comment($ticketId, $userId, $description = null)
    {

        TicketLog::create([
            'ticket_id' => $ticketId,
            'action' => 'comment',
            'author_id' => $userId,
        ]);
    }

    public function lock($ticketId, $userId, $description = null)
    {
        TicketLog::create([
            'ticket_id' => $ticketId,
            'action' => 'lock',
            'author_id' => $userId,
        ]);
    }

    public function lockNext($ticketId, $userId, $description = null)
    {
        TicketLog::create([
            'ticket_id' => $ticketId,
            'action' => 'lockNext',
            'author_id' => $userId,
        ]);
    }

    public function unlock($ticketId, $userId, $description = null)
    {
        TicketLog::create([
            'ticket_id' => $ticketId,
            'action' => 'unlock',
            'author_id' => $userId,
        ]);
    }

    public function ok($ticketId, $userId, $description = null)
    {
        TicketLog::create([
            'ticket_id' => $ticketId,
            'action' => 'ok',
            'author_id' => $userId,
        ]);
    }

    public function unok($ticketId, $userId, $description = null)
    {
        TicketLog::create([
            'ticket_id' => $ticketId,
            'action' => 'unok',
            'author_id' => $userId,
        ]);
    }

    public function unokNext($ticketId, $userId, $description = null)
    {
        TicketLog::create([
            'ticket_id' => $ticketId,
            'action' => 'unokNext',
            'author_id' => $userId,
        ]);
    }

    public function arch($ticketId, $userId, $description = null)
    {
        TicketLog::create([
            'ticket_id' => $ticketId,
            'action' => 'arch',
            'author_id' => $userId,
        ]);
    }

    public function edit($ticketId, $userId, $description = null)
    {
        TicketLog::create([
            'ticket_id' => $ticketId,
            'action' => 'edit',
            'author_id' => $userId,
        ]);
    }

    public function delete($ticketId, $userId, $description = null)
    {
        TicketLog::create([
            'ticket_id' => $ticketId,
            'action' => 'delete',
            'author_id' => $userId,
        ]);
    }

    public function restore($ticketId, $userId, $description = null)
    {
        TicketLog::create([
            'ticket_id' => $ticketId,
            'action' => 'restore',
            'author_id' => $userId,
        ]);
    }
    //ticketWaitOk
    public function waitok($ticketId, $userId, $description = null)
    {
        TicketLog::create([
            'ticket_id' => $ticketId,
            'action' => 'waitok',
            'author_id' => $userId,
        ]);
    }

    public function approve($ticketId, $userId, $description = null)
    {
        TicketLog::create([
            'ticket_id' => $ticketId,
            'action' => 'approve',
            'author_id' => $userId,
        ]);
    }

    public function noapprove($ticketId, $userId, $description = null)
    {
        TicketLog::create([
            'ticket_id' => $ticketId,
            'action' => 'noapprove',
            'author_id' => $userId,
        ]);
    }

}
