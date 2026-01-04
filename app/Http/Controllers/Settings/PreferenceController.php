<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\PreferenceUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PreferenceController extends Controller
{
    /**
     * Show the user's preference settings page.
     */
    public function edit(Request $request): Response
    {
        $preferences = $request->user()->preferences;

        return Inertia::render('settings/Preferences', [
            'preferences' => [
                'instruction_prompt' => $preferences->instruction_prompt,
            ],
        ]);
    }

    /**
     * Update the user's preference information.
     */
    public function update(PreferenceUpdateRequest $request): RedirectResponse
    {
        $preferences = $request->user()->preferences;

        $preferences->update($request->validated());

        return to_route('preferences.edit');
    }
}
