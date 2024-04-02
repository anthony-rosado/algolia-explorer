<?php

namespace App\Repositories;

use App\Models\Category;

/**
 * @property Category $model
 * @method Category getModel()
 * @method void setModel(Category $model)
 */
class CategoryRepository extends ModelRepository
{
    public function findById(int $id): ?Category
    {
        return Category::query()
            ->where('id', '=', $id)
            ->first();
    }

    public function create(
        string $name,
        string $description,
        ?int $parentId = null
    ): void {
        $category = new Category();
        $category->name = $name;
        $category->description = $description;
        $category->parent()->associate($parentId);
        $category->save();

        $this->setModel($category);
    }

    public function delete(): void
    {
        $this->model->delete();
    }
}
