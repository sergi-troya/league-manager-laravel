<?php

namespace Tests\Feature;

use App\Models\City;
use App\Models\Goalkeeper;
use App\Models\Matchday;
use App\Models\Player;
use App\Models\Scorer;
use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class LeagueSeasonSeedTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_complete_calendar_rosters_and_statistics_are_linked_correctly(): void
    {
        $this->seed();

        foreach ([
            'cities' => 58,
            'teams' => 20,
            'players' => 535,
            'goalkeepers' => 44,
            'scorers' => 239,
            'matchdays' => 38,
            'games' => 380,
        ] as $table => $count) {
            $this->assertDatabaseCount($table, $count);
        }

        $this->assertSame([
            'ath', 'atm', 'bar', 'bet', 'cel', 'dep', 'esp', 'gda', 'get', 'lev',
            'mal', 'mga', 'osa', 'ray', 'rma', 'rso', 'sev', 'vad', 'val', 'zar',
        ], Team::query()->orderBy('code')->pluck('code')->all());

        $this->assertSame(range(1, 38), Matchday::query()->orderBy('number')->pluck('number')->all());

        $days = Matchday::withCount('games')->orderBy('number')->get();
        $dates = $days->map(fn (Matchday $day) => $day->date->format('Y-m-d'))->all();
        $sortedDates = $dates;
        sort($sortedDates);
        $this->assertSame($sortedDates, $dates);
        $this->assertSame('2012-08-19', $dates[0]);
        $this->assertSame('2013-06-01', $dates[37]);

        // Each round has ten games, and every team participates exactly once.
        $games = DB::table('games')->get();

        foreach ($days as $day) {
            $round = $games->where('matchday_id', $day->id);
            $this->assertSame(10, $day->games_count);
            $this->assertSame(20, $round->pluck('home_team_id')
                ->concat($round->pluck('away_team_id'))->unique()->count());
        }

        // Each team hosts each other team exactly once, without self-matches.
        $this->assertSame(0, DB::table('games')->whereColumn('home_team_id', 'away_team_id')->count());
        $this->assertSame(380, DB::table('games')
            ->select('home_team_id', 'away_team_id')->distinct()->get()->count());
        $this->assertSame(20, Team::query()->has('city')->count());
        $this->assertSame(535, DB::table('players')
            ->join('teams', 'players.team_id', '=', 'teams.id')
            ->whereColumn('players.team', 'teams.code')->count());

        foreach (['goalkeepers' => 44, 'scorers' => 239] as $table => $count) {
            $this->assertSame($count, DB::table($table)
                ->join('players', $table . '.player_id', '=', 'players.id')
                ->join('teams', 'players.team_id', '=', 'teams.id')
                ->whereColumn($table . '.team', 'teams.code')
                ->whereColumn($table . '.number', 'players.number')
                ->count());
        }

        $this->assertSame(44, Player::query()->where('position', 'Portero')->count());
        $this->assertSame(253, Player::query()->where('position', 'Defensa')->count());
        $this->assertSame(197, Player::query()->where('position', 'Medio')->count());
        $this->assertSame(41, Player::query()->where('position', 'Delantero')->count());

        $this->assertSame(351, DB::table('games')->whereNotNull('home_goals')->whereNotNull('away_goals')->count());
        $this->assertSame(29, DB::table('games')->whereNull('home_goals')->whereNull('away_goals')->whereNull('home_possession')->count());
        $this->assertSame(1002, Matchday::dashboard()['total_goals']);

        $barcelona = Team::where('code', 'bar')->firstOrFail();
        $messi = $barcelona->players()->where('number', 10)->firstOrFail();
        $this->assertSame('Lionel Messi', $messi->name);
        $this->assertDatabaseHas('scorers', ['player_id' => $messi->id, 'goals' => 46]);
    }

    public function test_seeders_resolve_foreign_keys_when_existing_rows_shift_the_ids(): void
    {
        City::create(['code' => '9000', 'name' => 'Ciudad de prueba']);
        $city = City::create(['code' => '9001', 'name' => 'Otra ciudad de prueba']);
        $team = Team::create(['code' => 'zzz', 'short_name' => 'Equipo de prueba', 'city_id' => $city->id]);
        Player::create(['team_id' => $team->id, 'team' => $team->code, 'number' => 99, 'name' => 'Jugador de prueba']);
        Matchday::create(['number' => 99, 'date' => '2026-01-01']);

        $this->seed();

        $barcelonaCity = City::where('code', '8')->firstOrFail();
        $barcelona = Team::where('code', 'bar')->firstOrFail();
        $madrid = Team::where('code', 'rma')->firstOrFail();
        $matchday = Matchday::where('number', 7)->firstOrFail();
        $messi = $barcelona->players()->where('number', 10)->firstOrFail();
        $valdes = $barcelona->players()->where('number', 1)->firstOrFail();

        $this->assertNotSame(8, $barcelonaCity->id);
        $this->assertNotSame(7, $matchday->id);
        $this->assertSame($barcelonaCity->id, $barcelona->city_id);
        $this->assertDatabaseHas('games', [
            'matchday_id' => $matchday->id,
            'home_team_id' => $barcelona->id,
            'away_team_id' => $madrid->id,
            'home_goals' => 2,
            'away_goals' => 2,
        ]);
        $this->assertSame(46, Scorer::where('player_id', $messi->id)->firstOrFail()->goals);
        $this->assertSame($valdes->id, Goalkeeper::where('player_id', $valdes->id)->firstOrFail()->player_id);

        $ids = [
            $barcelonaCity->id, $barcelona->id, $matchday->id, $messi->id, $valdes->id,
        ];
        $this->seed();
        $this->assertSame($ids, [
            $barcelonaCity->fresh()->id, $barcelona->fresh()->id, $matchday->fresh()->id,
            $messi->fresh()->id, $valdes->fresh()->id,
        ]);

        foreach ([
            'cities' => 60, 'teams' => 21, 'players' => 536, 'matchdays' => 39,
            'games' => 380, 'goalkeepers' => 44, 'scorers' => 239,
        ] as $table => $count) {
            $this->assertDatabaseCount($table, $count);
        }
        $this->assertDatabaseHas('teams', ['id' => $team->id, 'code' => 'zzz']);
    }

    public function test_real_season_pages_render_after_seeding(): void
    {
        $this->withoutVite();
        $this->seed();

        $barcelona = Team::where('code', 'bar')->firstOrFail();
        $matchday = Matchday::where('number', 38)->firstOrFail();
        $game = $matchday->games()->whereNull('home_goals')->firstOrFail();

        foreach ([
            route('home.index'),
            route('cities.index'),
            route('teams.index'),
            route('players.index', ['team' => $barcelona]),
            route('scorers.index'),
            route('matchday.index', ['matchday' => 1]),
            route('matchday.index', ['matchday' => 38]),
            route('matchday.game.edit', ['matchday' => $matchday, 'game' => $game]),
        ] as $url) {
            $this->get($url)->assertOk();
        }
    }
}
