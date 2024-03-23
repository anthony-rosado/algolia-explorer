<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Products\UpdateProductRequest;
use App\Http\Resources\Products\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class UpdateProductController extends Controller
{
    public function __invoke(Product $product, UpdateProductRequest $request): JsonResponse|ProductResource
    {
        $productExists = Product::query()
            ->where('code', $request->input('code'))
            ->where('id', '!=', $product->id)
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

        $product->update($request->validated());

        return ProductResource::make($product);
    }
}
