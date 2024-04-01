<?php

namespace App\Exceptions\Categories;

use Exception;

class CategoryNotFoundByIdException extends Exception
{
    public function __construct(int $id)
    {
        parent::__construct("Category with id {$id} not found");
    }
}
