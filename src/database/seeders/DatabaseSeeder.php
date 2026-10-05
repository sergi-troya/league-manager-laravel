<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        DB::transaction(function () {
            if (!User::where('email', 'test@example.com')->exists()) {
                User::factory()->create([
                    'name' => 'Test User',
                    'email' => 'test@example.com',
                ]);
            }

            $this->call([
                CitySeeder::class,
                TeamSeeder::class,
                MatchdaySeeder::class,
                PlayerSeeder::class,
                GameSeeder::class,
                GoalkeeperSeeder::class,
                ScorerSeeder::class,
            ]);
        });
    }
}
