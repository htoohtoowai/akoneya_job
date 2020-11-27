<?php
namespace App\Repositories;

use App\Models\Job;
use App\Repositories\ResponseRepository;
use App\PersonalInformation;

class CommentLikeRepository extends BaseRepository
{
    public function __construct(Job $model, PersonalInformation $personalInfoModel, ResponseRepository $responseRepo)
    {
        $this->model = $model;
        $this->personalInfoModel = $personalInfoModel;
        $this->responseRepo = $responseRepo;
        $this->successStatusCode = config('http_status.success');
        $this->perPage = config('enums.perPage');
    }

    public function getJob()
    {
        return $this->model->count();
    }

}
