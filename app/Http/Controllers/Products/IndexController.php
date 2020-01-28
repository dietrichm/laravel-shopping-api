<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Http\Resources\Products\ProductResource;
use Domain\Products\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class IndexController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $products = Product::all();

        return ProductResource::collection($products)
            ->toResponse($request);
    }
}
