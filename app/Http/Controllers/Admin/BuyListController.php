<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\BoosterRepositoryInterface;
use App\Repositories\CardRepositoryInterface;
use App\Repositories\CommandRepositoryInterface;
use App\Repositories\EditionRepositoryInterface;
use App\Repositories\ProductRepositoryInterface;
use App\Repositories\StockRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BuyListController extends Controller
{

    protected $editionRepository;
    protected $cardRepository;

    public function __construct(
        EditionRepositoryInterface $editionRepository,
        CardRepositoryInterface $cardRepository
    )
    {
        $this->editionRepository = $editionRepository;
        $this->cardRepository = $cardRepository;
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function getBuyList($edition_id = null)
    {
        $standartEditions = $this->editionRepository->getStandartEditions();

        $editions = array();
        foreach ($standartEditions as $edition) {
            if($edition->id == 303)
                continue;
            $e = new ed();
            $e->name = $this->editionRepository->getById($edition->id)->name;
            $e->cards = array();

            $cards = $this->cardRepository->getCardsByEditionWithProductAndStock($edition->id);

            foreach ($cards as $card) {
                if($card->foil)
                    continue;
                /*
                if (
                    ($card->edition_id == 2 && $card->number >= 250) ||
                    ($card->edition_id == 3 && $card->number >= 255) ||
                    ($card->edition_id == 5 && $card->number >= 255) ||
                    ($card->edition_id == 6 && $card->number >= 261) //||
                    //($card->edition_id == 7 && $card->number >= 250) ||
                    //($card->edition_id == 9 && $card->number >= 250) ||
                    //($card->edition_id == 11 && $card->number >= 250)
                )
                    continue;
                */

                $s = $card->product->stock;
                //var_dump($s);
                $q = 0;

                foreach ($s as $c) {
                    $q += $c->quantity;

                }
                //add different for  common, uncommon, rare, mythic
                if ($q < 4) {
                    $card->quantity = 4 - $q;
                    array_push($e->cards, $card);
                }
            }

            array_push($editions, $e);
        }
        return view('admin.buyList', compact('editions'));
    }

}
class ed
{
    public $name;
    public $cards;
}
