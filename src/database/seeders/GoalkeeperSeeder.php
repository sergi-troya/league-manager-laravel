<?php

namespace Database\Seeders;

use App\Models\Goalkeeper;
use App\Models\Team;
use Illuminate\Database\Seeder;

class GoalkeeperSeeder extends Seeder
{
    public function run(): void
    {
        $teamIds = Team::query()->pluck('id', 'code')->all();
        $playerIds = SeasonData::playerIds();

        foreach (SeasonData::rows('goalkeepers') as $goalkeeper) {
            $teamId = SeasonData::resolveId($teamIds, $goalkeeper['team'], 'teams.code');
            $playerId = SeasonData::resolveId(
                $playerIds,
                $teamId . ':' . $goalkeeper['number'],
                'players(team_id, number)'
            );

            Goalkeeper::updateOrCreate(['player_id' => $playerId], $goalkeeper);
        }
    }
}
