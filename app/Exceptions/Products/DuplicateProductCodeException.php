<?php

namespace App\Exceptions\Products;

use Exception;

class DuplicateProductCodeException extends Exception
{
    public function __construct(string $code)
    {
        parent::__construct("A product with the code {$code} already exists");
    }
}
