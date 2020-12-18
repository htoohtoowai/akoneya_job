<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SavedJobTest extends TestCase
{
    protected $jobType, $job, $zones;

    public function setUp(): void
    {
        parent::setUp();

        $this->jobType = $this->createJobType();
        $this->job = $this->createJob($this->user->id, $this->jobType->id);
        $this->savedJob = $this->createSavedJob($this->job->id, $this->user->id);
        $this->zones = config('zones.zoneSelections');
    }

    public function testSaveJobSuccessfully()
    {
        $response = $this->get("api/jobs/".$this->job->id."/save", $this->headers);

        $response->assertCreated();

        $response->assertJsonStructure($this->jsonStructure());

        $response->assertJson(
            $this->successJson(null, config('http_status.created'), trans('message.saveSuccessMsg'))
        );
    }

    public function testSaveJobFailed_WhenJobIdIsInvalid()
    {
        $response = $this->get("api/jobs/".config('mock_data.invalidJobId')."/save", $this->headers);

        $response->assertStatus($this->badRequestStatusCode);

        $response->assertJsonStructure($this->jsonStructure());
      
        $response->assertJson($this->failedJson());
    }

    public function testGetSavedJobListSuccessfully()
    {
        $response = $this->get("api/saveJobs?nextId=0", $this->headers);

        $response->assertOk();

        $response->assertJsonStructure($this->jsonStructureWithResultArray(
            [
                "data",
                "pagination"
            ]
        ));

        $response->assertJson($this->successJson(
            [
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
            ], $this->successStatusCode, $this->successMsg));
    }

    public function testDeleteSavedJobSuccessfully()
    {
        $response = $this->json('Delete', 'api/savedJobs/'.$this->savedJob->job_id, [], $this->headers);

        $response->assertOk();

        $response->assertJsonStructure($this->jsonStructure());

        $response->assertJson(
            $this->successJson(null, $this->successStatusCode, trans('message.deleteSuccessMsg'))
        );
    }

    public function testDeleteSavedJobFailed()
    {
        $response = $this->json('Delete', 'api/savedJobs/'.config('mock_data.invalidJobId'), [], $this->headers);

        $response->assertStatus($this->badRequestStatusCode);

        $response->assertJsonStructure($this->jsonStructure());
      
        $response->assertJson($this->failedJson());
    }
}
