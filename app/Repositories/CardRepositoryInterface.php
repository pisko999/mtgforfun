<?php
/**
 * Created by PhpStorm.
 * User: spina
 * Date: 16/03/2019
 * Time: 13:58
 */

namespace App\Repositories;


use App\Http\Requests\CardAddJsonRequest;
use App\Http\Requests\CardSearchRequest;
use App\Models\Edition;
use App\Models\Product;

interface CardRepositoryInterface
{
    public function getCardsByEditionPaginate($editionId, $n, $orderBy = "base_price", $orderByType = "desc", $page = 1, $foil = 0);

    public function getCardsByEditionOnlyStockPaginate($editionId, $n, $orderBy = "base_price", $orderByType = "desc", $page = 1, $foil = 0);

    public function getCardsByEditionWithoutFoilPaginate($editionId, $n, $orderBy = "base_price", $orderByType = "desc", $page = 1, $foil = 0);

    public function getCardByNameAndEdition($cardName, $edition_id);

    public function getCardsByEditionGet($editionId);

    public function getCardsByEditionWithProductAndStock($edition_id);

    public function getCardsByEditionAndFoilWithProductAndStock($edition_id, $foil);

    public function add(CardAddJsonRequest $request, Product $product, Edition $set);

    public function getCardsSearchPaginate(CardSearchRequest $request, $nbrPerPage);

    public function getCardsByEditionOnlyStockWithProductAndStock($editionId);
    public function getByIdWithProductAndStock($id);

}
