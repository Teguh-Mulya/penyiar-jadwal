<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BroadcastGuest;
use App\Models\RadioBroadcast;
use Faker\Factory as Faker;

class BroadcastGuestsSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $broadcasts = RadioBroadcast::all();

        foreach ($broadcasts as $broadcast) {
            foreach (range(1, 3) as $index) { // Add 3 guests per broadcast
                BroadcastGuest::create([
                    'name' => $faker->name,
                    'broadcast_id' => $broadcast->id,
                    'status' => $faker->randomElement(['confirmed', 'pending']),
                ]);
            }
        }
    }
}
