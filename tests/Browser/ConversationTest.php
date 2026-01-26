<?php

use App\Models\Conversation;
use App\Models\User;
use App\OpenRouter\Chat\ChatRequest;
use App\OpenRouter\DTO\ModelData;
use App\OpenRouter\Facades\OpenRouter;
use Illuminate\Http\Client\ConnectionException;

it('can create a new conversation', function () {
    // Disable testing to avoid pest preventing streaming to reach client due to its output buffering
    $this->disableStreaming();

    // Fake OpenRouter response
    OpenRouter::fake()
        ->respondWith('I am fine, thank you!');

    $this->signIn();

    // Create a new conversation and send a message
    visit('/conversations/new')
        ->fill('message', 'Hello, how are you?')
        ->click('[aria-label="Send message"]')
        ->assertSee('Hello, how are you?')
        ->assertSee('I am fine, thank you!');
});

it('display available models correctly', function () {
    // Fake OpenRouter models
    OpenRouter::fake()
        ->withModels([
            ModelData::fake('fake-model-1', 'Fake Model One'),
            ModelData::fake('fake-model-2', 'Fake Model Two'),
            ModelData::fake('fake-model-3', 'Fake Model Three'),
        ]);

    $this->signIn();

    // Visit new conversation page and assert models are displayed
    visit('/conversations/new')
        ->click('Fake Model One')
        ->assertSee('Fake Model One')
        ->assertSee('Fake Model Two')
        ->assertSee('Fake Model Three');
});

it('can click on a model to select it', function () {
    // Fake OpenRouter models
    OpenRouter::fake()
        ->withModels([
            ModelData::fake('fake-model-1', 'Fake Model One'),
            ModelData::fake('fake-model-2', 'Fake Model Two'),
        ]);

    $this->signIn();

    // Visit new conversation page, select a model and assert it's selected
    visit('/conversations/new')
        ->assertSee('Fake Model One') // Assert the default model is displayed
        ->assertDontSee('Fake Model Two') // Assert the second model is not displayed
        ->click('Fake Model One') // Open the model selection dropdown
        ->click('Fake Model Two') // Select the second model
        ->assertDontSee('Fake Model One') // Assert the first model is not displayed
        ->assertSee('Fake Model Two'); // Assert the selected model is displayed
})->depends('it display available models correctly');

it('can create a conversation with a specific model', function () {
    // Disable testing to avoid pest preventing streaming to reach client due to its output buffering
    $this->disableStreaming();

    // Fake OpenRouter models
    OpenRouter::fake()
        ->withModels([
            ModelData::fake('fake-model-1', 'Fake Model One'),
            ModelData::fake('fake-model-2', 'Fake Model Two'),
        ])
        ->respondWith(function (ChatRequest $request) {
            return "Response from {$request->model}";
        });

    $this->signIn();

    // Create a new conversation with the second model and send a message
    visit('/conversations/new')
        ->fill('message', 'Test message for Fake Model Two')
        ->click('Fake Model One') // Open the model selection dropdown
        ->click('Fake Model Two') // Select the second model
        ->click('[aria-label="Send message"]')
        ->assertSee('Test message for Fake Model Two')
        ->assertSee('Response from fake-model-2');
});

it('can send a message in an existing conversation', function () {
    // Disable testing to avoid pest preventing streaming to reach client due to its output buffering
    $this->disableStreaming();

    // Fake OpenRouter response
    OpenRouter::fake()
        ->respondWith('What is your project about?');

    $user = $this->signIn();

    // Create a conversation with messages
    $conversation = Conversation::factory()
        ->forUser($user)
        ->withMessages(6)
        ->create();

    // Visit the conversation and send a message
    visit("/conversations/{$conversation->id}")
        ->fill('message', 'I need help with a project.')
        ->click('[aria-label="Send message"]')
        ->assertSee('I need help with a project.')
        ->assertSee('What is your project about?');
});

it('can navigate between conversations in from the sidebar', function () {
    $user = $this->signIn();

    // Create multiple conversations
    $conversations = Conversation::factory()
        ->forUser($user)
        ->withTitle()
        ->withMessages(4)
        ->count(3)
        ->create();

    // Visit the dashboard
    $browser = visit(route('conversations.new'));

    // Navigate to each conversation and assert the title
    foreach ($conversations as $conversation) {
        $browser = $browser->click($conversation->title)
            ->assertSee($conversation->messages->first()->content);
    }
});

it('can login and send a message in a row', function () {

    $this->disableStreaming();

    OpenRouter::fake()
        ->respondWith('What is your project about?');

    $user = User::factory()->withoutTwoFactor()->createOne();

    visit('/')
        ->click('Se connecter')
        ->fill('email', $user->email)
        ->fill('password', 'password')
        ->click('Log in')
        ->fill('message', 'Test message for Fake Model Two')
        ->click('[aria-label="Send message"]')
        ->assertSee('Test message for Fake Model Two')
        ->assertSee('What is your project about?');

});

it('can handle an error', function () {
    $this->disableStreaming();

    OpenRouter::fake()
        ->shouldThrow(new ConnectionException('Something went wrong'));

    $this->signIn();

    visit('/conversations/new')
        ->fill('message', 'Hello, how are you?')
        ->click('[aria-label="Send message"]')
        ->assertSee('Something went wrong');
});
