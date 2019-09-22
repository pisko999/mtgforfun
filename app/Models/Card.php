<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Card extends Product
{
    protected $fillable = ['id', 'rarity', 'number', 'promo', 'mana_cost', 'text', 'flavor', 'foil', 'exists_foil', 'exists_nonfoil'];

    public $timestamps = false;

    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'id', 'id');
    }

    public function edition()
    {
        return $this->belongsTo('App\Models\Edition');
    }

    public function colors()
    {
        return $this->belongsToMany('\App\Models\Color');
    }

    public function subtypes()
    {
        return $this->belongsToMany('\App\Models\Subtype');
    }

    public function types()
    {
        return $this->belongsToMany('\App\Models\Type');
    }

    public function creature()
    {
        return $this->hasOne('\App\Models\Creature');
    }

    public function planeswalker()
    {
        return $this->hasOne('\App\Models\Planeswalker');
    }

    public function legendary()
    {
        return $this->hasOne('\App\Models\Legendary');
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
