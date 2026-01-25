<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Cache;

abstract class TestCase extends BaseTestCase
{
    /**
     * Create a user and start acting as this user
     * @param ?User $user the user to login as (optional)
     * @return User the user logged in
     */
    protected function signIn(?User $user = null): User
    {
        $user = $user ?? User::factory()->createOne();
        $this->actingAs($user);
        return $user;
    }

    /**
     * Put a flag in the cache that the controller will use to disable streaming
     * @param bool $disable whether to disable the streaming or not
     * @return void
     */
    protected function disableStreaming(bool $disable = true): void
    {
        Cache::put('disable_streaming', $disable, 5);
    }
}
