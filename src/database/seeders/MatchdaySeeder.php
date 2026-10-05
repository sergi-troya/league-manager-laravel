<?php

namespace Database\Seeders;

use App\Models\Matchday;
use Illuminate\Database\Seeder;

class MatchdaySeeder extends Seeder
{
    public function run(): void
    {
        foreach (SeasonData::rows('matchdays') as $matchday) {
            Matchday::updateOrCreate(['number' => $matchday['number']], [
                'date' => $matchday['date'],
            ]);
        }
    }
}
