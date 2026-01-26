<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

test('confirm password screen can be rendered', function () {

    /** @var \App\Models\User $user */
    $user = User::factory()->create();

    $response = actingAs($user)->get(route('password.confirm'));

    $response->assertStatus(200);

    $response->assertInertia(
        fn (Assert $page) => $page
            ->component('auth/ConfirmPassword')
    );
});

test('password confirmation requires authentication', function () {
    $response = get(route('password.confirm'));

    $response->assertRedirect(route('login'));
});
