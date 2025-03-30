<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdController;

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

// Redirect root to ads index
Route::get('/', function () {
    return redirect()->route('ads.index');
});

// Ad routes
Route::get('/ads', [AdController::class, 'index'])->name('ads.index');
Route::post('/ads/refresh', [AdController::class, 'refresh'])->name('ads.refresh');