<?php

namespace Database\Seeders;

use App\Models\Goalkeeper;
use App\Models\Team;
use Illuminate\Database\Seeder;

class GoalkeeperSeeder extends Seeder
{
    public function run(): void
    {
        // Goles recibidos en los dos partidos jugados de cada equipo.
        foreach (['ALF' => 1, 'BET' => 4, 'GAM' => 1, 'DEL' => 0] as $code => $goals) {
            $team = Team::where('code', $code)->firstOrFail();
            $player = $team->players()->where('number', 1)->firstOrFail();

            Goalkeeper::updateOrCreate(['player_id' => $player->id], [
                'team' => $team->code,
                'number' => $player->number,
                'matches' => 2,
                'goals' => $goals,
            ]);
        }
    }
}
