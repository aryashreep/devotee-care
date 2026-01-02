<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\State;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get(database_path('data/locations.json'));
        $data = json_decode($json);

        foreach ($data->states as $stateData) {
            $state = State::create(['name' => $stateData->name]);

            foreach ($stateData->cities as $cityName) {
                City::create([
                    'name' => $cityName,
                    'state_id' => $state->id,
                ]);
            }
        }
    }
}
