<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TrackController;
use Illuminate\Support\Facades\Route;

Route::match(['get', 'post'], '/',
    [TrackController::class, 'index'])
->name('track.index');

Route::get('about', fn() => view('about') )->name('about');

Route::resource('track', TrackController::class)->middleware(['auth', 'verified'])->except(['index', 'edit', 'update']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
