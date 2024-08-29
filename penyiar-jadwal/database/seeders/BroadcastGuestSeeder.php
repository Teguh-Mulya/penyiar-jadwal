<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BroadcastGuest;
use App\Models\RadioBroadcast;
use Faker\Factory as Faker;

class BroadcastGuestSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $broadcasts = RadioBroadcast::all();

        foreach ($broadcasts as $broadcast) {
            foreach (range(1, 3) as $index) {
                BroadcastGuest::create([
                    'name' => $faker->name,
                    'broadcast_id' => $broadcast->id,
                    'status' => 'pending',
                ]);
            }
        }
    }
}

