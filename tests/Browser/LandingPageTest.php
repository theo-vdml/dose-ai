<?php

use App\Models\User;

use function Pest\Laravel\actingAs;

it('render the landing page', function () {
    visit('/')
        ->assertTitle('PersonAI - Conversations IA avec des personas uniques')
        ->assertSeeAnythingIn('#app');
});

it('contains the correct heading', function () {
    visit('/')
        ->assertSeeIn('#app h1', "L'IA conversationnelle réinventée");
});

it('has no accessibility issues', function () {
    visit('/')
        // Check for all accessibility issue levels in the following list:
        // https://github.com/dequelabs/axe-core/blob/develop/doc/rule-descriptions.md
        ->assertNoAccessibilityIssues(level: 3);
});

it('has no missing/broken images', function () {
    visit('/')
        ->assertNoMissingImages()
        ->assertNoBrokenImages();
});

it('has no console errors', function () {
    visit('/')
        ->assertNoJavaScriptErrors();
});

it('can toggle the faq answers', function () {
    $buttonText = 'Quels modèles IA sont disponibles ?';
    $answerExcerpt = 'Via OpenRouter, nous offrons un accès à plus de 20 modèles IA incluant GPT-4';

    visit('/')
        ->assertDontSee($answerExcerpt)
        ->click($buttonText)
        ->assertSee($answerExcerpt)
        ->click($buttonText)
        ->assertDontSee($answerExcerpt);
});

it('show login buttons when guest', function () {
    visit('/')
        ->assertSee('Se connecter')
        ->assertSee('Commencer')
        ->assertDontSee("Ouvrir l'App");
});

it('shows dashboard button when authenticated', function () {
    /** @var \App\Models\User $user */
    $user = User::factory()->create();

    actingAs($user);
    visit('/')
        ->assertDontSee('Se connecter')
        ->assertSee("Ouvrir l'App");
});
