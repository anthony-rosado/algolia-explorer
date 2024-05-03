<?php

namespace App\Services;

use Illuminate\Contracts\Support\Arrayable;

readonly class SearchResult implements Arrayable
{
    /**
     * @var ItemResult[]
     */
    private array $data;
    private array $meta;

    /**
     * @param ItemResult[] $records
     * @param int $recordsCount
     * @param int $recordsPerPage
     * @param int $page
     * @param int $pagesCount
     */
    public function __construct(
        array $records,
        int $recordsCount,
        int $recordsPerPage,
        int $page,
        int $pagesCount,
    ) {
        $this->data = $records;
        $this->meta = [
            'records_count' => $recordsCount,
            'records_per_page' => $recordsPerPage,
            'page' => $page,
            'pages_count' => $pagesCount,
        ];
    }

    public function toArray(): array
    {
        return [
            'data' => $this->data,
            'meta' => $this->meta,
        ];
    }
}
