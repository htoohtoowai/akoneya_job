<?php
namespace App\Repositories;

use App\Models\Job;
use App\Models\JobType;
use App\Repositories\ResponseRepository;
use App\Repositories\JobTypeRepository;
use App\PersonalInformation;
use App\Exceptions\NotFoundException;
use App\Models\SavedJob;
use App\Http\Resources\ZoneListResource;
use App\Http\Resources\JobResourceCollection;

class JobRepository extends BaseRepository
{
    public function __construct(Job $model, ResponseRepository $responseRepo)
    {
        $this->model = $model;
        $this->responseRepo = $responseRepo;
        $this->successStatusCode = config('http_status.success');
        $this->perPage = config('enums.perPage');
        $this->jobMediaDirectory = config('media.job.directory');
        $this->nextId = null;
        $this->zones = config('zones.zoneSelections');
        $this->jobInfos = [];
        $this->bannedStatus = 'banned_status';
        $this->disableBannedStatus = config('enums.disableBannedStatus');
    }
  
    public function getJobs($request, $filter)
    {
        $jobInfos = [];
        $jobInformation = new JobResourceCollection($jobInfos, $this->nextId);
        if ($request->nextId != "" && $request->nextId != 'null') {
            $jobInfoQuery = Job::filter($filter)
                                ->where($this->bannedStatus, $this->disableBannedStatus)
                                ->select('id', 'title', 'client_user_id', 'salary', 'company_name', 'zone_id', 'job_type', 'photo', 'created_at');

            $jobInfos = $this->getPaginatedByNextId('id', $request->nextId, $jobInfoQuery);
           
            foreach ($jobInfos['result'] as $key => $value) {
                $value['isSaved'] = (SavedJob::where('client_user_id', $request->auth_user_id)->where('job_id', $value['id'])->first()!= null);
            }
            
            $jobInformation = new JobResourceCollection($jobInfos['result'], $this->nextId);
        }
        return $this->responseRepo->successResponse($jobInformation, $this->responseRepo->successStatusCode, trans('message.successMsg'));
    }
    public function createJob($request, $clientUserId)
    {
        $this->checkValidation($request);
       
        if ($request->photo) {
            $mediaPath = fileUploadToAkoneyaMedia($request->photo, $this->jobMediaDirectory);
        } else {
            $mediaPath = "" ;
        }

        $job = $this->model->create([
            'client_user_id' => $clientUserId,
            'title' => $request->title,
            'company_name' => $request->companyName,
            'job_type' => $request->jobType,
            'zone_id' => $request->location,
            'salary' => $request->salary,
            'address' => $request->address,
            'email' => $request->email,
            'gender' => $request->gender,
            'phone' => $request->phone,
            'description' => $request->description,
            'requirement' => $request->requirement,
            'photo' => $mediaPath,
        ]);

        return $this->responseRepo->successResponse($this->getJobDetailResponse($job, $clientUserId), $this->responseRepo->createStatusCode, trans('message.createSuccessMsg'));
    }
    public function getJobById($id, $clientUserId)
    {
        $checkIntJobId = checkInteger($id);

        $job = $this->model->where('id', $checkIntJobId)
                            ->where('client_user_id', $clientUserId)
                            ->where($this->bannedStatus, $this->disableBannedStatus)->first();
        if (!$job) {
            throw new NotFoundException(trans('message.notFoundMsg'));
        }
        return $job;
    }
   
    public function updateJob($request, $id, $clientUserId)
    {
        $job = $this->getJobById($id, $clientUserId);
        
        $this->checkValidation($request);

        $mediaPath = "";

        if (request()->photo == null && request()->photoDeleteStatus == null || request()->photoDeleteStatus == "false") {
            if ($job->photo) {
                $mediaPath = $job->photo ;
            }
        }
        if (request()->photo == null && request()->photoDeleteStatus == "true") {
            deleteFileFromAkoneyaMedia($job->photo);
            $mediaPath = "" ;
        }
        if (request()->photo) {
            $mediaPath = fileUploadToAkoneyaMedia($request->photo, $this->jobMediaDirectory);
            deleteFileFromAkoneyaMedia($job->photo);
        }
        $job->update([
            'title' => $request->title,
            'company_name' => $request->companyName,
            'job_type' => $request->jobType,
            'zone_id' => $request->location,
            'salary' => $request->salary,
            'address' => $request->address,
            'email' => $request->email,
            'gender' => $request->gender,
            'phone' => $request->phone,
            'description' => $request->description,
            'requirement' => $request->requirement,
            'photo' => $mediaPath,
        ]);

        $updatedInfo = $this->getJobById($id, $clientUserId);
     
        return $this->responseRepo->successResponse($this->getJobDetailResponse($updatedInfo, $clientUserId), $this->responseRepo->successStatusCode, trans('message.updateSuccessMsg'));
    }
    public function deleteJob($id, $clientUserId)
    {
        $job = $this->getJobById($id, $clientUserId);
        SavedJob::where('job_id', $id)->delete();
        deleteFileFromAkoneyaMedia($job->photo);
        $job->delete();

        return $this->responseRepo->successResponse(null, $this->responseRepo->successStatusCode, trans('message.deleteSuccessMsg'));
    }
    public function getDetails($id, $clientUserId)
    {
        $job = $this->activeJobFind($id);
       
        if (!$job) {
            throw new NotFoundException(trans('message.notFoundMsg'));
        }

        return $this->responseRepo->successResponse($this->getJobDetailResponse($job, $clientUserId), $this->responseRepo->successStatusCode, trans('message.successMsg'));
    }

    public function getJobDetailResponse($job, $clientUserId)
    {
        $getProfile=PersonalInformation::select('client_user_id', 'name', 'image')->where('client_user_id', $job->client_user_id)->first();

        $jobType = JobType::select('id', 'name')->where('id', $job->job_type)->first();

        return  [
            'id' => $job->id,
            'title' => $job->title,
            'salary' => $job->salary,
            'jobType' => ['id'=>$jobType->id,'name'=>$jobType->name],
            'companyName' => $job->company_name,
            'location' => ['id'=>$this->zones[$job->zone_id]['id'],'name'=>$this->zones[$job->zone_id]['name']],
            'photo' => (getFileUrlFromAkoneyaMedia($job->photo))?? "",
            'phone' => explode(",", $job->phone),
            'gender' => (int)$job->gender,
            'email' => ($job->email) ?? "",
            'address' => ($job->address) ?? "",
            'description' => ($job->description)?? "",
            'requirement' => ($job->requirement) ?? "",
            'isSaved'=> (SavedJob::where('client_user_id', $clientUserId)->where('job_id', $job->id)->first() != null),
            'user'=> ['id'=> $getProfile->client_user_id, 'name'=> $getProfile->name, 'image'=>getFileUrlFromAkoneyaMedia($getProfile->image)],
            "createdAt"=> strTo24hrFormat($job->created_at)
        ];
    }

    public function checkValidation($request)
    {
        $jobType = JobType::where('id', $request->jobType)->where('deleted_status', config('enums.enableDeleteStatus'))->first();
        if (!in_array($request->location, range(1, 15))) {
            throw new NotFoundException(trans('message.locationNotFound'));
        }
        if (!in_array($request->gender, range(1, 3))) {
            throw new NotFoundException(trans('message.genderNotFound'));
        }
        if (!$jobType) {
            throw new NotFoundException(trans('message.jobTypeNotFound'));
        }
    }
}
