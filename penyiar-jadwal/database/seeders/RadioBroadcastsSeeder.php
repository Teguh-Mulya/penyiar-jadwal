<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RadioBroadcast;
use Faker\Factory as Faker;

class RadioBroadcastsSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1, 30) as $index) { // Create 10 broadcasts
            RadioBroadcast::create([
                'broadcast_name' => $faker->word . ' Show',
                'description' => $faker->sentence,
                'date' => $faker->date,
                'start_time' => $faker->time,
                'end_time' => $faker->time,
                'status' => $faker->randomElement(['active', 'inactive']),
            ]);
        }
    }
}
