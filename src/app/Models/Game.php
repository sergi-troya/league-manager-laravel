<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Game extends Model
{
    protected $fillable = [
        'home_team_id',
        'away_team_id',
        'matchday_id',
        'home_goals',
        'away_goals',
        'home_possession',
    ];

    protected $casts = [
        'home_team_id' => 'integer',
        'away_team_id' => 'integer',
        'matchday_id' => 'integer',
        'home_goals' => 'integer',
        'away_goals' => 'integer',
        'home_possession' => 'integer',
    ];

    public function homeTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'home_team_id');
    }

    public function awayTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'away_team_id');
    }

    public function matchday(): BelongsTo
    {
        return $this->belongsTo(Matchday::class);
    }
}