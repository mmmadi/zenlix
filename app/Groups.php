<?php

namespace zenlix;

use Illuminate\Database\Eloquent\Model;

class Groups extends Model
{
    //
    protected $fillable = array('name', 'description', 'cover', 'icon', 'status', 'group_urlhash', 'address', 'slogan', 'description_full', 'tags', 'facebook', 'twitter');

    public function user()
    {
        return $this->belongsToMany('zenlix\User');
    }

    public function users() // those who follow me

    {
        //$this->belongsToMany('App\Role', 'user_roles', 'user_id', 'role_id');
        return $this->belongsToMany('zenlix\User', 'user_groups', 'group_id', 'user_id')->withTimestamps()->withPivot('status', 'priviliges');
    }

    public function addFriend(User $user)
    {
        $this->users()->attach($user->id);
    }

    public function removeFriend(User $user)
    {
        $this->users()->detach($user->id);
    }

    public function scopeURLhash($query, $type)
    {
        return $query->where('group_urlhash', $type);
    }

    public function scopeGroupUser($query)
    {
        return $this->users()->wherePivot('priviliges', 'user')->get();
    }

    public function feeds()
    {
        return $this->hasMany('zenlix\GroupFeed', 'group_id', 'id');
    }

/*    public function scopeGroupAdmin($query)
{
return $this->users()->wherePivot('priviliges', 'admin');
}*/

}
