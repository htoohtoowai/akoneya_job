<?php
namespace App\Repositories;

use App\Models\Job;
use App\Models\JobType;
use App\Repositories\ResponseRepository;
use App\PersonalInformation;
use App\Http\Resources\JobCollection;
use App\Exceptions\NotFoundException;

class JobRepository extends BaseRepository
{
    public function __construct(Job $model, ResponseRepository $responseRepo)
    {
        $this->model = $model;
        $this->responseRepo = $responseRepo;
        $this->successStatusCode = config('http_status.success');
        $this->perPage = config('enums.perPage');
        $this->jobMediaDirectory = config('media.job.directory');

    }

    public function getJobs($request, $perPage)
    {
        if ( request()->nextId == "" ||  request()->nextId == 'null'){
            $jobInfos = [];
        } 
        else{
            $jobInfos = $this->model->where(function ($query) {
                                                    if(  request()->nextId != 0 ){
                                                        $query->where('id', '<=',  request()->nextId);
                                                    } 
                                                })
                                    ->orderBy('id', 'DESC')->limit($perPage + 1)->get();

            $totalCount = $jobInfos->count();

            if ($totalCount > $perPage) {

                $this->nextId = $jobInfos[$perPage]->id;
    
                unset($jobInfos[$totalCount - 1]);
            }
            else{
                $this->nextId = null;
            }
        }
       
        return $this->responseRepo->successResponse(new JobCollection($jobInfos,$this->nextId), $this->responseRepo->successStatusCode, trans('message.successMsg'));

    }
    public function createJob($request, $clientUserId)
    {      
        if($request->photo){
            $mediaPath = fileUploadToAkoneyaMedia($request->photo, $this->jobMediaDirectory);

        }else{
            $mediaPath = "" ;
        }

        $this->model->create([
            'client_user_id' => $clientUserId,
            'title' => $request->title,
            'company_name' => $request->companyName,
            'job_type' => $request->jobType,
            'zone_id' => $request->location,
            'salary' => $request->salary,
            'address' => $request->address,
            'email' => $request->email,
            'gender' => $request->gender,
            'description' => $request->description,
            'requirement' => $request->requirement,
            'photo' => $mediaPath,
        ]);

        return $this->responseRepo->successResponse(null, $this->responseRepo->successStatusCode, trans('message.successMsg'));

    }  
    public function updateJob()
    {
        return $this->model->count();
    }
    public function deleteJob($id)
    {
         
        deleteFileFromAkoneyaMedia($job->photo);

    }
    

}
