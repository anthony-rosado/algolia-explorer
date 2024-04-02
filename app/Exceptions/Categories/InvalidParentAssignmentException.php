<?php

namespace App\Exceptions\Categories;

use Exception;

class InvalidParentAssignmentException extends Exception
{
    public function __construct(string $parentCategoryName)
    {
        parent::__construct("Category {$parentCategoryName} cannot be assigned as a parent category");
    }
}
