<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use JWTAuth;
use App\User;
use App\PersonalInformation;

use App\Models\Job;
use App\Models\JobType;

class JobTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testJobList()
    {
        $user = $this->createUser();
        $token = JWTAuth::fromUser($user);
        $jobType = $this->createJobType();

        $job = $this->createJob($user->id,$jobType->id);
                                               
                                       
        $headers = ['Accept' => 'application/json','Authorization' => "Bearer $token"];


        $response = $this->json('GET', 'api/jobs', [], $headers);
        
        $response->assertStatus(200);
        
        $response->assertJsonStructure([
            "result",
            "statusCode",
            "message"
        ]);

        $response->assertJson([
            "statusCode" => config('http_status.success'),
            "message" => trans('message.successMsg')
        ]);
    }

    public function testCreatedJob()
    {
        $user = $this->createUser();
        $this->createPersonalInformation($user->id);
        $token = JWTAuth::fromUser($user);

        $jobType = $this->createJobType();
                                                                        
        $headers = ['Accept' => 'application/json','Authorization' => "Bearer $token"];

        $requestBody = [
            'title' => config('mock_data.name'),
            'companyName' => config('mock_data.companyName'),
            'jobType' => $jobType->id,
            'location' => config('mock_data.locationId'),
            'description' => config('mock_data.description'),
            'salary' => config('mock_data.salary'),
            'address' => config('mock_data.address'),
            'phone' => config('mock_data.phone'),
            'gender' => config('mock_data.genderId'),
            'email' => config('mock_data.email'),
            'description' => config('mock_data.jobDescription'),
            'requirement' => config('mock_data.requirement')
        ];

        $response = $this->json('POST', 'api/jobs', $requestBody, $headers);
        
        $response->assertStatus(201);
        
        $response->assertJsonStructure([
            "result",
            "statusCode",
            "message"
        ]);

        $response->assertJson([
            "statusCode" => config('http_status.created'),
            "message" => trans('message.createSuccessMsg')
        ]);
    }

    public function testDeletedJob()
    {
        $user = $this->createUser();
        $this->createPersonalInformation($user->id);

        $token = JWTAuth::fromUser($user);

        $jobType = $this->createJobType();

        $job = $this->createJob($user->id,$jobType->id);
                                               
        $headers = ['Accept' => 'application/json','Authorization' => "Bearer $token"];
      
        $response = $this->json('Delete', 'api/jobs/'.$job->id, [], $headers);
        
        $response->assertStatus(200);
        
        $response->assertJsonStructure([
            "result",
            "statusCode",
            "message"
        ]);

        $response->assertJson([
            "statusCode" => config('http_status.success'),
            "message" => trans('message.deleteSuccessMsg')
        ]);
    }

    public function testUpdatedJob()
    {
        $user = $this->createUser();
        $this->createPersonalInformation($user->id);

        $token = JWTAuth::fromUser($user);
        $jobType = $this->createJobType();

        $job = $this->createJob($user->id,$jobType->id);

                                                                        
        $headers = ['Accept' => 'application/json','Authorization' => "Bearer $token"];


        $requestBody = [
            'title' => config('mock_data.name'),
            'companyName' => config('mock_data.companyName'),
            'jobType' => $jobType->id,
            'location' => config('mock_data.locationId'),
            'description' => config('mock_data.description'),
            'salary' => config('mock_data.salary'),
            'address' => config('mock_data.address'),
            'phone' => config('mock_data.phone'),
            'gender' => config('mock_data.genderId'),
            'email' => config('mock_data.email'),
            'description' => config('mock_data.jobDescription'),
            'requirement' => config('mock_data.requirement'),
            'photoDeleteStatus'=>true
        ];

        $response = $this->json('POST', 'api/jobs/'.$job->id, $requestBody, $headers);

        $response->assertStatus(200);
        
        $response->assertJsonStructure([
            "result",
            "statusCode",
            "message"
        ]);

        $response->assertJson([
            "statusCode" => config('http_status.success'),
            "message" => trans('message.updateSuccessMsg')
        ]);
    }

    public function createUser()
    {
        return User::create([
            'name' => config('mock_data.name'),
            'email' => config('mock_data.email'),
            'login_type' => config('mock_data.emailLoginType'),
            'active_status' => "active",
            'password' => bcrypt(config('mock_data.password'))
        ]);
    }

    public function createPersonalInformation($userId)
    {
        return PersonalInformation::create([
            'client_user_id' => $userId,
            'name' => config('mock_data.name'),
            'email' => config('mock_data.email'),
            'image' => config('mock_data.coverPhotoUrl'),
        ]);

    }
    public function createJob($userId,$jobType)
    {
        return Job::create([
            'client_user_id' => $userId,
            'title' => config('mock_data.name'),
            'company_name' => config('mock_data.companyName'),
            'job_type' => $jobType,
            'zone_id' => config('mock_data.locationId'),
            'description' => config('mock_data.description'),
            'salary' => config('mock_data.salary'),
            'address' => config('mock_data.address'),
            'phone' => config('mock_data.phone'),
            'gender' => config('mock_data.genderId'),
            'email' => config('mock_data.email'),
            'description' => config('mock_data.jobDescription'),
            'requirement' => config('mock_data.requirement')
        ]);
    }

    public function createJobType()
    {
        return JobType::create([
            'name' => config('mock_data.name'),
        ]);
    }
}
