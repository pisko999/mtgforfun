<?php

namespace App\Http\Controllers\Cart;

use App\Http\Requests\CartBuyRequest;
use App\Http\Requests\CartConfirmRequest;
use App\Http\Requests\ItemAddRequest;
use App\Http\Requests\ItemRemoveRequest;
use App\Repositories\CartRepository;
use App\Repositories\CartRepositoryInterface;
use App\Repositories\CommandRepositoryInterface;
use Illuminate\Http\Request;

class IndexController extends \App\Http\Controllers\Command\IndexController
{

    public function showIndex($command_id = null, $command_type = "cart")
    {
        return parent::showIndex( $command_id, $command_type);
        //$cart = $this->commandRepository->getCartByUser($request->user());
        //return view('cart.show', compact('cart'));
    }

    public function addItem(ItemAddRequest $request, $command_type = "cart")
    {
        return parent::addItem($request, $command_type);
    }

    public function removeItem(ItemRemoveRequest $request, $command_type = "cart")
    {
        return parent::removeItem($request, $command_type);
    }

    public function buy()
    {
        $user = \Auth::user();
        $command = $this->commandRepository->getCartByUser($user);
        if(count($command->items) == 0)
            return $this->showIndex();

        return view('cart.buy', compact('command', 'user'));
    }

    public function confirm(CartConfirmRequest $request)
    {
        $user = $request->user();
        $command = $this->commandRepository->confirm($request);

        if(!$command)
            return $this->showIndex();

        return view('cart.confirm', compact('command', 'user'));
    }
}
