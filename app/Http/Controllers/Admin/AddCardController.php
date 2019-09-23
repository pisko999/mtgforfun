<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AddCardViewRequest;
use App\Http\Requests\RemoveCardSingleRequest;
use App\Http\Requests\StockAddRequest;
use App\Repositories\CardRepositoryInterface;
use App\Repositories\EditionRepositoryInterface;
use App\Repositories\ProductRepositoryInterface;
use App\Repositories\StockRepositoryInterface;
use App\Services\MKMService;
use App\Services\StockService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AddCardController extends Controller
{

    protected $editionRepository;
    protected $cardRepository;
    protected $productRepository;
    protected $stockRepository;
    protected $stockService;

    public function __construct(
        EditionRepositoryInterface $editionRepository,
        CardRepositoryInterface $cardRepository,
        ProductRepositoryInterface $productRepository,
        StockRepositoryInterface $stockRepository,
        StockService $stockService
    )
    {
        $this->editionRepository = $editionRepository;
        $this->cardRepository = $cardRepository;
        $this->productRepository = $productRepository;
        $this->stockRepository = $stockRepository;
        $this->stockService = $stockService;
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function addCardSelect()
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
        return view('admin.addCardSelect', compact('sortedEditionsTypes', 'editions'));
    }

    public function addCardViewGet(AddCardViewRequest $request, $edition_id = 3)
    {
        $nbrPerPage = 1;

        $cards = $this->cardRepository->getCardsByEditionWithoutFoilPaginate($edition_id, $nbrPerPage, 'number', 'asc', $request->page);

        if (count($cards) == 0)
            return abort(404);
        $links = $cards->render();
        $card = $cards->first();

        return view('admin.addCardView', compact('card', 'links', 'cards', 'edition_id'));
    }

    public function addCardViewPost(StockAddRequest $request, $edition_id = 3)
    {
        $nbrPerPage = 1;
        $cards = $this->cardRepository->getCardsByEditionPaginate($edition_id, $nbrPerPage, 'number', 'asc', $request->page);
        if($request->quantity > 0) {

            if (count($cards) == 0)
                return abort(404);
            $stock = $this->stockService->add($cards[0]->product, $request);
        }
        if ($cards->nextPageUrl() != null)
            return redirect($cards->nextPageUrl());
        else
            return $this->addCardSelect();
    }

    public function addCardSinglePost(StockAddRequest $request)
    {
        $product = $this->productRepository->getById($request->id);
        if (count($product) == 0)
            return abort(404);
        $stock = $this->stockService->add($product, $request);

        return redirect()->back();

    }

    public function removeCardSinglePost(RemoveCardSingleRequest $request)
    {
        $stock = $this->stockRepository->getById($request->id);

        if ($stock == null)
            return abort(404);

        $idArticleMKM = $this->stockService->decrease($stock, $request->quantity);

        return redirect()->back();

    }

}
