<?php

namespace zenlix;

use Illuminate\Database\Eloquent\Model;

class ChatRequest extends Model
{
    //

    protected $table = 'chat_request';
    protected $fillable = [
        'user_id',
        'chatWith_id',
    ];

    public function author()
    {
        return $this->hasOne('zenlix\User', 'id', 'user_id')->withTrashed();
    }

}
