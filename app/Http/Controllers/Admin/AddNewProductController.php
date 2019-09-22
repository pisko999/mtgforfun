<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ProductCreateRequest;
use App\Repositories\CategoryRepositoryInterface;
use App\Repositories\ProductRepositoryInterface;
use App\Http\Controllers\Controller;
use http\Env\Request;

class AddNewProductController extends Controller
{

    protected $categoryRepository;
    protected $productRepository;

    public function __construct(
        CategoryRepositoryInterface $categoryRepository,
        ProductRepositoryInterface $productRepository
    )
    {
        $this->categoryRepository = $categoryRepository;
        $this->productRepository = $productRepository;
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function addNewProductGet(CategoryRepositoryInterface $categoryRepository)
    {
        $categories = $categoryRepository->getAll();

        if (count($categories) == 0)
            return abort(404);
        return view("admin.addNewProduct", compact("categories"));
    }

    public function addNewProductPost(ProductCreateRequest $request, CategoryRepositoryInterface $categoryRepository)
    {
        $product = $this->productRepository->createProduct($request, $categoryRepository);
        return redirect()->route("admin.addNewProductGet");
    }

}
