<?php
namespace App\Repositories;

use App\Models\Job;
use App\Models\JobType;
use App\Repositories\ResponseRepository;
use App\PersonalInformation;
use App\Exceptions\NotFoundException;
use App\Models\SavedJob;
use App\Http\Resources\ZoneListResource;

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
    }
  
    public function getJobs($request, $perPage, $clientUserId)
    {

        if ( request()->nextId == "" ||  request()->nextId == 'null'){
            $this->jobInfos = [];
        } 
        else{
            $jobs = $this->model->select('id','title','client_user_id','salary','company_name','zone_id','photo','created_at')
                                ->where(function ($query) {
                                                if(  request()->nextId != 0 ){
                                                    $query->where('id', '<=',  request()->nextId);
                                                } 
                                            })
                                ->orderBy('id', 'DESC')->limit($perPage + 1)->get();

            $totalCount = $jobs->count();

            if ($totalCount > $perPage) {

                $this->nextId = $jobs[$perPage]->id;
    
                unset($jobs[$totalCount - 1]);
            }
            
            foreach($jobs as $key=> $value){
                $this->jobInfos[$key]['id'] = $value['id'];
                $this->jobInfos[$key]['userId'] = $value['client_user_id'];
                $this->jobInfos[$key]['title'] = $value['title'];
                $this->jobInfos[$key]['salary'] = $value['salary'];
                $this->jobInfos[$key]['companyName'] = $value['company_name'];
                $this->jobInfos[$key]['photo'] = (getFileUrlFromAkoneyaMedia($value['photo'])) ?? "";
                $this->jobInfos[$key]['location'] = $this->zones[$value['zone_id']]['name'];
                $this->jobInfos[$key]['isSaved'] = (SavedJob::where('client_user_id',$clientUserId)->where('job_id',$value['id'])->first() != null)? true:false;
                $this->jobInfos[$key]['createdAt'] = strTo24hrFormat($value['created_at']);
            }
            
        }
        return $this->responseRepo->successResponse(['data' => $this->jobInfos,'pagination' => ['nextId' =>$this->nextId]], $this->responseRepo->successStatusCode, trans('message.successMsg'));
    }
    public function createJob($request, $clientUserId)
    {    

        $jobType = JobType::where('id',$request->jobType)->first();

        $message = null;

        if(!in_array( $request->location,range(1,15))){
            $message = trans('message.locationNotFound');
        }
        elseif(!in_array( $request->gender,range(1,3))){
            $message = trans('message.genderNotFound');
        }
        elseif($jobType==null){
            $message = trans('message.jobTypeNotFound');
        }

        if($message != null){
            return $this->responseRepo->failResponse(null, $this->responseRepo->notFoundStatusCode, $message);
        }

        if($request->photo){
            $mediaPath = fileUploadToAkoneyaMedia($request->photo, $this->jobMediaDirectory);
        }else{
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
    public function getJobById($id,$clientUserId){
        return $this->model->where('id',$id)->where('client_user_id',$clientUserId)->first(); 
    } 
   
    public function updateJob($request,$id,$clientUserId)
    {
        
        $jobType = JobType::where('id',$request->jobType)->first();

        $message = null;

        if(!in_array( $request->location,range(1,15))){
            $message = trans('message.locationNotFound');
        }
        elseif(!in_array( $request->gender,range(1,3))){
            $message = trans('message.genderNotFound');
        }
        elseif($jobType==null){
            $message = trans('message.jobTypeNotFound');
        }

        if($message != null){
            return $this->responseRepo->failResponse(null, $this->responseRepo->notFoundStatusCode, $message);
        }

        $job = $this->getJobById($id,$clientUserId);
        if(!$job){
            return $this->notFoundResponse();
        }
        
        if(request()->photo){
            if(request()->photoDeleteStatus == "true"){
                deleteFileFromAkoneyaMedia($job->photo);
                $mediaPath = fileUploadToAkoneyaMedia($request->photo, $this->jobMediaDirectory);
            }
            else{
                $mediaPath = $job->photo;
            }
        }
        if($request->photoDeleteStatus == "true"){
            deleteFileFromAkoneyaMedia($job->photo);
            $mediaPath = "" ;
        }else{
            $mediaPath = $job->photo;
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

        $updatedInfo = $this->getJobById($id,$clientUserId);
     
        return $this->responseRepo->successResponse($this->getJobDetailResponse($updatedInfo, $clientUserId), $this->responseRepo->successStatusCode, trans('message.updateSuccessMsg'));

    }
    public function deleteJob($id,$clientUserId)
    {
        $job = $this->getJobById($id,$clientUserId);
        if(!$job){
            return $this->notFoundResponse();
        }
        deleteFileFromAkoneyaMedia($job->photo);
        $job->delete();

        return $this->responseRepo->successResponse( null, $this->responseRepo->successStatusCode, trans('message.deleteSuccessMsg'));

    }
    public function getDetails($id,$clientUserId){

        $job = $this->model->where('id',$id)->first();
        if(!$job){
            return $this->notFoundResponse();
        }

        return $this->responseRepo->successResponse($this->getJobDetailResponse($job, $clientUserId), $this->responseRepo->successStatusCode, trans('message.successMsg'));
    } 

    public function getJobDetailResponse($job,$clientUserId){

    
        $getProfile=PersonalInformation::select('client_user_id','name','image')->where('client_user_id',$job->client_user_id)->first();

        $jobType = JobType::select('id','name')->where('id',$job->job_type)->first();

        return  [
            'id' => $job->id,
            'title' => $job->title,
            'salary' => $job->salary,
            'jobType' => ['id'=>$jobType->id,'name'=>$jobType->name],
            'companyName' => $job->company_name,
            'location' => ['id'=>$this->zones[$job->zone_id]['id'],'name'=>$this->zones[$job->zone_id]['name']],
            'photo' => (getFileUrlFromAkoneyaMedia($job->photo))?? "",
            'phone' => explode(",",$job->phone),
            'gender' => $job->gender,
            'email' => ($job->email) ?? "",
            'address' => ($job->address) ?? "",
            'description' => ($job->description)?? "",
            'requirement' => ($job->requirement) ?? "",
            'isSaved'=> (SavedJob::where('client_user_id',$clientUserId)->where('job_id', $job->id)->first() != null)? true:false,
            'user'=> ['id'=> $getProfile->client_user_id, 'name'=> $getProfile->name, 'image'=>getFileUrlFromAkoneyaMedia($getProfile->image)],
            "createdAt"=> strTo24hrFormat($job->created_at)
        ];
    } 
    public function notFoundResponse(){
        return $this->responseRepo->failResponse(null, $this->responseRepo->notFoundStatusCode, trans('message.notFoundMsg'));
    }

}
