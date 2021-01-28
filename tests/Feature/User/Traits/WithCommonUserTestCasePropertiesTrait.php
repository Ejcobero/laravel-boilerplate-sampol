<?php

namespace Tests\Feature\User\Traits;

use Illuminate\Foundation\Testing\WithFaker;

trait WithCommonUserTestCasePropertiesTrait
{
    use WithFaker;

    public function getMappedFormDataWithRole(string $role): array
    {
        return [
            'first_name' => $this->faker->firstName,
            'middle_name' => $this->faker->lastName,
            'last_name' => $this->faker->lastName,
            'username' => $this->faker->userName,
            'email' => $this->faker->email,
            'password' => 'Password45512$!@#',
            'role' => $role,
        ];
    }

    public function getInvalidMappedFormData(string $role): array
    {
        return [
            'middle_name' => $this->faker->lastName,
            'username' => $this->faker->userName,
            'email' => $this->faker->email,
            'password' => null,
            'role' => $role,
        ];
    }
}
