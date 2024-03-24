<?php

namespace App\Http\Controllers\Categories;

use App\Http\Controllers\Controller;
use App\Http\Requests\Categories\GetCategoriesRequest;
use App\Http\Resources\Categories\CategoryResource;
use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GetCategoriesController extends Controller
{
    public function __invoke(GetCategoriesRequest $request): AnonymousResourceCollection
    {
        $categories = Category::query()
            ->when(
                $request->filled('name'),
                fn(Builder|Category $query) => $query->whereNameLike($request->query('name'))
            )
            ->onlyParents()
            ->get();

        return CategoryResource::collection($categories);
    }
}
