<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Goalkeeper extends Model
{
    protected $fillable = [
        'team',
        'number',
        'player_id',
        'matches',
        'goals',
    ];

    protected $casts = [
        'number' => 'integer',
        'player_id' => 'integer',
        'matches' => 'integer',
        'goals' => 'integer',
    ];

    /**
     * Player relationship
     */
    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }
}