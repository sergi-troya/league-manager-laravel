<?php

namespace Database\Seeders;

use App\Models\Player;
use App\Models\Team;
use Illuminate\Database\Seeder;

class PlayerSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['ALF', 'BET', 'GAM', 'DEL'] as $code) {
            $team = Team::where('code', $code)->firstOrFail();

            $players = [
                ['number' => 1, 'name' => 'Portero ' . $team->short_name, 'position' => 'Portero'],
                ['number' => 9, 'name' => 'Delantero ' . $team->short_name, 'position' => 'Delantero'],
            ];

            foreach ($players as $player) {
                Player::updateOrCreate([
                    'team_id' => $team->id,
                    'number' => $player['number'],
                ], [
                    'team' => $team->code,
                    'name' => $player['name'],
                    'position' => $player['position'],
                    'salary' => 100000,
                ]);
            }
        }
    }
}
