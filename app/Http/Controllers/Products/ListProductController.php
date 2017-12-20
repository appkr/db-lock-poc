<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ListProductRequest;
use App\Transformers\ProductTransformer;
use Appkr\Api\Http\Response;
use Myshop\Domain\Repository\ProductRepository;

class ListProductController extends Controller
{
    final public function __invoke(
        ListProductRequest $request,
        ProductRepository $repository,
        Response $presenter
    ) {
        $paginatedProductCollection = $repository->findBySearchParam(
            $request->getProductSearchParam()
        );

        return $presenter->withPagination(
            $paginatedProductCollection,
            new ProductTransformer
        );
    }
}
