<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

class AbstractRepository
{
    /**
     * @return \Illuminate\Database\Eloquent\Builder|Model|mixed
     */
    final protected function getNewQuery()
    {
        return $this->model->newQuery();
    }
}
