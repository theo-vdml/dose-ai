<?php

use App\Http\Controllers\ConversationController;
use App\Http\Controllers\LegalController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Landing');
})->name('home');

Route::get('/legal', [LegalController::class, 'index'])->name('legal');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('conversations', [ConversationController::class, 'index'])
        ->name('conversations.index');
    Route::get('conversations/new', [ConversationController::class, 'create'])
        ->name('conversations.new');
    Route::post('conversations/new', [ConversationController::class, 'store'])
        ->name('conversations.store');
    Route::get('conversations/{conversation}', [ConversationController::class, 'show'])
        ->name('conversations.show');
    Route::put('conversations/{conversation}', [ConversationController::class, 'update'])
        ->name('conversations.update');
    Route::delete('conversations/{conversation}', [ConversationController::class, 'destroy'])
        ->name('conversations.destroy');
    Route::post('conversations/{conversation}/messages', [ConversationController::class, 'storeMessage'])
        ->name('conversations.messages.store');
    Route::post('conversations/{conversation}/stream', [ConversationController::class, 'stream'])
        ->name('conversations.stream');
});

require __DIR__.'/settings.php';
