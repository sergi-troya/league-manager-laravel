<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['code', 'name', 'population'])]

class City extends Model
{
    protected $casts = [
        'code' => 'integer',
        'population' => 'integer'
    ];

    public function teams() : HasMany {
        return $this->hasMany(Team::class);
    }
}
