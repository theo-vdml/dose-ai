<?php

use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

test('guests are redirected to the login page', function () {
    $response = get(route('conversations.new'));
    $response->assertRedirect(route('login'));
});

test('authenticated users can visit the chat page', function () {
    /** @var \App\Models\User $user */
    $user = User::factory()->create();
    actingAs($user);

    $response = get(route('conversations.new'));
    $response->assertStatus(200);
});
