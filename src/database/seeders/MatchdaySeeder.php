<?php

namespace Database\Seeders;

use App\Models\Matchday;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MatchdaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Primero, limpiar la tabla si es necesario (opcional)
        // Matchday::truncate();
        
        $jornades = DB::table('jornades')->get();
        
        $matchdaysData = [];
        $count = 0;
        $skipped = 0;
        
        // Precargar números existentes para evitar duplicados
        $existingNumbers = Matchday::pluck('number')->toArray();

        foreach ($jornades as $jornada) {
            $matchdaysData[] = [
                'number' => $jornada->num,
                'date' => $jornada->data,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $count++;

            // Insertar en lotes cada 50 registros
            if ($count % 50 === 0) {
                Matchday::insert($matchdaysData);
                $matchdaysData = [];
            }
        }

        // Insertar los registros restantes
        if (!empty($matchdaysData)) {
            Matchday::insert($matchdaysData);
        }

        $this->command->info("Migración de jornadas completada: $count jornadas migradas, $skipped saltadas.");
    }
}