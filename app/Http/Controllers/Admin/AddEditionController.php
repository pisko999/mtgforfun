<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CardAddJsonRequest;
use App\Http\Requests\EditionAddRequest;
use App\Repositories\BoosterRepositoryInterface;
use App\Repositories\CardRepositoryInterface;
use App\Repositories\CommandRepositoryInterface;
use App\Repositories\EditionRepositoryInterface;
use App\Repositories\PriceRepositoryInterface;
use App\Repositories\ProductRepositoryInterface;
use App\Repositories\StockRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AddEditionController extends Controller
{
    protected $editionRepository;
    protected $cardRepository;
    protected $productRepository;
    protected $priceRepository;

    public function __construct(
        EditionRepositoryInterface $editionRepository,
        CardRepositoryInterface $cardRepository,
        ProductRepositoryInterface $productRepository,
        PriceRepositoryInterface $priceRepository
    )
    {
        $this->editionRepository = $editionRepository;
        $this->cardRepository = $cardRepository;
        $this->productRepository = $productRepository;
        $this->priceRepository = $priceRepository;
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function getEditionList()
    {
        $sets = json_decode(file_get_contents('https://api.scryfall.com/sets'))->data;

        //$setTypes = array_column($sets, 'set_type');
        //$setTypesUniques = array_unique($setTypes);
        //sort($setTypesUniques);

        $setTypes = array('core', 'draft_innovation', 'expansion', 'masterpiece', 'masters', 'promo');

        foreach ($setTypes as $setType) {
            $editions[$setType] = array_filter($sets, function ($value) use ($setType) {
                return $value->set_type == $setType;
            });
            $editionsLocal[$setType] = $this->editionRepository->getByTypeWithCountCards($setType);
        }

        //sorting array
        /*usort($sets, function($a, $b)
        {
            return strcmp($a->name, $b->name);
        });
        usort($sets, function($a, $b)
        {
            return strcmp($a->set_type, $b->set_type);
        });*/

        //$editions = $this->editionRepository->getTypes();

        return view('admin.editionList', compact('editions', 'editionsLocal', 'setTypes'));
    }

    public function addEditionGet()
    {
        $sets = json_decode(file_get_contents('https://api.scryfall.com/sets'))->data;

        $setTypes = array('core', 'draft_innovation', 'expansion', 'masterpiece', 'masters', 'promo');

        foreach ($setTypes as $setType) {
            $editions[$setType] = array_filter($sets, function ($value) use ($setType) {
                return $value->set_type == $setType;
            });
            $editionsLocal[$setType] = $this->editionRepository->getByType($setType);
            $editionsToBeAdded[$setType] = array();

            foreach ($editions[$setType] as $editionOnline) {
                $edition = $editionsLocal[$setType]->where('name', $editionOnline->name)->first();

                if ($edition == null)
                    array_push($editionsToBeAdded[$setType], $editionOnline);
            }
        }

        return view('admin.addEdition', compact('editionsToBeAdded', 'setTypes'));
    }

    public function addEditionPost(EditionAddRequest $request)
    {
        $edition = $this->editionRepository->add($request);
        if (count($edition) == 0)
            return abort(404);
        return $edition->name;
    }

    public function addCardPost(CardAddJsonRequest $request)
    {
        $set = $this->editionRepository->getByCode($request->set);

        if (count($set) == 0)
            return abort(404);

        $product = $this->productRepository->addFromNet($this->priceRepository, $request);

        if (count($product) == 0)
            return abort(404);//$priceRepository->add($product);

        $card = $this->cardRepository->add($product, $set, $request);
        return $product->name;
    }

}
