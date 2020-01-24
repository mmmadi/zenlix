<?php

namespace zenlix;

use Illuminate\Database\Eloquent\Model;

class TicketSla extends Model
{
    //ticket_sla_plans
    protected $table = 'ticket_sla_plans';

    protected $fillable = [
        'name',

        'reaction_time_def',
        'reaction_time_low_prio',
        'reaction_time_high_prio',
        'work_time_def',
        'work_time_low_prio',
        'work_time_high_prio',
        'deadline_time_def',
        'deadline_time_low_prio',
        'deadline_time_high_prio',

    ];
}
