<?php

namespace zenlix;

use Illuminate\Database\Eloquent\Model;

class TicketLog extends Model
{
    //

    protected $table = 'ticket_logs';

    protected $fillable = [
        'author_id',
        'ticket_id',
        'action',
        'description',
    ];

    public function author()
    {
        return $this->hasOne('zenlix\User', 'id', 'author_id')->withTrashed();
    }

    public function ticket()
    {
        return $this->hasOne('zenlix\Ticket', 'id', 'ticket_id');
    }

}
