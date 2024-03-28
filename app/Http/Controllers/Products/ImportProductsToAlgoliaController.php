<?php

namespace App\Http\Controllers\Products;

use Algolia\AlgoliaSearch\Exceptions\MissingObjectId;
use Algolia\AlgoliaSearch\SearchClient;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Throwable;

class ImportProductsToAlgoliaController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $products = Product::query()
            ->with(['category.parent'])
            ->whereIsAvailable(true)
            ->get();

        $client = SearchClient::create(
            config('services.algolia.app_id'),
            config('services.algolia.api_key'),
        );

        $index = $client->initIndex('products');

        $records = $products->map(function ($product) {
            return [
                'objectID' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'image_url' => $product->image_url,
                'subcategory' => $product->category->name,
                'category' => $product->category->parent->name,
            ];
        });

        try {
            $index->saveObjects($records)->wait();
        } catch (MissingObjectId $exception) {
            return response()->json(
                [
                    'error' => [
                        'message' => 'Missing objectID in the records',
                        'exception' => $exception->getMessage(),
                    ]
                ],
                500
            );
        } catch (Throwable $throwable) {
            return response()->json(
                [
                    'error' => [
                        'message' => 'An error occurred while importing products to Algolia',
                        'exception' => $throwable->getMessage(),
                    ]
                ],
                500
            );
        }

        return response()->json(
            [
                'data' => [
                    'message' => 'Products have been imported to Algolia successfully',
                ]
            ]
        );
    }
}
