<?php

namespace zenlix;

use Illuminate\Database\Eloquent\Model;

class Files extends Model
{
    //
    protected $fillable = [

        'user_id',
        'target_id',
        'target_type',
        'name',
        'hash',
        'mime',
        'extension',
        'status',
        'image',

    ];

    public function target()
    {
        return $this->morphTo();
    }

    public function scopeImg($query, $type)
    {
        return $query->where('image', $type);
    }

}
