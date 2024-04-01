<?php

namespace App\Http\Controllers\Products;

use App\Exceptions\Categories\CategoryNotFoundByIdException;
use App\Exceptions\Products\DuplicateProductCodeException;
use App\Exceptions\Products\InvalidParentCategoryAssignmentException;
use App\Exceptions\Products\ProductNotFoundByIdException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Products\UpdateProductRequest;
use App\Http\Resources\Products\ProductResource;
use App\Services\CategoryService;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class UpdateProductController extends Controller
{
    public function __construct(
        readonly private ProductService $productService,
        readonly private CategoryService $categoryService,
    ) {
    }

    public function __invoke(int $productId, UpdateProductRequest $request): JsonResponse|ProductResource
    {
        try {
            $product = $this->productService->findById($productId);

            if ($product === null) {
                throw new ProductNotFoundByIdException($productId);
            }

            $anotherProduct = $this->productService->findByCode($request->input('code'));

            if ($anotherProduct !== null && $anotherProduct->id !== $product->id) {
                throw new DuplicateProductCodeException($request->input('code'));
            }

            $category = $this->categoryService->findById($request->input('category_id'));

            if ($category === null) {
                throw new CategoryNotFoundByIdException($request->input('category_id'));
            }

            if ($category->isParent()) {
                throw new InvalidParentCategoryAssignmentException();
            }

            $this->productService->setModel($product);
            $this->productService->update(
                $request->input('code'),
                $request->input('name'),
                $request->input('description'),
                $request->input('is_available'),
                $request->input('price'),
                $request->input('stock'),
                $request->input('image_url'),
                $request->input('category_id'),
            );

            $product = $this->productService->getModel();

            return ProductResource::make($product);
        } catch (ProductNotFoundByIdException | CategoryNotFoundByIdException $exception) {
            return response()->json(
                [
                    'error' => [
                        'message' => $exception->getMessage(),
                    ],
                ],
                Response::HTTP_NOT_FOUND
            );
        } catch (DuplicateProductCodeException | InvalidParentCategoryAssignmentException $exception) {
            return response()->json(
                [
                    'error' => [
                        'message' => $exception->getMessage(),
                    ],
                ],
                Response::HTTP_BAD_REQUEST
            );
        } catch (Throwable $exception) {
            return response()->json(
                [
                    'error' => [
                        'message' => $exception->getMessage(),
                    ],
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
