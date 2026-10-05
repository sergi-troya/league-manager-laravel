<?php

namespace Database\Seeders;

use App\Models\Matchday;
use Illuminate\Database\Seeder;

class MatchdaySeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            1 => '2026-01-10',
            2 => '2026-01-17',
            3 => '2026-01-24',
        ] as $number => $date) {
            Matchday::updateOrCreate(['number' => $number], ['date' => $date]);
        }
    }
}
