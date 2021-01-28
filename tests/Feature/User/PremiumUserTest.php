<?php

namespace Tests\Feature\User;

use App\Enums\Roles;
use Tests\Feature\User\Traits\WithCommonUserTestCasePropertiesTrait;

class PremiumUserTest extends BaseUserTestCase
{
    use WithCommonUserTestCasePropertiesTrait;

    /**
     * @method store()
     */

    public function testCreateUserAsPremiumUserShouldFailAuthorization()
    {
        $this->actingAsFakeUser(Roles::PremiumUser);

        $formData = $this->getMappedFormDataWithRole(Roles::PremiumUser);

        $response = $this->post('/api/users', $formData, self::headers);

        $response->assertForbidden();
    }

    public function testCreateUserAsUnverifiedPremiumUserShouldFailAuthorization()
    {
        $this->actingAsUnverifiedFakeUser(Roles::PremiumUser);

        $formData = $this->getMappedFormDataWithRole(Roles::PremiumUser);

        $response = $this->post('/api/users', $formData, self::headers);

        $response->assertForbidden();
    }

    /**
     * @method show()
     */

    public function testGetUserAsUnverifiedPremiumUserShouldFailAuthorization()
    {
        $this->actingAsUnverifiedFakeUser(Roles::PremiumUser);

        $user = $this->createUser();

        $response = $this->get('/api/users/' . $user->id, self::headers);

        $response->assertForbidden();
    }

    public function testGetUserAsPremiumUser()
    {
       $this->actingAsFakeUser(Roles::PremiumUser);

        $user = $this->createUser();

        $response = $this->get('/api/users/' . $user->id, self::headers);

        $response->assertOk()->assertJsonStructure(self::userJsonStructure);
    }

    /**
     * @method update()
     */

    public function testUpdateExistingUserAsPremiumUserShouldFailAuthorization()
    {
        $this->actingAsFakeUser(Roles::PremiumUser);

        $user = $this->createUser(Roles::PremiumUser);

        $formData = $user->toArray();
        $formData['username'] = $this->faker->userName;

        $response = $this->put('/api/users/' . $user->id, $formData, self::headers);

        $response->assertForbidden();
    }

    public function testUpdateExistingUserAsPremiumUserWithInvalidDataShouldFailForAuthorization()
    {
        $this->actingAsFakeUser(Roles::PremiumUser);

        $user = $this->createUser()->assignRole(
            Roles::PremiumUser
        );

        $formData = $user->toArray();
        $formData['username'] = null;

        $response = $this->put('/api/users/' . $user->id, $formData, self::headers);

        $response->assertForbidden();
    }

    public function testUpdateExistingUserAsPremiumUserWithInvalidUserIdShouldFailForAuthorization()
    {
        $this->actingAsFakeUser(Roles::PremiumUser);

        $response = $this->put('/api/users/' . rand(), [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'username' => 'john.doe.1997',
            'email' => 'johndoe@gmail.com',
            'role' => Roles::PremiumUser
        ], self::headers);

        $response->assertForbidden();
    }

    /**
     * @method destroy()
     */

    public function testDeleteUserAsPremiumUserShouldFailAuthorization()
    {
        $this->actingAsFakeUser(Roles::PremiumUser);

        $user = $this->createUser();

        $response = $this->delete('api/users/' . $user->id, [], self::headers);

        $response->assertForbidden();
    }

    public function testDeleteUserAsUnverifiedPremiumUserShouldFailAuthorization()
    {
        $this->actingAsUnverifiedFakeUser(Roles::PremiumUser);

        $user = $this->createUser();

        $response = $this->delete('api/users/' . $user->id, [], self::headers);

        $response->assertForbidden();
    }

    /**
     * @method forceDelete()
     */

    public function testForceDeleteUserAsPremiumUserShouldFailAuthorization()
    {
        $this->actingAsUnverifiedFakeUser(Roles::PremiumUser);

        $user = $this->createUser();

        $response = $this->delete('api/users/' . $user->id . '/force-delete', [], self::headers);

        $response->assertForbidden();
    }

    public function testForceDeleteUserAsUnverifiedPremiumUserShouldFailAuthorization()
    {
        $this->actingAsUnverifiedFakeUser(Roles::PremiumUser);

        $user = $this->createUser();

        $response = $this->delete('api/users/' . $user->id . '/force-delete', [], self::headers);

        $response->assertForbidden();
    }

    /**
     * @method restore()
     */

    public function testRestoreUserAsPremiumUserShouldFailAuthorization()
    {
        $this->actingAsFakeUser(Roles::PremiumUser);

        $user = $this->createUser();

        $user->delete();

        $response = $this->get('/api/users/' . $user->id . '/restore');

        $response->assertForbidden();
    }

    public function testRestoreUserAsPremiumUserOnNotTrashedUserShouldFailAuthorization()
    {
        $this->actingAsFakeUser(Roles::PremiumUser);

        $user = $this->createUser();

        $response = $this->get('/api/users/' . $user->id . '/restore');

        $response->assertForbidden();
    }

    public function testRestoreUserAsPremiumUserOnInvalidUserIdShouldFailAuthorization()
    {
        $this->actingAsFakeUser(Roles::PremiumUser);

        $response = $this->get('/api/users/25$$!/restore');

        $response->assertForbidden();
    }
}
