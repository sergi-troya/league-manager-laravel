<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Primero obtener el mapeo de códigos de ciudad a IDs de la nueva tabla
        $cityMapping = DB::table('cities')
            ->pluck('id', 'code')
            ->toArray();

        // Obtener los datos de la tabla antigua
        $oldTeams = DB::table('equips')->get();

        // Preparar los datos para la nueva tabla
        $newTeams = [];
        $migratedCount = 0;
        $skippedCount = 0;
        
        foreach ($oldTeams as $oldTeam) {
            // Buscar el ID de la ciudad en la nueva tabla
            $cityId = null;
            if ($oldTeam->ciutat && isset($cityMapping[$oldTeam->ciutat])) {
                $cityId=$cityMapping[$oldTeam->ciutat];
            }
            
            // Solo migrar si la ciudad existe o es null (si permitimos null)
            $newTeams[] = [
                'code' => $oldTeam->codi,
                'short_name' => $oldTeam->nomcurt,
                'full_name' => $oldTeam->nomllarg,
                'city_id' => $cityId,
                'coach' => $oldTeam->entrenador,
                'stadium' => $oldTeam->estadi,
                'brand' => $oldTeam->marca,
                'sponsor' => $oldTeam->patrocinador,
                'budget' => $oldTeam->pressupost,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            
            $migratedCount++;
        }

        // Insertar los datos en la nueva tabla
        if (!empty($newTeams)) {
            // Si quieres evitar problemas con claves duplicadas, puedes usar insertOrIgnore
            // pero mejor usar try-catch para mostrar información de errores
            try {
                DB::table('teams')->insert($newTeams);
                
                $this->command->info(sprintf(
                    'Migrados %d equipos de "equips" a "teams"',
                    $migratedCount
                ));
                
                if ($skippedCount > 0) {
                    $this->command->warn(sprintf(
                        'Omitidos %d equipos por falta de ciudad relacionada',
                        $skippedCount
                    ));
                }
                
            } catch (\Exception $e) {
                $this->command->error('Erroralmigrarequipos:' . $e->getMessage());
                
                // Opción alternativa: insertar uno por uno para identificar errores
                $this->command->info('Intentando insertar uno por uno...');
                
                $successCount = 0;
                $errorCount = 0;
                
                foreach ($newTeams as $team) {
                    try {
                        DB::table('teams')->insert($team);
                        $successCount++;
                    } catch (\Exception $singleError) {
                        $this->command->error("Errorconequipo" . $singleError->getMessage());
                        $errorCount++;
                    }
                }
                
                $this->command->info(sprintf(
                    'Resultado: %d exitosos, %d con errores',
                    $successCount,$successCount,$successCount,$errorCount
                ));
            }
        }
    }
}