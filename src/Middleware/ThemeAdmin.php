<?php

namespace OEngine\Platform\Middleware;


class ThemeAdmin
{
    public function handle($request, \Closure $next)
    {
        return $next($request);;
    }
}
