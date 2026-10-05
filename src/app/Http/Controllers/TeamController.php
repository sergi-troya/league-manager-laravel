<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;
use Illuminate\View\View;
use App\Models\City;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() : View
    {
        $teams = Team::all();
        return view('teams.index', compact('teams'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() : View
    {
        $cities = City::orderBy('name','asc')->get();
        return view('teams.create', compact('cities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) : RedirectResponse
    {
        $validatedData = $request->validate([
            'code' => 'required|string|max:3|unique:teams',
            'short_name' => 'required|string|max:20',
            'full_name' => 'required|string|max:40',
            'city_id' => 'required|integer|exists:cities,id',
            'coach' => 'nullable|string|max:255',
            'stadium' => 'nullable|string|max:30',
            'brand' => 'nullable|string|max:30',
            'sponsor' => 'nullable|string|max:30',
            'budget' => 'nullable|integer|min:0|max:2147483647',
        ]);

        Team::create($validatedData);

        return redirect()->route('teams.index')->with('success', 'Team created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) : View
    {
        $team = Team::with('city')->withCount('players')->findOrFail($id);
        return view('teams.show', compact('team'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id) : View
    {
        $team = Team::findOrFail($id);
        $cities = City::orderBy('name','asc')->get();
        return view('teams.edit', compact('team', 'cities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id) : RedirectResponse
    {
        $team = Team::findOrFail($id);

        $validatedData = $request->validate([
            'code' => [
                'required',
                'string',
                'max:3',
                Rule::unique('teams', 'code')->ignore($team),
            ],
            'short_name' => 'required|string|max:20',
            'full_name' => 'required|string|max:40',
            'city_id' => 'required|integer|exists:cities,id',
            'coach' => 'nullable|string|max:255',
            'stadium' => 'nullable|string|max:30',
            'brand' => 'nullable|string|max:30',
            'sponsor' => 'nullable|string|max:30',
            'budget' => 'nullable|integer|min:0|max:2147483647',
        ]);

        $team->update($validatedData);

        return redirect()
            ->route('teams.index')
            ->with('success', 'Team updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) : RedirectResponse
    {
        $team = Team::findOrFail($id);
        $team->delete();

        return redirect()->route('teams.index')->with('success', 'Team deleted successfully.');
    }
}
