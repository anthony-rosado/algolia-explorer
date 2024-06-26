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
        $name = $request->has('name') ? (string)$request->query('name') : null;
        $isAvailable = $request->has('is_available') ? (bool)$request->query('is_available') : null;
        $perPage = $request->has('per_page') ? (int)$request->query('per_page') : null;
        $page = $request->has('page') ? (int)$request->query('page') : null;

        $products = $this->service->getListPaginated(
            $name,
            $isAvailable,
            $perPage,
            $page,
        );

        return ProductResource::collection($products);
    }
}
