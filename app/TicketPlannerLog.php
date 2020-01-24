<?php

namespace zenlix;

use Illuminate\Database\Eloquent\Model;

class TicketPlannerLog extends Model
{
    //
    protected $table = 'ticket_planner_log';

    protected $fillable = [

        'ticket_id',
        'planner_id',

    ];

    public function ticket()
    {
        return $this->hasOne('zenlix\Ticket', 'id', 'ticket_id')->withTrashed();
    }

}
