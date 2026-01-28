<?php

namespace App\Http\Responses;

use Inertia\Inertia;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Symfony\Component\HttpFoundation\Response;

class LoginResponse implements LoginResponseContract
{
    /**
     * {@inheritDoc}
     */
    public function toResponse($request): Response
    {
        return Inertia::location(config('fortify.home'));
    }
}
