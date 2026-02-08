<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@ott.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('admin123'),
            ]
        );

        // Attach admin role
        $adminRole = Role::where('name', 'admin')->first();

        if ($adminRole) {
            $admin->roles()->syncWithoutDetaching([$adminRole->id]);
        }

        // Create Normal User
        User::firstOrCreate(
            ['email' => 'user@ott.com'],
            [
                'name' => 'Demo User',
                'password' => Hash::make('user123'),
            ]
        );
    }
}
