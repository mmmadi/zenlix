<?php

namespace zenlix;

use Illuminate\Database\Eloquent\Model;

class TicketForms extends Model
{
    //
    protected $table = 'ticket_forms';
    public $timestamps = true;
    protected $fillable = [

        'name',
        'client_field',
        'target_field',
        'prio',
        'subj_field',
        'upload_files',
        'upload_files_types',
        'upload_files_count',
        'upload_files_size',
        'deadline_field',
        'watching_field',
        'individual_ok_field',
        'check_after_ok',
        'create_user',

    ];

    public function clientGroups() // those who follow me

    {
        //$this->belongsToMany('App\Role', 'user_roles', 'user_id', 'role_id');
        return $this->belongsToMany('zenlix\Groups', 'form_client_groups', 'form_id', 'client_group_id')->withTimestamps();
    }

//form_target_groups

    public function targetGroups() // those who follow me

    {
        //$this->belongsToMany('App\Role', 'user_roles', 'user_id', 'role_id');
        return $this->belongsToMany('zenlix\Groups', 'form_target_groups', 'form_id', 'target_group_id')->withTimestamps();
    }

//form_target_users
    public function targetUsers() // those who follow me

    {
        //$this->belongsToMany('App\Role', 'user_roles', 'user_id', 'role_id');
        return $this->belongsToMany('zenlix\User', 'form_target_users', 'form_id', 'target_user_id')->withTimestamps();
    }

//subjs
    public function subjs() // those who follow me

    {
        //$this->belongsToMany('App\Role', 'user_roles', 'user_id', 'role_id');
        return $this->belongsToMany('zenlix\TicketSubj', 'form_subj_lists', 'ticket_form_id', 'subj_id')->withTimestamps();
    }

    public function slas() // those who follow me

    {
        //$this->belongsToMany('App\Role', 'user_roles', 'user_id', 'role_id');
        return $this->belongsToMany('zenlix\TicketSla', 'form_sla', 'form_id', 'sla_id')->withTimestamps();
    }

//fields
    public function fields() // those who follow me

    {
        //$this->belongsToMany('App\Role', 'user_roles', 'user_id', 'role_id');
        return $this->belongsToMany('zenlix\TicketAdv', 'form_fields', 'form_id', 'field_id')->withTimestamps();
    }

}
