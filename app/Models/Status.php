<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $fillable = ['status'];

    public function commands()
    {
        return $this->belongsToMany('App\Models\Command');
    }

    public static function want()
    {
        return 3;
    }

    public static function confirmed()
    {
        return 5;
    }

    public static function paid()
    {
        return 6;
    }

    public static function preparing()
    {
        return 7;
    }

    public static function send()
    {
        return 8;
    }

    public static function delivered()
    {
        return 9;
    }
}
