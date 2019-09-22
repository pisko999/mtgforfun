<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\CommandRepositoryInterface;
use App\Http\Controllers\Controller;

class CommandController extends Controller
{
    protected $commandRepository;

    public function __construct(CommandRepositoryInterface $commandRepository)
    {
        $this->commandRepository = $commandRepository;
        $this->middleware('auth');
        $this->middleware('admin');
    }


    public function commands()
    {
        $commands = $this->commandRepository->getCommands();
        return view('command.index', compact('commands'));
    }
}
