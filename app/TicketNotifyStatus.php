<?php

namespace zenlix;

use Illuminate\Database\Eloquent\Model;

class TicketNotifyStatus extends Model
{
    //
    protected $table = 'ticket_notify_status';

    protected $fillable = [
        'ticket_id',
        'deadline_flag',
        'overtime_flag',
    ];

}
