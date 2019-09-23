<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\EditionGetRequest;
use App\Repositories\CardRepositoryInterface;
use App\Repositories\EditionRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EditionStatisticController extends Controller
{
    protected $editionRepository;
    protected $cardRepository;

    public function __construct(EditionRepositoryInterface $editionRepository, CardRepositoryInterface $cardRepository)
    {
        $this->editionRepository = $editionRepository;
        $this->cardRepository = $cardRepository;
    }

    public function editionsSatatisticGet()
    {
        $editions = $this->editionRepository->getArrayForSelect();
        return view('admin.editionsStatisticGet', compact('editions'));
    }

    public function editionsStatisticPost(EditionGetRequest $request)
    {
        $edition = $this->editionRepository->getById($request->edition);
        $cards = $this->cardRepository->getCardsByEditionOnlyStockWithProductAndStockPaginate($request->edition);
        $count = 0;
        $price = 0;
        foreach ($cards as $card) {
            foreach ($card->product->stock as $stock) {
                $price += $stock->price * $stock->quantity;
                $count += $stock->quantity;
            }
        }

        return view('admin.editionsStatisticPost', compact('edition', 'count', 'price'));
    }
}
