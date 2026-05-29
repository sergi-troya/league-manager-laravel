<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Matchday extends Model
{
    protected $fillable = [
        'number',
        'date',
    ];

    protected $casts = [
        'number' => 'integer',
        'date' => 'date',
    ];

    public function games(): HasMany
    {
        return $this->hasMany(Game::class, 'matchday_id');
    }

    static public function dashboard() {
        $num_jornadas = Matchday::count();
        $partidos_finalizados = Game::whereNotNull('home_goals')->count();
        $partidos_no_finalizados = Game::whereNull('home_goals')->count();
        $goles = Game::whereNotNull('home_goals')->sum('home_goals') +
                 Game::whereNotNull('away_goals')->sum('away_goals');
        return [
            'matchday_count' => $num_jornadas,
            'games_completed' => $partidos_finalizados,
            'games_pending' => $partidos_no_finalizados,
            'total_goals' => $goles,
            'url' => '#'
        ];
    }
}