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

    public function stockingShowGet()
    {

        $editions = $this->editionRepository->getArrayForSelect();
        $r = 'admin.stockingShow';
        return view('admin.editionGet', compact('editions', 'r'));
    }

    public function stockingShow(StockingPostRequest $request)
    {
        $cards = $this->cardRepository->getCardsByEditionWithProductAndColorsWithoutFoil($request->edition);
        $maxCard = $this->cardRepository->getCardByNameAndEdition("Plains", $request->edition)->first();
        if($maxCard != null)
            $cards = $cards->filter(function($value)use ($maxCard){
                return $value->number < $maxCard->number;
            });
        /*
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
        */

        $colors = ['White', 'Blue', 'Black', 'Red', 'Green'];
        $max = 0;
        foreach ($colors as $color) {
            $list[$color] = $cards->filter(function ($value) use ($color) {
                return count($value->colors) == 1 && $value->colors[0]->color == $color;
            })->values();
            if (count($list[$color]) > $max)
                $max = count($list[$color]);
        }

        $list["Multicolor"] = $cards->filter(function ($value) {
            return count($value->colors) > 1;
        })->values();

        $list["Colorless"] = $cards->filter(function ($value) {
            return count($value->colors) < 1;
        })->values();

        return view('admin.showStocking', compact('list', 'colors', 'max'));
    }
}
