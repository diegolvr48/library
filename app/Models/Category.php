<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description'
    ];

    public $timestamps = false;

    public function books() {
        return $this->hasMany('App\Models\Book', 'category', 'id');
    }
}
