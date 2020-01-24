<?php

namespace zenlix;

use Illuminate\Database\Eloquent\Model;

class UserTicketConf extends Model
{
    //user_ticket_conf

    protected $table = 'user_ticket_conf';

    protected $fillable = [
        'user_id',
        'ticket_form_id',
        'conf_params',
        'group_conf_id',

    ];

    public function groupTicket()
    {
        return $this->hasOne('zenlix\GroupTicketConf', 'id', 'group_conf_id');
    }

}
