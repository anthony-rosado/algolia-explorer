<?php

namespace App\Exceptions\Categories;

use Exception;

class ChildCategoryExistenceException extends Exception
{
    public function __construct()
    {
        parent::__construct('Cannot delete category with child categories');
    }
}
