<?php

namespace App\Providers;

use Whitecube\LaravelCookieConsent\Cookie;
use Whitecube\LaravelCookieConsent\CookiesServiceProvider as ServiceProvider;
use Whitecube\LaravelCookieConsent\Facades\Cookies;

class CookiesServiceProvider extends ServiceProvider
{
    /**
     * Define the cookies users should be aware of.
     */
    protected function registerCookies(): void
    {
        // Register the Required / Essential cookies
        Cookies::essentials()
            // The laravel session cookie
            ->session()
            // The CSRF token cookie
            ->csrf()
            // The sidebar state cookie (exempted from consent since it falls under the "User Requested Service" rule)
            ->cookie(function (Cookie $cookie) {
                $cookie->name('sidebar_state')
                    ->description('This cookie helps us remember whether you have the sidebar expanded or collapsed.')
                    ->duration(24 * 60 * 365); // 1 year
            })
            // The appearance mode cookie (exempted from consent since it falls under the "User Requested Service" rule)
            ->cookie(function (Cookie $cookie) {
                $cookie->name('appearance')
                    ->description('This cookie helps us remember your preferred appearance mode (light, dark or system).')
                    ->duration(24 * 60 * 365); // 1 year
            });
    }
}
