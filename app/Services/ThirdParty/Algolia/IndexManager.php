<?php

namespace App\Services\ThirdParty\Algolia;

use Algolia\AlgoliaSearch\SearchClient;
use Algolia\AlgoliaSearch\SearchIndex;

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

    public function removeRecord(int $id): void
    {
        $this->searchIndex->deleteObject($id);
    }
}
