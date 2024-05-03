<?php

namespace App\Http\Controllers\Products;

use App\Exceptions\ThirdParty\Algolia\SearchErrorException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Products\SearchProductsInAlgoliaRequest;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use function Sentry\captureException;

class SearchProductsInAlgoliaController extends Controller
{
    public function __construct(private readonly ProductService $service)
    {
    }

    public function __invoke(SearchProductsInAlgoliaRequest $request): JsonResponse
    {
        try {
            $searchResult = $this->service->searchInAlgolia(
                $request->query('query'),
                $request->query('page', 1),
                $request->query('per_page', 15)
            );

            return response()->json($searchResult->toArray());
        } catch (SearchErrorException $exception) {
            captureException($exception);

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
