<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BuysItem extends Model
{

    protected $fillable = ['idProduct', 'idStock','idBuy', 'quantity', 'price', 'state'];

    public $timestamps = false;

}
