<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TournamentController;
use App\Http\Controllers\Api\TeamController;
use App\Http\Controllers\Api\PlayerController;
use App\Http\Controllers\Api\MatchController;
use App\Http\Controllers\Api\CommitteeController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Tournaments
    Route::get('/tournaments', [TournamentController::class, 'index']);
    Route::get('/tournaments/{id}', [TournamentController::class, 'show']);
    Route::get('/tournaments/{id}/standings', [TournamentController::class, 'standings']);

    Route::middleware('role:admin,organizer')->group(function () {
        Route::post('/tournaments', [TournamentController::class, 'store'])->name('api.tournaments.store');
        Route::put('/tournaments/{id}', [TournamentController::class, 'update']);
        Route::delete('/tournaments/{id}', [TournamentController::class, 'destroy']);
        Route::post('/tournaments/{id}/teams', [TournamentController::class, 'addTeam']);
    });

    // Teams
    Route::get('/teams', [TeamController::class, 'index']);
    Route::get('/teams/{id}', [TeamController::class, 'show']);

    Route::middleware('role:admin,manager')->group(function () {
        Route::post('/teams', [TeamController::class, 'store'])->name('api.teams.store');
        Route::put('/teams/{id}', [TeamController::class, 'update']);
        Route::delete('/teams/{id}', [TeamController::class, 'destroy']);
        Route::post('/teams/{id}/players', [TeamController::class, 'addPlayer']);
    });

    // Players
    Route::get('/players', [PlayerController::class, 'index']);
    Route::get('/players/{id}', [PlayerController::class, 'show']);

    Route::middleware('role:admin,manager,organizer')->group(function () {
        Route::post('/players', [PlayerController::class, 'store'])->name('api.players.store');
        Route::put('/players/{id}', [PlayerController::class, 'update']);
        Route::delete('/players/{id}', [PlayerController::class, 'destroy']);
        Route::put('/players/{id}/stats', [PlayerController::class, 'updateStats']);
    });

    // Matches
    Route::get('/matches', [MatchController::class, 'index']);
    Route::get('/matches/{id}', [MatchController::class, 'show']);

    Route::middleware('role:admin,organizer,committee')->group(function () {
        Route::post('/matches', [MatchController::class, 'store'])->name('api.matches.store');
        Route::put('/matches/{id}', [MatchController::class, 'update']);
        Route::delete('/matches/{id}', [MatchController::class, 'destroy']);
        Route::post('/matches/{id}/events', [MatchController::class, 'addEvent']);
        Route::put('/matches/{id}/score', [MatchController::class, 'updateScore']);
    });

    // Committees
    Route::get('/committees', [CommitteeController::class, 'index']);
    Route::get('/committees/{id}', [CommitteeController::class, 'show']);

    Route::middleware('role:admin,organizer')->group(function () {
        Route::post('/committees', [CommitteeController::class, 'store']);
        Route::put('/committees/{id}', [CommitteeController::class, 'update']);
        Route::delete('/committees/{id}', [CommitteeController::class, 'destroy']);
    });
});

