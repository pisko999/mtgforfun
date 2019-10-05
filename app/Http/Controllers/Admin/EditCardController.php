<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\CardRepositoryInterface;
use App\Repositories\EditionRepositoryInterface;
use App\Repositories\ProductRepositoryInterface;
use App\Repositories\StockRepositoryInterface;
use App\Services\StockService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EditCardController extends Controller
{

    protected $editionRepository;
    protected $cardRepository;
    protected $productRepository;
    protected $stockService;

    public function __construct(
        EditionRepositoryInterface $editionRepository,
        CardRepositoryInterface $cardRepository,
        ProductRepositoryInterface $productRepository,
        StockService $stockService
    )
    {
        $this->editionRepository = $editionRepository;
        $this->cardRepository = $cardRepository;
        $this->productRepository = $productRepository;
        $this->stockService = $stockService;
        $this->middleware('auth');
        $this->middleware('admin');
    }

    //prasarna
    public function blbost(StockRepositoryInterface $stockRepository){
        $stock = $stockRepository->getAll();
        foreach ($stock as $item){
            $product = $item->product;
            $product->base_price = $item->price;
            $product->save();
            /*
            $product->price->PO = ceil($item->price * 0.3);
            $product->price->PL = ceil($item->price * 0.4);
            $product->price->LP = ceil($item->price * 0.6);
            $product->price->GD = ceil($item->price * 0.7);
            $product->price->EX = ceil($item->price * 0.8);
            $product->price->NM = $item->price;
            $product->price->MT = $item->price;
            $product->price->save();
            */
        }
        return redirect()->back();
    }

    public function editCardsSelect()
    {
        $editionsTypes = $this->editionRepository->getTypes();
        $sortedEditionsTypes = [
            $editionsTypes[1]['type'],
            $editionsTypes[0]['type'],
            $editionsTypes[3]['type'],
            $editionsTypes[5]['type'],
            $editionsTypes[6]['type'],
            $editionsTypes[4]['type'],
            $editionsTypes[2]['type'],
        ];
        //var_dump($editionsTypes);
        foreach ($sortedEditionsTypes as $editionsType) {
            $editions[$editionsType] = $this->editionRepository->getByType($editionsType);
        }
        return view('admin.editCardSelect', compact('sortedEditionsTypes', 'editions'));
    }

    public function editCardsViewGet(Request $request, $edition_id = 1)
    {
        $nbrPerPage = 50;

        if ((isset($request->page) && !is_numeric($request->page)) || !is_numeric($edition_id))
            return abort(404);

        $edition_name = $this->editionRepository->getById($edition_id)->name;
        $cards = $this->cardRepository->getCardsByEditionWithoutFoilPaginate($edition_id, $nbrPerPage, "number", "asc", $request->page);

        if (count($cards) == 0)
            return abort(404);
        $links = $cards->render();
        if ($cards->currentPage() > $cards->lastPage())
            return $this->editCardsSelect();

        return view('admin.editCardsView', compact('cards', 'links', 'edition_name'));
    }

    public function editCardsViewPost(Request $request, $edition_id = null)
    {
        if ($edition_id == null)
            return;
        $i = 0;
        $product_ids = array();

        while (isset($request['id' . $i])) {
            if (!is_numeric($request['id' . $i])
                || !is_numeric($request['quantity' . $i])
                || !is_numeric($request['origQuantity' . $i])
                || ($request['stock' . $i] != null
                    && (!is_numeric($request['stock' . $i])
                        || !preg_match('/MT|NM|EX|GD|LP|PL|PO/', $request['state' . $i])
                        || !preg_match('/MT|NM|EX|GD|LP|PL|PO/', $request['origState' . $i])
                        || !is_numeric($request['price' . $i])
                        || !is_numeric($request['origPrice' . $i])
                    )
                )
            )
                return abort(404);

            if ($request['origQuantity' . $i] != $request['quantity' . $i]
                || ($request['stock' . $i] != null
                    && ($request['origPrice' . $i] != $request['price' . $i]
                        || $request['origState' . $i] != $request['state' . $i]
                    )
                )
            )
                $product_ids[$i] = $request['id' . $i];

            $i++;
        }

        $products = $this->productRepository->getByIdsPaginate($product_ids, count($product_ids));

        foreach ($product_ids as $key => $id) {

            $product = $products->items()[array_search($id, array_column($products->items(), 'id'))];
            $data['stockId'] = $request['stock' . $key];
            $data['quantity'] = $request['quantity' . $key];
            $data['state'] = $request['state' . $key];
            $data['price'] = $request['price' . $key];

            $this->stockService->edit($product, $data);

        }
        $request->page++;
        return $this->editCardsViewGet($request, $edition_id);

    }

}
