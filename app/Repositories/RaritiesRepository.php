<?php
/**
 * Created by PhpStorm.
 * User: spina
 * Date: 21/03/2019
 * Time: 15:26
 */

namespace App\Repositories;


class RaritiesRepository
{
    protected $raritiesToShort;
    protected $raritiesToLong;

    public function __construct()
    {
        //$this->rarities = array('C' => 'common', 'U' => 'uncommon', 'R' => 'rare', 'M' => 'mythic');
        $this->raritiesToShort = array('common' => 'C', 'uncommon' => 'U', 'rare' => 'R', 'mythic' => 'M');
        $this->raritiesToLong = array('C' => 'common', 'U' => 'uncommon', 'R' => 'rare', 'M' => 'mythic');
    }

    public function getRaritiesToShort()
    {
        return $this->raritiesToShort;
    }

    public function getRaritiesToLong()
    {
        return $this->raritiesToLong;
    }

    public function getRarities()
    {

    }
}
