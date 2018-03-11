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
        Permission::create(['guard_name' => 'web','name' => 'preview posts']);

        $allPermissions = $this->getAllPermissions();

        foreach ($allPermissions as $permission) {
            Permission::create(['guard_name' => 'api', 'name' => $permission]);
        }
        
        // Create roles and assign existing permissions
        $role = Role::create(['guard_name' => 'api', 'name' => 'super_admin']);
        $role->givePermissionTo($allPermissions);

        $user = User::findOrFail(1);
        $user->assignRole($role);

        $role = Role::create(['guard_name' => 'web', 'name' => 'super_admin']);
        $role->givePermissionTo(['access admin cp', 'preview posts']);
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
            'edit posts', 
            'create posts', 
            'show posts', 
            'delete posts', 
        ];
    }
}
