<?php
namespace App\Repositories;

use App\Models\Job;
use App\Models\SavedJob;
use App\Repositories\ResponseRepository;
use App\Exceptions\NotFoundException;

class SaveJobRepository
{
    public function __construct(ResponseRepository $responseRepo)
    {
        $this->responseRepo = $responseRepo;
        $this->perPage = config('enums.perPage');
    }
    public function saveJob($jobId, $userId)
    {
        $job = $this->jobFind($jobId);
        if (!$job) {
            throw new NotFoundException(trans('message.notFoundMsg'));
        }
        $saveJob = SavedJob::where("job_id", $jobId)->where("client_user_id", $userId)->first();
        if (!$saveJob) {
            SavedJob::create([
                "job_id" => $jobId,
                "client_user_id" => $userId
            ]);
            return $this->responseRepo->successResponse(null, $this->responseRepo->createStatusCode, trans('message.saveSuccessMsg'));
        }
        return $this->responseRepo->successResponse(null, $this->responseRepo->successStatusCode, trans('message.alreadySaved'));
    }
    public function jobFind($jobId)
    {
        return Job::find($jobId);
    }
}
