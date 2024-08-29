<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BroadcastHost;
use App\Models\User;
use App\Models\RadioBroadcast;

class BroadcastHostSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();
        $broadcasts = RadioBroadcast::all();

        foreach ($broadcasts as $broadcast) {
            foreach ($users->random(3) as $user) {
                BroadcastHost::create([
                    'user_id' => $user->id,
                    'broadcast_id' => $broadcast->id,
                    'description' => 'Host for ' . $broadcast->broadcast_name,
                ]);
            }
        }
    }
}

