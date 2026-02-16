<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    /**
     * Switch the application language.
     */
    public function switch(Request $request, string $locale): RedirectResponse
    {
        if (in_array($locale, ['en', 'id'])) {
            Session::put('locale', $locale);
        }

        return redirect()->back();
    }
}
