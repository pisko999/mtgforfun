<?php
/**
 * Created by PhpStorm.
 * User: spina
 * Date: 21/03/2019
 * Time: 16:58
 */

namespace App\Repositories;


use App\Http\Requests\ItemAddRequest;
use App\Models\Command;

interface ItemRepositoryInterface
{
    public function stores(ItemAddRequest $request,Command $command);

    public function increase($id, $quantity);

    public function decrease($id, $quantity);

}
