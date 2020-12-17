<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class JobTest extends TestCase
{
    public function testGetJobTypeListSuccessfully()
    {
        $response = $this->get('api/jobTypes', $this->headers);
        
        $response->assertOk();

        $response->assertJsonStructure([
            "result",
            "statusCode",
            "message",
        ]);

        $response->assertJson([
            "result" => [
                "jobTypes" =>[
                    [
                        "id" => $this->jobType->id,
                        "name"=> $this->jobType->name,
                    ]
                ]
            ],
            "statusCode" => config('http_status.success'),
            "message" =>  config('message.successMsg')
        ]);
    }

    public function testGetJobListSuccessfully()
    {
        $response = $this->json('GET', 'api/jobs?nextId=0', [], $this->headers);
        
        $response->assertOk();

        $response->assertJsonStructure([
            "result" => [
                "data",
                "pagination"
            ],
            "statusCode",
            "message"
        ]);

        $response->assertJson([
            "result" => [
                "data" =>[
                    [
                        "id" => $this->job->id,
                        "userId"=> $this->user->id,
                        "title" => $this->job->title,
                        "salary" => $this->job->salary,
                        "companyName" => $this->job->company_name,
                        "photo" => getFileUrlFromAkoneyaMedia($this->job->photo),
                        "location" => $this->zones[$this->job->zone_id]['name'],
                        "isSaved" => true,
                        "createdAt" => strTo24hrFormat($this->job->created_at)
                    ]
                ],
                "pagination" => [
                    "nextId" => null
                ]
            ],
            "statusCode"=>config('http_status.success'),
            "message"=> config('message.successMsg')
        ]);
    }

    public function testCreateJobSuccessfully()
    {
        $image = UploadedFile::fake()->create('fake-photo.png');

        $this->createPersonalInformation($this->user->id);  

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
        $response = $this->json('POST', 'api/jobs', $requestBody, $this->headers);
     
        $response->assertCreated();

        $response->assertJsonStructure([
            "result",
            "statusCode",
            "message"
        ]);

        $content = $response->decodeResponseJson();

        $this->assertIsInt($content["result"]["id"]);

        $response->assertJson([
            "result" => $this->prepareJobResponseResource($content),
            "statusCode"=>config('http_status.created'),
            "message" => trans('message.createSuccessMsg')
        ]);
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

        $response = $this->json('POST', 'api/jobs', $requestBody, $this->headers);
        
        $response->assertStatus(400);
        
        $response->assertJsonStructure([
            "result",
            "statusCode",
            "message"
        ]);

        $response->assertJson([
            "result" => null,
            "statusCode" => config('http_status.badRequest'),
        ]);
    }

    public function testUpdateJobSuccessfully()
    {
        $this->createPersonalInformation($this->user->id);

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

        $response = $this->json('POST', 'api/jobs/'.$this->job->id, $requestBody, $this->headers);

        $response->assertOk();
        
        $response->assertJsonStructure([
            "result",
            "statusCode",
            "message"
        ]);

        $content = $response->decodeResponseJson();

        $response->assertJson([
            "result" => $this->prepareJobResponseResource($content),
            "statusCode" => config('http_status.success'),
            "message" => trans('message.updateSuccessMsg')
        ]);
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

        $response = $this->json('POST', 'api/jobs/'.config('mock_data.invalidJobId'), $requestBody, $this->headers);

        $response->assertStatus(400);
        
        $response->assertJsonStructure([
            "result",
            "statusCode",
            "message"
        ]);

        $response->assertJson([
            "statusCode" => config('http_status.badRequest'),
            "message" =>  trans('message.notFoundMsg')
        ]);
    }
 
    public function testDeleteJobSuccessfully()
    {
        $this->createPersonalInformation($this->user->id);

        $response = $this->json('Delete', 'api/jobs/'.$this->job->id, [], $this->headers);
        
        $response->assertOk();
        
        $response->assertJsonStructure([
            "result",
            "statusCode",
            "message"
        ]);

        $response->assertJson([
            "result" => null,
            "statusCode" => config('http_status.success'),
            "message" => trans('message.deleteSuccessMsg')
        ]);
    }

    public function testDeleteJobFailed_WhenJobIdIsInvalid()
    {
        $response = $this->json('Delete', 'api/jobs/'.config('mock_data.invalidJobId'), [], $this->headers);
        
        $response->assertStatus(400);
        
        $response->assertJsonStructure([
            "result",
            "statusCode",
            "message"
        ]);

        $response->assertJson([
            "statusCode" => config('http_status.badRequest'),
            "message" =>  trans('message.notFoundMsg')
        ]);
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
