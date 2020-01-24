<?php

namespace zenlix;

use Illuminate\Database\Eloquent\Model;

class HelpAccess extends Model
{
    //

    protected $table = 'help_access';

    protected $fillable = [

        'help_id',
        'group_id',

    ];

    public function help()
    {
        return $this->hasMany('zenlix\Help', 'id', 'help_id');
    }

}
