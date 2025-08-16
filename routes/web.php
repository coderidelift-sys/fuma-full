<?php

use App\Enums\UserRole;
use App\Http\Controllers\Console\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
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

use App\Http\Controllers\FumaController;

Route::get('/', [FumaController::class, 'index'])->name('fuma.index');

// FUMA Frontend Routes
Route::prefix('fuma')->name('fuma.')->group(function () {
    Route::get('/tournaments', [FumaController::class, 'tournaments'])->name('tournaments');
    Route::get('/tournaments/{id}', [FumaController::class, 'tournamentDetail'])->name('tournament-detail');
    Route::get('/teams', [FumaController::class, 'teams'])->name('teams');
    Route::get('/teams/{id}', [FumaController::class, 'teamDetail'])->name('team-detail');
    Route::get('/players', [FumaController::class, 'players'])->name('players');
    Route::get('/players/{id}', [FumaController::class, 'playerDetail'])->name('player-detail');
    Route::get('/matches', [FumaController::class, 'matches'])->name('matches');
    Route::get('/matches/{id}', [FumaController::class, 'matchDetail'])->name('match-detail');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

// FUMA Auth Routes (alternative styling)
Route::get('/fuma-login', function () {
    return view('auth.fuma-login');
})->name('fuma.login');

Route::get('/fuma-register', function () {
    return view('auth.fuma-register');
})->name('fuma.register');

Route::prefix('console')->middleware(['auth', 'verified'])->group(function () {
    Route::middleware('role:' . UserRole::ADMIN)->group(function () {
        Route::prefix('master-data')->group(function () {
            Route::resource('users', UserController::class);
        });
    });
});
