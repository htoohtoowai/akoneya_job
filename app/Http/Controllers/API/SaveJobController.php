<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\SaveJobRepository;

class SaveJobController extends Controller
{
    public function __construct(SaveJobRepository $SaveJobRepository)
    {
        $this->SaveJobRepository = $SaveJobRepository;
    }
    public function saveJob(Request $request, $jobId)
    {
        return $this->SaveJobRepository->saveJob($jobId, $request->auth_user_id);
    }
    public function saveJobList(Request $request)
    {
        return $this->SaveJobRepository->saveJobList($request);
    }
    public function saveJobDelete(Request $request, $jobId)
    {
        return $this->SaveJobRepository->saveJobDelete($jobId, $request->auth_user_id);
    }
}
