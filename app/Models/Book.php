<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'author', 'published_date', 'category', 'user'
    ];

    public $timestamps = false;

    public function category() {
        return $this->hasOne('App\Models\Category', 'id', 'category');
    }
}
