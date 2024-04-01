<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class ModelRepository
{
    protected Model $model;

    public function getModel(): Model
    {
        return $this->model;
    }

    public function setModel(Model $model): void
    {
        $this->model = $model;
    }
}
