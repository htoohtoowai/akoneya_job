<?php

namespace Tests\Feature;

use App\Models\Job;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReportTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();    

        $this->personalInfo = $this->createPersonalInformation($this->user->id);
        $this->jobType = $this->createJobType();
        $this->job = $this->createJob($this->user->id, $this->jobType->id);
        $this->reason = $this->createReason();
    }

    public function testGetReportReasonListSucessfully()
    {
        $response = $this->get('api/reportReasons', $this->headers);
        
        $response->assertOk();

        $response->assertJson($this->successJson(
            [
                "reportReasons" => [
                    [
                        "id" => $this->reason->id,
                        "name" => config('mock_data.name')
                    ],
                ]
            ], $this->successStatusCode, $this->successMsg));
    }

    public function testReportToJobSuccessfully()
    {
            $this->job = $this->createJob(2, $this->jobType->id);
            $requestBody = [
                'reasonId' => $this->reason->id
            ];
    
            $response = $this->json('POST', "api/jobs/".$this->job->id."/report", $requestBody, $this->headers);
    
            $response->assertCreated();
    
            $response->assertJson([
               "result" => null,
               "statusCode" => config('http_status.created'),
               "message" =>  trans('message.reportSuccessMsg')
            ]);
    }

    public function testReportToJobFailWhenUserIdIsEqual()
    {       
        $requestBody = [
            'reasonId' => $this->reason->id
        ];

        $response = $this->json('POST', "api/jobs/".$this->job->id."/report", $requestBody, $this->headers);

        $response->assertStatus($this->badRequestStatusCode);

        $response->assertJson($this->failedJsonForValidation());
    }

    public function testReportToJobFailWhenReasonIdIsNull()
    {
        $this->job = $this->createJob(2, $this->jobType->id);
        $requestBody = [
            'reasonId' => null
        ];

        $response = $this->json('POST', "api/jobs/".$this->job->id."/report", $requestBody, $this->headers);

        $response->assertStatus($this->badRequestStatusCode);

        $response->assertJson($this->reasonNotExitJsonStructure());

    }

    public function testReportToJobFailWhenJobIdIsInvalid()
    {
        $this->job = $this->createJob(2, $this->jobType->id);
        $requestBody = [
            'reasonId' => null
        ];

        $response = $this->json('POST', "api/jobs/45986255596/report", $requestBody, $this->headers);

        $response->assertStatus($this->badRequestStatusCode);

        $response->assertJson($this->jobNotExitJsonStructure());
    }
}
