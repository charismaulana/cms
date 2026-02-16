<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Validation\Rules\Password;

class ChangePasswordController extends Controller
{
    /**
     * Show the change password form.
     */
    public function show(): View|RedirectResponse
    {
        // If user doesn't need to change password, redirect to dashboard
        if (!auth()->user()->must_change_password) {
            return redirect()->route('dashboard');
        }

        return view('auth.change-password');
    }

    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $user = $request->user();
        $user->update([
            'password' => Hash::make($validated['password']),
            'must_change_password' => false,
        ]);

        return redirect()->route('dashboard')
            ->with('success', __('messages.password_changed'));
    }
}
