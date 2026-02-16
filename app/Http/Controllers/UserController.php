<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display a listing of registered users.
     */
    public function index(): View
    {
        $users = User::orderBy('name')->get();

        return view('users.index', compact('users'));
    }
}
