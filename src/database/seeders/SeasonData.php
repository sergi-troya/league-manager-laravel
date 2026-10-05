<?php

namespace Database\Seeders;

use App\Models\Player;
use InvalidArgumentException;
use RuntimeException;

final class SeasonData
{
    /**
     * @return list<array<string, int|string|null>>
     */
    public static function rows(string $table): array
    {
        if (!in_array($table, [
            'cities', 'teams', 'players', 'matchdays',
            'games', 'goalkeepers', 'scorers',
        ], true)) {
            throw new InvalidArgumentException("Unknown season dataset: {$table}");
        }

        $path = __DIR__ . "/data/laliga-2012-2013/{$table}.json";

        if (!is_readable($path)) {
            throw new RuntimeException("Missing season dataset: {$path}");
        }

        $contents = file_get_contents($path);

        if ($contents === false) {
            throw new RuntimeException("Cannot read season dataset: {$path}");
        }

        $rows = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);

        if (!is_array($rows) || !array_is_list($rows)) {
            throw new RuntimeException("Invalid season dataset: {$path}");
        }

        return $rows;
    }

    /**
     * Resolve a natural key to the ID assigned by the current database.
     *
     * @param array<int|string, int> $ids
     */
    public static function resolveId(array $ids, int|string $key, string $reference): int
    {
        if (!array_key_exists($key, $ids)) {
            throw new RuntimeException(
                "Missing {$reference} reference '{$key}'. Run php artisan db:seed to load dependencies."
            );
        }

        return (int) $ids[$key];
    }

    /**
     * @return array<string, int>
     */
    public static function playerIds(): array
    {
        return Player::query()
            ->get(['id', 'team_id', 'number'])
            ->mapWithKeys(fn (Player $player) => [
                $player->team_id . ':' . $player->number => $player->id,
            ])
            ->all();
    }
}
