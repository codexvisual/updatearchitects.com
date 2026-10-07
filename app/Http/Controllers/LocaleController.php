<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;

class LocaleController extends Controller
{
    public function switch(string $locale): RedirectResponse
    {
        $allowedLocales = ['en', 'bn'];

        if (! in_array($locale, $allowedLocales)) {
            abort(404);
        }

        App::setLocale($locale);
        session(['locale' => $locale]);

        $response = back()->withCookie(
            Cookie::forever('locale', $locale)
        );

        return $response;
    }
}
