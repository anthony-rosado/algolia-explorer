<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository
{
    public function findByCode(string $code): ?Product
    {
        return Product::query()
            ->where('code', '=', $code)
            ->first();
    }

    public function create(
        string $code,
        string $name,
        string $description,
        bool $isAvailable,
        float $price,
        int $stock,
        ?string $imageUrl,
        int $categoryId,
    ): Product {
        $product = new Product();
        $product->code = $code;
        $product->name = $name;
        $product->description = $description;
        $product->is_available = $isAvailable;
        $product->price = $price;
        $product->stock = $stock;
        $product->image_url = $imageUrl;
        $product->category()->associate($categoryId);
        $product->save();

        return $product;
    }
}
