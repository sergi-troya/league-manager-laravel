<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        foreach (SeasonData::rows('cities') as $city) {
            City::updateOrCreate(['code' => $city['code']], [
                'name' => $city['name'],
                'population' => $city['population'],
            ]);
        }
    }
}
