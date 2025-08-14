<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Fuma\DashboardController;
use App\Http\Controllers\Fuma\TournamentController;
use App\Http\Controllers\Fuma\TeamController;
use App\Http\Controllers\Fuma\PlayerController;
use App\Http\Controllers\Fuma\MatchController;
use App\Http\Controllers\Fuma\CommitteeController;
use App\Http\Controllers\Fuma\UserController;
use App\Http\Controllers\Fuma\RoleController;
use App\Http\Controllers\Fuma\StatisticsController;
use App\Http\Controllers\Fuma\StandingsController;
use App\Http\Controllers\Fuma\ProfileController;
use App\Http\Controllers\Fuma\AuthController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [DashboardController::class, 'index']);
// FUMA Backoffice Routes
Route::prefix('fuma')->name('fuma.')->group(function () {

    // Public routes
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.post');

    // Protected routes
    Route::middleware(['auth', 'fuma.auth'])->group(function () {

        // Dashboard
        Route::get('/', [DashboardController::class, 'index']);
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Tournaments
        Route::resource('tournaments', TournamentController::class);
        Route::get('tournaments/{id}/standings', [TournamentController::class, 'standings'])->name('tournaments.standings');

        // Teams
        Route::resource('teams', TeamController::class);
        Route::post('teams/{id}/players', [TeamController::class, 'addPlayer'])->name('teams.add-player');

        // Players
        Route::resource('players', PlayerController::class);
        Route::put('players/{id}/stats', [PlayerController::class, 'updateStats'])->name('players.update-stats');

        // Matches
        Route::resource('matches', MatchController::class);
        Route::post('matches/{id}/events', [MatchController::class, 'addEvent'])->name('matches.add-event');
        Route::put('matches/{id}/score', [MatchController::class, 'updateScore'])->name('matches.update-score');

        // Committees
        Route::resource('committees', CommitteeController::class);

        // Statistics
        Route::get('statistics', [StatisticsController::class, 'index'])->name('statistics.index');

        // Standings
        Route::get('standings', [StandingsController::class, 'index'])->name('standings.index');

        // Profile
        Route::get('profile', [ProfileController::class, 'edit'])->name('profile');
        Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');

        // Logout
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');

        // Admin only routes
        Route::middleware(['fuma.role:admin'])->group(function () {
            Route::resource('users', UserController::class);
            Route::resource('roles', RoleController::class);
        });
    });
});

require __DIR__ . '/auth.php';
