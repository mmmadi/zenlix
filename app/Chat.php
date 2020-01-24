<?php

namespace zenlix;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    //
    protected $table = 'chat';
    protected $fillable = [
        'text',
        'read_flag',
        'from_user_id',
        'to_user_id',
    ];
    public function fromUser()
    {
        return $this->hasOne('zenlix\User', 'id', 'from_user_id')->withTrashed();
    }

    public function toUser()
    {
        return $this->hasOne('zenlix\User', 'id', 'to_user_id')->withTrashed();
    }
}
