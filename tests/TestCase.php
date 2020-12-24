<?php

namespace Tests;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\UploadedFile;
use JWTAuth;
use App\User;
use App\Models\Job;
use App\Models\JobType;
use App\Models\SavedJob;
use App\PersonalInformation;
use App\Models\JobReport;
use App\Models\JobReportReason;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseMigrations;

    protected $user, $token, $headers;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = $this->createUser();
        $this->token = JWTAuth::fromUser($this->user);
        $this->headers = ['Accept' => 'application/json','Authorization' => "Bearer $this->token"];

        $this->successStatusCode = config('http_status.success');
        $this->badRequestStatusCode = config('http_status.badRequest');
        $this->createStatusCode = config('http_status.created');

        $this->successMsg = trans('message.successMsg');
        $this->notFoundMsg = trans('message.notFoundMsg');
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

    public function createJob($userId, $jobType)
    {
        $image = UploadedFile::fake()->create('fake-photo.png');

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
            'photo' => $image,
            'gender' => config('mock_data.genderId'),
            'email' => config('mock_data.email'),
            'description' => config('mock_data.jobDescription'),
            'requirement' => config('mock_data.requirement'),
            'photo' => config('mock_data.photo')
        ]);
    }

    public function createJobType()
    {
        return JobType::create([
            'name' => config('mock_data.jobTypeName'),
        ]);
    }

    public function createSavedJob($jobId, $userId)
    {
        return SavedJob::create([
            'job_id' => $jobId,
            'client_user_id' => $userId
        ]);
    }

    public function createReason()
    {
        return JobReportReason::create([
            'name' => config('mock_data.name'),
        ]);
    }

    public function successJson($result, $statusCode, $message)
    {
        return [
            "result" => $result,
            "statusCode" => $statusCode,
            "message" =>  $message
        ];
    }

    public function failedJson()
    {
        return [
            "result" => null,
            "statusCode" => $this->badRequestStatusCode,
            "message" =>  $this->notFoundMsg
        ];
    }

    public function failedJsonForValidation()
    {
        return [
            "result" => null,
            "statusCode" => $this->badRequestStatusCode,
        ];
    }

    public function reasonNotExistJsonStructure()
    {
        return [
            "result" => null,
            "statusCode" => $this->badRequestStatusCode,
            "message" => trans('message.reportReasonNotFound')
        ];
    }

    public function jobNotExistJsonStructure()
    {
        return [
            "result" => null,
            "statusCode" => $this->badRequestStatusCode,
            "message" => trans('message.itemNotFoundMsg')
        ];
    }
}
