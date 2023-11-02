<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;
use App\Http\Controllers\MatchingController;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');


    // ゲーム作成画面
    Route::get('/game/matching', [MatchingController::class, "create"])->name("game.matching");

    // ゲーム参加画面
    Route::get('/game/join', [MatchingController::class, "join"])->name("game.join");

    // マッチング画面
    Route::post('/game/matching', [MatchingController::class, "matching"]);

    // ゲーム画面
    Route::post('/game/board', [MatchingController::class, "board"])->name("game.board");
});


