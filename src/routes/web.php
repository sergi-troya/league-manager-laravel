<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\MatchdayController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home.index');
});


Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('/matchday', [MatchdayController::class, 'index'])->name('matchday.index');
Route::get('/matchday/{matchday}/game/{game}/edit', [MatchdayController::class, 'editGame'])->name('matchday.game.edit');
Route::post('/matchday/{matchday }/game/{game}/update', [MatchdayController::class, 'updateGame'])->name('matchday.game.update');