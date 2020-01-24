<?php

namespace zenlix;

use Illuminate\Database\Eloquent\Model;

class TicketAdv extends Model
{
    //

    protected $table = 'ticket_fields_structure';

    protected $fillable = [
        'name',
        'f_type',
        'required',
        'field_name',
        'field_value',
        'field_placeholder',
        'field_hash',

    ];

}
