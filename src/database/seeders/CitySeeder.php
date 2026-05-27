<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Obtener los datos de la tabla antigua
        $oldCities = DB::table('ciutats')->get();

        // Preparar los datos para la nueva tabla
        $newCities = [];
        
        foreach ($oldCities as $oldCity) {
            $newCities[] = [
                'code' => $oldCity->codi,
                'name' => $oldCity->nom,
                'population' => $oldCity->habitants,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insertar los datos en la nueva tabla
        if (!empty($newCities)) {
            DB::table('cities')->insert($newCities);
            
            $this->command->info(sprintf(
                'Migradas %d ciudades de "ciutats" a "cities"',
                count($newCities)
            ));
        } else {
            $this->command->warn('No se encontraron ciudades para migrar');
        }
    }
}