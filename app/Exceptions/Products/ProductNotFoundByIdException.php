<?php

namespace App\Exceptions\Products;

use Exception;

class ProductNotFoundByIdException extends Exception
{
    public function __construct(int $id)
    {
        parent::__construct("Product with id {$id} not found");
    }
}
