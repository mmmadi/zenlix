<?php

namespace zenlix;

use Illuminate\Database\Eloquent\Model;

class FeedComments extends Model
{
    //
    protected $table = 'feed_comments';

    protected $fillable = [

        'text',
        'author_id',
        'feed_id',
        'comment_urlhash',

    ];

    public function author()
    {
        return $this->hasOne('zenlix\User', 'id', 'author_id')->withTrashed();
    }

}
