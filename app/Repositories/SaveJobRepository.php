<?php
namespace App\Repositories;

use App\Models\Job;
use App\Models\SavedJob;
use App\Repositories\ResponseRepository;
use App\Exceptions\NotFoundException;
use App\Http\Resources\SaveJob as SaveJobResource;
use App\Http\Resources\SaveJobCollection;

class SaveJobRepository extends BaseRepository
{
    public function __construct(ResponseRepository $responseRepo)
    {
        $this->responseRepo = $responseRepo;
        $this->perPage = config('enums.perPage');
        $this->nextId = null;
        $this->bannedStatus = 'banned_status';
        $this->disableBannedStatus = config('enums.disableBannedStatus');
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
        $saveJobList=[];
        $saveJobListData = new SaveJobCollection($saveJobList, $this->nextId);

        if ($request->nextId != "" && $request->nextId != 'null') {
            $saveJobListQuery = SavedJob::where('client_user_id', $request->auth_user_id);
            
            $saveJobs = $this->getPaginatedByNextId('id', $request->nextId, $saveJobListQuery);
            
            foreach ($saveJobs['result'] as $saveJob) {
                $saveJob['jobInfo'] = Job::where('id', $saveJob->job_id)->select('id', 'photo', 'title', 'created_at', 'zone_id', 'salary', 'company_name')->first();
            }

            $saveJobListData = new SaveJobCollection($saveJobs['result'], $this->nextId);

        }
        
        return $this->responseRepo->successResponse($saveJobListData, $this->responseRepo->successStatusCode, trans('message.successMsg'));
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
        return Job::where('id', $jobId)->where($this->bannedStatus, $this->disableBannedStatus)->first();
    }
}
