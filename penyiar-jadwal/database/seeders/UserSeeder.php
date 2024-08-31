<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Faker\Factory as Faker;
use App\Models\Role;

class UserSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        $roles = Role::pluck('id'); // Get an array of role IDs

        // Create 15 users
        foreach (range(1, 15) as $index) {
            $user = User::create([
                'username' => $faker->userName,
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password'),
                'status' => $faker->word,
            ]);

            // Attach 2 random roles to the user
            $user->roles()->attach($roles->random(2)->toArray());
        }
    }
}
