<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\City;
use Illuminate\Http\RedirectResponse;
use Illuminate\Database\QueryException;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() : View
    {
        $cities = City::orderBy('name', 'asc')->paginate(10);
        return view('cities.index', compact('cities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() : View
    {
        return view('cities.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) : RedirectResponse
    {
        $validatedData = $request->validate([
            'code' => 'required|integer|unique:cities,code',
            'name' => 'required|string|max:30',
            'population' => 'nullable|integer|min:0|max:2147483647'
        ]);

        City::create($validatedData);

        return redirect()->route('cities.index')->with('success', 'City created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id) : View
    {
        $city = City::findOrFail($id);
        return view('cities.edit', compact('city'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id) : RedirectResponse
    {
        $city = City::findOrFail($id);

        $validatedData = $request->validate([
            'code' => 'required|integer|unique:cities,code,' . $city->id,
            'name' => 'required|string|max:30',
            'population' => 'nullable|integer|min:0|max:2147483647'
        ]);

        $city->update($validatedData);

        return redirect()->route('cities.index')->with('success', 'City updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(City $city)
    {
        if ($city->teams()->exists()) {
            return redirect()->route('cities.index')
                ->with('error', 'No se puede eliminar una ciudad con equipos asociados.');
        }

        try {
            $city->delete();

            return redirect()->route('cities.index')
                ->with('success', 'Ciudad eliminada correctamente.');
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                return redirect()->route('cities.index')
                    ->with('error', 'No se puede eliminar la ciudad porque tiene registros vinculados.');
            }

            throw $e;
        }
    }
}
