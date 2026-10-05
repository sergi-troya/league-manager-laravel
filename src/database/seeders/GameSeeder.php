<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\Matchday;
use App\Models\Team;
use Illuminate\Database\Seeder;

class GameSeeder extends Seeder
{
    public function run(): void
    {
        // Jornada, local, visitante, goles local, goles visitante, posesión local.
        $games = [
            [1, 'ALF', 'BET', 2, 1, 55],
            [1, 'GAM', 'DEL', 0, 0, 50],
            [2, 'ALF', 'GAM', 1, 0, 52],
            [2, 'BET', 'DEL', 0, 2, 45],
            [3, 'ALF', 'DEL', null, null, null],
            [3, 'BET', 'GAM', null, null, null],
        ];

        foreach ($games as [$number, $homeCode, $awayCode, $homeGoals, $awayGoals, $possession]) {
            $matchday = Matchday::where('number', $number)->firstOrFail();
            $homeTeam = Team::where('code', $homeCode)->firstOrFail();
            $awayTeam = Team::where('code', $awayCode)->firstOrFail();

            Game::updateOrCreate([
                'matchday_id' => $matchday->id,
                'home_team_id' => $homeTeam->id,
                'away_team_id' => $awayTeam->id,
            ], [
                'home_goals' => $homeGoals,
                'away_goals' => $awayGoals,
                'home_possession' => $possession,
            ]);
        }
    }
}
