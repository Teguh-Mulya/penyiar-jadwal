<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RadioBroadcast;
use App\Models\Approval;
use App\Models\LogStatus;
use App\Models\Comment;
use App\Models\User;
use Faker\Factory as Faker;

class RadioBroadcastSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $requiredRoles = ['Koordinator Siaran', 'Kepala Bidang Siaran', 'Kepala Stasiun'];

        // Fetch users with their roles that match the required roles
        $usersWithRoles = User::whereHas('roles', function ($query) use ($requiredRoles) {
            $query->whereIn('role_name', $requiredRoles);
        })->with('roles')->get();

        foreach (range(1, 10) as $index) {
            $broadcast = RadioBroadcast::create([
                'broadcast_name' => $faker->word,
                'description' => $faker->sentence,
                'date' => now(),
                'start_time' => $faker->time,
                'end_time' => $faker->time,
                'status' => 'created', // Initial status
            ]);

            // Create initial log status for the new broadcast
            LogStatus::create([
                'radio_broadcast_id' => $broadcast->id,
                'user_id' => $usersWithRoles->random()->id,
                'status' => 'created',
                'description' => 'Broadcast created.',
            ]);

            // Flatten user-role relationships and create approvals
            $approvals = [];
            foreach ($usersWithRoles as $user) {
                foreach ($user->roles as $role) {
                    if ($role->id != 1) {
                        $approvals[] = [
                            'radio_broadcast_id' => $broadcast->id,
                            'user_id' => $user->id,
                            'role_id' => $role->id, // Add role_id directly
                            'status' => 'pending',
                        ];
                    }
                }
            }
            // Bulk insert all approvals at once
            Approval::insert($approvals);

            // Create comments for the broadcast
            foreach ($usersWithRoles->random(2) as $user) {
                Comment::create([
                    'radio_broadcast_id' => $broadcast->id,
                    'user_id' => $user->id,
                    'comment' => $faker->text,
                ]);
            }
        }
    }
}

