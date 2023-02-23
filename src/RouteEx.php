<?php

namespace OEngine\Platform;

use Illuminate\Database\Console\Migrations\StatusCommand;
use Illuminate\Support\Facades\Route;

class RouteEx
{
    private static $cacheRouteLoaded = [];
    public static function checkPath($path)
    {
        return (isset(self::$cacheRouteLoaded[$path]) && self::$cacheRouteLoaded[$path]);
    }
    public static function Load($path)
    {
        if (self::checkPath($path)) return;
        self::$cacheRouteLoaded[$path] = true;
        if (file_exists(($path . '/api.php')))
            Route::middleware(apply_filters(PLATFORM_MIDDLEWARE_API, ['api', \OEngine\Platform\Middleware\Platform::class]))
                ->prefix('api')
                ->group($path . '/api.php');

        if (file_exists($path . '/web.php'))
            Route::middleware(apply_filters(PLATFORM_MIDDLEWARE_WEB, ['web', \OEngine\Platform\Middleware\Platform::class]))
                ->group($path . '/web.php');

        if (file_exists($path . 'admin.php'))
            Route::middleware(apply_filters(PLATFORM_MIDDLEWARE_ADMIN, ['web', \OEngine\Platform\Middleware\Authenticate::class, \OEngine\Platform\Middleware\ThemeAdmin::class, \OEngine\Platform\Middleware\Platform::class]))
                ->prefix(adminUrl())
                ->group($path . '/admin.php');
    }
}
