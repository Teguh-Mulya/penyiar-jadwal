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
        $requiredRoles = ['Koordinator Siaran', 'Kabid', 'Kepala Siaran'];

        // Ambil user yang memiliki roles yang diperlukan
        $users = User::whereHas('roles', function ($query) use ($requiredRoles) {
            $query->whereIn('role_name', $requiredRoles);
        })->get();

        foreach (range(1, 10) as $index) {
            $broadcast = RadioBroadcast::create([
                'broadcast_name' => $faker->word,
                'description' => $faker->sentence,
                'date' => $faker->date,
                'start_time' => $faker->time,
                'end_time' => $faker->time,
                'status' => 'created', // Status awal
            ]);

            // Buat log status untuk broadcast baru
            LogStatus::create([
                'radio_broadcast_id' => $broadcast->id,
                'user_id' => $users->random()->id,
                'status' => 'created',
                'description' => 'Broadcast created.',
            ]);

            // Buat approval untuk semua peran yang diperlukan
            foreach ($users->unique('id') as $user) {
                // Ambil role ID yang dimiliki user
                $roleIds = $user->roles->pluck('id')->toArray();

                // Buat approval untuk setiap user
                $approval = Approval::create([
                    'radio_broadcast_id' => $broadcast->id,
                    'user_id' => $user->id,
                    'status' => 'pending',
                ]);

                // Hubungkan role ke approval melalui tabel pivot
                $approval->roles()->sync($roleIds);
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
