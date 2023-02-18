<?php

namespace OEngine\Platform\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * 
 * @method static string Current()
 * @method static mixed SwitchTo(string $curent)
 * @method static mixed FileVersion()
 * @method static bool download($remote_file_url, $local_file, $throw = false)
 * @method static mixed findFile(string $name)
 * @method static mixed downloadFile(string $name)
 * @method static mixed install(string $name)
 * @method static mixed installLocal(string $file)
 * @method static mixed Load(string $path)
 * 
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
