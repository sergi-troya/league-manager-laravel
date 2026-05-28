<?php

namespace Database\Seeders;

use App\Models\Player;
use App\Models\Scorer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ScorerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $golejadors = DB::table('golejadors')->get();
        
        $count = 0;
        $skipped = 0;

        foreach ($golejadors as $golejador) {
            // Buscar jugador por team y number
            $player=Player::where('team', $golejador->equip)
                          ->where('number', $golejador->dorsal)
                          ->first();
            
            if (!$player) {
                $skipped++;
                $this->command->warn("Jugador no encontrado para golejador: Equipo '{golejador->equip}', Dorsal {$golejador->dorsal}");
                continue;
            }

            // Crear el scorer
            Scorer::create([
                'team' => $golejador->equip,
                'number' => $golejador->dorsal,
                'player_id' => $player->id,
                'matches' => $golejador->partits,
                'goals' => $golejador->gols,
                'penalties' => $golejador->penals,
                'own_goals' => $golejador->pp,
                'minutes_per_goal' => $golejador->minutsgol,
                'goals_starting' => $golejador->gtitular,
                'goals_substitute' => $golejador->gsuplent,
                'points' => $golejador->gpunts,
                'victory_goals' => $golejador->gvictoria,
                'comeback_goals' => $golejador->gremuntada,
                'percentage' => $golejador->percent,
            ]);

            $count++;
            
            // Mostrar progreso cada 50 registros
            if ($count % 50 === 0) {
                $this->command->info("Progreso: {$count} golejadores procesados...");
            }
        }

        $this->command->info("Migración de golejadores completada: $count golejadores migrados, $skipped saltados.");
    }
}