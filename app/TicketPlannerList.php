<?php

namespace zenlix;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

//use Illuminate\Database\Eloquent\TicketPlanner;

class TicketPlannerList extends Model
{
    //

    use SoftDeletes;

    //use TicketPlanner;

    protected $table = 'tickets';
    protected $morphClass = 'ticket';
    protected $dates = ['deleted_at'];

    protected $fillable = [

        'author_id',
        'code',
//'client_id',
        'prio',
        'text',
        'subject',
        'tags',
        'urlhash',
//'number',
        'sla_id',
        'target_group_id',
        'deadline_time',
        'inspect_after_ok',
        'individual_ok',
        'status',
        'overtime',
        'planner_flag',
        'merge_flag',

    ];
    public function scopePlanner($query)
    {
        return $this->where('planner_flag', 'true');
    }

    public function planners()
    {
        return $this->hasOne('zenlix\TicketPlanner', 'ticket_id', 'id');
    }

/*public function newQuery($excludeDeleted = true)
{
return parent::newQuery()->where('planner_flag', 'false');
}
 */

/*
public function scopePlanner($query,  $type)
{
return $this->where('planner_flag', $type);
}*/

    public function authorUser()
    {
        return $this->hasOne('zenlix\User', 'id', 'author_id')->withTrashed();
    }

    public function slaLog()
    {
        return $this->hasOne('zenlix\TicketSlaLog', 'ticket_id', 'id');
    }

    public function sla()
    {
        return $this->hasOne('zenlix\TicketSla', 'id', 'sla_id');
    }

    public function TicketNotifyStatus()
    {
        return $this->hasOne('zenlix\TicketNotifyStatus', 'ticket_id', 'id');
    }

    public function targetGroup()
    {
        return $this->hasOne('zenlix\Groups', 'id', 'target_group_id');
    }

    public function comments()
    {
        return $this->hasMany('zenlix\TicketComments', 'ticket_id', 'id');
    }

    public function logs()
    {
        return $this->hasMany('zenlix\TicketLog', 'ticket_id', 'id');
    }

    public function files()
    {
        return $this->morphMany('zenlix\Files', 'target');
    }

    public function clients() // those who follow me

    {
        //$this->belongsToMany('App\Role', 'user_roles', 'user_id', 'role_id');
        return $this->belongsToMany('zenlix\User', 'ticket_clients', 'ticket_id', 'user_id')->withTimestamps()->withTrashed();
    }

    public function targetUsers() // those who follow me

    {
        //$this->belongsToMany('App\Role', 'user_roles', 'user_id', 'role_id');
        return $this->belongsToMany('zenlix\User', 'ticket_target_user', 'ticket_id', 'user_id')->withTimestamps()->withPivot('individual_ok_status', 'individual_lock_status')->withTrashed();
    }

    public function watchingUsers() // those who follow me

    {
        //$this->belongsToMany('App\Role', 'user_roles', 'user_id', 'role_id');
        return $this->belongsToMany('zenlix\User', 'ticket_watching', 'ticket_id', 'user_id')->withTimestamps()->withTrashed();
    }

    public function fields() // those who follow me

    {
        //$this->belongsToMany('App\Role', 'user_roles', 'user_id', 'role_id');
        return $this->belongsToMany('zenlix\TicketAdv', 'ticket_fields_data', 'ticket_id', 'ticket_field_id')->withTimestamps()->withPivot('field_data', 'ticket_field_id');
    }

}
