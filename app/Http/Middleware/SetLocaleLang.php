<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Carbon\Carbon;

class SetLocaleLang
{
    /**
     * Handle an incoming request.
     */
   public function handle(Request $request, Closure $next)
{
    // أولوية للباراميتر في الرابط
    $locale = $request->query('lang');

    // وإذا لم يتم إرساله، نستخدم Accept-Language
    if (empty($locale)) {
        $locale = $request->header('Accept-Language');
    }

    if (!empty($locale)) {
        $locale = strtolower(trim($locale));

        // اللغات المدعومة فقط
        $supported = ['en', 'ar'];

        if (in_array($locale, $supported)) {
            App::setLocale($locale);
            Carbon::setLocale($locale);
        }
    }

    return $next($request);
}

}
