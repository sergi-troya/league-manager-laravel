<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\UniqueConstraintViolationException;

class PlayerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Team $team) : View 
    {
        $players = $team->players()->paginate(10);
        return view('players.index', compact('players', 'team'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Team $team) : View
     {
        return view('players.create', compact('team'));
     }
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Team $team): RedirectResponse
    {
        $validatedData = $request->validate([
            'number' => [
                'bail',
                'required',
                'integer',
                'min:0',
                'max:2147483647',
                Rule::unique('players', 'number')
                    ->where('team_id', $team->id),
            ],
            'name' => 'required|string|max:30',
            'position' => 'required|string|max:10',
        ]);

        try {
            $team->players()->create($validatedData);
        } catch (UniqueConstraintViolationException $exception) {
            throw ValidationException::withMessages([
                'number' => 'Este dorsal ya está asignado a otro jugador del equipo.',
            ]);
        }

        return redirect()
            ->route('players.index', $team)
            ->with('success', 'Player created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Team $team, string $id) : View
    {
        $player = $team->players()->findOrFail($id);
        return view('players.show', compact('team', 'player'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Team $team, string $id) : View
     {
        $player = $team->players()->findOrFail($id);
        return view('players.edit', compact('team', 'player'));
     }
    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Team $team, string $id)
    {
        $player = $team->players()->findOrFail($id);

        $validatedData = $request->validate([
            'number' => [
            'bail',
            'required',
            'integer',
            'min:0',
            'max:2147483647',
            Rule::unique('players', 'number')
                ->where('team_id', $team->id)
                ->ignore($player),
            ],
            'name' => 'required|string|max:30',
            'position' => 'required|string|max:10',
        ]);

       try {
            $player->update($validatedData);
        } catch (UniqueConstraintViolationException $exception) {
            throw ValidationException::withMessages([
                'number' => 'Este dorsal ya está asignado a otro jugador del equipo.',
            ]);
        }

        return redirect()
            ->route('players.index', $team)
            ->with('success', 'Player updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Team $team, string $id) 
    {
        $player = $team->players()->findOrFail($id);
        $player->delete();

        return redirect()->route('players.index', $team)->with('success', 'Player deleted successfully.');
    }
}
