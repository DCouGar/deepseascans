<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User; // Optional: Add for type hinting

class ProfileController extends Controller
{
    /**
     * Display the form for editing the user's profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function edit(Request $request): View
    {
        // Return the 'profile.edit' view, passing the authenticated user
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the authenticated user's profile information.
     * Uses ProfileUpdateRequest for validation.
     *
     * @param  \App\Http\Requests\ProfileUpdateRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // Fill the user model with validated data from the request
        $request->user()->fill($request->validated());

        // If the email address was changed, mark it as unverified
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        // Save the changes to the database
        $request->user()->save();

        // Redirect back to the profile edit page with a success status message
        // return Redirect::route('profile.edit')->with('status', 'profile-updated'); // Original
        return Redirect::route('profile.edit')->with('success', __('Profile updated successfully!')); // Example with translated message
    }

    /**
     * Delete the authenticated user's account.
     * Validates the current password before deletion.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Validate that the provided password matches the user's current password
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        // Get the authenticated user model instance
        $user = $request->user();

        // Log the user out before deleting
        Auth::logout();

        // Delete the user record from the database
        $user->delete();

        // Invalidate the session and regenerate the CSRF token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect the user to the homepage after deletion
        return Redirect::to('/');
    }
}