<?php

namespace zenlix;

use Illuminate\Database\Eloquent\Model;

class Calendar extends Model
{
    //
    protected $table = 'calendar';

    protected $dates = ['dtStart', 'dtStop'];

    protected $fillable = [

        'title',
        'dtStart',
        'dtStop',
        'allday',
        'backgroundColor',
        'borderColor',
        'description',
        'user_id',
        'uniq_hash',
        'personal',

    ];

    public function user()
    {
        return $this->hasOne('zenlix\User', 'id', 'user_id')->withTrashed();
    }

    public function groups() // those who follow me

    {
        //$this->belongsToMany('App\Role', 'user_roles', 'user_id', 'role_id');
        return $this->belongsToMany('zenlix\Groups', 'calendar_group', 'event_id', 'group_id')->withTimestamps();
    }

}
