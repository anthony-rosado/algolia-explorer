<?php

namespace App\Http\Controllers\Products;

use Algolia\AlgoliaSearch\SearchClient;
use App\Http\Requests\Products\SearchProductsInAlgoliaRequest;
use Illuminate\Http\JsonResponse;

class SearchProductsInAlgoliaController
{
    public function __invoke(SearchProductsInAlgoliaRequest $request): JsonResponse
    {
        $client = SearchClient::create(
            config('services.algolia.app_id'),
            config('services.algolia.api_key'),
        );

        $index = $client->initIndex('products');

        $index->setSettings(
            [
                'searchableAttributes' => ['name', 'subcategory', 'category'],
                'attributesToRetrieve' => ['name', 'price', 'image_url', 'subcategory', 'category'],
            ]
        );

        $results = $index->search($request->query('query'), ['page' => $request->query('page') - 1]);

        $products = collect($results['hits'])
            ->map(function ($product) {
                return [
                    'id' => $product['objectID'],
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'image_url' => $product['image_url'],
                    'subcategory' => $product['subcategory'],
                    'category' => $product['category'],
                ];
            });

        return response()->json(
            [
                'data' => $products,
                'meta' => [
                    'current_page' => $results['page'] + 1,
                    'total_pages' => $results['nbPages'],
                    'records_per_page' => $results['hitsPerPage'],
                    'total_records' => $results['nbHits'],
                ]
            ]
        );
    }
}
