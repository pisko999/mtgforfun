<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = ['street', 'number', 'flat', 'city', 'country', 'region', 'postal'];

    public $timestamps = false;

    public function users()
    {
        return $this->hasMany('App\Models\User');
    }

    public function commands()
    {
        return $this->hasMany('App\Models\Command');
    }
}
