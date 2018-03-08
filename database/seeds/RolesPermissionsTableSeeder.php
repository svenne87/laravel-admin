<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;

class RolesPermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()['cache']->forget('spatie.permission.cache');

        // Permissions
        Permission::create(['guard_name' => 'web', 'name' => 'access admin cp']);

        // User permissions
        Permission::create(['guard_name' => 'api', 'name' => 'edit users']);
        Permission::create(['guard_name' => 'api','name' => 'create users']);
        Permission::create(['guard_name' => 'api','name' => 'show users']);
        Permission::create(['guard_name' => 'api','name' => 'delete users']);

        // Role permissions
        Permission::create(['guard_name' => 'api', 'name' => 'edit roles']);
        Permission::create(['guard_name' => 'api','name' => 'create roles']);
        Permission::create(['guard_name' => 'api','name' => 'show roles']);
        Permission::create(['guard_name' => 'api','name' => 'delete roles']);

        // Permissions permissions
        Permission::create(['guard_name' => 'api','name' => 'show permissions']);

        // Create roles and assign existing permissions
        $role = Role::create(['guard_name' => 'api', 'name' => 'super_admin']);
        $role->givePermissionTo($this->getAllPermissions());

        $user = User::findOrFail(1);
        $user->assignRole($role);

        $role = Role::create(['guard_name' => 'web', 'name' => 'super_admin']);
        $role->givePermissionTo(['access admin cp']);
        $user->assignRole($role);

        Role::create(['guard_name' => 'api', 'name' => 'user']);
        Role::create(['guard_name' => 'web', 'name' => 'user']);
    }

    private function getAllPermissions() 
    {
        return [
            'edit users', 
            'create users', 
            'show users', 
            'delete users', 
            'edit roles',
            'create roles',
            'show roles',
            'delete roles',
            'show permissions',
        ];
    }
}
