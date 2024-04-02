<?php

namespace App\Services;

use App\Exceptions\Categories\CategoryNotFoundByIdException;
use App\Exceptions\Categories\ChildCategoryExistenceException;
use App\Exceptions\Categories\InvalidParentAssignmentException;
use App\Exceptions\Categories\ProductCategoryExistenceException;
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

    /**
     * @throws CategoryNotFoundByIdException
     * @throws InvalidParentAssignmentException
     */
    public function create(
        string $name,
        string $description,
        ?int $parentId = null
    ): void {
        $parentCategory = null;

        if ($parentId !== null) {
            $parentCategory = $this->repository->findById($parentId);

            if (is_null($parentCategory)) {
                throw new CategoryNotFoundByIdException($parentId);
            }

            if (!$parentCategory->isParent()) {
                throw new InvalidParentAssignmentException($parentCategory->name);
            }
        }

        $this->repository->create($name, $description, $parentCategory?->id);
    }

    /**
     * @throws ChildCategoryExistenceException
     * @throws ProductCategoryExistenceException
     */
    public function delete(): void
    {
        $category = $this->getModel()->loadCount(['children', 'products']);

        if ($category->children_count > 0) {
            throw new ChildCategoryExistenceException();
        }

        if ($category->products_count > 0) {
            throw new ProductCategoryExistenceException();
        }

        $this->repository->delete();
    }
}
