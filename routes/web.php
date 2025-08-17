<?php

use App\Enums\UserRole;
use App\Http\Controllers\Console\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Fuma\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TournamentController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\MatchController;
use App\Http\Controllers\MatchEventController;
use App\Http\Controllers\MatchCommentaryController;
use App\Http\Controllers\MatchLineupController;
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

Route::get('/', function () {
    return view('welcome');
});

require __DIR__ . '/auth.php';

Route::prefix('console')->middleware(['auth', 'verified'])->group(function () {
    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
    Route::middleware('role:' . UserRole::ADMIN)->group(function () {
        Route::prefix('master-data')->group(function () {
            Route::resource('users', UserController::class);
        });
    });
});

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/homepage-data', [HomeController::class, 'homePageData'])->name('homepage-data');
Route::get('/tournaments', [HomeController::class, 'tournaments'])->name('tournaments.index');
Route::get('/tournaments-data', [TournamentController::class, 'tournamentsData'])->name('tournaments.data');

Route::prefix('tournaments')->name('tournaments.')->group(function () {
    Route::post('/', [TournamentController::class, 'storeTournament'])->name('store');
    Route::get('/{tournament}', [TournamentController::class, 'showTournament'])->name('show');
    Route::put('/{tournament}', [TournamentController::class, 'updateTournament'])->name('update');
    Route::delete('/{tournament}', [TournamentController::class, 'deleteTournament'])->name('delete');

    Route::get('/{tournament}/available-teams', [TournamentController::class, 'availableTeams'])
        ->name('availableTeams');
    Route::post('/{tournament}/team', [TournamentController::class, 'addTeamToTournament'])->name('addTeam');

    Route::post('/{tournament}/schedule', [TournamentController::class, 'addScheduleMatch'])->name('addScheduleMatch');
    Route::put('/{tournament}/updateMatch/{match}', [TournamentController::class, 'updateScheduleMatch'])->name('updateScheduleMatch');
    Route::delete('/{tournament}/matches/{match}', [TournamentController::class, 'deleteScheduleMatch'])->name('deleteScheduleMatch');
});

// Teams Routes
Route::get('/teams', [TeamController::class, 'index'])->name('teams.index');
Route::get('/teams-data', [TeamController::class, 'teamsData'])->name('teams.data');

Route::prefix('teams')->name('teams.')->group(function () {
    Route::post('/', [TeamController::class, 'store'])->name('store');
    Route::get('/{team}', [TeamController::class, 'show'])->name('show');
    Route::put('/{team}', [TeamController::class, 'update'])->name('update');
    Route::delete('/{team}', [TeamController::class, 'destroy'])->name('delete');
});

// Players Routes (for Add Player functionality)
// Players routes
Route::get('/players', [PlayerController::class, 'index'])->name('players.index');
Route::get('/players-data', [PlayerController::class, 'playersData'])->name('players.data');
Route::prefix('players')->name('players.')->group(function () {
    Route::post('/', [PlayerController::class, 'store'])->name('store');
    Route::get('/{player}', [PlayerController::class, 'show'])->name('show');
    Route::put('/{player}', [PlayerController::class, 'update'])->name('update');
    Route::delete('/{player}', [PlayerController::class, 'destroy'])->name('delete');
});

// Matches routes
Route::get('/matches', [MatchController::class, 'index'])->name('matches.index');
Route::get('/matches-data', [MatchController::class, 'data'])->name('matches.data');
Route::prefix('matches')->name('matches.')->group(function () {
    Route::post('/', [MatchController::class, 'store'])->name('store');
    Route::get('/{match}', [MatchController::class, 'show'])->name('show');
    Route::put('/{match}', [MatchController::class, 'update'])->name('update');
    Route::delete('/{match}', [MatchController::class, 'destroy'])->name('delete');

    // Match Management Routes
    Route::post('/{match}/start', [MatchController::class, 'startMatch'])->name('start');
    Route::post('/{match}/pause', [MatchController::class, 'pauseMatch'])->name('pause');
    Route::post('/{match}/resume', [MatchController::class, 'resumeMatch'])->name('resume');
    Route::post('/{match}/complete', [MatchController::class, 'completeMatch'])->name('complete');
    Route::put('/{match}/score', [MatchController::class, 'updateScore'])->name('update-score');
    Route::put('/{match}/minute', [MatchController::class, 'updateMinute'])->name('update-minute');
    Route::get('/{match}/management-data', [MatchController::class, 'getMatchManagementData'])->name('management-data');
});

// Match Lineup Management Routes
Route::prefix('match-lineups')->name('match-lineups.')->group(function () {
    Route::get('/{match}', [MatchLineupController::class, 'getLineup'])->name('get');
    Route::post('/{match}', [MatchLineupController::class, 'setLineup'])->name('set');
    Route::post('/{match}/update-line-up', [MatchLineupController::class, 'updateLineup'])->name('update');
    Route::get('/{match}/available-players/{team}', [MatchLineupController::class, 'getAvailablePlayers'])->name('available-players');
});

// Match Events routes
Route::prefix('match-events')->name('match-events.')->group(function () {
    Route::post('/', [MatchEventController::class, 'store'])->name('store');
    Route::put('/{event}', [MatchEventController::class, 'update'])->name('update');
    Route::delete('/{event}', [MatchEventController::class, 'destroy'])->name('delete');
    Route::get('/match/{match}', [MatchEventController::class, 'getMatchEvents'])->name('match-events');
});

// Match Commentary Routes
Route::prefix('matches/{match}/commentary')->group(function () {
    Route::get('/', [MatchCommentaryController::class, 'getMatchCommentary'])->name('match.commentary.index');
    Route::post('/', [MatchCommentaryController::class, 'store'])->name('match.commentary.store');
    Route::put('/{commentary}', [MatchCommentaryController::class, 'update'])->name('match.commentary.update');
    Route::delete('/{commentary}', [MatchCommentaryController::class, 'destroy'])->name('match.commentary.destroy');
    Route::get('/type/{type}', [MatchCommentaryController::class, 'getByType'])->name('match.commentary.by-type');
    Route::get('/important', [MatchCommentaryController::class, 'getImportant'])->name('match.commentary.important');
    Route::get('/range', [MatchCommentaryController::class, 'getByMinuteRange'])->name('match.commentary.by-range');
});
