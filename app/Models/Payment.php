<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['id', 'type', 'address', 'amount', 'currency', 'status', 'txid'];

    public function command()
    {
        return $this->hasOne('App\Models\Command');
    }
}
