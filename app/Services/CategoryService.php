<?php

namespace App\Services;

use App\Exceptions\Categories\CategoryNotFoundByIdException;
use App\Exceptions\Categories\ChildCategoryParentRemovalException;
use App\Exceptions\Categories\InappropriateParentCategoryAssignmentException;
use App\Exceptions\Categories\InvalidParentAssignmentException;
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
    * @throws CategoryNotFoundByIdException
    * @throws ChildCategoryParentRemovalException
    * @throws InappropriateParentCategoryAssignmentException
    * @throws InvalidParentAssignmentException
     */
    public function update(
        string $name,
        string $description,
        ?int $parentId = null
    ): void {
        $category = $this->getModel();
        $parentCategory = null;

        if ($category->isParent() && !is_null($parentId)) {
            throw new InappropriateParentCategoryAssignmentException();
        }

        if ($category->isChild() && is_null($parentId)) {
            throw new ChildCategoryParentRemovalException();
        }

        if (!is_null($parentId)) {
            $parentCategory = $this->repository->findById($parentId);

            if (is_null($parentCategory)) {
                throw new CategoryNotFoundByIdException($parentId);
            }

            if (!$parentCategory->isParent()) {
                throw new InvalidParentAssignmentException($parentCategory->name);
            }
        }

        $this->repository->update($name, $description, $parentCategory?->id);
    }
}
