<?php

namespace App\Http\Controllers\Categories;

use App\Http\Requests\Categories\GetCategoriesRequest;
use App\Http\Resources\Categories\CategoryResource;
use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GetChildrenOfCategoryController
{
    public function __invoke(Category $category, GetCategoriesRequest $request): AnonymousResourceCollection
    {
        $categories = Category::query()
            ->when(
                $request->filled('name'),
                fn(Builder|Category $query) => $query->whereNameLike($request->query('name'))
            )
            ->whereParentId($category->id)
            ->get();

        return CategoryResource::collection($categories);
    }
}
