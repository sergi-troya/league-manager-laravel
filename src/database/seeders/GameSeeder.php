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
        $teamIds = Team::query()->pluck('id', 'code')->all();
        $matchdayIds = Matchday::query()->pluck('id', 'number')->all();

        foreach (SeasonData::rows('games') as $game) {
            Game::updateOrCreate([
                'matchday_id' => SeasonData::resolveId($matchdayIds, $game['matchday_number'], 'matchdays.number'),
                'home_team_id' => SeasonData::resolveId($teamIds, $game['home_team_code'], 'teams.code'),
                'away_team_id' => SeasonData::resolveId($teamIds, $game['away_team_code'], 'teams.code'),
            ], [
                'home_goals' => $game['home_goals'],
                'away_goals' => $game['away_goals'],
                'home_possession' => $game['home_possession'],
            ]);
        }
    }
}
