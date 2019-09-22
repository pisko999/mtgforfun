<?php

namespace App\Http\Middleware;

use App\Repositories\CategoryRepository;
use Closure;
use Illuminate\Support\Facades\View;

class navbarMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $categoryRepository = new CategoryRepository();
        $navbarItems = $categoryRepository->getAll();
//var_dump($navbarItems);
        View::share('navbarItems', $navbarItems);
        return $next($request);
    }
}
