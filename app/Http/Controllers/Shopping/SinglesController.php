<?php

namespace App\Http\Controllers\Shopping;

use App\Http\Requests\CardSearchRequest;
use App\Http\Requests\SearchRequest;
use App\Http\Requests\ShowListRequest;
use App\Models\Search;
use App\Models\SearchSelected;
use App\Repositories\CardRepositoryInterface;
use App\Repositories\CategoryRepositoryInterface;
use App\Repositories\EditionRepositoryInterface;
use App\Repositories\ProductRepositoryInterface;
use App\Repositories\RaritiesRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SinglesController extends Controller
{
    protected $productRepository;
    protected $editionRepository;
    protected $cardRepository;
    protected $raritiesRepository;
    protected $nbrPerPage = 25;


    public function __construct(
        ProductRepositoryInterface $productRepository,
        EditionRepositoryInterface $editionRepository,
        CardRepositoryInterface $cardRepository,
        RaritiesRepository $raritiesRepository)
    {
        $this->productRepository = $productRepository;
        $this->editionRepository = $editionRepository;
        $this->cardRepository = $cardRepository;
        $this->raritiesRepository = $raritiesRepository;
    }


    public function showList(ShowListRequest $request, Search $search, SearchSelected $selected, $edition_id)
    {
        $products = $this->cardRepository->getCardsByEditionOnlyStockPaginate($edition_id, $this->nbrPerPage, 'products.base_price', 'desc', $request->page);

        $links = $products->render();

        $rarities = $this->raritiesRepository->getRaritiesToLong();


        $selected->editionId = $edition_id;
        $selected->onlyStock = 1;

        $showingCards = 1;

        return view('shopping.list', compact('products', 'links', 'rarities', 'search', 'selected', 'showingCards'));
    }


    public function search(SearchRequest $request, Search $search, SearchSelected $selected)
    {
        //return view('home');
        if (isset($request->searchTextHidden))
            $request->searchedText = $request->searchTextHidden;

        $products = $this->productRepository->getProductsByNamePaginate($request->searchedText, $this->nbrPerPage);

        $links = $products->render();

        $rarities = $this->raritiesRepository->getRaritiesToLong();


        $selected->hydrate($request);

        $showingCards = 0;

        return view('shopping.list', compact('products', 'links', 'rarities', 'search', 'selected', 'showingCards'));

    }

    public function searchCard(CardSearchRequest $request, Search $search, SearchSelected $selected)
    {
        $products = $this->cardRepository->getCardsSearchPaginate($request, $this->nbrPerPage);

        $links = $products->render();

        $rarities = $this->raritiesRepository->getRaritiesToLong();


        $selected->hydrate($request);

        $showingCards = 1;

        //return view('shopping.list', compact('products', 'links', 'rarities', 'search', 'selected', 'showingCards'));

    }

}
