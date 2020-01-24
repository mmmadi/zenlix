<?php

namespace zenlix;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    //
    protected $table = 'user_profiles';
    protected $primaryKey = 'user_id';

    protected $fillable = [
        'id',
        'user_id',
        'user_img',
        'user_cover',
        'lang',
        'full_name',
        'user_urlhash',
        'telephone',
        'skype',
        'address',
        'position',
        'birthdayDay',
        'birthdayMonth',
        'birthdayYear',
        'email',
        'facebook',
        'twitter',
        'website',
        'about',
        'skills',
        'sms',
        'pb',

    ];

    public function user()
    {
        return $this->belongsTo('zenlix\User');
    }

    public function scopeURLhash($query, $type)
    {
        return $query->where('user_urlhash', $type);
    }
}
