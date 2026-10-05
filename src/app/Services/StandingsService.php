<?php

namespace App\Services;

use App\Models\Game;
use App\Models\Matchday;
use App\Models\Team;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use InvalidArgumentException;

final class StandingsService
{
    /**
     * Derive the dashboard totals from the same scored games as the table.
     */
    public function seasonSnapshot(): array
    {
        $standings = $this->calculate();
        $completed = intdiv($standings->sum('played'), 2);

        return [
            'standings' => $standings,
            'teams_count' => $standings->count(),
            'matchday_count' => Matchday::query()->count(),
            'games_completed' => $completed,
            'games_pending' => Game::query()->count() - $completed,
            'total_goals' => $standings->sum('goals_for'),
        ];
    }

    /**
     * Return every team's cumulative totals and position.
     *
     * @return Collection<int, array{
     *     position: int, team: Team, played: int, won: int, drawn: int,
     *     lost: int, goals_for: int, goals_against: int,
     *     goal_difference: int, points: int
     * }>
     */
    public function calculate(?int $matchdayNumber = null): Collection
    {
        if ($matchdayNumber !== null && $matchdayNumber < 1) {
            throw new InvalidArgumentException('The matchday number must be positive.');
        }

        $teams = Team::query()->get(['id', 'code', 'short_name', 'full_name']);
        $table = [];

        foreach ($teams as $team) {
            $table[$team->id] = [
                'team' => $team,
                'played' => 0,
                'won' => 0,
                'drawn' => 0,
                'lost' => 0,
                'goals_for' => 0,
                'goals_against' => 0,
                'goal_difference' => 0,
                'points' => 0,
            ];
        }

        $games = Game::query()
            ->whereNotNull('home_goals')
            ->whereNotNull('away_goals')
            ->when($matchdayNumber !== null, function (Builder $query) use ($matchdayNumber) {
                $query->whereHas('matchday', function (Builder $matchdays) use ($matchdayNumber) {
                    $matchdays->where('number', '<=', $matchdayNumber);
                });
            })
            ->get(['id', 'home_team_id', 'away_team_id', 'home_goals', 'away_goals']);

        foreach ($games as $game) {
            foreach ([
                [$game->home_team_id, $game->home_goals, $game->away_goals],
                [$game->away_team_id, $game->away_goals, $game->home_goals],
            ] as [$teamId, $goalsFor, $goalsAgainst]) {
                $row = $table[$teamId];
                $row['played']++;
                $row['goals_for'] += $goalsFor;
                $row['goals_against'] += $goalsAgainst;
                $row['goal_difference'] = $row['goals_for'] - $row['goals_against'];

                if ($goalsFor > $goalsAgainst) {
                    $row['won']++;
                    $row['points'] += 3;
                } elseif ($goalsFor === $goalsAgainst) {
                    $row['drawn']++;
                    $row['points']++;
                } else {
                    $row['lost']++;
                }

                $table[$teamId] = $row;
            }
        }

        $rows = array_values($table);

        usort($rows, static function (array $left, array $right): int {
            return ($right['points'] <=> $left['points'])
                ?: ($right['goal_difference'] <=> $left['goal_difference'])
                ?: ($right['goals_for'] <=> $left['goals_for'])
                // An exact tie gets a stable display order, independent of IDs.
                ?: strcmp($left['team']->code, $right['team']->code);
        });

        return collect($rows)->map(static fn (array $row, int $index) => [
            'position' => $index + 1,
            ...$row,
        ]);
    }
}
