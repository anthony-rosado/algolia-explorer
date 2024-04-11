<?php

namespace App\Http\Controllers\Categories;

use App\Exceptions\Categories\CategoryNotFoundByIdException;
use App\Exceptions\Categories\ChildCategoryParentRemovalException;
use App\Exceptions\Categories\InappropriateParentCategoryAssignmentException;
use App\Exceptions\Categories\InvalidParentAssignmentException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Categories\UpdateCategoryRequest;
use App\Http\Resources\Categories\CategoryResource;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class UpdateCategoryController extends Controller
{
    public function __construct(private readonly CategoryService $service)
    {
    }

    public function __invoke(int $categoryId, UpdateCategoryRequest $request): CategoryResource|JsonResponse
    {
        try {
            $category = $this->service->findById($categoryId);

            if ($category === null) {
                throw new CategoryNotFoundByIdException($categoryId);
            }

            $this->service->setModel($category);
            $this->service->update(
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
        } catch (
            ChildCategoryParentRemovalException
            | InappropriateParentCategoryAssignmentException
            | InvalidParentAssignmentException $exception
        ) {
            return response()->json(
                [
                    'error' => [
                        'message' => $exception->getMessage(),
                    ],
                ],
                Response::HTTP_BAD_REQUEST
            );
        } catch (Throwable $throwable) {
            return response()->json(
                [
                    'error' => [
                        'message' => $throwable->getMessage(),
                    ],
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
