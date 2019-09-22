<?php

namespace App\Models;


class BoosterBox extends Product
{
    protected $fillable = ['boosters'];

    public $timestamps = false;

    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'id', 'id');
    }

    public function edition()
    {
        return $this->belongsTo('App\Models\Edition');
    }

    public function promo()
    {
        return $this->belongsTo('App\Models\Product');
    }

    public function getId()
    {
        return $this->product()->first()->getId();
    }

    public function getName()
    {
        return $this->product()->first()->name;
    }
}
