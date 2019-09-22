<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Creature extends Card
{
    protected $fillable = ['power', 'toughness'];

    public function card()
    {
        return $this->belongsTo('App\Models\Card');
    }
}
