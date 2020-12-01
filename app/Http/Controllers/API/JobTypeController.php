<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\JobTypeRepository;

class JobTypeController extends Controller
{
    public function __construct(JobTypeRepository $jobTypeRepo)
    {
        $this->jobTypeRepo = $jobTypeRepo;
    }

    public function index()
    {
        return $this->jobTypeRepo->index();
    }
}
