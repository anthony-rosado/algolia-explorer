<?php

namespace App\Observers;

use App\Models\Product;
use App\Services\ThirdParty\Algolia\IndexManager;
use App\Services\ThirdParty\Algolia\Record;

readonly class ProductObserver
{
    public function __construct(private IndexManager $index)
    {
    }

    public function created(Product $product): void
    {
        if (!$product->is_available) {
            return;
        }

        $record = new Record(
            $product->id,
            $product->name,
            (float)$product->price,
            $product->image_url,
            $product->category->name,
            $product->category->parent->name,
        );

        $this->index->addRecord($record);
    }

    public function updated(Product $product): void
    {
        if (!$product->is_available || is_null($product->category)) {
            $this->index->removeRecord($product->id);

            return;
        }

        $record = new Record(
            $product->id,
            $product->name,
            (float)$product->price,
            $product->image_url,
            $product->category->name,
            $product->category->parent->name,
        );

        $this->index->patchRecord($record);
    }

    public function deleted(Product $product): void
    {
        $this->index->removeRecord($product->id);
    }
}
