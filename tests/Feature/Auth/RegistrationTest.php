<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    const headers = [
        'Content-Type' => 'application/json',
        'Accept' => 'application/json'
    ];

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testRegisterWithValidCredentials()
    {
        $credentials = [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->email,
            'username' => $this->faker->userName,
            'password' => 'password',
            'device_name' => 'Awesome Device'
        ];

        $response = $this->post('/api/auth/register', $credentials, [
            'Accept' => 'application/json'
        ]);

        $response->assertCreated()
            ->assertJsonStructure([
                'user' => [
                    'first_name',
                    'middle_name',
                    'last_name',
                    'email',
                    'username',
                    'role',
                    'created_at',
                    'deleted_at'
                ],
                'access_token',
                'token_type'
            ]);
    }

    public function testRegisterWithInvalidEmailShouldFail()
    {
        $credentials = [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => 'not an email',
            'username' => $this->faker->userName,
            'password' => 'password',
        ];

        $response = $this->post('/api/auth/register', $credentials, self::headers);

        $response->assertStatus(422);
    }

    public function testRegisterWithInvalidPasswordShouldFail()
    {
        $credentials = [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->email,
            'username' => $this->faker->userName,
            'password' => 'pass',
        ];

        $response = $this->post('/api/auth/register', $credentials, self::headers);

        $response->assertStatus(422);
    }
}
