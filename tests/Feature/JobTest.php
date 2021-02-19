<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class JobTest extends TestCase
{
    private $personalInfo;
    private $jobType;
    private $job;
    private $zones;

    public function setUp(): void
    {
        parent::setUp();

        $this->personalInfo = $this->createPersonalInformation($this->user->id);
        $this->jobType = $this->createJobType();
        $this->job = $this->createJob($this->user->id, $this->jobType->id);
        $this->zones = config('zones.zoneSelections');
    }

    public function testGetJobTypeListSuccessfully()
    {
        $response = $this->get('api/v1.0/jobTypes', $this->headers);
        
        $response->assertOk();

        $response->assertJson($this->successJson(
            [
                "jobTypes" => [
                    [
                        "id" => $this->jobType->id,
                        "name"=> $this->jobType->name,
                    ]
                ]
            ],
            $this->successStatusCode,
            $this->successMsg
        ));
    }

    public function testGetJobListSuccessfully()
    {
        $response = $this->json('GET', 'api/v1.0/jobs?nextId=0', [], $this->headers);
        
        $response->assertOk();

        $response->assertJson($this->successJson(
            [
                "data" =>[
                    [
                        "id" => $this->job->id,
                        "userId"=> $this->user->id,
                        "title" => $this->job->title,
                        "salary" => $this->job->salary,
                        "companyName" => $this->job->company_name,
                        "photo" => getFileUrlFromAkoneyaMedia($this->job->photo),
                        "location" => $this->zones[$this->job->zone_id]['name'],
                        "isSaved" => false,
                        "createdAt" => strTo24hrFormat($this->job->created_at)
                    ]
                ],
                "pagination" => [
                    "nextId" => null
                ]
            ],
            $this->successStatusCode,
            $this->successMsg
        ));
    }

    public function testCreateJobSuccessfully()
    {
        $image = UploadedFile::fake()->create('fake-photo.png');

        $requestBody = [
            'title' => config('mock_data.name'),
            'companyName' => config('mock_data.companyName'),
            'jobType' => $this->jobType->id,
            'location' => config('mock_data.locationId'),
            'description' => config('mock_data.description'),
            'salary' => config('mock_data.salary'),
            'address' => config('mock_data.address'),
            'phone' => config('mock_data.phone'),
            'photo' => $image,
            'gender' => config('mock_data.genderId'),
            'email' => config('mock_data.email'),
            'description' => config('mock_data.jobDescription'),
            'requirement' => config('mock_data.requirement')
        ];
        $response = $this->json('POST', 'api/v1.0/jobs', $requestBody, $this->headers);
     
        $response->assertCreated();

        $content = $response->decodeResponseJson();

        $this->assertIsInt($content["result"]["id"]);

        $response->assertJson(
            $this->successJson($this->prepareJobResponseResource($content), $this->createStatusCode, trans('message.createSuccessMsg'))
        );
    }

    public function testCreateJobFailed()
    {
        $this->createPersonalInformation($this->user->id);

        $requestBody = [
            'title' => null,
            'companyName' => null,
            'jobType' => null,
            'location' => null,
            'description' => null,
            'salary' => null,
            'address' => null,
            'phone' => null,
            'gender' => null,
            'email' => null,
            'description' => null,
            'requirement' => null
        ];

        $response = $this->json('POST', 'api/v1.0/jobs', $requestBody, $this->headers);
        
        $response->assertStatus($this->badRequestStatusCode);

        $response->assertJson($this->failedJsonForValidation());
    }

    public function testUpdateJobSuccessfully()
    {
        $requestBody = [
            'title' => config('mock_data.name'),
            'companyName' => config('mock_data.companyName'),
            'jobType' => $this->jobType->id,
            'location' => config('mock_data.locationId'),
            'description' => config('mock_data.description'),
            'salary' => config('mock_data.salary'),
            'address' => config('mock_data.address'),
            'phone' => config('mock_data.phone'),
            'gender' => config('mock_data.genderId'),
            'email' => config('mock_data.email'),
            'description' => config('mock_data.jobDescription'),
            'requirement' => config('mock_data.requirement'),
            'photoDeleteStatus' => true
        ];

        $response = $this->json('POST', 'api/v1.0/jobs/'.$this->job->id, $requestBody, $this->headers);

        $response->assertOk();
        
        $content = $response->decodeResponseJson();

        $response->assertJson(
            $this->successJson($this->prepareJobResponseResource($content), $this->successStatusCode, trans('message.updateSuccessMsg'))
        );
    }

    public function testUpdateJobFailed_WhenJobIdIsInvalid()
    {
        $requestBody = [
            'title' => config('mock_data.name'),
            'companyName' => config('mock_data.companyName'),
            'jobType' => $this->jobType->id,
            'location' => config('mock_data.locationId'),
            'description' => config('mock_data.description'),
            'salary' => config('mock_data.salary'),
            'address' => config('mock_data.address'),
            'phone' => config('mock_data.phone'),
            'gender' => config('mock_data.genderId'),
            'email' => config('mock_data.email'),
            'description' => config('mock_data.jobDescription'),
            'requirement' => config('mock_data.requirement'),
            'photoDeleteStatus' => true
        ];

        $response = $this->json('POST', 'api/v1.0/jobs/'.config('mock_data.invalidJobId'), $requestBody, $this->headers);

        $response->assertStatus($this->badRequestStatusCode);

        $response->assertJson($this->failedJson());
    }
 
    public function testDeleteJobSuccessfully()
    {
        $response = $this->json('Delete', 'api/v1.0/jobs/'.$this->job->id, [], $this->headers);
        
        $response->assertOk();
        
        $response->assertJson(
            $this->successJson(null, $this->successStatusCode, trans('message.deleteSuccessMsg'))
        );
    }

    public function testDeleteJobFailed_WhenJobIdIsInvalid()
    {
        $response = $this->json('Delete', 'api/v1.0/jobs/'.config('mock_data.invalidJobId'), [], $this->headers);
        
        $response->assertStatus($this->badRequestStatusCode);

        $response->assertJson($this->failedJson());
    }

    public function prepareJobResponseResource($content)
    {
        return [
            "title" => config("mock_data.name"),
            "salary" => config("mock_data.salary"),
            "jobType" => [
                "id" => $content["result"]['jobType']['id'],
                "name" => $content["result"]['jobType']['name']
            ],
            "companyName" => config("mock_data.companyName"),
            "location" => [
                "id" => $content["result"]['location']['id'],
                "name" => $content["result"]['location']['name']
            ],
            "description" => config("mock_data.description"),
            "phone" => $content["result"]['phone'],
            "gender" => config("mock_data.genderId"),
            "email" => config("mock_data.email"),
            "address" => config("mock_data.address"),
            "description" => config("mock_data.jobDescription"),
            "requirement" => config("mock_data.requirement"),
            "user" => [
                "id" => $content["result"]['user']['id'],
                "name" => $content["result"]['user']['name'],
                "image" => $content["result"]['user']['image']
            ],
            "createdAt" => $content["result"]['createdAt']
        ];
    }
}
