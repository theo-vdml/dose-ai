<?php

use App\Http\Controllers\QuickPromptController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('quick-prompt', [QuickPromptController::class, 'index'])->name('quick-prompt.index');
Route::post('quick-prompt', [QuickPromptController::class, 'completion'])->name('quick-prompt.completion');

require __DIR__ . '/settings.php';
