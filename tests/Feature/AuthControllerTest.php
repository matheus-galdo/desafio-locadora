<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @check if a user is created */
    public function test_user_can_register()
    {
        User::factory();
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'secret',
        ];

        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'token'
            ]);
    }

    /** @check if user can log in */
    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'email' => 'jane@example.com',
            'password' => bcrypt('password'),
        ]);

        $loginData = [
            'email' => 'jane@example.com',
            'password' => 'password',
        ];

        $response = $this->postJson('/api/login', $loginData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'token'
            ]);
    }
}
