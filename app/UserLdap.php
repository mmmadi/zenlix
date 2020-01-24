<?php

namespace zenlix;

use Illuminate\Database\Eloquent\Model;

class UserLdap extends Model
{
    //
    protected $table = 'user_ldap';
    protected $primaryKey = 'user_id';

    protected $fillable = [
        'status',
        'user_id',
        'login',
        'authType',
    ];

}
