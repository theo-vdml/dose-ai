<?php

use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertAuthenticated;

it('redirects to login when accessing dashboard as guest', function () {
    visit(route('conversations.new'))
        ->assertRoute('login');
});

it('can register from the landing page', function () {

    $user = User::where('email', 'test@user.com')->first();
    expect($user)->toBeNull();

    visit(route('home'))
        ->assertSee('Commencer')
        ->click('Commencer')
        ->assertRoute('register')
        ->assertSee('Create an account')
        ->fill('name', 'Test User')
        ->fill('email', 'test@user.com')
        ->fill('password', 'password')
        ->fill('password_confirmation', 'password')
        ->click('Create account')
        ->assertRoute('conversations.new');

    assertAuthenticated();

    $user = User::where('email', 'test@user.com')->first();
    expect($user)->not->toBeNull();
});

it('can login from the landing page', function () {
    /** @var \App\Models\User $user */
    $user = User::factory()->withoutTwoFactor()->create();

    visit(route('home'))
        ->assertSee('Se connecter')
        ->click('Se connecter')
        ->assertRoute('login')
        ->assertSee('Log in to your account')
        ->fill('email', $user->email)
        ->fill('password', 'password')
        ->click('Log in')
        ->assertRoute('conversations.new');

    assertAuthenticated();
});

it('allow authenticated users to access the dashboard', function () {
    /** @var \App\Models\User $user */
    $user = User::factory()->create();

    actingAs($user);

    visit(route('home'))
        ->assertSee("Ouvrir l'App")
        ->click("Ouvrir l'App")
        ->assertRoute('conversations.new');
});
