<?php
/**
 * Created by PhpStorm.
 * User: spina
 * Date: 20/03/2019
 * Time: 12:50
 */

namespace App\Repositories;

use App\Http\Requests\StockAddRequest;
use App\Models\Product;
use App\Models\Stock;
use App\Services\MKMService;


interface StockRepositoryInterface
{
    //public function whereInPaginate($list, $n);

    //public function getByProductIds($product_ids);

    public function addItem(Product $product, StockAddRequest $request);

    public function changePrice(Stock $stock, $price);

    public function changeState(Stock $stock, $state);

    public function increaseStock(Stock $stock, $quantity);

    public function decreaseStock(Stock $stock, $quantity);

    public function removeItemFromExistsExact(Stock $stock, $data);

    public function removeItemFromExists($product, $stockId);


}
