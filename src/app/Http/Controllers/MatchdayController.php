<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Matchday;
use App\Models\Game;
use App\Http\Requests\MatchdayRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class MatchdayController extends Controller
{
    public function index(MatchdayRequest $request)
    {
        $matchdays = Matchday::orderBy('number')->get();

        if ($matchdays->isEmpty()) {
            return view('matchday.index', [
                'matchdays' => collect(),
                'currentMatchday' => null,
                'games' => collect(),
            ]);
        }

        $validated = $request->validated();
        $requestedNumber = $validated['matchday'] ?? null;

        if ($requestedNumber === null) {
            $currentMatchday = $matchdays->first();
        } else {
            $currentMatchday = $matchdays->firstWhere('number', (int) $requestedNumber);
        }

        $games = $currentMatchday->games()->with(['homeTeam', 'awayTeam'])->get();

        return view('matchday.index', compact('matchdays', 'currentMatchday', 'games'));
    }

    public function editGame(Request $request, Matchday $matchday, Game $game) : View {
        abort_unless((int) $game->matchday_id === (int) $matchday->id, 404);
        $game->load(['homeTeam', 'awayTeam']);
        return view('game.edit', compact('matchday', 'game'));
    }

    public function updateGame(Request $request, Matchday $matchday, Game $game): RedirectResponse {
        abort_unless((int) $game->matchday_id === (int) $matchday->id, 404);

        $validatedData = $request->validate([
            'home_goals' => 'required|integer|min:0|max:2147483647',
            'away_goals' => 'required|integer|min:0|max:2147483647',
            'home_team_id' => 'prohibited',
            'away_team_id' => 'prohibited',
            'matchday_id' => 'prohibited',
        ]);

        $game->update([
            'home_goals' => $validatedData['home_goals'],
            'away_goals' => $validatedData['away_goals'],
        ]);

        return redirect()
            ->route('matchday.index', ['matchday' => $game->matchday->number])
            ->with('success', 'Game updated successfully.');
    }
}
