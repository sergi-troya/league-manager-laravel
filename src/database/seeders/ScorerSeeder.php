<?php

namespace Database\Seeders;

use App\Models\Scorer;
use App\Models\Team;
use Illuminate\Database\Seeder;

class ScorerSeeder extends Seeder
{
    public function run(): void
    {
        $teamIds = Team::query()->pluck('id', 'code')->all();
        $playerIds = SeasonData::playerIds();

        foreach (SeasonData::rows('scorers') as $scorer) {
            $teamId = SeasonData::resolveId($teamIds, $scorer['team'], 'teams.code');
            $playerId = SeasonData::resolveId(
                $playerIds,
                $teamId . ':' . $scorer['number'],
                'players(team_id, number)'
            );

            Scorer::updateOrCreate(['player_id' => $playerId], $scorer);
        }
    }
}
