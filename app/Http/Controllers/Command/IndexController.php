<?php

namespace App\Http\Controllers\Command;

use App\Http\Requests\ItemAddRequest;
use App\Http\Requests\ItemRemoveRequest;
use App\Repositories\CommandRepositoryInterface;
use App\Repositories\ItemRepositoryInterface;
use App\Repositories\StockRepositoryInterface;
use App\Services\MKMService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    protected $commandRepository;
    protected $stockRepository;
    protected $itemRepository;

    public function __construct(CommandRepositoryInterface $commandRepository, StockRepositoryInterface $stockRepository, ItemRepositoryInterface $itemRepository)
    {
        $this->middleware('auth');
        //$this->middleware('admin')->only(['addItem', 'removeItem']);
        $this->commandRepository = $commandRepository;
        $this->stockRepository = $stockRepository;
        $this->itemRepository = $itemRepository;
    }

    protected function index( $command_type = "command")
    {
        $commands = $this->commandRepository->getByUser(\Auth::user());
        return view('command.index', compact('commands'));

        /*
            if ($command_type == "command")
                $commands = $this->commandRepository->getByUser($request->user());
            else if ($command_type == "want")
                $commands = $this->commandRepository->getWantByUser($request->user());
            else
                throw new \Exception();

            return view($command_type . '.index', compact('commands'));
            */
    }

    public function showIndex($command_id, $command_type = "command")
    {
        switch ($command_type){
            case "command":
            $command = $this->commandRepository->getById($command_id);
            break;
            case "cart":
            $command = $this->commandRepository->getCartByUser(\Auth::user());
            break;
            case "want":
            $command = $this->commandRepository->getWantByUser(\Auth::user());
            break;
        }

        if (!isset($command))
            return abort(404);
        return view($command_type . '.show', compact('command'));
    }


    public function addItem(ItemAddRequest $request, $command_type = "command")
    {
        switch ($command_type) {
            case "command":
                $this->itemRepository->increase($request->id, $request->quantity);
                break;
            case "cart":
                $this->commandRepository->addItemToCart($request);
                break;
            case "want":
                $this->commandRepository->addItemToWant($request);
                break;
        }

        return redirect()->back();
    }

    public function removeItem(ItemRemoveRequest $request, $command_type = "command")
    {
        switch ($command_type) {
            case "command":
                $this->itemRepository->decrease($request->id,$request->quantity);
                break;
            case "cart":
                $this->commandRepository->removeItemFromCart($request);

                break;
            case "want":
                $this->commandRepository->removeItemFromWant($request);
                break;
        }
        return redirect()->back();
    }

}
