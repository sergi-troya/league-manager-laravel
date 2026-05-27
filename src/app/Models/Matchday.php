<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MatchDay extends Model
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
        return $this->hasMany(Game::class, 'match_day_id');
    }
}