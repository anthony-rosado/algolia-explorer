<?php

namespace App\Http\Controllers\Categories;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Response;

class DeleteCategoryController extends Controller
{
    public function __invoke(Category $category): Response
    {
        $category->delete();

        return response()->noContent();
    }
}
