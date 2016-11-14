<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Manga extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'title', 'favorited', 'last_read_chapter',
    ];

    public $incrementing = false;
}
