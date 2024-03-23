<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Products\GetProductsRequest;
use App\Http\Resources\Products\ProductResource;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GetProductsController extends Controller
{
    public function __invoke(GetProductsRequest $request): AnonymousResourceCollection
    {
        $products = Product::query()
            ->when(
                $request->filled('name'),
                fn(Builder|Product $query) => $query->whereNameLike($request->query('name')),
            )
            ->when(
                $request->filled('is_available'),
                fn(Builder|Product $query) => $query->whereIsAvailable($request->query('is_available')),
            )
            ->simplePaginate();

        return ProductResource::collection($products);
    }
}
