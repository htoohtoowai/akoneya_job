<?php

namespace App\Repositories;
use App\Models\JobType;
use App\Repositories\ResponseRepository;
use App\Http\Resources\JobTypeResource;

class JobTypeRepository extends BaseRepository
{
	public function __construct(JobType $model, ResponseRepository $responseRepo)
    {
    	$this->model = $model;
    	$this->responseRepo = $responseRepo;
    }

    public function index()
    {
        $data = $this->getActiveList()->orderBy('name')->get();
        return $this->responseRepo->successResponse(JobTypeResource::collection($data), $this->responseRepo->successStatusCode, trans('message.successMsg'));
    }
}