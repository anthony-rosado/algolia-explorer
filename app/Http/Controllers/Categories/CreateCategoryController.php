<?php

namespace App\Http\Controllers\Categories;

use App\Http\Controllers\Controller;
use App\Http\Requests\Categories\CreateCategoryRequest;
use App\Http\Resources\Categories\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CreateCategoryController extends Controller
{
    public function __invoke(CreateCategoryRequest $request): CategoryResource|JsonResponse
    {
        $parentCategory = null;

        if ($request->filled('parent_id')) {
            $parentCategory = Category::query()->find($request->input('parent_id'));

            if (is_null($parentCategory)) {
                return response()->json(
                    [
                        'error' => [
                            'message' => 'Parent category not found',
                        ]
                    ],
                    404
                );
            }

            if (!$parentCategory->isParent()) {
                return response()->json(
                    [
                        'error' => [
                            'message' => "Category '{$parentCategory->name}' is not a parent category",
                        ]
                    ],
                    400
                );
            }
        }

        $category = Category::query()->make($request->only(['name', 'description']));
        $category->parent()->associate($parentCategory);
        $category->save();

        return CategoryResource::make($category);
    }
}
