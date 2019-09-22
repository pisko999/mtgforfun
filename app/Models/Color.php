<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    protected $fillable = ['color'];

    public function cards()
    {
        return $this->belongsToMany('App\Models\Card');
    }
}
