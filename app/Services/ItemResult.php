<?php

namespace App\Services;

use Illuminate\Contracts\Support\Arrayable;

readonly class ItemResult implements Arrayable
{
    public function __construct(
        private int $id,
        private string $name,
        private float $price,
        private ?string $imageUrl,
        private string $subcategoryName,
        private string $categoryName,
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'image_url' => $this->imageUrl,
            'subcategory_name' => $this->subcategoryName,
            'category_name' => $this->categoryName,
        ];
    }
}
