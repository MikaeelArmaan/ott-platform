<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;

class RbacSeeder extends Seeder
{
    public function run(): void
    {
        // Roles
        $admin   = Role::firstOrCreate(['name' => 'admin']);
        $content = Role::firstOrCreate(['name' => 'content_manager']);

        // Permissions
        $manageUsers = Permission::firstOrCreate(['name' => 'manage_users']);
        $upload      = Permission::firstOrCreate(['name' => 'upload_content']);
        $delete      = Permission::firstOrCreate(['name' => 'delete_content']);

        // Attach permissions to roles
        $admin->permissions()->sync([
            $manageUsers->id,
            $upload->id,
            $delete->id
        ]);

        $content->permissions()->sync([
            $upload->id
        ]);

        // Assign admin role to first user
        $user = User::first();
        if ($user) {
            $user->roles()->syncWithoutDetaching([$admin->id]);
        }
    }
}
