<?php

namespace zenlix;

use Illuminate\Database\Eloquent\Model;

class UsersNotifyConfig extends Model
{
    //
    protected $table = 'user_notify';
    protected $fillable = ['user_id', 'target', 'type'];

}
