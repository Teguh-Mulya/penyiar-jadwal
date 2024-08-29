<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RadioBroadcast;
use App\Models\Approval;
use App\Models\LogStatus;
use App\Models\Comment;
use App\Models\User;
use App\Models\Role;
use Faker\Factory as Faker;

class RadioBroadcastSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $statuses = ['created', 'pending', 'approved', 'ongoing', 'completed', 'canceled', 'rejected'];
        $roles = Role::whereIn('role_name', ['Koordinator Siaran', 'Kabid', 'Kepala Siaran'])->get(); // Ambil role yang diperlukan
        $users = User::all();

        foreach (range(1, 10) as $index) {
            $broadcast = RadioBroadcast::create([
                'broadcast_name' => $faker->word,
                'description' => $faker->sentence,
                'date' => $faker->date,
                'start_time' => $faker->time,
                'end_time' => $faker->time,
                'status' => 'created', // Status awal
            ]);

            // Create log status untuk broadcast baru
            LogStatus::create([
                'radio_broadcast_id' => $broadcast->id,
                'user_id' => $users->random()->id,
                'status' => 'created',
                'description' => 'Broadcast created.',
            ]);

            // Buat approval untuk semua peran yang diperlukan
            foreach ($roles as $role) {
                foreach ($users->whereIn('id', $users->pluck('id')->random(2)) as $user) {
                    Approval::create([
                        'radio_broadcast_id' => $broadcast->id,
                        'user_id' => $user->id,
                        'role' => $role->role_name, // Gunakan nama role dari database
                        'status' => 'pending',
                    ]);
                }
            }

            // Buat komentar untuk broadcast
            foreach ($users->random(2) as $user) {
                Comment::create([
                    'radio_broadcast_id' => $broadcast->id,
                    'user_id' => $user->id,
                    'comment' => $faker->text,
                ]);
            }
        }
    }
}
