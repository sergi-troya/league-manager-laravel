<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Player extends Model
{
    protected $fillable = [
        'team',
        'team_id',
        'number',
        'name',
        'position',
        'salary',
    ];

    protected $casts = [
        'team_id' => 'integer',
        'number' => 'integer',
        'salary' => 'integer',
    ];

    protected $guarded = [
            'id',
            'team',
        ];  

    /**
     * Team relationship
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

}