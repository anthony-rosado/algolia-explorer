<?php

namespace App\Services;

use App\Models\Category;
use App\Repositories\CategoryRepository;

readonly class CategoryService
{
    public function __construct(private CategoryRepository $repository)
    {
    }

    public function getModel(): Category
    {
        return $this->repository->getModel();
    }

    public function setModel(Category $category): void
    {
        $this->repository->setModel($category);
    }

    public function findById(int $id): ?Category
    {
        return $this->repository->findById($id);
    }
}
