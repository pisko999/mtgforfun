<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $fillable = ['product_id', 'initial_price', 'quantity', 'price', 'language', 'state', 'idArticleMKM'];

    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }

    public function image()
    {
        return $this->hasOne('App\Models\Image_stock');
    }

    public function items()
    {
        return $this->hasMany('App\Models\Item');
    }
}
