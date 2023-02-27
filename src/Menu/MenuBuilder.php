<?php

namespace OEngine\Platform\Menu;

use Illuminate\Support\Facades\Request;
use OEngine\Platform\HtmlBuilder;
use Illuminate\Support\Str;
use OEngine\Platform\Facades\Menu;

class MenuBuilder extends HtmlBuilder
{
    protected $warps = [];
    protected $items = [];
    protected $sub = false;
    protected $targetId = '';
    protected static $url_current = null;
    protected static $sort_number = 1000;
    protected $sub_level = 0;
    protected $position = [];

    protected MenuItemBuilder|null $parent;
    public function getPosition()
    {
        return $this->position;
    }
    public function getSubLevel()
    {
        return $this->sub_level;
    }
    public function __construct($position = '', $sub = false, $level = 0, $parent = null)
    {
        $this->position = $position;
        if (!self::$url_current) self::$url_current = Request::url();
        $this->sub = $sub;
        $this->sub_level = $level;
        $this->parent = $parent;
    }
    public static function checkUrl($url)
    {
        return self::$url_current == $url;
    }
    public function setTargetId($targetId): self
    {
        $this->targetId = $targetId;
        return $this;
    }
    public function isSub()
    {
        return $this->sub;
    }
    public function getItems()
    {
        return $this->items;
    }
    public function link($link, $text, $icon = '', $attributes = [], $per = '', $sort = -1): self
    {
        $this->items[] = new MenuItemBuilder([
            MenuItemBuilder::KEY_TYPE => MenuItemBuilder::ITEM_LINK,
            MenuItemBuilder::KEY_LINK => $link,
            MenuItemBuilder::KEY_TEXT => $text,
            MenuItemBuilder::KEY_ICON => $icon,
            MenuItemBuilder::KEY_ATTRIBUTE => $attributes,
            MenuItemBuilder::KEY_PERMISSION => $per,
            MenuItemBuilder::KEY_SORT => $sort >  -1 ? $sort : (self::$sort_number++)
        ], $this);
        return $this;
    }
    public function div($text = '', $icon = '', $attributes = [], $per = '', $sort  = -1): self
    {
        $this->items[] = new MenuItemBuilder([
            MenuItemBuilder::KEY_TYPE => MenuItemBuilder::ITEM_DIV,
            MenuItemBuilder::KEY_TEXT => $text,
            MenuItemBuilder::KEY_ICON => $icon,
            MenuItemBuilder::KEY_ATTRIBUTE => $attributes,
            MenuItemBuilder::KEY_PERMISSION => $per,
            MenuItemBuilder::KEY_SORT => $sort >  -1 ? $sort : (self::$sort_number++)
        ], $this);
        return $this;
    }
    public function tag($tag, $text, $icon = '', $attributes = [], $per = '', $sort  = -1)
    {
        $this->items[] = new MenuItemBuilder([
            MenuItemBuilder::KEY_TYPE => MenuItemBuilder::ITEM_TAG,
            MenuItemBuilder::KEY_TAG => $tag,
            MenuItemBuilder::KEY_TEXT => $text,
            MenuItemBuilder::KEY_ICON => $icon,
            MenuItemBuilder::KEY_ATTRIBUTE => $attributes,
            MenuItemBuilder::KEY_PERMISSION => $per,
            MenuItemBuilder::KEY_SORT => $sort >  -1 ? $sort : (self::$sort_number++)

        ], $this);
        return $this;
    }
    public function button($text, $icon = '', $attributes = [], $per = '', $sort  = -1): self
    {
        $this->items[] = new MenuItemBuilder([
            MenuItemBuilder::KEY_TYPE => MenuItemBuilder::ITEM_BUTTON,
            MenuItemBuilder::KEY_TEXT => $text,
            MenuItemBuilder::KEY_ICON => $icon,
            MenuItemBuilder::KEY_ATTRIBUTE => $attributes,
            MenuItemBuilder::KEY_PERMISSION => $per,
            MenuItemBuilder::KEY_SORT => $sort >  -1 ? $sort : (self::$sort_number++)
        ], $this);
        return $this;
    }
    public function subMenu($text, $icon = '', $callback, $sort  = -1)
    {
        $this->items[] = new MenuItemBuilder([
            MenuItemBuilder::KEY_TYPE => MenuItemBuilder::ITEM_SUB,
            MenuItemBuilder::KEY_TEXT => $text,
            MenuItemBuilder::KEY_ICON => $icon,
            MenuItemBuilder::KEY_CALLBACK => $callback,
            MenuItemBuilder::KEY_SORT => $sort >  -1 ? $sort : (self::$sort_number++)

        ], $this);
        return $this;
    }
    public function attachMenu($targetId, $callback): self
    {
        add_action(ADMIN_BUILDER_ATTACH_MENU . '_' . Str::Upper($targetId), function ($menu) use ($callback) {
            if ($callback) {
                $callback($menu);
            }
        });
        return $this;
    }
    public function wrapDiv($class, $id, $attributes = []): self
    {
        $this->warps[] = ['class' => $class, 'id' => $id, MenuItemBuilder::KEY_ATTRIBUTE => $attributes];
        return $this;
    }
    public function checkActive()
    {
        foreach ($this->items as $item) {
            if ($item->checkActive()) {
                return true;
            }
        }
        return false;
    }
    public function checkView()
    {
        foreach ($this->items as $item) {
            if ($item->checkView()) {
                return true;
            }
        }
        return false;
    }
    public function beforeRender(): self
    {
        foreach ($this->items as $item) {
            $item->beforeRender();
        }
        do_action(ADMIN_BUILDER_ATTACH_MENU . '_' . Str::Upper($this->targetId), $this);
        return $this;
    }
    protected function render()
    {
        if (!$this->isSub())
            $this->beforeRender();
        foreach ($this->warps as $item) {
            $attribute = '';
            if (isset($item[MenuItemBuilder::KEY_ATTRIBUTE])) {
                foreach ($item[MenuItemBuilder::KEY_ATTRIBUTE] as $key => $value) {
                    $attribute .= $key . "='" . urlencode(json_encode($value)) . "' ";
                }
            }

            echo "<div class='" . $item['class'] . "' id='" . $item['id'] . "' " . $attribute . " >";
        }
        Menu::DoRender($this, $this->position);
        foreach ($this->warps as $item) {
            echo "</div>";
        }
    }
}
