<?php

namespace App\Infrastructure\Middleware;

use App\Infrastructure\Constants\General;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;

/**
 * Class SetSiteLocale
 * @package App\Infrastructure\Middleware
 */
class SetSiteLocale
{
    /**
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // First priority: Session
        if ( session()->has(General::LOCALE_SESSION_KEY) && session()->get(General::LOCALE_SESSION_KEY) ) {
            $this->set(session()->get(General::LOCALE_SESSION_KEY));

            return $next($request);
        }

        // Default from config
        $this->set(config('app.locale'));

        return $next($request);
    }

    /**
     * @param string $locale
     */
    protected function set(string $locale): void
    {
        if ( !in_array($locale, ['en', 'ne']) ) {
            $locale = config('app.locale');
        }

        app()->setLocale($locale);
        Carbon::setLocale($locale);
    }
}
