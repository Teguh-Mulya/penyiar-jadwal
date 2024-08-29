<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BroadcastHost;
use App\Models\User;
use App\Models\RadioBroadcast;

class BroadcastHostsSeeder extends Seeder
{
    public function run()
    {
        $broadcasts = RadioBroadcast::all();
        $users = User::all();

        foreach ($broadcasts as $broadcast) {
            foreach ($users->random(2) as $user) { // Assign 2 random users to each broadcast
                BroadcastHost::create([
                    'user_id' => $user->id,
                    'broadcast_id' => $broadcast->id,
                    'description' => 'Host for ' . $broadcast->broadcast_name,
                ]);
            }
        }
    }
}
