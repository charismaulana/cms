<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     * Only accessible by admin users.
     */
    public function create(): View
    {
        // Check if user is admin (middleware should handle this, but double check)
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'Only administrators can register new users.');
        }

        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     * Admin creates new user - user is not logged in automatically.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Check if user is admin
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'Only administrators can register new users.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:admin,user,viewer'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'must_change_password' => true, // New users must change password on first login
        ]);

        event(new Registered($user));

        // Don't log in as new user, stay as admin
        return redirect(route('dashboard'))->with('success', 'User ' . $user->name . ' created successfully. They must change their password on first login.');
    }
}
