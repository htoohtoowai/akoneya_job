<?php
namespace App\Repositories;

use App\Models\Job;
use App\Models\SavedJob;
use App\Repositories\ResponseRepository;
use App\Exceptions\NotFoundException;
use App\Http\Resources\SaveJob as SaveJobResource;
use App\Http\Resources\SaveJobCollection;

class SaveJobRepository
{
    public function __construct(ResponseRepository $responseRepo)
    {
        $this->responseRepo = $responseRepo;
        $this->perPage = config('enums.perPage');
        $this->nextId = null;
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
        }
        return $this->responseRepo->successResponse(null, $this->responseRepo->createStatusCode, trans('message.saveSuccessMsg'));
    }
    public function saveJobList($request)
    {
        if ($request->nextId == "" || $request->nextId == 'null') {
            $saveJobList=[];
        } else {
            $saveJobList = SavedJob::join('jobs', 'jobs.id', '=', 'saved_jobs.job_id')
                                ->where(function ($q) {
                                    if (request()->nextId != 0) {
                                        $q->where('saved_jobs.id', '<=', request()->nextId);
                                    }
                                    $q->where('saved_jobs.client_user_id', request()->auth_user_id);
                                })
                                ->select('saved_jobs.id as saveJobId', 'jobs.Id as jobId', 'jobs.photo', 'jobs.title', 'jobs.created_at', 'zone_id', 'jobs.salary', 'jobs.company_name')
                                ->orderBy('saveJobId', 'DESC')
                                ->limit($this->perPage+1)
                                ->get();

            $totalCount = $saveJobList->count();
        
            if ($totalCount > $this->perPage) {
                $this->nextId = $saveJobList[$this->perPage]->saveJobId;
           
                unset($saveJobList[$totalCount - 1]);
            }
        }
        
        return $this->responseRepo->successResponse(new SaveJobCollection($saveJobList, $this->nextId), $this->responseRepo->successStatusCode, trans('message.successMsg'));
    }
    public function saveJobDelete($jobId, $userId)
    {
        $job = SavedJob::where('job_id', $jobId)->where('client_user_id', $userId)->first();
        
        if (!$job) {
            throw new NotFoundException(trans('message.notFoundMsg'));
        }
        $job->delete();
        return $this->responseRepo->successResponse(null, $this->responseRepo->successStatusCode, trans('message.deleteSuccessMsg'));
    }
    public function jobFind($jobId)
    {
        return Job::find($jobId);
    }
}
