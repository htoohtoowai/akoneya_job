<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\JobType;
use JWTAuth;
use App\User;

class JobTypeListTest extends TestCase
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

    public function testJobTypeListSuccess()
    {
        $user = $this->createUser();
        $token = JWTAuth::fromUser($user);

        $response = $this->get('api/jobs/types', [
            'Accept' => 'application/json',
            'Authorization' => "Bearer $token"
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            "result",
            "statusCode",
            "message",
        ]);
        $response->assertJson([
            "statusCode" => config('http_status.success'),
            "message" =>  config('message.successMsg')
        ]);
    }
}
