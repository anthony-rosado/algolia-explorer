<?php

namespace App\Http\Controllers\Categories\Products;

use App\Http\Controllers\Controller;
use App\Http\Resources\Products\ProductResource;
use App\Models\Category;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GetProductsController extends Controller
{
    public function __invoke(Category $category): AnonymousResourceCollection
    {
        $products = $category->products()->simplePaginate();

        return ProductResource::collection($products);
    }
}
