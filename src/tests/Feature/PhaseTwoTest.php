<?php

namespace Tests\Feature;

use App\Models\City;
use App\Models\Game;
use App\Models\Matchday;
use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PhaseTwoTest extends TestCase
{
    use RefreshDatabase;

    private const COUNTS = [
        'users' => 1,
        'cities' => 58,
        'teams' => 20,
        'players' => 535,
        'matchdays' => 38,
        'games' => 380,
        'goalkeepers' => 44,
        'scorers' => 239,
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
        $this->seed();
    }

    public function test_seeders_are_autonomous_and_idempotent(): void
    {
        foreach (['ciutats', 'equips', 'jugadors', 'jornades', 'partits', 'porters', 'golejadors'] as $table) {
            $this->assertFalse(Schema::hasTable($table));
        }

        foreach (self::COUNTS as $table => $count) {
            $this->assertDatabaseCount($table, $count);
        }

        $before = $this->snapshot();

        $this->seed();
        $this->assertSame($before, $this->snapshot());

        $this->seed();
        $this->assertSame($before, $this->snapshot());

        $stats = Matchday::dashboard();
        $this->assertSame(351, $stats['games_completed']);
        $this->assertSame(29, $stats['games_pending']);
        $this->assertSame(1002, $stats['total_goals']);
    }

    public function test_string_limits_are_enforced_on_create_and_update(): void
    {
        $city = City::where('code', '8')->firstOrFail();
        $team = Team::where('code', 'bar')->firstOrFail();
        $player = $team->players()->where('number', 9)->firstOrFail();

        $this->post(route('cities.store'), [
            'code' => '9001',
            'name' => str_repeat('x', 31),
        ])->assertSessionHasErrors('name');

        $this->put(route('cities.update', $city), [
            'code' => (string) $city->code,
            'name' => str_repeat('x', 31),
        ])->assertSessionHasErrors('name');

        $invalidTeam = [
            'code' => 'NEW',
            'city_id' => $city->id,
            'short_name' => str_repeat('x', 21),
            'full_name' => str_repeat('x', 41),
            'stadium' => str_repeat('x', 31),
            'brand' => str_repeat('x', 31),
            'sponsor' => str_repeat('x', 31),
        ];

        $teamErrors = ['short_name', 'full_name', 'stadium', 'brand', 'sponsor'];

        $this->post(route('teams.store'), $invalidTeam)
            ->assertSessionHasErrors($teamErrors);

        $this->put(route('teams.update', $team), array_replace($invalidTeam, [
            'code' => $team->code,
        ]))->assertSessionHasErrors($teamErrors);

        $invalidPlayer = [
            'number' => 99,
            'name' => str_repeat('x', 31),
            'position' => str_repeat('x', 11),
        ];

        $this->post(route('players.store', ['team' => $team]), $invalidPlayer)
            ->assertSessionHasErrors(['name', 'position']);

        $this->put(route('players.update', ['team' => $team, 'player' => $player]), array_replace($invalidPlayer, [
            'number' => $player->number,
        ]))->assertSessionHasErrors(['name', 'position']);

        $this->assertDatabaseMissing('cities', ['code' => '9001']);
        $this->assertDatabaseMissing('teams', ['code' => 'NEW']);
        $this->assertDatabaseMissing('players', ['team_id' => $team->id, 'number' => 99]);

        $this->assertSame('Barcelona', $city->fresh()->name);
        $this->assertSame('Barça', $team->fresh()->short_name);
        $this->assertSame('Alexis Sánchez', $player->fresh()->name);
    }

    public function test_player_number_is_unique_per_team_and_edit_ignores_the_current_player(): void
    {
        $team = Team::where('code', 'bar')->firstOrFail();
        $otherTeam = Team::where('code', 'rma')->firstOrFail();
        $payload = ['number' => 99, 'name' => 'Jugador prueba', 'position' => 'Defensa'];

        foreach ([$team, $otherTeam] as $currentTeam) {
            $this->post(route('players.store', ['team' => $currentTeam]), $payload)
                ->assertSessionHasNoErrors()
                ->assertRedirect(route('players.index', ['team' => $currentTeam]));
        }

        $player = $team->players()->where('number', 99)->firstOrFail();

        $this->post(route('players.store', ['team' => $team]), $payload)
            ->assertSessionHasErrors('number');

        $this->put(route('players.update', ['team' => $team, 'player' => $player]), array_replace($payload, [
            'name' => 'Jugador editado',
        ]))->assertSessionHasNoErrors()
            ->assertRedirect(route('players.index', ['team' => $team]));

        $this->put(route('players.update', ['team' => $team, 'player' => $player]), array_replace($payload, [
            'number' => 9,
        ]))->assertSessionHasErrors('number');

        $this->put(route('players.update', ['team' => $otherTeam, 'player' => $player]), $payload)
            ->assertNotFound();

        $this->assertSame(2, DB::table('players')->where('number', 99)->count());
        $this->assertSame(99, $player->fresh()->number);
        $this->assertSame('Jugador editado', $player->fresh()->name);
        $this->assertSame($team->id, $player->fresh()->team_id);
    }

    public function test_match_updates_validate_goals_scope_the_matchday_and_keep_the_teams(): void
    {
        $matchday = Matchday::where('number', 38)->firstOrFail();
        $wrongMatchday = Matchday::where('number', 1)->firstOrFail();
        $game = $matchday->games()->whereNull('home_goals')->whereNull('away_goals')->orderBy('id')->firstOrFail();
        $beforeStats = Matchday::dashboard();

        $homeTeamId = $game->home_team_id;
        $awayTeamId = $game->away_team_id;

        $url = route('matchday.game.update', ['matchday' => $matchday, 'game' => $game]);
        $wrongUrl = route('matchday.game.update', ['matchday' => $wrongMatchday, 'game' => $game]);

        $this->get(route('matchday.game.edit', ['matchday' => $wrongMatchday, 'game' => $game]))
            ->assertNotFound();

        $this->post($wrongUrl, ['home_goals' => 3, 'away_goals' => 0])
            ->assertNotFound();

        foreach (['home_goals', 'away_goals'] as $field) {
            foreach (['abc', -1, 1.5, 2147483648] as $value) {
                $this->post($url, array_replace([
                    'home_goals' => 3,
                    'away_goals' => 0,
                ], [$field => $value]))->assertSessionHasErrors($field);
            }
        }

        foreach ([
            'home_team_id' => $awayTeamId,
            'away_team_id' => $homeTeamId,
            'matchday_id' => $wrongMatchday->id,
        ] as $field => $value) {
            $this->post($url, [
                'home_goals' => 3,
                'away_goals' => 0,
                $field => $value,
            ])->assertSessionHasErrors($field);
        }

        $this->assertNull($game->fresh()->home_goals);
        $this->assertNull($game->fresh()->away_goals);

        // Los campos prohibidos vacíos tampoco deben llegar a la actualización.
        $this->post($url, [
            'home_goals' => 3,
            'away_goals' => 0,
            'home_team_id' => null,
            'away_team_id' => null,
            'matchday_id' => null,
        ])->assertSessionHasNoErrors()
            ->assertRedirect(route('matchday.index', ['matchday' => $matchday->number]));

        $this->assertDatabaseHas('games', [
            'id' => $game->id,
            'matchday_id' => $matchday->id,
            'home_team_id' => $homeTeamId,
            'away_team_id' => $awayTeamId,
            'home_goals' => 3,
            'away_goals' => 0,
        ]);

        $stats = Matchday::dashboard();
        $this->assertSame($beforeStats['games_completed'] + 1, $stats['games_completed']);
        $this->assertSame($beforeStats['games_pending'] - 1, $stats['games_pending']);
        $this->assertSame($beforeStats['total_goals'] + 3, $stats['total_goals']);
    }

    private function snapshot(): array
    {
        $snapshot = [];

        foreach (array_keys(self::COUNTS) as $table) {
            $snapshot[$table] = DB::table($table)
                ->orderBy('id')
                ->get()
                ->map(fn ($row) => Arr::except((array) $row, ['created_at', 'updated_at']))
                ->all();
        }

        return $snapshot;
    }
}