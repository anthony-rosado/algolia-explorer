<?php

namespace App\Http\Controllers\Products;

use App\Exceptions\Products\ProductNotFoundByIdException;
use App\Http\Controllers\Controller;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as FoundationResponse;
use Throwable;

class DeleteProductController extends Controller
{
    public function __construct(private readonly ProductService $service)
    {
    }

    public function __invoke(int $productId): Response|JsonResponse
    {
        try {
            $product = $this->service->findById($productId);

            if ($product === null) {
                throw new ProductNotFoundByIdException($productId);
            }

            $this->service->setModel($product);
            $this->service->delete();
        } catch (ProductNotFoundByIdException $exception) {
            return response()->json(
                [
                    'error' => [
                        'message' => $exception->getMessage(),
                    ],
                ],
                FoundationResponse::HTTP_NOT_FOUND,
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

        return response()->noContent();
    }
}
