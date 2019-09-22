<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StockAddRequest;
use App\Repositories\BoosterRepositoryInterface;
use App\Repositories\CardRepositoryInterface;
use App\Repositories\CommandRepositoryInterface;
use App\Repositories\EditionRepositoryInterface;
use App\Repositories\ProductRepositoryInterface;
use App\Repositories\StockRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AddBoosterController extends Controller
{
    protected $boosterRepository;
    protected $stockRepository;

    public function __construct(
        BoosterRepositoryInterface $boosterRepository,
        StockRepositoryInterface $stockRepository
    )
    {
        $this->boosterRepository = $boosterRepository;
        $this->stockRepository = $stockRepository;
        $this->middleware('auth');
        $this->middleware('admin');
    }
    public function addBoosterSelect()
    {
        $boosters = $this->boosterRepository->getAllWithProduct();


        return view('admin.addBoosterSelect', compact('boosters'));
    }

    public function addBoosterViewGet($id)
    {
        if (!is_numeric($id))
            return abort(404);
        $booster = $this->boosterRepository->getByIdWithProduct($id);

        if (count($booster) == 0)
            return abort(404);
//return var_dump($booster->product->image);
        return view('admin.addBoosterView', compact('booster'));
    }

    public function addBoosterViewPost(StockAddRequest $request, $id)
    {
        if (!is_numeric($id))
            return abort(404);
        $booster = $this->boosterRepository->getByIdWithProduct($id);

        if (count($booster) == 0)
            return abort(404);
        $this->stockRepository->addItem($booster->product, $request);
        //return view('home');
        return $this->addBoosterSelect();
    }
}
