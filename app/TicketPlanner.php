<?php

namespace zenlix;

use Illuminate\Database\Eloquent\Model;

class TicketPlanner extends Model
{
    //

    protected $table = 'ticket_planner';

    protected $dates = ['startWork', 'endWork'];

    protected $fillable = [

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

    ];

    public function log()
    {

        return $this->hasMany('zenlix\TicketPlannerLog', 'planner_id', 'id');

    }

}
