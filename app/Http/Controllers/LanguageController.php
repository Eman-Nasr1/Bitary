<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redirect;

class LanguageController extends Controller
{
    /**
     * Switch language
     */
    public function switchLang(Request $request, $locale)
    {
        if (!in_array($locale, ['en', 'ar'])) {
            $locale = 'en';
        }

        App::setLocale($locale);
        
        if ($request->hasSession()) {
            $request->session()->put('locale', $locale);
        }

        return Redirect::back();
    }
}

