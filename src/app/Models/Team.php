<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Override;

#[Fillable([
    'code',
    'short_name',
    'full_name',
    'city_id',
    'coach',
    'stadium',
    'brand',
    'sponsor',
    'budget'
])]

class Team extends Model
{
    protected function casts() : array
    {
        return [
            'city_id' => 'integer',
            'budget' => 'integer'
        ];
        
    }

    public function city() : BelongsTo {
        return $this->belongsTo(City::class);
    }

    public function players(): HasMany
    {
        return $this->hasMany(Player::class);
    }
}
