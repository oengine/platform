<?php

namespace OEngine\Platform\Middleware;

use OEngine\Platform\Facades\Theme;

class LayoutFull
{
    public function handle($request, \Closure $next)
    {
        Theme::setLayoutNone();
        return $next($request);;
    }
}
