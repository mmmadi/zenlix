<?php

namespace zenlix;

use Illuminate\Database\Eloquent\Model;

class GroupTicketConf extends Model
{
    protected $table = 'group_ticket_conf';

    protected $fillable = [
        'group_id',
        'ticket_form_id',
        'status',
        'group_type',

    ];

}
