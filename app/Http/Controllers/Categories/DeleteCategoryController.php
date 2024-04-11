<?php

namespace App\Http\Controllers\Categories;

use App\Exceptions\Categories\CategoryNotFoundByIdException;
use App\Exceptions\Categories\ChildCategoryExistenceException;
use App\Exceptions\Categories\ProductCategoryExistenceException;
use App\Http\Controllers\Controller;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as FoundationResponse;
use Throwable;

class DeleteCategoryController extends Controller
{
    public function __construct(private readonly CategoryService $service)
    {
    }

    public function __invoke(int $categoryId): Response|JsonResponse
    {
        try {
            $category = $this->service->findById($categoryId);

            if ($category === null) {
                throw new CategoryNotFoundByIdException($categoryId);
            }

            $this->service->setModel($category);
            $this->service->delete();

            return response()->noContent();
        } catch (CategoryNotFoundByIdException $exception) {
            return response()->json(
                [
                    'error' => [
                        'message' => $exception->getMessage(),
                    ],
                ],
                FoundationResponse::HTTP_NOT_FOUND,
            );
        } catch (ChildCategoryExistenceException | ProductCategoryExistenceException $exception) {
            return response()->json(
                [
                    'error' => [
                        'message' => $exception->getMessage(),
                    ],
                ],
                FoundationResponse::HTTP_BAD_REQUEST,
            );
        } catch (Throwable $exception) {
            return response()->json(
                [
                    'error' => [
                        'message' => $exception->getMessage(),
                    ],
                ],
                FoundationResponse::HTTP_INTERNAL_SERVER_ERROR,
            );
        }
    }
}
