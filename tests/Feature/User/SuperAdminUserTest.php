<?php

namespace Tests\Feature\User;

use App\Enums\HttpCode;
use App\Enums\Roles;
use App\Models\User;
use Tests\Feature\User\BaseUserTestCase;
use Tests\Feature\User\Traits\WithCommonUserTestCasePropertiesTrait;

class SuperAdminUserTest extends BaseUserTestCase
{
    use WithCommonUserTestCasePropertiesTrait;

    /**
     * @method store()
     */

    public function testCreateUserAsSuperAdmin()
    {
        $this->actingAsFakeUser(Roles::SuperAdmin);

        $formData = $this->getMappedFormDataWithRole(Roles::RegularUser);

        $response = $this->post('/api/users', $formData, self::headers);

        $response->assertCreated()->assertJsonStructure(self::userJsonStructure);
    }

    public function testCreateUserAsSuperAdminWithInvalidFormDataShouldFailValidation()
    {
        $this->actingAsFakeUser(Roles::SuperAdmin);

        $formData = $this->getInvalidMappedFormData(Roles::RegularUser);

        $response = $this->post('/api/users', $formData, self::headers);

        $response->assertStatus(HttpCode::UNPROCESSABLE_ENTITY);
    }

    /**
     * @method show()
     */

    public function testGetUserAsSuperAdmin()
    {
        $this->actingAsFakeUser(Roles::SuperAdmin);

        $user = $this->createUser();

        $response = $this->get('/api/users/' . $user->id , self::headers);

        $response->assertOk()->assertJsonStructure(self::userJsonStructure);
    }

    public function testGetUserAsSuperAdminOnNonExistingUserIdShouldFailForNotFound()
    {
        $this->actingAsFakeUser(Roles::SuperAdmin);

        $response = $this->get('/api/users/8165456489987', self::headers);

        $response->assertNotFound();
    }

    public function testGetUserAsSuperAdminOnInvalidUserIdShouldFailForServerError()
    {
        $this->actingAsFakeUser(Roles::SuperAdmin);

        $response = $this->get('/api/users/invalid$user$id', self::headers);

        $response->assertStatus(HttpCode::INTERNAL_SERVER_ERROR);
    }


    /**
     * @method update()
     */

    public function testUpdateExistingUserAsSuperAdmin()
    {
        $this->withoutExceptionHandling();

        $authUser = $this->actingAsFakeUser(Roles::SuperAdmin);

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

    public function testUpdateExistingUserAsSuperAdminWithInvalidDataShouldFailForValidation()
    {
        $this->actingAsFakeUser(Roles::SuperAdmin);

        $user = $this->createUser()->assignRole(
            Roles::PremiumUser
        );

        $formData = $user->toArray();
        $formData['username'] = null;

        $response = $this->put('/api/users/' . $user->id, $formData, self::headers);

        $response->assertStatus(HttpCode::UNPROCESSABLE_ENTITY);
    }

    public function testUpdateExistingUserAsSuperAdminWithInvalidUserIdShouldFailForNotFound()
    {
        $this->actingAsFakeUser(Roles::SuperAdmin);

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

    public function testDeleteUserAsSuperAdmin()
    {
        $this->actingAsFakeUser(Roles::SuperAdmin);

        $user = $this->createUser();

        $response = $this->delete('api/users/' . $user->id, [], self::headers);

        $response->assertNoContent();
    }

    public function testDeleteUserAsSuperAdminWithNonExistingUserIdShouldFailForNotFound()
    {
        $this->actingAsFakeUser(Roles::SuperAdmin);

        $response = $this->delete('api/users/32316532', [], self::headers);

        $response->assertNotFound();
    }

    public function testDeleteUserAsSuperAdminWithInvalidUserIdShouldFailForServerError()
    {
        $this->actingAsFakeUser(Roles::SuperAdmin);

        $response = $this->delete('api/users/$INVALID_USER_ID#@!#%', [], self::headers);

        $response->assertStatus(HttpCode::INTERNAL_SERVER_ERROR);
    }

    /**
     * @method forceDelete()
     */

    public function testForceDeleteUserAsSuperAdmin()
    {
        $this->actingAsFakeUser(Roles::SuperAdmin);

        $user = $this->createUser();

        $response = $this->delete('api/users/' . $user->id . '/force-delete', [], self::headers);

        $response->assertNoContent();
    }

    /**
     * @method restore()
     */

    public function testRestoreUserAsSuperAdmin()
    {
        $this->actingAsFakeUser(Roles::SuperAdmin);

        $user = $this->createUser();

        $user->delete();

        $response = $this->get('/api/users/' . $user->id . '/restore');

        $response->assertNoContent();
    }

    public function testRestoreUserAsSuperAdminOnNotTrashedUserShouldFailForNotFound()
    {
        $this->actingAsFakeUser(Roles::SuperAdmin);

        $user = $this->createUser();

        $response = $this->get('/api/users/' . $user->id . '/restore');

        $response->assertNotFound();
    }

    public function testRestoreUserAsSuperAdminOnInvalidUserIdShouldFailForServerError()
    {
        $this->actingAsFakeUser(Roles::SuperAdmin);

        $response = $this->get('/api/users/25$$!/restore');

        $response->assertStatus(HttpCode::INTERNAL_SERVER_ERROR);
    }
}
