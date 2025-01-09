<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    /** @test */
    public function it_can_register_a_user()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
        ];

        $response = $this->postJson('/api/auth/register', $data);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => true,
            'message' => 'user register successfully',
        ]);

        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'user' => [
                    'name',
                    'email',
                    'id',
                    'created_at',
                    'updated_at',
                ],
                'token',
            ],
        ]);

        // Ensure that the user is saved in the database
        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
        ]);
    }

    /** @test */
    public function it_returns_validation_error_when_registering_with_invalid_data()
    {
        $data = [
            'name' => 'John Doe',
            'password' => 'password123',
        ];

        $response = $this->postJson('/api/auth/register', $data);

        $response->assertStatus(422);

        $response->assertJson([
            'status' => false,
            'message' => [
                'email' => [
                    'The email field is required.',
                ],
            ],
        ]);
    }

    /** @test */
    public function it_can_login_and_receive_jwt_token()
    {
        // Create a user
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        $data = [
            'email' => $user->email,
            'password' => 'password123',
        ];

        $response = $this->postJson('/api/auth/login', $data);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'user',
                'authorization' => [
                    'token',
                    'type',
                    'expires_in',
                ]

            ],
        ]);
    }

    /** @test */
    public function it_returns_unauthorized_when_login_with_invalid_credentials()
    {
        $data = [
            'email' => 'nonexistent@example.com',
            'password' => 'wrongpassword',
        ];

        $response = $this->postJson('/api/auth/login', $data);

        $response->assertStatus(401); // Expect HTTP 401 (Unauthorized)
        $response->assertJson([
            'status' => false,
            'message' => 'Invalid credentials',
        ]);
    }
}
