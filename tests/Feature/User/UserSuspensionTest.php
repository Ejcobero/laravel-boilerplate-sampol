<?php

namespace Tests\Feature\User;

use App\Enums\HttpCode;
use App\Enums\Roles;

class UserSuspensionTest extends BaseUserTestCase
{
    public function testSuspendUserAsSuperAdmin()
    {
        $this->actingAsFakeUser(Roles::SuperAdmin);

        $user = $this->createUser(Roles::RegularUser);

        $response = $this->patch('/api/users/' . $user->id . '/suspend', [], self::headers);

        $response->assertStatus(HttpCode::ACCEPTED)->assertJson([
            'is_suspended' => true
        ]);
    }

    public function testUnsuspendUserAsSuperAdmin()
    {
        $this->withoutExceptionHandling();

        $this->actingAsFakeUser(Roles::SuperAdmin);

        $user = $this->createUser(Roles::PremiumUser);

        $user->suspend();

        $response = $this->patch('/api/users/' . $user->id . '/unsuspend', [], self::headers);

        $response->assertStatus(HttpCode::ACCEPTED)->assertJson([
            'is_suspended' => false
        ]);
    }
}
