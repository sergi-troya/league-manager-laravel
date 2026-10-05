<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\MatchdayController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\ScorerController;
use App\Http\Controllers\StandingsController;
use Illuminate\Support\Facades\Route;

//Ruta principal (Dashboard)
Route::get('/', [HomeController::class, 'index'])->name('home.index');

Route::get('/standings', [StandingsController::class, 'index'])->name('standings.index');

Route::get('scorers', [ScorerController::class, 'index'])->name('scorers.index');

// Jornada y gestión de partidos
Route::get('/matchday', [MatchdayController::class, 'index'])->name('matchday.index');
Route::get('/matchday/{matchday}/game/{game}/edit', [MatchdayController::class, 'editGame'])->name('matchday.game.edit');
Route::post('/matchday/{matchday}/game/{game}/update', [MatchdayController::class, 'updateGame'])->name('matchday.game.update');

//Recursos CRUD estándar
Route::resource('cities', CityController::class);
Route::resource('teams', TeamController::class);

//Recursos anidados para jugadores dependientes de un equipo     
Route::prefix('teams/{team}')->group(function () {
    Route::resource('/players', PlayerController::class);
});
