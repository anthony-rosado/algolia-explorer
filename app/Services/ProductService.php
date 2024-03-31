<?php

namespace App\Services;

use App\Models\Product;
use App\Repositories\ProductRepository;

readonly class ProductService
{
    public function __construct(private ProductRepository $repository)
    {
    }

    public function findByCode(string $code): ?Product
    {
        return $this->repository->findByCode($code);
    }
}
