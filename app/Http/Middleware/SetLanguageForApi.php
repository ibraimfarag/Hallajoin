<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Arr;

class SetLanguageForApi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        \Debugbar::disable();

        if ($locale = $request->get('lang')) {
            $languages = \Modules\Language\Models\Language::getActive();
            $localeCodes = Arr::pluck($languages, 'locale');
            if (in_array($locale, $localeCodes)) {
                app()->setLocale($locale);
            } else {
                app()->setLocale(setting_item('site_locale'));
            }
        }

        return $next($request);
    }
}
