<?php

namespace OEngine\Platform\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * 
 * @method static string getName()
 * @method static string FileInfoJson()
 * @method static string HookFilterPath()
 * @method static string PathFolder()
 * @method static string getPath(string $path)
 * @method static string PublicFolder()
 * @method static void LoadApp()
 * @method static void RegisterApp()
 * @method static void BootApp()
 * @method static \Illuminate\Support\Collection<string, \OEngine\Platform\DataInfo> getData()
 * @method static \OEngine\Platform\DataInfo find(string $name)
 * @method static bool has(string $name)
 * @method static void delete(string $name)
 * @method static void Load(string $path)
 * @method static void AddItem(string $path)
 * @method static string getUsed()
 * @method static void forgetUsed()
 * @method static void setUsed(string $name)
 * @method static void update(string $name)
 * @method static void setAsset($key, $value)
 * @method static mix getAsset($key, $default = '')
 * @method static void addScript($local, $contentOrPath, $cdn = '', $priority = 20, $isLink = true)
 * @method static void addStyle($local, $contentOrPath, $cdn = '', $priority = 20, $isLink = true)
 * @method static void contentScript($content, $priority = 20)
 * @method static void contentStyle($content, $priority = 20)
 * @method static string loadAsset($local)
 * @method static void getHeaderInfo()
 * @method static string getTitle()
 * @method static void setTitle($title)
 * @method static string Layout($default='')
 * @method static mix ThemeCurrent()
 * @method static void RegisterTheme()
 * @method static void setLayoutNone()
 * 
 * @see \OEngine\Platform\Facades\Theme
 */
class Theme extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \OEngine\Platform\Support\Theme\ThemeManager::class;
    }
}
