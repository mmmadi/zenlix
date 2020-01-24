<?php

namespace zenlix;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Messages extends Model
{
    //
    use SoftDeletes;

    protected $table = 'messages';
    protected $morphClass = 'message';
    protected $dates = ['deleted_at'];

    protected $fillable = [

        'subject',
        'text',
        'draft_flag',
        'read_flag',
        'star_flag',
        'from_user_id',
        'to_user_id',
        'message_urlhash',

    ];

    public function fromUser()
    {
        return $this->hasOne('zenlix\User', 'id', 'from_user_id')->withTrashed();
    }

    public function toUser()
    {
        return $this->hasOne('zenlix\User', 'id', 'to_user_id')->withTrashed();
    }

    public function files()
    {
        return $this->morphMany('zenlix\Files', 'target');
    }

}
