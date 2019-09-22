<?php
/**
 * Created by PhpStorm.
 * User: spina
 * Date: 21/03/2019
 * Time: 16:59
 */

namespace App\Repositories;

use App\Http\Requests\ItemAddRequest;
use \App\Models\Item;
use \App\Models\Command;
use App\Services\StockService;

class ItemRepository extends ModelRepository implements ItemRepositoryInterface
{
    protected $stockService;

    public function __construct(StockService $stockService, Item $item)
    {
        $this->model = $item;
        $this->stockService = $stockService;
    }

    public function stores(ItemAddRequest $request,Command $command)
    {

        $item = $this->model->where('stock_id', $request->stock_id)->where('command_id', $command->id)->where('price', $request->price)->first();

        if ($item == null)
            $item = $this->getNewItem($request, $command);

        $item = $this->increaseQuantity($item, $request->quantity);

        return $item;
    }

    public function increase($id, $quantity)
    {
        $item = $this->getById($id);
        if($item != null)
            return $this->increaseQuantity($item, $quantity);
        return false;
    }

    public function decrease($id, $quantity)
    {
        $item = $this->getById($id);

        if ($item == null)
            return false;

        //if ($item->quantity <= $quantity)
        $item->quantity -= $quantity;

        $this->stockService->increase($item->stock, $quantity);

        if ($item->quantity <= 0)
            $item->delete();
        else
            $item->save();

        //if ($quantity > 0)
        //$this->delete($id, $quantity);
        return true;

    }

    private function getNewItem(ItemAddRequest $request,Command $command){
        $item = new $this->model([
            'stock_id' => $request->stock_id,
            'command_id' => $command->id,
            'price' => $request->price,
            'quantity' => 0,
        ]);

        $command->items()->save($item);

        return $item;
    }

    private function increaseQuantity(Item $item, $quantity)
    {
        if ($item->stock->quantity < $quantity)
            $quantity = $item->stock->quantity;

        $item->quantity += $quantity;
        $item->save();
        $this->stockService->decrease($item->stock, $quantity);

        return $item;

    }

}
