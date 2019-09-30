<?php
/**
 * Created by PhpStorm.
 * User: spina
 * Date: 20/03/2019
 * Time: 12:51
 */

namespace App\Repositories;

use App\Http\Requests\StockAddRequest;
use App\Models\Image;
use App\Models\Image_stock;
use App\Models\Stock;
use App\Models\Product;
use App\Services\MKMService;

class StockRepository extends ModelRepository implements StockRepositoryInterface
{

    public function __construct(Stock $stock)
    {
        $this->model = $stock;
    }

    /*
        public function whereInPaginate($list, $n)
        {
            return $this->model->where('state', 'NM')->whereOr('state', 'M')->whereIn('product_id', $list)->paginate($n);
        }


        public function getByProductIds($product_ids)
        {
            $items = $this->model->whereIn('product_id', $product_ids)->get();
            return $items;
        }
    */
    public function addItem(Product $product, $request)
    {

        //$request['quantity']
        //$request['foil']
        //$request['price']
        //$request['state']


        if ($request['quantity'] == 0 || $request['quantity'] == null)
            return;
        $stock = $this->model->where('product_id', $product->id)->get();
        //trying to add to exists
        //\Debugbar::info($stock);

        foreach ($stock as $s) {

            $ret = $this->addItemToExists($s, $request);
            if ($ret != false) {
                return $ret;
            }
        }

        return $this->addNewItem($product, $request);
    }

    private function addNewItem(Product $product, $request)
    {

        //adding new item
        $product->price != null ? $price = $product->price->m : $price = 0;

        if (isset($request['price']) && $request['price'] > 0) {
            $price = $request['price'];
        }
        $state = "NM";
        if (isset($request['state'])) {


            $state = $request['state'];
        }

        $item = new Stock([
            'product_id' => $product->id,
            'initial_price' => $price, //set back to $product->price->m as not work with items without initial prices
            'quantity' => $request['quantity'],
            'price' => $price,
            'state' => $state
        ]);

        $item->save();
//\Debugbar::info($request->image);
        if (isset($request->image) && $request->image != null) {
            $fileName = $item->id . '.' . $request->image->getClientOriginalExtension();
            //var_dump($categoryRepository->getById($request->category));
            $storagePath = 'image/stock';
            $path = $request->image->storeAs('public/' . $storagePath, $fileName);
            \Debugbar::info( $path);
            $image = new Image_stock([
                'path' => $storagePath .'/' . $fileName,
                'alt' => $product->name,
            ]);
            $item->image()->save($image);
        }
        return $item;
    }

    private function addItemToExists(Stock $stock, $request)
    {
        //var_dump($data['state']);
        //\Debugbar::info($data);
        //\Debugbar::info($stock->foil);

        if (($request['price'] == null || $stock->price == $request['price']) && $stock->state == $request['state']) {
            $stock->quantity += $request['quantity'];
            $stock->save();
            return $stock;
        }
        return false;
    }

    public function changePrice(Stock $stock, $price){
        $stock->price = $price;
        $stock->save();
    }

    public function changeState(Stock $stock, $state){
        $stock->state = $state;
        $stock->save();
    }

    public function increaseStock(Stock $stock, $quantity){
        $stock->quantity += $quantity;
        $stock->save();
    }

    public function decreaseStock(Stock $stock, $quantity)
    {
        \Debugbar::info($quantity);

        $stock->quantity -= $quantity;
        $idArticleMKM = $stock->idArticleMKM;
        if ($stock->quantity <= 0) {
            $stock->quantity = 0;
            $stock->idArticleMKM = null;
        }
        $stock->save();

        if ($stock->quantity == 0 && count($stock->items) == 0)
            $stock->delete();
        return $idArticleMKM;
    }

    public function removeItemFromExistsExact(Stock $stock, $data)
    {
        //var_dump($data['state']);

        if (($data['price'] == null || $stock->price == $data['price']) && $stock->state == $data['state']) {
            //var_dump($data);
            $stock->quantity -= $data['quantity'];
            $stock->save();
            return true;
        }
        return false;
    }

    public function removeItemFromExists($product, $stockId)
    {
        $stock = $this->model->whereId($stockId)->first();
        if ($stock->quantity > $product->quantity) {
            $stock->quantity -= $product->quantity;
            $product->quantity = 0;
        } else {
            $product->quantity -= $stock->quantity;
            $stock->quantity = 0;
        }
        $stock->save();
    }


}
