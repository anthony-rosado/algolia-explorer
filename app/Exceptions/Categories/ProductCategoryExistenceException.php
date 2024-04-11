<?php

namespace App\Exceptions\Categories;

use Exception;

class ProductCategoryExistenceException extends Exception
{
    public function __construct()
    {
        parent::__construct('Cannot delete category with products');
    }
}
