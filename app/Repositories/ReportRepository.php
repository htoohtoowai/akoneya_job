<?php
namespace App\Repositories;

use App\Models\JobReport;
use App\Models\JobReportReason;
use App\Repositories\ResponseRepository;
use App\Repositories\JobRepository;
use App\Exceptions\NotFoundException;

class ReportRepository extends BaseRepository
{
    public function __construct(JobReportReason $model, JobReport $reportModel, ResponseRepository $responseRepo)
    {
        $this->model = $model;
        $this->reportModel = $reportModel;
        $this->responseRepo = $responseRepo;
        $this->disableDeletedStatus = config('enums.disableDeletedStatus');
        $this->disableBannedStatus = config('enums.disableBannedStatus');
        $this->enablePublishStatus = config('enums.enablePublishStatus');

    }

    public function getReportReason($request)
    {
        $reportReasonList = $this->model->select('id', 'name')->where('publish_status', $this->enablePublishStatus)->get();

        return $this->responseRepo->successResponse(["reportReasons" => $reportReasonList], $this->responseRepo->successStatusCode, trans('message.successMsg'));
    }

    public function createJobReport($request, $jobId)
    { 
        $checkIntJobId = checkInteger($jobId);
        $job = $this->activeJobFind($checkIntJobId);

        if (!$job) {
            throw new NotFoundException(trans('message.itemNotFoundMsg'));
        }

        if($request->auth_user_id == $job->client_user_id ){
            return $this->responseRepo->failResponse(null, $this->responseRepo->badRequestStatusCode, trans('message.jobReportFail'));
        } 

        $this->findReportReason($request->reasonId);

        $this->reportModel->create([
            'client_user_id' => $request->auth_user_id,
            'reason_id' => $request->reasonId,
            'job_id' => $job->id,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return $this->responseRepo->successResponse(null, $this->responseRepo->createStatusCode, trans('message.reportSuccessMsg'));
    }

    public function findReportReason($reasonId)
    {
        $reportReason = $this->model->where('id', $reasonId)->where('publish_status', $this->enablePublishStatus)->first();
        
        if (!$reportReason) {
            throw new NotFoundException(trans('message.reportReasonNotFound'));
        }

        return  $reportReason;
    }
}
