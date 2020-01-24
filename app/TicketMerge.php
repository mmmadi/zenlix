<?php

namespace zenlix;

use Illuminate\Database\Eloquent\Model;

class TicketMerge extends Model
{
    //
    protected $table = 'ticket_merge';
    protected $fillable = [

        'author_id',
        'parent_id',
        'child_id',

    ];

    /**
     * @return mixed
     */
    public function author()
    {
        return $this->hasOne('zenlix\User', 'id', 'author_id')->withTrashed();
    }

    /**
     * @return mixed
     */
    public function ticketParent()
    {
        return $this->hasOne('zenlix\Ticket', 'id', 'parent_id');
    }

    /**
     * @return mixed
     */
    public function ticketChild()
    {
        return $this->hasOne('zenlix\Ticket', 'id', 'child_id');
    }

}
