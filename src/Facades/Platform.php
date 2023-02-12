<?php

namespace OEngine\Platform\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * 
 * @method static string Current()
 * @method static mixed SwitchTo(string $curent)
 *
 * @see \OEngine\Platform\Facades\Platform
 */
class Platform extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \OEngine\Platform\Support\Platform\PlatformManager::class;
    }
}
