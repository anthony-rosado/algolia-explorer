<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Products\GetProductsRequest;
use App\Http\Resources\Products\ProductResource;
use App\Services\ProductService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GetProductsController extends Controller
{
    public function __construct(private readonly ProductService $service)
    {
    }

    public function __invoke(GetProductsRequest $request): AnonymousResourceCollection
    {
        $products = $this->service->getListPaginated(
            $request->query('name'),
            $request->query('is_available'),
            $request->query('per_page'),
            $request->query('page'),
        );

        return ProductResource::collection($products);
    }
}
