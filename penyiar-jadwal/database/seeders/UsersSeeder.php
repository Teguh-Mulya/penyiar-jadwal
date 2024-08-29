<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Faker\Factory as Faker;

class UsersSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $roles = Role::all();

        foreach (range(1, 15) as $index) { // Create 15 users
            $role = $roles->random();
            $user = User::create([
                'username' => $faker->unique()->userName,
                'password' => bcrypt('password'), // default password
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'status' => 'active',
            ]);

            $user->roles()->attach($role->id);
        }
    }
}
