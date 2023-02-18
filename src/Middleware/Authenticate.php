<?php

namespace OEngine\Platform\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Closure;
use Illuminate\Support\Str;

class Authenticate extends Middleware
{
    public function handle($request, Closure $next, ...$guards)
    {
        $response = parent::handle($request, $next, ...$guards);
        // Like: users.index
        $route = $request->route()->getName();
        //skip with prexfix '_'
        if (!Str::startsWith($route, '_')) {
            if ($route == 'core.table.slug') {
                $route = 'core.' . $request->route('module');
            }
            // Hasn't permission
            if (!checkPermission($route)) {
                return abort(403);
            }
        }

        return $response;
    }
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            return (apply_filters(PLATFORM_URL_LOGIN, route('auth.login')));
        }
    }
}
