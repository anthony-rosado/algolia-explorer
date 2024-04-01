<?php

namespace App\Services;

use App\Models\Product;
use App\Repositories\ProductRepository;

readonly class ProductService
{
    public function __construct(private ProductRepository $repository)
    {
    }

    public function setModel(Product $product): void
    {
        $this->repository->setModel($product);
    }

    public function getModel(): Product
    {
        return $this->repository->getModel();
    }

    public function findById(int $id): ?Product
    {
        return $this->repository->findById($id);
    }

    public function findByCode(string $code): ?Product
    {
        return $this->repository->findByCode($code);
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
        return $this->repository->create(
            $code,
            $name,
            $description,
            $isAvailable,
            $price,
            $stock,
            $imageUrl,
            $categoryId,
        );
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
        $this->repository->update(
            $code,
            $name,
            $description,
            $isAvailable,
            $price,
            $stock,
            $imageUrl,
            $categoryId,
        );
    }
}
