<?php
use Illuminate\Support\Facades\DB;

if (!function_exists('is_active_menu')) {
    function is_active_menu(string|array $route): string
    {
        $activeClass = ' active';

        if (is_string($route)) {
            if (request()->is(substr($route . '*', 1))) {
                return $activeClass;
            }

            if (request()->is(str($route)->slug() . '*')) {
                return $activeClass;
            }

            if (request()->segment(2) === str($route)->before('/')) {
                return $activeClass;
            }

            if (request()->segment(3) === str($route)->after('/')) {
                return $activeClass;
            }
        }

        if (is_array($route)) {
            foreach ($route as $value) {
                $actualRoute = str($value)->remove(' view')->plural();

                if (request()->is(substr($actualRoute . '*', 1))) {
                    return $activeClass;
                }

                if (request()->is(str($actualRoute)->slug() . '*')) {
                    return $activeClass;
                }

                if (request()->segment(2) === $actualRoute) {
                    return $activeClass;
                }

                if (request()->segment(3) === $actualRoute) {
                    return $activeClass;
                }
            }
        }

        return '';
    }
}

function is_active_submenu(string|array $route): string
{
    $activeClass = ' submenu-open';

    if (is_string($route)) {
        if (request()->is(substr($route . '*', 1))) {
            return $activeClass;
        }

        if (request()->is(str($route)->slug() . '*')) {
            return $activeClass;
        }

        if (request()->segment(2) === str($route)->before('/')) {
            return $activeClass;
        }

        if (request()->segment(3) === str($route)->after('/')) {
            return $activeClass;
        }
    }

    if (is_array($route)) {
        foreach ($route as $value) {
            $actualRoute = str($value)->remove(' view')->plural();

            if (request()->is(substr($actualRoute . '*', 1))) {
                return $activeClass;
            }

            if (request()->is(str($actualRoute)->slug() . '*')) {
                return $activeClass;
            }

            if (request()->segment(2) === $actualRoute) {
                return $activeClass;
            }

            if (request()->segment(3) === $actualRoute) {
                return $activeClass;
            }
        }
    }

    return '';
}

if (!function_exists('set_active')) {
    function set_active($uri)
    {
        if (is_array($uri)) {
            foreach ($uri as $u) {
                if (Route::is($u)) { // jika route sekarang sama dengan variable u
                    return 'active';
                }
            }
        } else {
            if (Route::is($uri)) { // jika route sekarang sama dengan variable u
                return 'active';
            }
        }
        // return request()->routeIs($uri) ? 'active' : '';
    }
}
