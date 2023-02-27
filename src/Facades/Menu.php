<?php

namespace OEngine\Platform\Facades;

use Illuminate\Support\Facades\Facade;
use OEngine\Platform\Menu\MenuBuilder;

/**
 * 
 * @method static string render($_position = '')
 * @method static string getDefault()
 * @method static void switchDefault($default)
 * @method static MenuBuilder Position($_position = '')
 * @method static MenuBuilder link($link, $text, $icon = '', $attributes = [], $per = '', $sort = -1, $_position = '')
 * @method static MenuBuilder div($text = '', $icon = '', $attributes = [], $per = '', $sort  = -1, $_position = '')
 * @method static MenuBuilder tag($tag, $text, $icon = '', $attributes = [], $per = '', $sort  = -1, $_position = '')
 * @method static MenuBuilder button($text, $icon = '', $attributes = [], $per = '', $sort  = -1, $_position = '')
 * @method static MenuBuilder subMenu($text, $icon = '', $callback, $sort  = -1, $_position = '')
 * @method static MenuBuilder attachMenu($targetId, $callback, $_position = '')
 * @method static MenuBuilder wrapDiv($class, $id, $attributes = [], $_position = '')
 * @method static void renderCallback($callback, $_position = '')
 * @method static void renderItemCallback($callback, $_position = '')
 * @method static void DoRender($item, $_position = '')
 * @method static void DoRenderItem($item, $_position = '')
 * @method static void Register($callback)
 * @method static void DoRegister()
 *
 * @see \OEngine\Platform\Facades\MenuManager
 */
class Menu extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \OEngine\Platform\Menu\MenuManager::class;
    }
}
