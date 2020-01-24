<?php

namespace zenlix;

use Illuminate\Database\Eloquent\Model;

class HelpCategory extends Model
{
    //

    protected $table = 'help_category';

    protected $fillable = [

        'user_id',
        'parent_id',
        'sort_id',
        'name',
        'group_id'

    ];

    public function help()
    {
        return $this->hasMany('zenlix\Help', 'category_id', 'id');
    }

}
