<?php

use App\Enums\UserRole;
use App\Http\Controllers\Console\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FumaController;
use App\Http\Controllers\FumaTournamentController;
use App\Http\Controllers\FumaTeamController;
use App\Http\Controllers\FumaMatchController;
use App\Http\Controllers\FumaPlayerController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// FUMA Frontend Routes (Public)
Route::get('/', [FumaController::class, 'index'])->name('fuma.index');

Route::prefix('fuma')->name('fuma.')->group(function () {
    // Tournaments
    Route::get('/tournaments', [FumaTournamentController::class, 'index'])->name('tournaments.index');
    Route::get('/tournaments/{tournament}', [FumaTournamentController::class, 'show'])->name('tournaments.show');
    Route::get('/tournaments/create', [FumaTournamentController::class, 'create'])->name('tournaments.create');
    Route::post('/tournaments', [FumaTournamentController::class, 'store'])->name('tournaments.store');
    Route::delete('/tournaments/{tournament}', [FumaTournamentController::class, 'destroy'])->name('tournaments.destroy');

    // Teams
    Route::get('/teams', [FumaTeamController::class, 'index'])->name('teams.index');
    Route::get('/teams/{team}', [FumaTeamController::class, 'show'])->name('teams.show');
    Route::get('/teams/create', [FumaTeamController::class, 'create'])->name('teams.create');
    Route::post('/teams', [FumaTeamController::class, 'store'])->name('teams.store');

    // Matches
    Route::get('/matches', [FumaMatchController::class, 'index'])->name('matches.index');
    Route::get('/matches/{match}', [FumaMatchController::class, 'show'])->name('matches.show');
    Route::get('/matches/create', [FumaMatchController::class, 'create'])->name('matches.create');
    Route::post('/matches', [FumaMatchController::class, 'store'])->name('matches.store');
    Route::patch('/matches/{match}/score', [FumaMatchController::class, 'updateScore'])->name('matches.updateScore');

    // Players
    Route::get('/players', [FumaPlayerController::class, 'index'])->name('players.index');
    Route::get('/players/{player}', [FumaPlayerController::class, 'show'])->name('players.show');
    Route::get('/players/create', [FumaPlayerController::class, 'create'])->name('players.create');
    Route::post('/players', [FumaPlayerController::class, 'store'])->name('players.store');
});

// Admin Dashboard Routes
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

Route::prefix('console')->middleware(['auth', 'verified'])->group(function () {
    Route::middleware('role:' . UserRole::ADMIN)->group(function () {
        Route::prefix('master-data')->group(function () {
            Route::resource('users', UserController::class);
        });
    });
});
