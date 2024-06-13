<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Mockery;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;

class LoginTest extends TestCase
{
    public function test_successful_login()
    {
        $email = 'test@example.com';
        $password = 'password';
        $token = 'fake-jwt-token';

        // Create a mock user
        $user = new User([
            'id' => 1,
            'email' => $email,
            'name' => 'Test User',
            'avatar' => 'avatar.png',
            'phone' => '123456789'
        ]);

        // Mock the request
        $request = Request::create('/api/login', 'POST', [
            'email' => $email,
            'password' => $password,
        ]);

        // Mock JWTAuth attempt and user methods
        JWTAuth::shouldReceive('attempt')
            ->with(['email' => $email, 'password' => $password])
            ->andReturn($token);

        JWTAuth::shouldReceive('user')
            ->andReturn($user);

        // Call the login method
        $response = $this->call('POST', '/api/login', [
            'email' => $email,
            'password' => $password,
        ]);

        // Assert the response
        $response->assertStatus(200);
        $response->assertJson([
            'response' => 'success',
            'result' => [
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'email' => $user->email,
                    'name' => $user->name,
                    'avatar' => $user->avatar,
                    'phone' => $user->phone,
                ],
            ],
        ]);
    }

    public function test_validation_error()
    {
        $response = $this->call('POST', '/api/login', [
            'email' => '',
            'password' => '',
        ]);

        $response->assertStatus(400);
        $response->assertJson([
            'error' => [
                'The email field is required.',
                'The password field is required.',
            ],
        ]);
    }

    public function test_invalid_credentials()
    {
        $email = 'test@example.com';
        $password = 'wrongpassword';

        // Mock JWTAuth attempt method
        JWTAuth::shouldReceive('attempt')
            ->with(['email' => $email, 'password' => $password])
            ->andReturn(false);

        // Call the login method
        $response = $this->call('POST', '/api/login', [
            'email' => $email,
            'password' => $password,
        ]);

        // Assert the response
        $response->assertStatus(400);
        $response->assertJson([
            'response' => 'error',
            'message' => 'invalid_email_or_password',
        ]);
    }
}
