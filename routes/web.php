<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TrackController;
use Illuminate\Support\Facades\Route;

Route::match(['get', 'post'], '/',
    [TrackController::class, 'index'])
->name('track.index');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('track', TrackController::class)->except(['index', 'edit', 'update']);
    Route::match(['get', 'post'], 'map', [TrackController::class, 'map'])->name('track.map');
    Route::get('chart', [TrackController::class, 'chart'])->name('track.chart');
});

Route::middleware('auth')->group(function () {
    Route::get('about', fn() => inertia('About/Index') )->name('about');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
