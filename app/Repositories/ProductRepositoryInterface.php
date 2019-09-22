<?php
/**
 * Created by PhpStorm.
 * User: spina
 * Date: 12/03/2019
 * Time: 23:45
 */

namespace App\Repositories;


use App\Http\Requests\CardAddJsonRequest;
use App\Http\Requests\ProductCreateRequest;

interface ProductRepositoryInterface
{
    public function createProduct(ProductCreateRequest $request, CategoryRepositoryInterface $categoryRepository);

    public function addFromNet(PriceRepositoryInterface $priceRepository, CardAddJsonRequest $request);

    public function getById($id);

    public function getByIdsAll($ids);

    public function getByIdsPaginate($ids, $nbrPerPage);

    public function getPaginate($n);

    public function getProductsByCategoryPaginate($product, $n);

    public function getLanguages();

    public function getProductsByNamePaginate($name, $n);

    public function getProductsByName($name);

    public function getProductsByExactName($name);

    public function getProductByExactName($name);

    public function getProductByExactNameAndExpansionMKM($name, $category, $expansionMKMId);

    public function getProductsByExactNameAndEdition($name, $editionCode);

    public function getPrints($name, $edition_id, EditionRepositoryInterface $editionRepository);

}
