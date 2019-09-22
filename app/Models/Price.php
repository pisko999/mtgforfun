<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    protected $fillable = ['MT', 'NM', 'EX', 'GD', 'LP', 'PL', 'PO'];
    public $timestamps = false;

    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }
}
