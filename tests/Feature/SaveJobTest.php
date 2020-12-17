<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SaveJobTest extends TestCase
{
    public function testSaveJobSuccessfully()
    {
        $headers = ['Accept' => 'application/json','Authorization' => "Bearer $this->token"];

        $response = $this->get("api/jobs/".$this->job->id."/save", $headers);

        $response->assertCreated();

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

    public function testSaveJobFailed_WhenJobIdIsInvalid()
    {
        $headers = ['Accept' => 'application/json','Authorization' => "Bearer $this->token"];

        $response = $this->get("api/jobs/".config('mock_data.invalidJobId')."/save", $headers);

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

    public function testGetSavedJobListSuccessfully()
    {
        $headers = ['Accept' => 'application/json','Authorization' => "Bearer $this->token"];

        $response = $this->get("api/saveJobs?nextId=0", $headers);

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
                        "id" => $this->savedJob->id,
                        "jobId"=> $this->job->id,
                        "photo" => getFileUrlFromAkoneyaMedia($this->job->photo),
                        "title" => $this->job->title,
                        "createdAt" => strTo24hrFormat($this->job->created_at),
                        "location" => $this->zones[$this->job->zone_id]['name'],
                        "salary"=> $this->job->salary,
                        "companyName" => $this->job->company_name
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

    public function testDeleteSavedJobSuccessfully()
    {
        $headers = ['Accept' => 'application/json','Authorization' => "Bearer $this->token"];

        $response = $this->json('Delete', 'api/savedJobs/'.$this->savedJob->job_id, [], $headers);

        $response->assertOk();

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

    public function testDeleteSavedJobFailed()
    {
        $headers = ['Accept' => 'application/json','Authorization' => "Bearer $this->token"];

        $response = $this->json('Delete', 'api/savedJobs/'.config('mock_data.invalidJobId'), [], $headers);

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
}
