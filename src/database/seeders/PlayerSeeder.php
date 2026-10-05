<?php

namespace Database\Seeders;

use App\Models\Player;
use App\Models\Team;
use Illuminate\Database\Seeder;

class PlayerSeeder extends Seeder
{
    public function run(): void
    {
        $teamIds = Team::query()->pluck('id', 'code')->all();

        foreach (SeasonData::rows('players') as $player) {
            $teamId = SeasonData::resolveId($teamIds, $player['team'], 'teams.code');

            Player::updateOrCreate([
                'team_id' => $teamId,
                'number' => $player['number'],
            ], [
                'team' => $player['team'],
                'name' => $player['name'],
                'position' => $player['position'],
                'salary' => $player['salary'],
            ]);
        }
    }
}
