<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository
{
    public function findById(int $id): ?Category
    {
        return Category::query()
            ->where('id', '=', $id)
            ->first();
    }
}
