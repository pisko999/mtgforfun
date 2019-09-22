<?php

namespace App\Http\Controllers\Shopping;

use App\Repositories\CategoryRepositoryInterface;
use App\Repositories\EditionRepositoryInterface;
use App\Repositories\ProductRepositoryInterface;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    protected $categoryRepository;
    protected $productRepository;
    protected $editionRepository;

    protected $nbrPerPage = 25;


    public function __construct(
        CategoryRepositoryInterface $categoryRepository,
        ProductRepositoryInterface $productRepository,
        EditionRepositoryInterface $editionRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->productRepository = $productRepository;
        $this->editionRepository = $editionRepository;
    }

    public function showCategory($category)
    {
        if ($category == 'Card')
            return $this->showEditions();

        $category = $this->categoryRepository->getCategory($category);
        if ($category == null)
            return abort(404);

        $products = $this->productRepository->getProductsByCategoryPaginate($category, $this->nbrPerPage);

        $links = $products != null ? $products->render() : null;

        return view('shopping.list', compact('products', 'links'));

    }


    private function showEditions()
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
        return view('shopping.editions', compact('sortedEditionsTypes', 'editions'));
    }

}
