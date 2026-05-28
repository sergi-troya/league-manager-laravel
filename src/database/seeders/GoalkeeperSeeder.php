<?php

namespace Database\Seeders;

use App\Models\Goalkeeper;
use App\Models\Player;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GoalkeeperSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Primero, obtener todos los porteros de la tabla antigua
        $porters = DB::table('porters')->get();
        
        $count = 0;
        $skipped = 0;

        foreach ($porters as $porter) {
            // Buscar el jugador correspondiente en la nueva tabla players
            // usando team (equip) y number (dorsal)
            $player=Player::where('team', $porter->equip)
                          ->where('number', $porter->dorsal)
                          ->first();
            
            if (!$player) {
                $skipped++;
                $this->command->warn("Jugador no encontrado para portero: Equipo {$porter->equip}, Dorsal {$porter->dorsal}");
                continue;
            }

            // Crear el registro en goalkeepers
            Goalkeeper::create([
                'team' => $porter->equip,
                'number' => $porter->dorsal,
                'player_id' => $player->id,
                'matches' => $porter->partits,
                'goals' => $porter->gols,
            ]);

            $count++;
        }

        $this->command->info("Migración de porteros completada: $count porteros migrados, $skipped saltados.");
    }
}