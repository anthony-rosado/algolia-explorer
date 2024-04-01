<?php

namespace App\Repositories;

use App\Models\Product;

/**
 * @property Product $model
 * @method Product getModel()
 * @method void setModel(Product $model)
 */
class ProductRepository extends ModelRepository
{
    public static function findById(int $id): ?Product
    {
        return Product::query()->find($id);
    }

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

    public function update(
        string $code,
        string $name,
        string $description,
        bool $isAvailable,
        float $price,
        int $stock,
        ?string $imageUrl,
        int $categoryId,
    ): void {
        $this->model->code = $code;
        $this->model->name = $name;
        $this->model->description = $description;
        $this->model->is_available = $isAvailable;
        $this->model->price = $price;
        $this->model->stock = $stock;
        $this->model->image_url = $imageUrl;
        $this->model->category()->associate($categoryId);
        $this->model->save();
    }
}
