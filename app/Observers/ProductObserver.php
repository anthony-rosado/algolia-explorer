<?php

namespace App\Observers;

use Algolia\AlgoliaSearch\SearchClient;
use App\Models\Product;

class ProductObserver
{
    public function created(Product $product): void
    {
        if (!$product->is_available) {
            return;
        }

        $client = SearchClient::create(
            config('services.algolia.app_id'),
            config('services.algolia.api_key'),
        );

        $index = $client->initIndex('products');

        $record = [
            'objectID' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'image_url' => $product->image_url,
            'subcategory' => $product->category->name,
            'category' => $product->category->parent->name,
        ];

        $index->saveObject($record);
    }

    public function updated(Product $product): void
    {
        $client = SearchClient::create(
            config('services.algolia.app_id'),
            config('services.algolia.api_key'),
        );

        $index = $client->initIndex('products');

        if ($product->is_available) {
            $record = [
                'objectID' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'image_url' => $product->image_url,
                'subcategory' => $product->category->name,
                'category' => $product->category->parent->name,
            ];

            $index->partialUpdateObject($record);
        } else {
            $index->deleteObject($product->id);
        }
    }

    public function deleted(Product $product): void
    {
        $client = SearchClient::create(
            config('services.algolia.app_id'),
            config('services.algolia.api_key'),
        );

        $index = $client->initIndex('products');

        $index->deleteObject($product->id);
    }
}
