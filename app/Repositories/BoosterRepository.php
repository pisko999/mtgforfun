<?php
/**
 * Created by PhpStorm.
 * User: spina
 * Date: 14/05/2019
 * Time: 23:43
 */

namespace App\Repositories;


use App\Models\Booster;

class BoosterRepository extends ProductModelRepository implements BoosterRepositoryInterface
{
    public function __construct(Booster $booster)
    {
        $this->model = $booster;
    }
}
