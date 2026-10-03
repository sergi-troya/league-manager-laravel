<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Team extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'code',
        'short_name',
        'full_name',
        'city_id',
        'coach',
        'stadium',
        'brand',
        'sponsor',
        'budget',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'city_id' => 'integer',
            'budget' => 'integer',
        ];
    }

    /**
     * Get the city that owns the team.
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Get the players for the team.
     */
    public function players(): HasMany
    {
        return $this->hasMany(Player::class);
    }

    static public function dashboard(): array
    {
        $sql = "SELECT id, short_name AS label,
                (SELECT COUNT(*) FROM games g WHERE g.home_team_id = t.id AND g.home_goals > g.away_goals) * 3 +
                (SELECT COUNT(*) FROM games g WHERE g.away_team_id = t.id AND g.away_goals > g.home_goals) * 3 +
                (SELECT COUNT(*) FROM games g WHERE (g.home_team_id = t.id OR g.away_team_id = t.id) AND g.away_goals = g.home_goals)
                AS value
                FROM teams t 
                ORDER BY value DESC 
                LIMIT 3";

        $topTeams = array_map(function ($team) {
            return [
                'value' => (int) $team->value,
                'label' => $team->label,
            ];
        }, DB::select($sql));

        return [
            'teams_count' => self::count(),
            'top_teams' => $topTeams,
            'url' => route('teams.index'),
        ];
    }
}
