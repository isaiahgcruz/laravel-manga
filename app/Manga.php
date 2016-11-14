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
        'id', 'title', 'favorited'
    ];

    public $incrementing = false;
}
