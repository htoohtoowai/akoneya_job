<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\JobRepository;
use App\Http\Requests\JobRequest;

class JobController extends Controller
{
    public function __construct(JobRepository $jobRepo)
    {
        $this->jobRepo = $jobRepo;
    }
    public function getJobs(Request $request)
    {
        return $this->jobRepo->getJobs($request,10);
    }
    public function createJob(JobRequest $request)
    {
        $clientUserId = $request->auth_user_id;

        return $this->jobRepo->createJob($request,$clientUserId);
    }
    public function updateJob()
    {
        return $this->jobRepo->updateJob();
    }
    public function deleteJob()
    {
        return $this->jobRepo->deleteJob();
    }
    
}
