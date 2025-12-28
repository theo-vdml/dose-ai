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

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('quick-prompt', [QuickPromptController::class, 'index'])->name('quick-prompt.index');
    Route::post('quick-prompt', [QuickPromptController::class, 'completion'])->name('quick-prompt.completion');

    Route::get('conversations', [\App\Http\Controllers\ConversationController::class, 'index'])->name('conversations.index');
    Route::get('conversations/new', [\App\Http\Controllers\ConversationController::class, 'create'])->name('conversations.new');
    Route::post('conversations/new', [\App\Http\Controllers\ConversationController::class, 'store'])->name('conversations.store');
    Route::get('conversations/{conversation}', [\App\Http\Controllers\ConversationController::class, 'show'])->name('conversations.show');
    Route::post('conversations/{conversation}/stream', [\App\Http\Controllers\ConversationController::class, 'stream'])->name('conversations.stream');
});

require __DIR__ . '/settings.php';
