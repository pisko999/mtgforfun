<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Planeswalker extends Card
{
    protected $fillable = ['loyality'];

    public function card()
    {
        return $this->belongsTo('App\Models\Card');
    }
}
