<?php

use App\Enums\UserRole;
use App\Http\Controllers\Console\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Fuma\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TournamentController;
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
