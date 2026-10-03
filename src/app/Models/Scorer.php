<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Scorer extends Model
{
    protected $fillable = [
        'team',
        'number',
        'player_id',
        'matches',
        'goals',
        'penalties',
        'own_goals',
        'minutes_per_goal',
        'goals_starting',
        'goals_substitute',
        'points',
        'victory_goals',
        'comeback_goals',
        'percentage',
    ];

    protected $casts = [
        'number' => 'integer',
        'player_id' => 'integer',
        'matches' => 'integer',
        'goals' => 'integer',
        'penalties' => 'integer',
        'own_goals' => 'integer',
        'minutes_per_goal' => 'integer',
        'goals_starting' => 'integer',
        'goals_substitute' => 'integer',
        'points' => 'integer',
        'victory_goals' => 'integer',
        'comeback_goals' => 'integer',
        'percentage' => 'integer',
    ];

    /**
     * Player relationship
     */
    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }
    
    public static function dashboard(): array
    {
        $topScorers = self::with('player')
            ->orderByDesc('goals')
            ->take(3)
            ->get()
            ->map(fn ($scorer) => [
                'value' => $scorer->goals,
                'label' => $scorer->player->name ?? 'Jugador no asignado',
            ])
            ->toArray();

        return [
            'top_scorers' => $topScorers,
            'url' => route('scorers.index'),
        ];    
    }

}