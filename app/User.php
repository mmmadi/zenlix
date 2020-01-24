<?php

namespace zenlix;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\Access\Authorizable;

//use zenlix\UserProfile;
//use zenlix\Groups;

class User extends Model implements AuthenticatableContract,
AuthorizableContract,
CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';
    /**
     * @var array
     */
    protected $dates = ['deleted_at'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password', 'last_login'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * @return mixed
     */
    public function profile()
    {
        return $this->hasOne('zenlix\UserProfile');
    }

    /**
     * @return mixed
     */
    public function logs()
    {
        return $this->hasMany('zenlix\TicketLog', 'author_id', 'id');
    }

    /**
     * @return mixed
     */
    public function ldap()
    {
        return $this->hasOne('zenlix\UserLdap');
    }

//UserTicketConf
    /**
     * @return mixed
     */
    public function UserTicketConf()
    {
        return $this->hasOne('zenlix\UserTicketConf');
    }

    /**
     * @return mixed
     */
    public function roles()
    {
        return $this->hasOne('zenlix\UserRole');
    }

    /**
     * @return mixed
     */
    public function groups() // those who follow me

    {
        //$this->belongsToMany('App\Role', 'user_roles', 'user_id', 'role_id');
        return $this->belongsToMany('zenlix\Groups', 'user_groups', 'user_id', 'group_id')->withTimestamps()->withPivot('status', 'priviliges');
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeGroupAdmin($query)
    {
        return $this->groups()->wherePivot('priviliges', 'admin')->get();
    }

    /**
     * @param $query
     * @param $groupID
     * @return mixed
     */
    public function scopeGroupAdminSet($query, $groupID)
    {
        return $this->groups()->wherePivot('priviliges', 'admin')->wherePivot('group_id', $groupID)->get();
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeGroupUser($query)
    {
        return $this->groups()->wherePivot('priviliges', 'user')->get();
    }

//chatRequests

    /**
     * @return mixed
     */
    public function chatRequests()
    {
        return $this->hasMany('zenlix\ChatRequest', 'user_id', 'id');
    }

    /**
     * @return mixed
     */
    public function chatRequest()
    {
        return $this->hasOne('zenlix\ChatRequest', 'user_id', 'id');
    }

    /**
     * @return mixed
     */
    public function comments()
    {
        return $this->hasMany('zenlix\TicketComments', 'author_id', 'id');
    }

    /**
     * @return mixed
     */
    public function notify()
    {
        return $this->hasMany('zenlix\UsersNotifyConfig', 'user_id', 'id');
    }

    /**
     * @param $query
     * @param $target
     * @param $type
     * @return mixed
     */
    public function scopeNotifyConfig($query, $target, $type)
    {
        return $this->notify()->where('target', $target)->where('type', $type)->get();
    }

    /**
     * @param $query
     * @param $target
     * @return mixed
     */
    public function scopeNotifyConfigCount($query, $target)
    {
        return $this->notify()->where('target', $target)->get();
    }

    /**
     * @return mixed
     */
    public function devices()
    {
        return $this->hasMany('zenlix\UserDevices', 'user_id', 'id');
    }

    /**
     * @return mixed
     */
    public function fields() // those who follow me

    {
        return $this->hasMany('zenlix\UserFieldsData', 'user_id', 'id');
/*        //$this->belongsToMany('App\Role', 'user_roles', 'user_id', 'role_id');
return $this->belongsToMany('zenlix\UserFields', 'user_fields_data', 'user_id', 'user_field_id')->withTimestamps()->withPivot('field_data','user_field_id');*/
    }

}
