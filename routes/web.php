<?php

use App\Http\Controllers\ConversationController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Landing');
})->name('home');

Route::get('/mentions-legales', function () {
    return Inertia::render('LegalNotice');
})->name('legal.notice');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('conversations', [ConversationController::class, 'index'])
        ->name('conversations.index');
    Route::get('conversations/new', [ConversationController::class, 'create'])
        ->name('conversations.new');
    Route::post('conversations/new', [ConversationController::class, 'store'])
        ->name('conversations.store');
    Route::get('conversations/{conversation}', [ConversationController::class, 'show'])
        ->name('conversations.show');
    Route::post('conversations/{conversation}/messages', [ConversationController::class, 'storeMessage'])
        ->name('conversations.messages.store');
    Route::post('conversations/{conversation}/stream', [ConversationController::class, 'stream'])
        ->name('conversations.stream');
});

require __DIR__ . '/settings.php';
