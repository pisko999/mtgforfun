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
    }

    public function getBuyList($edition_id = null)
    {
        $standartEditions = $this->editionRepository->getBuyListEditions();

        $editions = array();
        foreach ($standartEditions as $edition) {
            $e = new ed();
            $e->name = $this->editionRepository->getById($edition->id)->name;
            $e->cards = array();

            $cards = $this->cardRepository->getCardsByEditionWithProductAndStock($edition->id);

            foreach ($cards as $card) {
                if($card->foil)
                    continue;

                if (
                    ($card->edition_id == 299 && $card->number >= 261) ||
                    ($card->edition_id == 303 && $card->number >= 250) ||
                    ($card->edition_id == 295 && $card->number >= 250) ||
                    ($card->edition_id == 291 && $card->number >= 260) ||
                    ($card->edition_id == 285 && $card->number >= 260) //||
                    //($card->edition_id == 281 && $card->number >= 250) ||
                    //($card->edition_id == 9 && $card->number >= 250) ||
                    //($card->edition_id == 11 && $card->number >= 250)
                )
                    continue;


                $s = $card->product->stock;
                //var_dump($s);
                $q = 0;

                foreach ($s as $c) {
                    $q += $c->quantity;

                }
                //add different for  common, uncommon, rare, mythic
                $count = 4;
                if($card->rarity == 'U')
                    $count = 8;
                elseif($card->rarity == 'C')
                    $count = 16;

                if ($q < $count) {
                    $card->quantity = $count - $q;
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
