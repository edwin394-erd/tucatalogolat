<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;

class LanguageController extends Controller
{
    public function setLocale($locale)
    {
        // Validate the locale
        if (in_array($locale, ['en', 'es'])) { // Add more locales as needed
            Cookie::queue('locale', $locale, 525600); // 1 year
            App::setLocale($locale);

        }
        return redirect()->back();
    }
}