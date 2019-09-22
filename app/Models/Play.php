<?php
/**
 * Created by PhpStorm.
 * User: spina
 * Date: 16/04/2019
 * Time: 20:24
 */

namespace App\Models;


class Play extends Product
{

    protected $fillable = ['id'];

    public $timestamps = false;

    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'id', 'id');
    }
}
