<?php

namespace Tests\Feature;

use App\Models\City;
use App\Models\Game;
use App\Models\Matchday;
use App\Models\Player;
use App\Models\Team;
use App\Services\StandingsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Tests\TestCase;

class PhaseThreeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    public function test_calculates_home_and_away_wins_draws_losses_and_goals_exactly(): void
    {
        $a = $this->team('aaa');
        $b = $this->team('bbb');
        $c = $this->team('ccc');
        $first = $this->matchday(1);
        $second = $this->matchday(2);
        $third = $this->matchday(3);

        $this->game($first, $a, $b, 3, 1);
        $this->game($second, $b, $c, 2, 0);
        $this->game($second, $c, $a, 1, 1);
        $this->game($third, $a, $c, 0, 2);

        $rows = $this->service()->calculate();
        $this->assertSame([
            'played' => 3, 'won' => 1, 'drawn' => 1, 'lost' => 1,
            'goals_for' => 4, 'goals_against' => 4, 'goal_difference' => 0, 'points' => 4,
        ], $this->stats($rows, $a));
        $this->assertSame([
            'played' => 2, 'won' => 1, 'drawn' => 0, 'lost' => 1,
            'goals_for' => 3, 'goals_against' => 3, 'goal_difference' => 0, 'points' => 3,
        ], $this->stats($rows, $b));
        $this->assertSame([
            'played' => 3, 'won' => 1, 'drawn' => 1, 'lost' => 1,
            'goals_for' => 3, 'goals_against' => 3, 'goal_difference' => 0, 'points' => 4,
        ], $this->stats($rows, $c));
        $this->assertSame(['aaa', 'ccc', 'bbb'], $this->codes($rows));
        $this->assertSame([1, 2, 3], $rows->pluck('position')->all());
        $this->assertSame(8, $rows->sum('played'));
        $this->assertSame($rows->sum('goals_for'), $rows->sum('goals_against'));
    }

    public function test_pending_and_incomplete_results_do_not_contribute_to_the_table_or_dashboard(): void
    {
        $a = $this->team('aaa');
        $b = $this->team('bbb');
        $c = $this->team('ccc');
        $first = $this->matchday(1);
        $second = $this->matchday(2);

        $this->game($first, $a, $b, 1, 0);
        $this->game($second, $b, $a, null, null);
        $this->game($second, $a, $c, 2, null);
        $this->game($second, $c, $a, null, 2);

        $snapshot = $this->service()->seasonSnapshot();
        $rows = $snapshot['standings'];
        $this->assertSame(1, $this->stats($rows, $a)['played']);
        $this->assertSame(3, $this->stats($rows, $a)['points']);
        $this->assertSame(0, $this->stats($rows, $b)['points']);
        $this->assertSame([
            'played' => 0, 'won' => 0, 'drawn' => 0, 'lost' => 0,
            'goals_for' => 0, 'goals_against' => 0, 'goal_difference' => 0, 'points' => 0,
        ], $this->stats($rows, $c));
        $this->assertSame(1, $snapshot['games_completed']);
        $this->assertSame(3, $snapshot['games_pending']);
        $this->assertSame(1, $snapshot['total_goals']);
        $this->assertSame(2, $snapshot['matchday_count']);

        $this->get(route('home.index'))->assertOk()->assertViewHas('data', function (array $data) {
            return $data['games_completed'] === 1 && $data['games_pending'] === 3 && $data['total_goals'] === 1;
        });
    }

    public function test_points_take_priority_over_goal_difference_and_goals_for(): void
    {
        $a = $this->team('aaa');
        $b = $this->team('bbb');
        $c = $this->team('ccc');
        $d = $this->team('ddd');
        $this->game($this->matchday(1), $a, $c, 10, 0);
        $this->game($this->matchday(2), $b, $d, 1, 0);
        $this->game($this->matchday(3), $d, $b, 0, 1);

        $this->assertSame(['bbb', 'aaa', 'ddd', 'ccc'], $this->codes($this->service()->calculate()));
    }

    public function test_goal_difference_takes_priority_over_goals_for_when_points_are_equal(): void
    {
        $a = $this->team('aaa');
        $b = $this->team('bbb');
        $c = $this->team('ccc');
        $d = $this->team('ddd');
        $day = $this->matchday(1);
        $this->game($day, $a, $d, 3, 0);
        $this->game($day, $b, $c, 5, 4);

        $this->assertSame(['aaa', 'bbb', 'ccc', 'ddd'], $this->codes($this->service()->calculate()));
    }

    public function test_goals_for_break_a_tie_in_points_and_goal_difference(): void
    {
        $high = $this->team('zzz');
        $low = $this->team('aaa');
        $c = $this->team('ccc');
        $d = $this->team('ddd');
        $day = $this->matchday(1);
        $this->game($day, $high, $c, 3, 2);
        $this->game($day, $low, $d, 1, 0);

        $this->assertSame(['zzz', 'aaa', 'ccc', 'ddd'], $this->codes($this->service()->calculate()));
    }

    public function test_teams_without_games_have_zero_totals_and_exact_ties_have_a_stable_order(): void
    {
        $this->team('zzz');
        $a = $this->team('aaa');
        $rows = $this->service()->calculate();

        $this->assertSame(['aaa', 'zzz'], $this->codes($rows));
        $this->assertSame([
            'played' => 0, 'won' => 0, 'drawn' => 0, 'lost' => 0,
            'goals_for' => 0, 'goals_against' => 0, 'goal_difference' => 0, 'points' => 0,
        ], $this->stats($rows, $a));
    }

    public function test_filter_is_cumulative_and_uses_the_matchday_number_instead_of_its_id(): void
    {
        $a = $this->team('aaa');
        $b = $this->team('bbb');
        $c = $this->team('ccc');
        $this->matchday(99);
        $third = $this->matchday(3);
        $first = $this->matchday(1);
        $second = $this->matchday(2);
        $this->game($first, $a, $b, 2, 0);
        $this->game($second, $b, $c, 1, 1);
        $this->game($third, $c, $a, 3, 0);

        $this->assertNotSame(1, $first->id);
        $throughFirst = $this->service()->calculate(1);
        $throughSecond = $this->service()->calculate(2);
        $this->assertSame(0, $this->stats($throughFirst, $c)['played']);
        $this->assertSame(3, $this->stats($throughSecond, $a)['points']);
        $this->assertSame(1, $this->stats($throughSecond, $b)['points']);
        $this->assertSame(1, $this->stats($throughSecond, $c)['points']);
        $this->assertSame(4, $throughSecond->sum('played'));
        $this->assertSame(['aaa', 'ccc', 'bbb'], $this->codes($throughSecond));
        $this->assertSame(['ccc', 'aaa', 'bbb'], $this->codes($this->service()->calculate()));

        $this->get(route('standings.index', ['matchday' => 2]))
            ->assertOk()->assertViewIs('standings.index')
            ->assertViewHas('selectedMatchday', 2)
            ->assertViewHas('standings', fn (Collection $rows) => $this->codes($rows) === ['aaa', 'ccc', 'bbb']);
    }

    public function test_changing_a_result_recalculates_points_and_the_dashboard_podium(): void
    {
        $a = $this->team('aaa', 'Equipo A');
        $b = $this->team('bbb', 'Equipo B');
        $game = $this->game($this->matchday(1), $a, $b, 0, 0);
        $service = $this->service();
        $this->assertSame(1, $this->stats($service->calculate(), $a)['points']);

        $game->update(['home_goals' => 0, 'away_goals' => 2]);
        $updated = $service->calculate();
        $this->assertSame(0, $this->stats($updated, $a)['points']);
        $this->assertSame(3, $this->stats($updated, $b)['points']);
        $this->assertSame(['bbb', 'aaa'], $this->codes($updated));

        $this->get(route('home.index'))->assertOk()->assertViewHas('teams_data', function (array $data) {
            return $data['top_teams'] === [
                ['value' => 3, 'label' => 'Equipo B'],
                ['value' => 0, 'label' => 'Equipo A'],
            ] && $data['url'] === route('standings.index');
        });
    }

    public function test_invalid_matchday_filters_are_rejected_and_browser_errors_use_a_clean_url(): void
    {
        $this->matchday(1);
        foreach (['abc', 0, -1, '1.5', 999, '1 OR 1=1', [1]] as $value) {
            $this->getJson(route('standings.index', ['matchday' => $value]))
                ->assertUnprocessable()->assertJsonValidationErrors('matchday');
        }

        $this->from(route('standings.index', ['matchday' => 1]))
            ->get(route('standings.index', ['matchday' => 'invalid']))
            ->assertRedirect(route('standings.index'))
            ->assertSessionHasErrors('matchday');

        $this->followingRedirects()
            ->from(route('standings.index', ['matchday' => 1]))
            ->get(route('standings.index', ['matchday' => 'invalid']))
            ->assertOk()
            ->assertSee('alert-danger', false);
    }

    public function test_the_table_renders_all_teams_zones_and_matchday_options_with_working_team_links(): void
    {
        $teams = [];
        for ($number = 1; $number <= 20; $number++) {
            $teams[] = $this->team(sprintf('t%02d', $number), sprintf('Equipo %02d', $number));
        }
        for ($number = 1; $number <= 38; $number++) {
            $this->matchday($number);
        }

        $response = $this->get(route('standings.index'));
        $response->assertOk()->assertViewHas('standings', fn (Collection $rows) => $rows->count() === 20)
            ->assertViewHas('selectedMatchday', null)
            ->assertSee('value="38"', false);
        $this->assertNull($response['selectedMatchday']);
        $html = $response->getContent();
        $this->assertSame(4, substr_count($html, 'title="Champions League"'));
        $this->assertSame(2, substr_count($html, 'title="Europa League"'));
        $this->assertSame(3, substr_count($html, 'title="Descenso"'));
        foreach ($teams as $team) {
            $response->assertSee('href="' . route('teams.show', $team) . '"', false);
        }

        $city = City::create(['code' => '9001', 'name' => 'Ciudad de prueba']);
        $team = $teams[0];
        $team->update(['city_id' => $city->id]);
        Player::create(['team_id' => $team->id, 'team' => $team->code, 'number' => 1, 'name' => 'Jugador prueba']);

        $this->get(route('teams.show', $team))->assertOk()->assertViewIs('teams.show')
            ->assertSee($team->short_name)->assertSee('Ciudad de prueba')
            ->assertSee('href="' . route('players.index', ['team' => $team]) . '"', false);
        $this->get(route('teams.show', ['team' => 999999]))->assertNotFound();
    }

    public function test_empty_and_unfiltered_requests_render_safely_and_team_names_are_escaped(): void
    {
        $this->get(route('standings.index'))->assertOk()->assertSee('Todavía no hay equipos cargados.');
        $this->assertTrue($this->service()->calculate()->isEmpty());

        $unsafe = '<script>x</script>';
        $team = $this->team('aaa', $unsafe);
        $this->get(route('standings.index', ['matchday' => '']))->assertOk()
            ->assertSee('&lt;script&gt;x&lt;/script&gt;', false)->assertDontSee($unsafe, false);
        $this->get(route('teams.show', $team))->assertOk()
            ->assertSee('&lt;script&gt;x&lt;/script&gt;', false)->assertDontSee($unsafe, false);
    }

    public function test_standings_use_two_queries_even_with_twenty_teams_and_a_filter(): void
    {
        for ($number = 1; $number <= 20; $number++) {
            $this->team(sprintf('t%02d', $number));
        }
        $this->matchday(1);
        DB::flushQueryLog();
        DB::enableQueryLog();
        try {
            $rows = $this->service()->calculate(1);
            $queries = DB::getQueryLog();
        } finally {
            DB::disableQueryLog();
        }

        $this->assertCount(20, $rows);
        $this->assertCount(2, $queries);
    }

    public function test_service_rejects_non_positive_matchday_numbers(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->service()->calculate(0);
    }

    private function service(): StandingsService
    {
        return app(StandingsService::class);
    }

    private function team(string $code, ?string $name = null): Team
    {
        return Team::create([
            'code' => $code,
            'short_name' => $name ?? strtoupper($code),
            'full_name' => $name ?? 'Club ' . strtoupper($code),
        ]);
    }

    private function matchday(int $number): Matchday
    {
        return Matchday::create(['number' => $number, 'date' => '2012-08-19']);
    }

    private function game(Matchday $day, Team $home, Team $away, ?int $homeGoals, ?int $awayGoals): Game
    {
        return Game::create([
            'matchday_id' => $day->id, 'home_team_id' => $home->id, 'away_team_id' => $away->id,
            'home_goals' => $homeGoals, 'away_goals' => $awayGoals,
            'home_possession' => $homeGoals === null || $awayGoals === null ? null : 50,
        ]);
    }

    private function codes(Collection $rows): array
    {
        return $rows->map(fn (array $row) => $row['team']->code)->all();
    }

    private function stats(Collection $rows, Team $team): array
    {
        $row = $rows->first(fn (array $row) => $row['team']->id === $team->id);

        return Arr::except($row, ['team', 'position']);
    }
}
