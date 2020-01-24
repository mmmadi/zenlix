<?php

namespace zenlix;

use Illuminate\Database\Eloquent\Model;

class NotificationMenu extends Model
{
    //

    protected $table = 'notification_menu';

    protected $fillable = [
        'user_id',
        'author_id',
        'ticket_id',
        'action',

    ];

    public function author()
    {
        return $this->hasOne('zenlix\User', 'id', 'author_id')->withTrashed();
    }

    public function ticket()
    {
        return $this->hasOne('zenlix\Ticket', 'id', 'ticket_id')->withTrashed();
    }

}
