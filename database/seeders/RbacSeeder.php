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
        $manageUsers   = Permission::firstOrCreate(['name' => 'manage_users']);

        // 🔥 Content Permissions
        $manageContent = Permission::firstOrCreate(['name' => 'manage_content']);
        $viewContent   = Permission::firstOrCreate(['name' => 'view_content']);
        $createContent = Permission::firstOrCreate(['name' => 'create_content']);
        $editContent   = Permission::firstOrCreate(['name' => 'edit_content']);
        $deleteContent = Permission::firstOrCreate(['name' => 'delete_content']);
        $publishContent = Permission::firstOrCreate(['name' => 'publish_content']);

        /*
    |--------------------------------------------------------------------------
    | Assign Permissions
    |--------------------------------------------------------------------------
    */

        // Admin → full access
        $admin->permissions()->sync([
            $manageUsers->id,
            $manageContent->id,
            $viewContent->id,
            $createContent->id,
            $editContent->id,
            $deleteContent->id,
            $publishContent->id,
        ]);

        // Content Manager → limited access
        $content->permissions()->sync([
            $viewContent->id,
            $createContent->id,
            $editContent->id,
            $publishContent->id,
        ]);

        /*
    |--------------------------------------------------------------------------
    | Assign Role to User
    |--------------------------------------------------------------------------
    */

        $user = User::first();
        if ($user) {
            $user->roles()->syncWithoutDetaching([$admin->id]);
        }
    }
}
