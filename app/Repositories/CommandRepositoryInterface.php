<?php
/**
 * Created by PhpStorm.
 * User: spina
 * Date: 21/03/2019
 * Time: 15:37
 */

namespace App\Repositories;


use App\Http\Requests\CartConfirmRequest;
use App\Http\Requests\ItemAddRequest;
use App\Http\Requests\ItemRemoveRequest;
use App\Http\Requests\WantConfirmRequest;

interface CommandRepositoryInterface
{
    public function newCart($user);

    public function getById($id);

    public function getByUser($id);

    public function getWantByUser($user);

    public function getWantById($id);

    public function getCartByUser($user);

    public function addItemToCart(ItemAddRequest $request);

    public function addItemToWant(ItemAddRequest $request);

    public function removeItemFromCart(ItemRemoveRequest $request);

    public function removeItemFromWant(ItemRemoveRequest $request);

    public function want(WantConfirmRequest $request);

    public function confirm(CartConfirmRequest $request);

    public function getCommands();

}
