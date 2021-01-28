<?php

namespace Tests\Traits;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;

trait WithCommonMethodsTrait
{
    /**
     * Act as a verified user with role.
     *
     * @param string $roleName
     * @return Authenticatable
     */
    public function actingAsFakeUser(string $roleName): Authenticatable
    {
        return Sanctum::actingAs(
            User::factory()->create()->assignRole(
                Role::findByName($roleName)
            )
        );
    }

    /**
     * Act as a not verified user.
     *
     * @param string $roleName
     * @return Authenticatable
     */
    public function actingAsUnverifiedFakeUser(string $roleName): Authenticatable
    {
        return Sanctum::actingAs(
            User::factory(['email_verified_at' => null])->create()->assignRole(
                Role::findByName($roleName)
            )
        );
    }

    /**
     * Create a verified user.
     *
     * @param string $rolenName
     * @return User
     */
    public function createUser(string $roleName = null): User
    {
        return User::factory()->create()->assignRole(
            $roleName
        );
    }

    /**
     * Create a verified user.
     *
     * @param string $rolenName
     * @return User
     */
    public function createUnverifiedUser(string $roleName = null): User
    {
        return User::factory(['email_verified_at' => null])->create()->assignRole(
            $roleName
        );
    }
}
