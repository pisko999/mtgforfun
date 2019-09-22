<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StockAddRequest;
use App\Repositories\BoosterBoxRepositoryInterface;
use App\Repositories\StockRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Services\StockService;

class AddBoosterBoxController extends Controller
{
    protected $boosterBoxRepository;
    protected $stockService;

    public function __construct(
        BoosterBoxRepositoryInterface $boosterBoxRepository,
        StockService $stockService
    )
    {
        $this->boosterBoxRepository = $boosterBoxRepository;
        $this->stockService = $stockService;
        $this->middleware('auth');
        $this->middleware('admin');
    }
    public function addBoosterBoxSelect()
    {
        $boosterBoxes = $this->boosterBoxRepository->getAllWithProduct();


        return view('admin.addBoosterBoxSelect', compact('boosterBoxes'));
    }

    public function addBoosterBoxViewGet($id)
    {
        if (!is_numeric($id))
            return abort(404);
        $boosterBox = $this->boosterBoxRepository->getByIdWithProduct($id);

        if (count($boosterBox) == 0)
            return abort(404);
//return var_dump($booster->product->image);
        return view('admin.addBoosterBoxView', compact('boosterBox'));
    }

    public function addBoosterBoxViewPost(StockAddRequest $request, $id)
    {
        if (!is_numeric($id))
            return abort(404);
        $boosterBox = $this->boosterBoxRepository->getByIdWithProduct($id);

        if (count($boosterBox) == 0)
            return abort(404);
        $this->stockService->add($boosterBox->product, $request);
        //return view('home');
        return $this->addBoosterBoxSelect();
    }
}
