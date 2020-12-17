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

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseMigrations;

    protected $user, $personalInfo, $token, $jobType, $job, $savedJob, $zones;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = $this->createUser();
        $this->personalInfo = $this->createPersonalInformation($this->user);
        $this->token = JWTAuth::fromUser($this->user);
        $this->jobType = $this->createJobType();
        $this->job = $this->createJob($this->user->id, $this->jobType->id);
        $this->savedJob = $this->createSavedJob($this->job->id, $this->user->id);
        $this->zones = config('zones.zoneSelections');
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
}
