<?php

namespace zenlix;

use Illuminate\Database\Eloquent\Model;

class GroupFeed extends Model
{
    //
    protected $table = 'group_feed';

    protected $fillable = [

        'text',
        'target',
        'comments_flag',
        'author_id',
        'group_id',
        'mark',
        'feed_urlhash',

    ];

    public function comments()
    {
        return $this->hasMany('zenlix\FeedComments', 'feed_id', 'id');
    }

    public function author()
    {
        return $this->hasOne('zenlix\User', 'id', 'author_id')->withTrashed();
    }

    public function group()
    {
        return $this->hasOne('zenlix\Groups', 'id', 'group_id');
    }

    public function commentsShort()
    {
        return $this->hasMany('zenlix\FeedComments', 'feed_id', 'id')->limit(2)->orderBy('created_at', 'desc');
    }

}
