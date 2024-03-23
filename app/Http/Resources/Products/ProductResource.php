<?php

namespace App\Http\Resources\Products;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * @var Product
     */
    public $resource;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'code' => $this->resource->code,
            'name' => $this->resource->name,
            'description' => $this->resource->description,
            'is_available' => $this->resource->is_available,
            'price' => $this->resource->price,
            'stock' => $this->resource->stock,
            'image_url' => $this->resource->image_url,
        ];
    }
}
