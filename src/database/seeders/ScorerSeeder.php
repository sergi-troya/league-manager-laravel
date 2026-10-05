<?php

namespace Database\Seeders;

use App\Models\Scorer;
use App\Models\Team;
use Illuminate\Database\Seeder;

class ScorerSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['ALF' => 3, 'BET' => 1, 'DEL' => 2] as $code => $goals) {
            $team = Team::where('code', $code)->firstOrFail();
            $player = $team->players()->where('number', 9)->firstOrFail();

            Scorer::updateOrCreate(['player_id' => $player->id], [
                'team' => $team->code,
                'number' => $player->number,
                'matches' => 2,
                'goals' => $goals,
                'penalties' => 0,
                'own_goals' => 0,
                'minutes_per_goal' => intdiv(180, $goals),
                'goals_starting' => $goals,
                'goals_substitute' => 0,
                'points' => null,
                'victory_goals' => null,
                'comeback_goals' => null,
                'percentage' => null,
            ]);
        }
    }
}
