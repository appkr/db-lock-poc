<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\CreateProductRequest;
use App\Http\Requests\Product\DeleteProductRequest;
use App\Http\Requests\Product\ListProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use Myshop\Domain\Model\Product;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.basic.once', ['except' => 'index']);
    }

    public function index(ListProductRequest $request)
    {
        return $request->all();
    }

    public function store(CreateProductRequest $request)
    {
        return $request->all();
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        return $request->all();
    }

    public function destroy(DeleteProductRequest $request, Product $product)
    {
        return $request->all();
    }
}
