<?php

namespace zenlix;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    //
    protected $table = 'user_roles';
    protected $primaryKey = 'user_id';

    protected $fillable = [
        'id',
        'user_id',
        'role',
    ];

}
