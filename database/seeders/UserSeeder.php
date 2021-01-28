<?php

namespace Database\Seeders;

use App\Enums\Roles;
use App\Repository\Contracts\User\UserRepositoryInterface;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = $this->userRepository->create([
            'first_name' => 'Carlo Miguel',
            'last_name' => 'Dy',
            'email' => 'dev@dev.com',
            'username' => 'carlomigueldy',
            'email_verified_at' => now(),
            'password' => bcrypt('password')
        ]);

        $user->assignRole($this->getRoleName(Roles::SuperAdmin));

        $this->generateAdmins(2);
        $this->generateRegularUsers(3);
        $this->generatePremiumUsers(1);
        $this->generateUnverifiedRegularUsers(5);
    }

    private function generateAdmins($amount)
    {
        $users = \App\Models\User::factory($amount)->create();

        foreach ($users as $user) {
            $user->assignRole($this->getRoleName(Roles::Admin));
        }
    }

    private function generateRegularUsers($amount)
    {
        $users = \App\Models\User::factory($amount)->create();

        foreach ($users as $user) {
            $user->assignRole($this->getRoleName(Roles::RegularUser));
        }
    }

    private function generatePremiumUsers($amount)
    {
        $users = \App\Models\User::factory($amount)->create();

        foreach ($users as $user) {
            $user->assignRole($this->getRoleName(Roles::PremiumUser));
        }
    }

    private function generateUnverifiedRegularUsers($amount)
    {
        $users = \App\Models\User::factory($amount, ['email_verified_at' => null])->create();

        foreach ($users as $user) {
            $user->assignRole($this->getRoleName(Roles::PremiumUser));
        }
    }

    private function getRoleName(string $roleName)
    {
        return Role::findByName($roleName);
    }
}
