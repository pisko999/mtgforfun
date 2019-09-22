<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['idProductMKM', 'name', 'categoryId', 'lang', 'base_price', 'release_date'];

    public function image()
    {
        return $this->hasOne('App\Models\Image');
    }

    public function price()
    {
        return $this->hasOne('App\Models\Price');
    }

    public function stock()
    {
        return $this->hasMany('App\Models\Stock');
    }

    public function items()
    {
        return $this->hasMany('App\Models\Item');
    }

    public function booster()
    {
        return $this->hasOne("App\Models\Booster", 'id', 'id');
    }

    public function boosterBox()
    {
        return $this->hasOne('App\Models\BoosterBox', 'id','id');
    }

    public function card()
    {
        return $this->hasOne('App\Models\Card', 'id', 'id');
    }

    public function collect()
    {
        return $this->hasOne('App\Models\Collect', 'id', 'id');
    }

    public function play()
    {
        return $this->hasOne('App\Models\Play', 'id', 'id');
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCategoryId()
    {
        return $this->categoryId;
    }
}
