<?php

namespace App\Exceptions\Products;

use Exception;

class InvalidParentCategoryAssignmentException extends Exception
{
    public function __construct()
    {
        parent::__construct('Cannot assign a parent category to a product');
    }
}
