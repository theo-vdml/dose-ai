<?php

use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertAuthenticated;

it('redirects to login when accessing dashboard as guest', function () {
    visit('/dashboard')
        ->assertPathIs('/login');
});

it('can register from the landing page', function () {

    $user = User::where('email', 'test@user.com')->first();
    expect($user)->toBeNull();

    visit('/')
        ->assertSee('Commencer')
        ->click('Commencer')
        ->assertPathIs('/register')
        ->assertSee('Create an account')
        ->fill('name', 'Test User')
        ->fill('email', 'test@user.com')
        ->fill('password', 'password')
        ->fill('password_confirmation', 'password')
        ->click('Create account')
        ->assertPathIs('/dashboard');

    assertAuthenticated();

    $user = User::where('email', 'test@user.com')->first();
    expect($user)->not->toBeNull();
});

it('can login from the landing page', function () {
    /** @var \App\Models\User $user */
    $user = User::factory()->withoutTwoFactor()->create();

    visit('/')
        ->assertSee('Se connecter')
        ->click('Se connecter')
        ->assertPathIs('/login')
        ->assertSee('Log in to your account')
        ->fill('email', $user->email)
        ->fill('password', 'password')
        ->click('Log in')
        ->assertPathIs('/dashboard');

    assertAuthenticated();
});

it('allow authenticated users to access the dashboard', function () {
    /** @var \App\Models\User $user */
    $user = User::factory()->create();

    actingAs($user);

    visit('/')
        ->assertSee("Ouvrir l'App")
        ->click("Ouvrir l'App")
        ->assertPathIs('/dashboard');
});
