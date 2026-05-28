<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\Team;
use App\Models\Matchday;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $partits = DB::table('partits')->get();
        
        // Precargar todos los datos necesarios
        $teams = Team::pluck('id', 'code')->toArray();
        $matchdays = Matchday::pluck('id', 'number')->toArray();
        
        // Obtener partidos existentes para evitar duplicados
        $existingGames = Game::select('home_team_id', 'away_team_id', 'matchday_id')
            ->get()
            ->map(function ($game) {
                return $game->home_team_id . '_' .$game->away_team_id . '_' . $game->matchday_id;
            })
            ->toArray();

        $gamesData = [];
        $count = 0;
        $skipped = 0;

        foreach ($partits as $partit) {
            // Validar existencia de equipos
            if (!isset($teams[$partit->equipc]) || !isset($teams[$partit->equipf])) {
                $skipped++;
                continue;
            }
            
            // Validar existencia de jornada
            if (!isset($matchdays[$partit->jornada])) {
                $skipped++;
                continue;
            }
            
            $homeTeamId=$teams[$partit->equipc];
            $awayTeamId=$teams[$partit->equipf];
            $matchdayId=$matchdays[$partit->jornada];
            
            // Verificar si ya existe este partido
            $gameKey = $homeTeamId . '_' . $awayTeamId . '_' .$matchdayId;
            if (in_array($gameKey,$existingGames)) {
                $skipped++;
                continue;
            }

            $gamesData[] = [
                'home_team_id' => $homeTeamId,
                'away_team_id' => $awayTeamId,
                'matchday_id' => $matchdayId,
                'home_goals' => $partit->golsc,
                'away_goals' => $partit->golsf,
                'home_possession' => $partit->possessioc,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Agregar a la lista de existentes para prevenir duplicados en este batch
            $existingGames[] = $gameKey;
            $count++;
        }

        // Insertar todos los datos en una sola operación
        if (!empty($gamesData)) {
            Game::insert($gamesData);
        }

        $this->command->info("Migración de partidos completada: $count partidos migrados, $skipped saltados.");
    }
}