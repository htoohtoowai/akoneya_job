<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\JobRepository;
use App\Repositories\ZoneRepository;

use App\Http\Requests\JobRequest;
use App\Http\Requests\UpdateJobRequest;
use App\Filters\JobFilter;

class JobController extends Controller
{
    public function __construct(JobRepository $jobRepo, ZoneRepository $zoneRepo, JobFilter $jobFilter)
    {
        $this->jobRepo = $jobRepo;
        $this->zoneRepo = $zoneRepo;
        $this->jobFilter = $jobFilter;
    }
    public function getLocations()
    {
        return $this->zoneRepo->getZones();
    }
    public function getJobs(Request $request)
    {
        return $this->jobRepo->getJobs($request, $this->jobFilter);
    }
    public function createJob(JobRequest $request)
    {
        return $this->jobRepo->createJob($request, $request->auth_user_id);
    }
    public function updateJob(UpdateJobRequest $request, $id)
    {
        return $this->jobRepo->updateJob($request, $id, $request->auth_user_id);
    }
    public function deleteJob(Request $request, $id)
    {
        return $this->jobRepo->deleteJob($id, $request->auth_user_id);
    }
    public function details(Request $request, $id)
    {
        return $this->jobRepo->getDetails($id, $request);
    }
}
