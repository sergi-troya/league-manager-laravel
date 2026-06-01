<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Matchday;
use App\Models\Game;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class MatchdayController extends Controller
{
    public function index( Request $request) : View {
        $matchdayData = Matchday::with('games')->get();
        $matchday_number = $request->input('matchday') ?? 1;
        return view('matchday.index', compact('matchdayData', 'matchday_number'));
    }

    public function editGame(Request $request, Matchday $matchday, Game $game) : View {
        return view('game.edit', compact('matchday', 'game'));
    }

    public function updateGame(Request $request, Matchday $matchday, Game $game): RedirectResponse {
        $validatedData = $request->validate([
            'home_goals' => 'required|string|min:0',
            'away_goals' => 'required|string|max:255',
            'home_team_id' => 'required|exists:teams,id',
            'away_team_id' => 'required|exists:teams,id|different:home_team_id',
        ]);

        $game->update($validatedData);

        return redirect()->route('matchday.index', ['matchday' => $matchday->id])->with('success', 'Game updated successfully.');
    }
}
