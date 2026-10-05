<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Team;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class TeamSeeder extends Seeder
{
    public function run(): void
    {
        $cityIds = City::query()->pluck('id', 'code')->all();

        foreach (SeasonData::rows('teams') as $team) {
            $values = Arr::except($team, ['code', 'city_code']);
            $values['city_id'] = $team['city_code'] === null
                ? null
                : SeasonData::resolveId($cityIds, $team['city_code'], 'cities.code');

            Team::updateOrCreate(['code' => $team['code']], $values);
        }
    }
}
