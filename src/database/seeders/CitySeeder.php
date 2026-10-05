<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        $cities = [
            ['code' => '1001', 'name' => 'Ciudad Alfa', 'population' => 100000],
            ['code' => '1002', 'name' => 'Ciudad Beta', 'population' => 120000],
            ['code' => '1003', 'name' => 'Ciudad Gamma', 'population' => 80000],
            ['code' => '1004', 'name' => 'Ciudad Delta', 'population' => 90000],
        ];

        foreach ($cities as $city) {
            City::updateOrCreate(['code' => $city['code']], $city);
        }
    }
}
