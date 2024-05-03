<?php

namespace App\Services\ThirdParty\Algolia;

readonly class SearchResponse
{
    public function __construct(
        public array $hits,
        public int $hitsCount,
        public int $hitsPerPage,
        public int $page,
        public int $pagesCount,
    ) {
    }
}
