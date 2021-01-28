<?php

namespace Tests\Feature\User;

use App\Enums\HttpCode;
use App\Enums\Roles;
use Tests\Feature\User\Traits\WithCommonUserTestCasePropertiesTrait;

class RegularUserTest extends BaseUserTestCase
{
    use WithCommonUserTestCasePropertiesTrait;

    /**
     * @method store()
     */

    public function testCreateUserAsRegularUserShouldFailAuthorization()
    {
        $this->actingAsFakeUser(Roles::RegularUser);

        $formData = $this->getMappedFormDataWithRole(Roles::RegularUser);

        $response = $this->post('/api/users', $formData, self::headers);

        $response->assertStatus(HttpCode::FORBIDDEN);
    }

    public function testCreateUserAsUnverifiedRegularUserShouldFailAuthorization()
    {
        $this->actingAsUnverifiedFakeUser(Roles::RegularUser);

        $formData = $this->getMappedFormDataWithRole(Roles::RegularUser);

        $response = $this->post('/api/users', $formData, self::headers);

        $response->assertStatus(HttpCode::FORBIDDEN);
    }

    /**
     * @method show()
     */

    public function testGetUserAsUnverifiedRegularUserShouldFailAuthorization()
    {
        $this->actingAsUnverifiedFakeUser(Roles::RegularUser);

        $user = $this->createUser();

        $response = $this->get('/api/users/' . $user->id, self::headers);

        $response->assertStatus(HttpCode::FORBIDDEN);
    }

    public function testGetUserAsRegularUser()
    {
       $this->actingAsFakeUser(Roles::RegularUser);

        $user = $this->createUser();

        $response = $this->get('/api/users/' . $user->id, self::headers);

        $response->assertOk()->assertJsonStructure(self::userJsonStructure);
    }

    /**
     * @method update()
     */

    public function testUpdateExistingUserAsRegularUserShouldFailAuthorization()
    {
        $this->actingAsFakeUser(Roles::RegularUser);

        $user = $this->createUser(Roles::PremiumUser);

        $formData = $user->toArray();
        $formData['username'] = $this->faker->userName;
        $formData['first_name'] = $this->faker->firstName;
        $formData['last_name'] = $this->faker->lastName;

        $response = $this->put('/api/users/' . $user->id, $formData, self::headers);

        $response->assertForbidden();
    }

    public function testUpdateExistingUserAsRegularUserWithInvalidDataShouldFailForAuthorization()
    {
        $this->actingAsFakeUser(Roles::RegularUser);

        $user = $this->createUser()->assignRole(
            Roles::PremiumUser
        );

        $formData = $user->toArray();
        $formData['username'] = null;

        $response = $this->put('/api/users/' . $user->id, $formData, self::headers);

        $response->assertForbidden();
    }

    public function testUpdateExistingUserAsRegularUserWithInvalidUserIdShouldFailForAuthorization()
    {
        $this->actingAsFakeUser(Roles::RegularUser);

        $response = $this->put('/api/users/' . rand(), [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'username' => 'john.doe.1997',
            'email' => 'johndoe@gmail.com',
            'role' => Roles::RegularUser
        ], self::headers);

        $response->assertForbidden();
    }

    /**
     * @method destroy()
     */

    public function testDeleteUserAsRegularUserShouldFailAuthorization()
    {
        $this->actingAsFakeUser(Roles::RegularUser);

        $user = $this->createUser();

        $response = $this->delete('api/users/' . $user->id, [], self::headers);

        $response->assertForbidden();
    }

    public function testDeleteUserAsUnverifiedRegularUserShouldFailAuthorization()
    {
        $this->actingAsUnverifiedFakeUser(Roles::RegularUser);

        $user = $this->createUser();

        $response = $this->delete('api/users/' . $user->id, [], self::headers);

        $response->assertForbidden();
    }

    /**
     * @method forceDelete()
     */

    public function testForceDeleteUserAsRegularUserShouldFailAuthorization()
    {
        $this->actingAsUnverifiedFakeUser(Roles::RegularUser);

        $user = $this->createUser();

        $response = $this->delete('api/users/' . $user->id . '/force-delete', [], self::headers);

        $response->assertForbidden();
    }

    public function testForceDeleteUserAsUnverifiedRegularUserShouldFailAuthorization()
    {
        $this->actingAsUnverifiedFakeUser(Roles::RegularUser);

        $user = $this->createUser();

        $response = $this->delete('api/users/' . $user->id . '/force-delete', [], self::headers);

        $response->assertForbidden();
    }

    /**
     * @method restore()
     */

    public function testRestoreUserAsRegularUserShouldFailAuthorization()
    {
        $this->actingAsFakeUser(Roles::RegularUser);

        $user = $this->createUser();

        $user->delete();

        $response = $this->get('/api/users/' . $user->id . '/restore');

        $response->assertForbidden();
    }

    public function testRestoreUserAsRegularUserOnNotTrashedUserShouldFailAuthorization()
    {
        $this->actingAsFakeUser(Roles::RegularUser);

        $user = $this->createUser();

        $response = $this->get('/api/users/' . $user->id . '/restore');

        $response->assertForbidden();
    }

    public function test_restore_user_as_regular_user_on_invalid_user_id_should_fail_authorization()
    {
        $this->actingAsFakeUser(Roles::RegularUser);

        $response = $this->get('/api/users/25$$!/restore');

        $response->assertForbidden();
    }
}
