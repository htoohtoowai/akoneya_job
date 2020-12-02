<?php

namespace App\Repositories;


abstract class BaseRepository
{
    protected $model;

    public function __construct(\Model $model)
    {
        $this->model = $model;
    }

    public function getById($id)
    {
        return $this->model->where('id', $id)->first();
    }

    public function getActiveList()
    {
        return $this->model->where('deleted_status', config('enums.disableDeletedStatus'));
    }
}
