<?php

namespace App\Exceptions\Categories;

use Exception;

class InappropriateParentCategoryAssignmentException extends Exception
{
    public function __construct()
    {
        parent::__construct("Cannot assign parent category to a parent category");
    }
}
