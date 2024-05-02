<?php

namespace App\Services\ThirdParty\Algolia;

use Algolia\AlgoliaSearch\Exceptions\MissingObjectId;
use Algolia\AlgoliaSearch\SearchClient;
use Algolia\AlgoliaSearch\SearchIndex;
use Illuminate\Support\Collection;

class IndexManager
{
    private const NAME = 'products';

    private readonly SearchIndex $searchIndex;

    public function __construct()
    {
        $searchClient = SearchClient::create(
            config('services.algolia.app_id'),
            config('services.algolia.api_key'),
        );
        $this->searchIndex = $searchClient->initIndex(self::NAME);
    }

    public function addRecord(Record $record): void
    {
        $this->searchIndex->saveObject($record->toArray());
    }

    public function patchRecord(Record $record): void
    {
        $this->searchIndex->partialUpdateObject($record->toArray());
    }

    /**
     * @param Collection<Record> $records
     * @return void
     * @throws MissingObjectId
     */
    public function bulkSaveRecords(Collection $records): void
    {
        if ($records->isEmpty()) {
            return;
        }

        $this->searchIndex->saveObjects($records->toArray())->wait();
    }

    public function removeRecord(int $id): void
    {
        $this->searchIndex->deleteObject($id);
    }
}
