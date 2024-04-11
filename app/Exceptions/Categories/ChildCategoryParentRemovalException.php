<?php

namespace App\Exceptions\Categories;

use Exception;

class ChildCategoryParentRemovalException extends Exception
{
    public function __construct()
    {
        parent::__construct('Cannot remove parent category from a child category');
    }
}
