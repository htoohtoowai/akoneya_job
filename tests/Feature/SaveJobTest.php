<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use JWTAuth;
use App\User;
use App\Models\SavedJob;
use App\Models\Job;

class SaveJobTest extends TestCase
{
    public function createUser()
    {
        $user = User::create([
            'name' => config('mock_data.name'),
            'email' => config('mock_data.email'),
            'login_type' => config('mock_data.emailLoginType'),
            'active_status' => "active",
            'password' => bcrypt(config('mock_data.password'))
        ]);
        return $user;
    }

    public function createJob()
    {
        $user = $this->createUser();
        $image = UploadedFile::fake()->create('fake-photo.png');
        $job = Job::create([
            'client_user_id'=> $user->id,
            'title' => 'Testing',
            'company_name' => 'Testing',
            'job_type' => 1,
            'zone_id' => 14,
            'salary' => 3000,
            'address' => 'yangon',
            'phone' => '0934543534',
            'email' => 'testing@gmail.com',
            'gender' => 1,
            'description' => 'ABCABC',
            'requirement'=>'CBCBBC',
            'photo' => $image ,

        ]);
        return $job;
    }
    /** @test */

    public function saveJobSuccess()
    {
        $user = $this->createUser();
        $token = JWTAuth::fromUser($user);
        $job = $this->createJob();
        $headers = ['Accept' => 'application/json','Authorization' => "Bearer $token"];
        $response = $this->get("api/jobs/$job->id/save", $headers);
        $response->assertStatus(config('http_status.created'));
        $response->assertJsonStructure([
            "result",
            "statusCode",
            "message"
        ]);
        $response->assertJson([
            "result" => null,
            "statusCode" => config('http_status.created'),
            "message" =>  trans('message.saveSuccessMsg')
        ]);
    }

    public function saveJobList()
    {
        $user = $this->createUser();
        $token = JWTAuth::fromUser($user);
        $job = $this->createJob();
        $headers = ['Accept' => 'application/json','Authorization' => "Bearer $token"];
        $response = $this->get("api/saveJobs", $headers);
        $response->assertStatus(config('http_status.success'));
        $response->assertJsonStructure([
            "result" => [
                "jobs",
                "pagination" => [
                    "nextId"
                ]
            ],
            "statusCode",
            "message"
        ]);
        $response->assertJson([
            "statusCode" => config('http_status.success'),
            "message" =>  trans('message.saveSuccessMsg')
        ]);
    }

    public function saveJobDeleteSuccess()
    {
        $user = $this->createUser();
        $token = JWTAuth::fromUser($user);
        $job = $this->createJob();
        $headers = ['Accept' => 'application/json','Authorization' => "Bearer $token"];
        $response = $this->get("api/savedJobs/$job->id", $headers);
        $response->assertStatus(config('http_status.success'));
        $response->assertJsonStructure([
            "result",
            "statusCode",
            "message"
        ]);
        $response->assertJson([
            "result" => null,
            "statusCode" => config('http_status.success'),
            "message" =>  trans('message.deleteSuccessMsg')
        ]);
    }
}
