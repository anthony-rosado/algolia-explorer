<?php

namespace App\Exceptions\ThirdParty\Algolia;

use Algolia\AlgoliaSearch\Exceptions\AlgoliaException;
use Throwable;

class SearchErrorException extends AlgoliaException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('Search error occurred', 0, $previous);
    }
}
