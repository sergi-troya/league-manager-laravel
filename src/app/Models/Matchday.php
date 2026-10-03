<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Game;

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

    public static function dashboard(): array
    {
        $gameStats = Game::selectRaw('
            COUNT(CASE WHEN home_goals IS NOT NULL THEN 1 END) as games_completed,
            COUNT(CASE WHEN home_goals IS NULL THEN 1 END) as games_pending,
            COALESCE(SUM(home_goals), 0) + COALESCE(SUM(away_goals), 0) as total_goals
        ')->first();
        
        return [
            'matchday_count'  => self::count(),
            'games_completed' => (int) ($gameStats->games_completed ?? 0),
            'games_pending'   => (int) ($gameStats->games_pending ?? 0),
            'total_goals'     => (int) ($gameStats->total_goals ?? 0),
            'url'             => route('matchday.index'),
        ];
    }
}