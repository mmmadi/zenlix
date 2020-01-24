<?php

namespace zenlix;

use Illuminate\Database\Eloquent\Model;

class UserFields extends Model
{
    //

    protected $table = 'user_fields_struct';

    protected $fillable = [

        'id',
        'status',
        'visible_client',
        'field_type',
        'name',
        'placeholder',
        'value',

    ];

}
