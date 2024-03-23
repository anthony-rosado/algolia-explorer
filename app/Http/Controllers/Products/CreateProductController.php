<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Products\CreateProductRequest;
use App\Http\Resources\Products\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class CreateProductController extends Controller
{
    public function __invoke(CreateProductRequest $request): JsonResponse|ProductResource
    {
        $productExists = Product::query()
            ->where('code', $request->input('code'))
            ->exists();

        if ($productExists) {
            return response()->json(
                [
                    'error' => [
                        'message' => 'Product with the same code already exists',
                    ],
                ],
                400
            );
        }

        $product = Product::query()->create($request->validated());

        return ProductResource::make($product);
    }
}
