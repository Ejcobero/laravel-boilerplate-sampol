<?php

namespace Database\Seeders;

use App\Enums\Roles;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        foreach ($this->permissions() as $permission) {
            Permission::create(['name' => $permission]);
        }

        // create roles
        foreach ($this->roles() as $role) {
            $role = Role::create([
                'name' => $role,
            ]);

            $this->assignPermissionToRole($role);
        }
    }

    private function roles(): Collection
    {
        return collect(Roles::All);
    }

    private function permissions(): Collection
    {
        return collect([
            ...$this->userModulePermissions(),
        ]);
    }

    private function superAdminPermissions(): Collection
    {
        return $this->permissions();
    }

    private function adminPermissions(): Collection
    {
        return collect([
            ...$this->userModulePermissions(),
        ]);
    }

    private function userModulePermissions(): Collection
    {
        return $this->actions()->map(function ($action) {
            return "UserModule:$action";
        });
    }

    private function actions(): Collection
    {
        return collect([
            'Create',
            'Read',
            'Update',
            'Delete',
            'Restore',
            'ForceDelete'
        ]);
    }

    private function assignPermissionToRole(Role $role)
    {
        if ($role->name == Roles::SuperAdmin)
            $role->givePermissionTo($this->superAdminPermissions());

        if ($role->name == Roles::Admin)
            $role->givePermissionTo($this->adminPermissions());
    }
}
