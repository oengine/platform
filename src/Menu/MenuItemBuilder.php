<?php

namespace OEngine\Platform\Menu;

use OEngine\Platform\Facades\Menu;
use OEngine\Platform\HtmlBuilder;

class MenuItemBuilder extends HtmlBuilder
{
    public const ITEM_DIV = 'ITEM_DIV';
    public const ITEM_TAG = 'ITEM_TAG';
    public const ITEM_LINK = 'ITEM_LINK';
    public const ITEM_BUTTON = 'ITEM_BUTTON';
    public const ITEM_SUB = 'ITEM_SUB';

    public const KEY_TYPE = 'KEY_TYPE';
    public const KEY_TEXT = 'KEY_TEXT';
    public const KEY_ICON = 'KEY_ICON';
    public const KEY_ATTRIBUTE = 'KEY_ATTRIBUTE';
    public const KEY_SORT = 'KEY_SORT';
    public const KEY_TAG = 'KEY_TAG';
    public const KEY_LINK = 'KEY_LINK';
    public const KEY_CALLBACK = 'KEY_CALLBACK';
    public const KEY_BADGE = 'KEY_BADGE';
    public const KEY_PERMISSION = 'KEY_PERMISSION';

    protected MenuBuilder $parent;

    public function __construct($data = [], MenuBuilder $parent)
    {
        $this->dataItem = $data;
        $this->parent = $parent;
    }
    protected $dataItem = [];
    protected $subMenu = null;
    protected function getValue($key, $default = null)
    {
        if (isset($this->dataItem[$key]) && $this->dataItem[$key]) return $this->dataItem[$key];
        return $default;
    }
    public function getValueType()
    {
        return $this->getValue(self::KEY_TYPE);
    }
    public function getPermission()
    {
        return $this->getValue(self::KEY_PERMISSION);
    }
    public function getValueText()
    {
        return $this->getValue(self::KEY_TEXT);
    }
    public function getValueIcon()
    {
        return $this->getValue(self::KEY_ICON);
    }
    public function getValueAttribute()
    {
        return $this->getValue(self::KEY_ATTRIBUTE);
    }
    public function getValueSort()
    {
        return $this->getValue(self::KEY_SORT);
    }
    public function getValueTag()
    {
        return $this->getValue(self::KEY_TAG);
    }
    public function getValueLink()
    {
        return $this->getValue(self::KEY_LINK);
    }
    public function getValueCallback()
    {
        return $this->getValue(self::KEY_CALLBACK);
    }
    public function checkActive()
    {
        if ($this->getValueType() === self::ITEM_SUB) {
            return $this->subMenu->checkActive();
        }
        return MenuBuilder::checkUrl($this->getValueLink());
    }
    public function checkView()
    {
        if ($this->getValueType() === self::ITEM_SUB) {
            return $this->subMenu->checkView();
        }
        $per = $this->getPermission();
        if (!$per) return true;
        return checkPermission($per);
    }
    public function checkSubMenu()
    {
        return isset($this->subMenu) && $this->subMenu != null;
    }
    public function getSubMenu(): MenuBuilder
    {
        return $this->subMenu;
    }
    public function beforeRender()
    {
        if ($this->getValueType() === self::ITEM_SUB) {
            $callback = $this->getValueCallback();
            if ($callback && is_callable($callback)) {
                $this->subMenu = new MenuBuilder($this->parent->getPosition(), true, $this->parent->getSubLevel() + 1, $this);
                $callback($this->subMenu);
                $this->subMenu->beforeRender();
            }
        } else {
            $link = $this->getValueLink();
            if (!$link) return;

            if (is_callable($link)) {
                $this->dataItem[self::KEY_LINK] = $link($this);
            } else if (is_array($link)) {
                ['route' => $route, 'params' => $params] = $link;
                $this->dataItem[self::KEY_LINK] = route($route, $params);
            }
        }
    }
    protected function render()
    {
        Menu::DoRenderItem($this, $this->parent->getPosition());
    }
}
