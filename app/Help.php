<?php

namespace zenlix;

use Illuminate\Database\Eloquent\Model;

class Help extends Model
{
    //

    protected $table = 'help';
    protected $morphClass = 'help';

    protected $fillable = [

        'user_id',
        'category_id',
        'description',
        'text',
        'tags',
        'name',
        'access_all',
        'slug',

    ];

    public function category()
    {
        return $this->hasOne('zenlix\HelpCategory', 'id', 'category_id');
    }

    public function author()
    {
        return $this->hasOne('zenlix\User', 'id', 'user_id');
    }

    public function groups() // those who follow me

    {
        //$this->belongsToMany('App\Role', 'user_roles', 'user_id', 'role_id');
        return $this->belongsToMany('zenlix\Groups', 'help_access', 'help_id', 'group_id')->withTimestamps();
    }

    public function files()
    {
        return $this->morphMany('zenlix\Files', 'target');
    }

}
