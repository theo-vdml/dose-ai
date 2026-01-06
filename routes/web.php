<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;


Route::get('/', function () {
    return Inertia::render('Landing', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('/mentions-legales', function () {
    return Inertia::render('LegalNotice');
})->name('legal.notice');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('conversations', [\App\Http\Controllers\ConversationController::class, 'index'])->name('conversations.index');
    Route::get('conversations/new', [\App\Http\Controllers\ConversationController::class, 'create'])->name('conversations.new');
    Route::post('conversations/new', [\App\Http\Controllers\ConversationController::class, 'store'])->name('conversations.store');
    Route::get('conversations/{conversation}', [\App\Http\Controllers\ConversationController::class, 'show'])->name('conversations.show');
    Route::post('conversations/{conversation}/messages', [\App\Http\Controllers\ConversationController::class, 'storeMessage'])->name('conversations.messages.store');
    Route::post('conversations/{conversation}/stream', [\App\Http\Controllers\ConversationController::class, 'stream'])->name('conversations.stream');
});

require __DIR__ . '/settings.php';
