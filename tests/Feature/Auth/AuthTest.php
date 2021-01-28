<?php

namespace Tests\Feature\Auth;

use App\Enums\HttpCode;
use App\Enums\Roles;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;
use Tests\TestCase;
use Tests\Traits\WithCommonMethodsTrait;

class AuthTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithCommonMethodsTrait;

    const headers = [
        'Accept' => 'application/json'
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate');
        $this->artisan('db:seed');
    }

    public function testLoginWithNonExistingEmailShouldFailValidation()
    {
        $credentials = [
            'email' => 'i.am.nonexistentemail@dev.com',
            'password' => 'password',
            'device_name' => 'ASUS ROG Cool Phone'
        ];

        $response = $this->post('/api/auth/login', $credentials, self::headers);

        $response->assertStatus(HttpCode::UNPROCESSABLE_ENTITY);
    }

    public function testLoginWithNonExistingUsernameShouldFailValidation()
    {
        $credentials = [
            'username' => 'i.am.nonexistentusername',
            'password' => 'Awesome_password_123!',
            'device_name' => 'ASUS ROG Cool Phone'
        ];

        $response = $this->post('/api/auth/login', $credentials, self::headers);

        $response->assertStatus(HttpCode::UNPROCESSABLE_ENTITY);
    }

    public function testLoginWithExistingUsername()
    {
        $credentials = [
            'username' => 'carlomigueldy',
            'password' => 'Awesome_password_123!',
            'device_name' => 'ASUS ROG Cool Phone'
        ];

        $response = $this->post('/api/auth/login', $credentials, self::headers);

        $response->assertOk()->assertJsonStructure([
            'access_token',
            'token_type',
        ]);
    }

    public function testLoginWithExistingEmail()
    {
        $credentials = [
            'email' => 'dev@dev.com',
            'password' => 'password',
            'device_name' => 'ASUS ROG Cool Phone'
        ];

        $response = $this->post('/api/auth/login', $credentials, self::headers);

        $response->assertOk()->assertJsonStructure([
            'access_token',
            'token_type',
        ]);
    }

    public function testAttemptLogoutAsSuperAdmin()
    {
        $this->actingAsFakeUser(Roles::SuperAdmin);

        $response = $this->delete('/api/auth/logout', [], self::headers);

        $response->assertNoContent();
    }

    public function testAttemptLogoutAsAdmin()
    {
        $this->actingAsFakeUser(Roles::Admin);

        $response = $this->delete('/api/auth/logout', [], self::headers);

        $response->assertNoContent();
    }

    public function testAttemptLogoutAsRegularUser()
    {
        $this->actingAsFakeUser(Roles::RegularUser);

        $response = $this->delete('/api/auth/logout', [], self::headers);

        $response->assertNoContent();
    }

    public function testAttemptLogoutAsPremiumUser()
    {
        $this->actingAsFakeUser(Roles::PremiumUser);

        $response = $this->delete('/api/auth/logout', [], self::headers);

        $response->assertNoContent();
    }

    public function testAttemptLogoutAsUnverifiedUser()
    {
        $this->actingAsUnverifiedFakeUser(Roles::RegularUser);

        $response = $this->delete('/api/auth/logout', [], self::headers);

        $response->assertNoContent();
    }

    public function testAttemptLogoutAsGuestShoudFail()
    {
        $response = $this->delete('/api/auth/logout', [], self::headers);

        $response->assertUnauthorized()->assertExactJson([
            'message' => 'Unauthenticated.'
        ]);
    }

    public function testAttemptLogoutFromAllDevicesAsSuperAdmin()
    {
        $this->actingAsFakeUser(Roles::SuperAdmin);

        $response = $this->delete('/api/auth/logout-all-devices', [], self::headers);

        $response->assertNoContent();
    }

    public function testAttemptLogoutFromAllDevicesAsAdmin()
    {
        $this->actingAsFakeUser(Roles::Admin);

        $response = $this->delete('/api/auth/logout-all-devices', [], self::headers);

        $response->assertNoContent();
    }

    public function testAttemptLogoutFromAllDevicesAsUnverifiedUser()
    {
        $this->actingAsFakeUser(Roles::RegularUser);

        $response = $this->delete('/api/auth/logout-all-devices', [], self::headers);

        $response->assertNoContent();
    }

    public function testAttemptLogoutFromAllDevicesAsVerifiedUser()
    {
        $this->actingAsFakeUser(Roles::RegularUser);

        $response = $this->delete('/api/auth/logout-all-devices', [], self::headers);

        $response->assertNoContent();
    }

    public function testAttemptLogoutFromAllDevicesAsPremiumUser()
    {
        $this->actingAsFakeUser(Roles::PremiumUser);

        $response = $this->delete('/api/auth/logout-all-devices', [], self::headers);

        $response->assertNoContent();
    }

    public function testAttemptLogoutFromAllDevicesAsGuestShouldFail()
    {
        $response = $this->delete('/api/auth/logout-all-devices', [], self::headers);

        $response->assertUnauthorized()->assertExactJson([
            'message' => 'Unauthenticated.'
        ]);
    }
}
