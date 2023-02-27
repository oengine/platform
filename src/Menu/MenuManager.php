<?php

namespace OEngine\Platform\Menu;

use Illuminate\Support\Facades\Route;

class MenuManager
{
    private $registers = [];
    private $positions = [];
    private $positiosCallback = [];
    private $positiosItemCallback = [];
    private $default = 'sidebar';
    public function getDefault()
    {
        return $this->default;
    }
    public function switchDefault($default)
    {
        $this->default = $default;
    }
    public function position($_position = ''): MenuBuilder
    {
        if (!$_position) $_position = $this->default;
        if (!isset($this->positions[$_position]) || !$this->positions[$_position]) {
            $this->positions[$_position] = new MenuBuilder($_position);
            $this->renderCallback(function (MenuBuilder $item) {
                echo '<ul>';
                foreach ($item->getItems() as $_item) {
                    echo $_item->toHtml();
                }
                echo '</ul>';
            }, $_position);
            $this->renderItemCallback(function (MenuItemBuilder $item) {
                echo '<li>';
                echo $item->getValueText();
                if ($item->checkSubMenu()) {
                    echo $item->getSubMenu()->toHtml();
                }
                echo '</li>';
            }, $_position);
        }
        return $this->positions[$_position];
    }
    public function render($_position = '')
    {
        return $this->position($_position)->toHtml();
    }
    public function link($link, $text, $icon = '', $attributes = [], $per = '', $sort = -1, $_position = ''): MenuBuilder
    {
        return $this->position($_position)->link($link, $text, $icon, $attributes, $per, $sort);
    }
    public function div($text = '', $icon = '', $attributes = [], $per = '', $sort  = -1, $_position = ''): MenuBuilder
    {
        return $this->position($_position)->div($text, $icon, $attributes, $per, $sort);
    }
    public function tag($tag, $text, $icon = '', $attributes = [], $per = '', $sort  = -1, $_position = ''): MenuBuilder
    {
        return $this->position($_position)->tag($tag, $text, $icon, $attributes, $per, $sort);
    }
    public function button($text, $icon = '', $attributes = [], $per = '', $sort  = -1, $_position = ''): MenuBuilder
    {
        return $this->position($_position)->button($text, $icon, $attributes, $per, $sort);
    }
    public function subMenu($text, $icon = '', $callback, $sort  = -1, $_position = ''): MenuBuilder
    {
        return $this->position($_position)->subMenu($text, $icon, $callback, $sort);
    }
    public function attachMenu($targetId, $callback, $_position = ''): MenuBuilder
    {
        return $this->position($_position)->attachMenu($targetId, $callback);
    }
    public function wrapDiv($class, $id, $attributes = [], $_position = ''): MenuBuilder
    {
        return $this->position($_position)->wrapDiv($class, $id, $attributes);
    }
    public function renderCallback($callback, $_position = '')
    {
        $this->positiosCallback[$_position ?? $this->default] = $callback;
    }
    public function renderItemCallback($callback, $_position = '')
    {
        $this->positiosItemCallback[$_position ?? $this->default] = $callback;
    }
    public function doRender($item, $_position = '')
    {
        if ($callback = $this->positiosCallback[$_position ?? $this->default]) {
            if (is_callable($callback))
                $callback($item);
        }
    }
    public function doRenderItem($item, $_position = '')
    {
        if ($callback = $this->positiosItemCallback[$_position ?? $this->default]) {
            if (is_callable($callback))
                $callback($item);
        }
    }
    public function register($callback)
    {
        if ($callback && is_callable($callback)) {
            $this->registers[] = $callback;
        }
    }
    public function doRegister()
    {
        if (!Route::current()) return;
        foreach ($this->registers as $callback) {
            $callback();
        }
        $this->registers = [];
    }
}