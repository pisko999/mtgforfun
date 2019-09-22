<?php

namespace App\Models;


class Booster extends Product
{
    protected $fillable = ['cards'];

    public $timestamps = false;

    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'id', 'id');
    }

    public function edition()
    {
        return $this->belongsTo('App\Models\Edition');
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
