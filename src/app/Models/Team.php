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
        $sql = "select id, short_name as label,
                (select count(*) from games g where g.home_team_id = t.id and g.home_goals > g.away_goals) * 3 +
                (select count(*) from games g where g.away_team_id = t.id and g.away_goals > g.home_goals) * 3 +
                (select count(*) from games g where (g.home_team_id = t.id or g.away_team_id = t.id) and g.away_goals = g.home_goals)
                 as value
                from teams t 
                order by value DESC 
                LIMIT 3;";

        /* $topTeams = collect(DB::select($sql))
            ->map(function ($team) {
                return [
                    'value' => $team->value,
                    'label' => $team->label,
                ];
            })
            ->toArray(); */

        $resultado = DB::select($sql);

        $topTeams = [];

        foreach ($resultado as $team) {
            $topTeams[] = [
                'value' => $team->value,
                'label' => $team->label,
            ];
        }

        return [
            'teams_count' => self::count(),
            'top_teams' => $topTeams,
            'url' => '#',
        ];
    }
}
