<?php

namespace Database\Seeders;

use App\Models\Player;
use App\Models\Team;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlayerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jugadors = DB::table('jugadors')->get();
        $count = 0;
        $skipped = 0;

        foreach ($jugadors as $jugador ) {
            // Verificar si existe el equipo
            $team=Team::where('code',$jugador->equip)->first();
            
            if (!$team) {
                $skipped++;
                $this->command->warn("Equipo $jugador->equip no encontrado para jugador: {$jugador->nom}");
                continue;
            }

            Player::create([
                'team_id' => $team->id,
                'team' => $jugador->equip,
                'number' => $jugador->dorsal,
                'name' => $jugador->nom,
                'position' => $jugador->lloc,
                'salary' => $jugador->sou,
            ]);

            $count++;
        }

       $this->command->info("Migración completada: $count jugadores migrados, $skipped saltados.");
    }
}