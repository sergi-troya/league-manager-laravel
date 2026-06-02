<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\MatchdayController;
use App\Http\Controllers\CityController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home.index');
});


Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('/matchday', [MatchdayController::class, 'index'])->name('matchday.index');
Route::get('/matchday/{matchday}/game/{game}/edit', [MatchdayController::class, 'editGame'])->name('matchday.game.edit');
Route::post('/matchday/{matchday}/game/{game}/update', [MatchdayController::class, 'updateGame'])->name('matchday.game.update');

/* Rutas CRUD cities */
Route::get('/cities', [CityController::class, 'index'])->name('cities.index');
Route::get('/cities/create', [CityController::class, 'create'])->name('cities.create');
Route::post('/cities', [CityController::class, 'store'])->name('cities.store');
Route::get('/cities/{city}', [CityController::class, 'show'])->name('cities.show');
Route::get('/cities/{city}/edit', [CityController::class, 'edit'])->name('cities.edit');
Route::put('/cities/{city}', [CityController::class, 'update'])->name('cities.update');
Route::delete('/cities/{city}', [CityController::class, 'destroy'])->name('cities.destroy');