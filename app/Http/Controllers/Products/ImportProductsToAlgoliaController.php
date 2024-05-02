<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use function Sentry\captureException;

class ImportProductsToAlgoliaController extends Controller
{
    public function __construct(private readonly ProductService $productService)
    {
    }

    public function __invoke(): JsonResponse
    {
        try {
            $this->productService->importToAlgolia();

            return response()->json(
                [
                    'data' => [
                        'message' => 'Products have been imported to Algolia successfully',
                    ]
                ]
            );
        } catch (Throwable $throwable) {
            captureException($throwable);

            return response()->json(
                [
                    'error' => [
                        'message' => 'An error occurred while importing products to Algolia',
                    ]
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
