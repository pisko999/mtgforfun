<?php
/**
 * Created by PhpStorm.
 * User: spina
 * Date: 14/05/2019
 * Time: 23:43
 */

namespace App\Repositories;


use App\Models\BoosterBox;

class BoosterBoxRepository extends ProductModelRepository implements BoosterBoxRepositoryInterface
{
    public function __construct(BoosterBox $boosterBox)
    {
        $this->model = $boosterBox;
    }
}
