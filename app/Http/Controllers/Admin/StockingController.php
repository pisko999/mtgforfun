<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StockingPostRequest;
use App\Http\Requests\StockingShowRequest;
use App\Repositories\CardRepositoryInterface;
use App\Repositories\EditionRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StockingController extends Controller
{
    private $editionRepository;
    private $cardRepository;

    public function __construct(EditionRepositoryInterface $editionRepository, CardRepositoryInterface $cardRepository)
    {
        $this->editionRepository = $editionRepository;
        $this->cardRepository = $cardRepository;
    }

    public function stockingList()
    {

        $editions = $this->editionRepository->getArrayForSelect();
        $r = 'admin.stocking';
        return view('admin.editionGet', compact('editions', 'r'));
    }

    public function stockingPost(StockingPostRequest $request)
    {

        $edition = $this->editionRepository->getById($request->edition);
        if ($edition == null)
            return abort(404);
        $cards = $edition->cards;
        foreach ($cards as $card) {
            if (count($card->product->stock) > 0)
                foreach ($card->product->stock as $stock) {
                    $stock->stock = $stock->price >= 25 ? ($stock->price >= 50 ? 3 : 2) : 1;
                    $stock->save();

                }
        }

        return redirect()->back();
    }

    public function stockingShow(StockingShowRequest $request)
    {
        $allCards = $this->cardRepository->getCardsByEditionWithProductAndStock($request->edition);

        switch ($request->stock) {
            case 1:
                $cards = $allCards->filter(function ($value, $key) {
                    return $value->product->base_price < 25;
                });
                break;
            case 2:
                $cards = $allCards->filter(function ($value, $key) {
                    return $value->product->base_price >= 25 && $value->product->base_price < 50;
                });
                break;
            case 3:
                $cards = $allCards->filter(function ($value, $key) {
                    return $value->product->base_price >= 50;
                });
                break;
        }

        return view('admin.showStocking', compact('cards'));
    }
}
