<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = ['path', 'alt'];
    public $timestamps = false;

    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }
}
