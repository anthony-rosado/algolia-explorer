<?php

namespace App\Http\Controllers\Categories;

use App\Exceptions\Categories\CategoryNotFoundByIdException;
use App\Exceptions\Categories\InvalidParentAssignmentException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Categories\CreateCategoryRequest;
use App\Http\Resources\Categories\CategoryResource;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CreateCategoryController extends Controller
{
    public function __construct(private readonly CategoryService $service)
    {
    }

    public function __invoke(CreateCategoryRequest $request): CategoryResource|JsonResponse
    {
        try {
            $this->service->create(
                $request->input('name'),
                $request->input('description'),
                $request->input('parent_id')
            );

            $category = $this->service->getModel();

            return CategoryResource::make($category);
        } catch (CategoryNotFoundByIdException $exception) {
            return response()->json(
                [
                    'error' => [
                        'message' => $exception->getMessage(),
                    ],
                ],
                Response::HTTP_NOT_FOUND
            );
        } catch (InvalidParentAssignmentException $exception) {
            return response()->json(
                [
                    'error' => [
                        'message' => $exception->getMessage(),
                    ],
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
}
