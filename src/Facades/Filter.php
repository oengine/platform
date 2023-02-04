<?php

namespace OEngine\Platform\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * 
 * @method static mixed addListener(string|array $hook, mixed $callback,int  $priority)
 * @method static \OEngine\Platform\Support\Hook\FilterHook removeListener(string  $hook)
 * @method static array getListeners()
 * @method static mixed fire(string  $action,array  $args)
 *
 * @see \OEngine\Platform\Facades\Filter
 */
class Filter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \OEngine\Platform\Support\Hook\FilterHook::class;
    }
}
