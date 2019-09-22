<?php

namespace App\Http\Controllers\Shopping;

use App\Models\Product;
use App\Repositories\EditionRepositoryInterface;
use App\Repositories\ProductRepositoryInterface;
use App\Repositories\RaritiesRepository;
use App\Http\Controllers\Controller;

class ShowController extends Controller
{
    protected $productRepository;
    protected $editionRepository;
    protected $raritiesRepository;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        EditionRepositoryInterface $editionRepository,
        RaritiesRepository $raritiesRepository)
    {
        $this->productRepository = $productRepository;
        $this->editionRepository = $editionRepository;
        $this->raritiesRepository = $raritiesRepository;
    }

    public function showItem($itemId)
    {
        if (!is_numeric($itemId))
            return abort(404);

        $product = $this->productRepository->getById($itemId);
        //var_dump($product);
        $prints = array();
        if ($product->categoryId == 1) {
            $prints = $this->productRepository->getPrints($product->name, $product->card->edition_id, $this->editionRepository);
        }
//var_dump($prints);
        $product = $this->getProduct($product);
        //var_dump($product->product);
        $rarities = $this->raritiesRepository->getRaritiesToLong();

        return view('shopping.product', compact('product', 'rarities', 'prints'));
    }

    private function getProduct(Product $product)
    {
        switch ($product->getCategoryId()) {
            case 1:
                $product = $product->card;
                break;
            case 2:
                $product = $product->booster;
                break;
            case 3:
                $product = $product->boosterBox;
                break;
            case 4:
                $product = $product->collect;
                break;
            case 5:
                $product = $product->play;
                break;
            default:
                break;

        }

        return $product;
    }
}
