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

        // Create permissions
        Permission::create(['guard_name' => 'web', 'name' => 'access admin cp']);
        Permission::create(['guard_name' => 'api', 'name' => 'edit users']);
        Permission::create(['guard_name' => 'api','name' => 'create users']);
        Permission::create(['guard_name' => 'api','name' => 'show users']);
        Permission::create(['guard_name' => 'api','name' => 'delete users']);

        // Create roles and assign existing permissions
        $role = Role::create(['guard_name' => 'api', 'name' => 'super_admin']);
        $role->givePermissionTo(['edit users', 'create users', 'show users', 'delete users']);

        $user = User::findOrFail(1);
        $user->assignRole($role);

        $role = Role::create(['guard_name' => 'web', 'name' => 'super_admin']);
        $role->givePermissionTo(['access admin cp']);
        $user->assignRole($role);

        Role::create(['guard_name' => 'api', 'name' => 'user']);
        Role::create(['guard_name' => 'web', 'name' => 'user']);
    }
}
