<?php

namespace Tests\Feature\User;

use App\Enums\HttpCode;
use App\Enums\Roles;
use Tests\Feature\User\Traits\WithCommonUserTestCasePropertiesTrait;

class AdminUserTest extends BaseUserTestCase
{
    use WithCommonUserTestCasePropertiesTrait;

    /**
     * @method store()
     */

    public function testCreateUserAsAdmin()
    {
        $this->actingAsFakeUser(Roles::Admin);

        $formData = $this->getMappedFormDataWithRole(Roles::RegularUser);

        $response = $this->post('/api/users', $formData, self::headers);

        $response->assertCreated()->assertJsonStructure(self::userJsonStructure);
    }

    public function testCreateUserAsAdminWithInvalidFormDataShouldFailValidation()
    {
        $this->actingAsFakeUser(Roles::Admin);

        $formData = $this->getInvalidMappedFormData(Roles::RegularUser);

        $response = $this->post('/api/users', $formData, self::headers);

        $response->assertStatus(HttpCode::UNPROCESSABLE_ENTITY);
    }

    /**
     * @method show()
     */

    public function testGetUserAsAdmin()
    {
        $this->actingAsFakeUser(Roles::Admin);

        $user = $this->createUser();

        $response = $this->get('/api/users/' . $user->id, self::headers);

        $response->assertOk()->assertJsonStructure(self::userJsonStructure);
    }

    public function testGetUserAsAdminOnNonExistingUserIdShouldFailForNotFound()
    {
        $this->actingAsFakeUser(Roles::Admin);

        $response = $this->get('/api/users/8165456489987', self::headers);

        $response->assertNotFound();
    }

    public function testGetUserAsAdminOnInvalidUserIdShouldFailForServerError()
    {
        $this->actingAsFakeUser(Roles::Admin);

        $response = $this->get('/api/users/invalid$user$id', self::headers);

        $response->assertStatus(HttpCode::INTERNAL_SERVER_ERROR);
    }

    /**
     * @method update()
     */

    public function testUpdateExistingUserAsAdmin()
    {
        $this->withoutExceptionHandling();

        $authUser = $this->actingAsFakeUser(Roles::Admin);

        $user = $this->createUser()->assignRole(
            Roles::PremiumUser
        );

        $formData = $user->toArray();
        $formData['username'] = $this->faker->userName;
        $formData['first_name'] = $this->faker->firstName;
        $formData['last_name'] = $this->faker->lastName;

        $response = $this->put('/api/users/' . $user->id, $formData, self::headers);

        $response->assertNoContent();
    }

    public function testUpdateExistingUserAsAdminWithInvalidDataShouldFailForValidation()
    {
        $this->actingAsFakeUser(Roles::Admin);

        $user = $this->createUser()->assignRole(
            Roles::PremiumUser
        );

        $formData = $user->toArray();
        $formData['username'] = null;

        $response = $this->put('/api/users/' . $user->id, $formData, self::headers);

        $response->assertStatus(HttpCode::UNPROCESSABLE_ENTITY);
    }

    public function testUpdateExistingUserAsAdminWithInvalidUserIdShouldFailForNotFound()
    {
        $this->actingAsFakeUser(Roles::Admin);

        $response = $this->put('/api/users/' . rand(), [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'username' => 'john.doe.1997',
            'email' => 'johndoe@gmail.com',
            'role' => Roles::RegularUser
        ], self::headers);

        $response->assertNotFound();
    }


    /**
     * @method destroy()
     */

    public function testDeleteUserAsAdmin()
    {
        $this->actingAsFakeUser(Roles::Admin);

        $user = $this->createUser();

        $response = $this->delete('api/users/' . $user->id, [], self::headers);

        $response->assertNoContent();
    }

    public function testDeleteUserAsAdminWithNonExistingUserIdShouldFailForNotFound()
    {
        $this->actingAsFakeUser(Roles::Admin);

        $response = $this->delete('api/users/32316532', [], self::headers);

        $response->assertNotFound();
    }

    public function testDeleteUserAsAdminWithInvalidUserIdShouldFailForServerError()
    {
        $this->actingAsFakeUser(Roles::Admin);

        $response = $this->delete('api/users/$INVALID_USER_ID#@!#%', [], self::headers);

        $response->assertStatus(HttpCode::INTERNAL_SERVER_ERROR);
    }

    /**
     * @method forceDelete()
     */

    public function testForceDeleteUserAsAdmin()
    {
        $this->actingAsFakeUser(Roles::Admin);

        $user = $this->createUser();

        $response = $this->delete('api/users/' . $user->id . '/force-delete', [], self::headers);

        $response->assertNoContent();
    }

    /**
     * @method restore()
     */

    public function testRestoreUserAsAdmin()
    {
        $this->actingAsFakeUser(Roles::Admin);

        $user = $this->createUser();

        $user->delete();

        $response = $this->get('/api/users/' . $user->id . '/restore');

        $response->assertNoContent();
    }

    public function testRestoreUserAsAdminOnNotTrashedUserShouldFailForNotFound()
    {
        $this->actingAsFakeUser(Roles::Admin);

        $user = $this->createUser();

        $response = $this->get('/api/users/' . $user->id . '/restore');

        $response->assertNotFound();
    }

    public function testRestoreUserAsAdminOnInvalidUserIdShouldFailForServerError()
    {
        $this->actingAsFakeUser(Roles::Admin);

        $response = $this->get('/api/users/25$$!/restore');

        $response->assertStatus(HttpCode::INTERNAL_SERVER_ERROR);
    }
}
