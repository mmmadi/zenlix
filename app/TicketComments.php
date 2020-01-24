<?php

namespace zenlix;

use Illuminate\Database\Eloquent\Model;

class TicketComments extends Model
{
    //
    protected $morphClass = 'ticketComment';
    protected $table = 'ticket_comments';
    //protected $touches = ['ticket'];

    protected $fillable = [
        'text',
        'author_id',
        'ticket_id',
        'visible_client',
        'urlhash',

    ];

    public function author()
    {
        return $this->hasOne('zenlix\User', 'id', 'author_id')->withTrashed();
    }

    public function files()
    {
        return $this->morphMany('zenlix\Files', 'target');
    }

}
