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
}
