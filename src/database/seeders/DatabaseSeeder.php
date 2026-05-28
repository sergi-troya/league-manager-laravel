<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->call([
            CitySeeder::class,         // ciudades
            TeamSeeder::class,         // Primero equipos
            MatchdaySeeder::class,     // Luego jornadas
            PlayerSeeder::class,       // Jugadores (necesitan equipos)
            GoalkeeperSeeder::class,   // Porteros (necesitan jugadores)
            ScorerSeeder::class,       // Goleadores (necesitan jugadores)
            GameSeeder::class,         // Partidos (necesitan equipos y jornadas)
        ]);
    }
}
