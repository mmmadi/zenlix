<?php

namespace zenlix;

use Illuminate\Database\Eloquent\Model;

class TicketSlaLog extends Model
{
    //
    protected $table = 'ticket_sla_log';

    protected $fillable = [

        'ticket_id',
        'reaction_time',
        'work_time',
        'deadline_time',

    ];

}
