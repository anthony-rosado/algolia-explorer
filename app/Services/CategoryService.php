<?php

namespace App\Services;

use App\Models\Category;
use App\Repositories\CategoryRepository;

readonly class CategoryService
{
    public function __construct(private CategoryRepository $repository)
    {
    }

    public function findById(int $id): ?Category
    {
        return $this->repository->findById($id);
    }
}
