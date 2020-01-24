<?php

namespace zenlix;

use Illuminate\Database\Eloquent\Model;

class UserFieldsData extends Model
{
    //

    protected $table = 'user_fields_data';

    protected $fillable = [

        'id',
        'user_field_id',
        'user_id',
        'field_data',

    ];

}
