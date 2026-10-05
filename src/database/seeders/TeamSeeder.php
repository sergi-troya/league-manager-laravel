<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Team;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    public function run(): void
    {
        $teams = [
            ['ALF', '1001', 'Alfa FC', 'Club Deportivo Alfa'],
            ['BET', '1002', 'Beta FC', 'Club Deportivo Beta'],
            ['GAM', '1003', 'Gamma FC', 'Club Deportivo Gamma'],
            ['DEL', '1004', 'Delta FC', 'Club Deportivo Delta'],
        ];

        foreach ($teams as [$code, $cityCode, $shortName, $fullName]) {
            $city = City::where('code', $cityCode)->firstOrFail();

            Team::updateOrCreate(['code' => $code], [
                'short_name' => $shortName,
                'full_name' => $fullName,
                'city_id' => $city->id,
                'coach' => 'Entrenador ' . $shortName,
                'stadium' => 'Estadio ' . $shortName,
                'brand' => 'Demo',
                'sponsor' => 'Demo',
                'budget' => 1000000,
            ]);
        }
    }
}
