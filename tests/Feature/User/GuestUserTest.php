<?php

namespace Tests\Feature\User;

use App\Enums\HttpCode;
use App\Enums\Roles;
use Tests\Feature\User\Traits\WithCommonUserTestCasePropertiesTrait;

class GuestUserTest extends BaseUserTestCase
{
    use WithCommonUserTestCasePropertiesTrait;

    /**
     * @method store()
     */

    public function testCreateUserAsGuestShouldFailAuthentication()
    {
        $formData = $this->getMappedFormDataWithRole(Roles::RegularUser);

        $response = $this->post('/api/users', $formData, self::headers);

        $response->assertUnauthorized();
    }

    /**
     * @method show()
     */

    public function testGetUserAsGuestShouldFailAuthentication()
    {
        $user = $this->createUser();

        $response = $this->get('/api/users/' . $user->id, self::headers);

        $response->assertUnauthorized();
    }

    /**
     * @method update()
     */

    public function testUpdateExistingUserAsGuestShouldFailAuthentication()
    {
        $user = $this->createUser(Roles::PremiumUser);

        $formData = $user->toArray();
        $formData['username'] = $this->faker->userName;

        $response = $this->put('/api/users/' . $user->id, $formData, self::headers);

        $response->assertUnauthorized();
    }

    public function testUpdateExistingUserAsGuestWithInvalidDataShouldFailForAuthentication()
    {
        $user = $this->createUser()->assignRole(
            Roles::Guest
        );

        $formData = $user->toArray();
        $formData['username'] = null;

        $response = $this->put('/api/users/' . $user->id, $formData, self::headers);

        $response->assertUnauthorized();
    }

    public function testUpdateExistingUserAsGuestWithInvalidUserIdShouldFailForAuthentication()
    {
        $response = $this->put('/api/users/' . rand(), [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'username' => 'john.doe.1997',
            'email' => 'johndoe@gmail.com',
            'role' => Roles::PremiumUser
        ], self::headers);

        $response->assertUnauthorized();
    }

    /**
     * @method destroy()
     */

    public function testDeleteUserAsGuestShouldFailAuthentication()
    {
        $user = $this->createUser();

        $response = $this->delete('api/users/' . $user->id, [], self::headers);

        $response->assertUnauthorized();
    }

    public function testForceDeleteUserAsGuestShouldFailAuthentication()
    {
        $user = $this->createUser();

        $response = $this->delete('api/users/' . $user->id . '/force-delete', [], self::headers);

        $response->assertUnauthorized();
    }

    /**
     * @method restore()
     */

    public function testRestoreUserAsGuestShouldFailAuthentication()
    {
        $user = $this->createUser();

        $user->delete();

        $response = $this->get('/api/users/' . $user->id . '/restore', self::headers);

        $response->assertUnauthorized();
    }

    public function testRestoreUserAsGuestOnNotTrashedUserShouldFailAuthentication()
    {
        $user = $this->createUser();

        $response = $this->get('/api/users/' . $user->id . '/restore', self::headers);

        $response->assertUnauthorized();
    }

    public function testRestoreUserAsGuestOnInvalidUserIdShouldFailAuthentication()
    {
        $response = $this->get('/api/users/25$$!/restore', self::headers);

        $response->assertUnauthorized();
    }
}
