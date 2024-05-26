<?php

namespace App\Services;

use App\Exceptions\ThirdParty\Algolia\SearchErrorException;
use App\Models\Product;
use App\Repositories\ProductRepository;
use App\Services\ThirdParty\Algolia\IndexManager;
use App\Services\ThirdParty\Algolia\Record;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Collection;

readonly class ProductService
{
    public function __construct(private ProductRepository $repository)
    {
    }

    public function setModel(Product $product): void
    {
        $this->repository->setModel($product);
    }

    public function getModel(): Product
    {
        return $this->repository->getModel();
    }

    public function getListPaginated(
        ?string $name,
        ?bool $isAvailable,
        int $perPage = 1,
        int $page = 1,
    ): Paginator {
        return $this->repository->fetchPaginated($name, $isAvailable, $perPage, $page);
    }

    public function findById(int $id): ?Product
    {
        return $this->repository->findById($id);
    }

    public function findByCode(string $code): ?Product
    {
        return $this->repository->findByCode($code);
    }

    public function create(
        string $code,
        string $name,
        string $description,
        bool $isAvailable,
        float $price,
        int $stock,
        ?string $imageUrl,
        int $categoryId,
    ): Product {
        return $this->repository->create(
            $code,
            $name,
            $description,
            $isAvailable,
            $price,
            $stock,
            $imageUrl,
            $categoryId,
        );
    }

    public function update(
        string $code,
        string $name,
        string $description,
        bool $isAvailable,
        float $price,
        int $stock,
        ?string $imageUrl,
        int $categoryId,
    ): void {
        $this->repository->update(
            $code,
            $name,
            $description,
            $isAvailable,
            $price,
            $stock,
            $imageUrl,
            $categoryId,
        );
    }

    public function importToAlgolia(): void
    {
        $records = $this->getIndexableRecords();

        $indexManager = new IndexManager();
        $indexManager->bulkSaveRecords($records);
    }

    /**
     * @throws SearchErrorException
     */
    public function searchInAlgolia(string $query, int $page, int $perPage): SearchResult
    {
        $indexManager = new IndexManager();
        $response = $indexManager->searchRecords($query, $page - 1, $perPage);

        $records = collect($response->hits)
            ->map(function (array $record) {
                return new ItemResult(
                    $record['objectID'],
                    $record['name'],
                    $record['price'],
                    $record['image_url'],
                    $record['subcategory_name'],
                    $record['category_name'],
                );
            });

        return new SearchResult(
            $records->toArray(),
            $response->hitsCount,
            $response->hitsPerPage,
            $response->page + 1,
            $response->pagesCount,
        );
    }

    public function delete(): void
    {
        $this->repository->delete();
    }

    private function getIndexableRecords(): Collection
    {
        $products = $this->repository->fetchIndexables();

        return $products->map(function ($product) {
            /** @var Product $product */
            return new Record(
                $product->id,
                $product->name,
                (float)$product->price,
                $product->image_url,
                $product->category->name,
                $product->category->parent->name,
            );
        });
    }
}
