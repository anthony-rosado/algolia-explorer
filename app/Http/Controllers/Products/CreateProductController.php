<?php

namespace App\Http\Controllers\Products;

use App\Exceptions\Categories\CategoryNotFoundByIdException;
use App\Exceptions\Products\DuplicateProductCodeException;
use App\Exceptions\Products\InvalidParentCategoryAssignmentException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Products\CreateProductRequest;
use App\Http\Resources\Products\ProductResource;
use App\Services\CategoryService;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class CreateProductController extends Controller
{
    public function __construct(
        private readonly ProductService $productService,
        private readonly CategoryService $categoryService,
    ) {
    }

    public function __invoke(CreateProductRequest $request): JsonResponse|ProductResource
    {
        try {
            $product = $this->productService->findByCode($request->input('code'));

            if ($product !== null) {
                throw new DuplicateProductCodeException($request->input('code'));
            }

            $category = $this->categoryService->findById($request->input('category_id'));

            if ($category === null) {
                throw new CategoryNotFoundByIdException($request->input('category_id'));
            }

            if ($category->isParent()) {
                throw new InvalidParentCategoryAssignmentException();
            }

            $product = $this->productService->create(
                $request->input('code'),
                $request->input('name'),
                $request->input('description'),
                $request->input('is_available'),
                $request->input('price'),
                $request->input('stock'),
                $request->input('image_url'),
                $request->input('category_id'),
            );

            return ProductResource::make($product);
        } catch (DuplicateProductCodeException | InvalidParentCategoryAssignmentException $exception) {
            return response()->json(
                [
                    'error' => [
                        'message' => $exception->getMessage(),
                    ],
                ],
                Response::HTTP_BAD_REQUEST
            );
        } catch (CategoryNotFoundByIdException $exception) {
            return response()->json(
                [
                    'error' => [
                        'message' => $exception->getMessage(),
                    ],
                ],
                Response::HTTP_NOT_FOUND
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
