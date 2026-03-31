<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Profile;

class ProfileSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {

            // Only consumers get profiles
            //if ($user->isConsumer() || $user->isAdmin()) {

                // Avoid duplicate profile
                if ($user->profiles()->count() === 0) {

                    Profile::create([
                        'user_id' => $user->id,
                        'name' => $user->name,
                        'is_kids' => false,
                        'maturity_level' => 'U'
                    ]);

                    $this->command->info("Profile created for {$user->email}");
                }
            //}
        }
    }
}
