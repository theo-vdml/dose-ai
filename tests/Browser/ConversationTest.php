<?php

use App\Models\User;
use App\OpenRouter\Facades\OpenRouter;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    /** @var \App\Models\User $user */
    $user = User::factory()->create();
    actingAs($user);
});

it('can access the dashboard', function () {
    visit('/dashboard')
        ->assertTitle('Dashboard')
        ->assertSeeAnythingIn('#app');
});


it('can create a new conversation', function () {

    OpenRouter::fake()
        ->shouldReturnChatContent('I am fine, thank you!');

    visit('/conversations/new')
        ->fill('message', 'Hello, how are you?')
        ->click('[aria-label="Send message"]')
        ->debug();
});
