<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LocationTest extends TestCase
{
    public function testGetLocationListSuccessfully()
    {
        $response = $this->get('api/locations', $this->headers);
        
        $response->assertOk();

        $response->assertJsonStructure([
            "result",
            "statusCode",
            "message",
        ]);

        $response->assertJson([
            "result" => [
                "locations" => [
                    [
                        "id" => config('zones.zoneSelections.1.id'),
                        "name" => config('zones.zoneSelections.1.name')
                    ],
                    [
                        "id" => config('zones.zoneSelections.2.id'),
                        "name" => config('zones.zoneSelections.2.name')
                    ],
                    [
                        "id" => config('zones.zoneSelections.3.id'),
                        "name" => config('zones.zoneSelections.3.name')
                    ]
                    //...more zones list order by name
                ]
            ],
            "statusCode" => config('http_status.success'),
            "message" =>  config('message.successMsg')
        ]);
    }
}
