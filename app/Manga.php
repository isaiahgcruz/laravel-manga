<?php

namespace App;

use Sofa\Eloquence\Eloquence;
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

    /**
     * The attributes that are searchable
     *
     * @var array
     */
    protected $searchableColumns = ['title'];

    public $incrementing = false;
}
