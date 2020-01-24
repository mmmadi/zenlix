<?php

namespace zenlix;

use Illuminate\Database\Eloquent\Model;

class TicketSubj extends Model
{
    //

    protected $table = 'ticket_subj';

    protected $fillable = [
        'name',
    ];

}
