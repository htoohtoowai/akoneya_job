<?php

namespace App\Repositories;
use App\Models\Job;

abstract class BaseRepository
{
    protected $model;
    public function __construct(\Model $model)
    {
        $this->model = $model;
        $this->disableBannedStatus = config('enums.disableBannedStatus');
    }

    public function getById($id)
    {
        return $this->model->where('id', $id)->first();
    }

    public function getActiveList()
    {
        return $this->model->where('deleted_status', config('enums.disableDeletedStatus'));
    }
    public function activeJobFind($jobId)
    {
        $checkIntJobId = checkInteger($jobId);

        return Job::where('id', $checkIntJobId)->where('banned_status', $this->disableBannedStatus)->first();
    }
    public function getPaginatedByNextId($columnId, $nextId, $queryInfo)
    {
       
        if ($nextId != 0) {
            $queryInfo = $queryInfo->where($columnId, '<=', $nextId);
        }
        $getAll = $queryInfo->orderBy($columnId, 'desc')->limit($this->perPage + 1)->get();
        
        $paginationCount = $getAll->count();
        
        if ($paginationCount > $this->perPage) {
            $this->nextId = $getAll[$this->perPage]->id;
            unset($getAll[$paginationCount - 1]);
        } else {
            $this->nextId = null;
        }
       
        return ["result"=>$getAll,"nextId"=>$this->nextId];
    }
}
