<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\ReportRepository;

class ReportController extends Controller
{
    public function __construct(ReportRepository $reportRepo)
    {
        $this->reportRepo = $reportRepo;

    }
    
    public function getReportReason(Request $request)
    {
        return $this->reportRepo->getReportReason($request);
    }

    public function createJobReport(Request $request, $jobId)
    {
        return $this->reportRepo->createJobReport($request, $jobId);
    }
    
}
