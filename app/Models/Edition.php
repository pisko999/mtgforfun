<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Edition extends Model
{
    protected $fillable = ['idExpansionMKM', 'name', 'cards_count', 'symbol_path', 'sign', 'type', 'release_date'];

    public $timestamps = false;

    public function boosters()
    {
        return $this->hasMany('App\Models\Booster');
    }

    public function booster_boxes()
    {
        return $this->hasOne('App\Models\Booster_Box');
    }

    public function cards()
    {
        return $this->hasMany('App\Models\Card');
    }

    public function getId()
    {
        return $this->id;
    }
}
