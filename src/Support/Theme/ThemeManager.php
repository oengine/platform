<?php

namespace OEngine\Platform\Support\Theme;

use Illuminate\Support\Facades\Log;
use OEngine\Platform\DataInfo;
use OEngine\Platform\Traits\WithSystemExtend;

class ThemeManager
{
    use WithSystemExtend;
    use WithAsset;
    public function isRegisterBeforeLoad()
    {
        return false;
    }
    public function getName()
    {
        return "theme";
    }
    private $layout;
    private $model_seo;
    private ?DataInfo $data_active;
    public function setTitle($title)
    {
        $this->setAsset('page_title', $title);
    }
    public function getTitle()
    {
        return apply_filters(PLATFORM_PAGE_TITLE, $this->getAsset('page_title'));
    }
    public function setModelSeo($model_seo)
    {
        $this->model_seo = $model_seo;
    }
    public function getModelSeo()
    {
        return $this->model_seo;
    }
    public function getHeaderInfo()
    {
        if (function_exists('seo') && $this->data_active && !$this->data_active->admin) {
            echo seo($this->getModelSeo());
        } else {
            echo "<title>" . page_title() . "</title>";
        }
    }
    public function setLayoutNone()
    {
        $this->setLayout('none');
    }
    public function setLayout($layout)
    {
        $this->layout = 'theme::' . $layout;
    }

    public function getStatusData($theme)
    {
        if (isset($theme['admin']) && $theme['admin'] == 1) {
            return get_option(PLATFORM_THEME_ADMIN) == $theme->getId() ? 1 : 0;
        } else {
            return get_option(PLATFORM_THEME_WEB) == $theme->getId() ? 1 : 0;
        }
    }

    public function setStatusData($theme, $value)
    {
        if (isset($theme['admin']) && $theme['admin'] == 1) {
            set_option(PLATFORM_THEME_ADMIN, $theme->getId());
        } else {
            set_option(PLATFORM_THEME_WEB, $theme->getId());
        }
        run_cmd(base_path(''), 'php artisan platform:link');
    }
    public $data_themes = [];
    public function findAndRegister($theme, $parentId = null)
    {
        if (!$parentId) $parentId = $theme;
        if (!isset($this->data_themes[$parentId])) $this->data_themes[$parentId] = [];
        $theme_data = $this->find($theme);
        if ($theme_data == null) return null;
        $this->data_themes[$parentId][] = $theme_data;
        if ($parent = $theme_data['parent']) {
            $this->findAndRegister($parent, $parentId);
        }
        $theme_data->DoRegister();
        return $theme_data;
    }
    public function RegisterTheme()
    {
        $this->findAndRegister('none');
        $this->findAndRegister(apply_filters(PLATFORM_THEME_FILTER_LAYOUT, get_option(PLATFORM_THEME_ADMIN, 'oengine-admin'), 1));
        $this->findAndRegister(apply_filters(PLATFORM_THEME_FILTER_LAYOUT, get_option(PLATFORM_THEME_WEB, 'oengine-none'), 0));
    }
    public function ThemeCurrent()
    {
        if (!isset($this->data_active) || !$this->data_active) {
            if (platform_route_is_admin()) {
                $this->data_active = $this->findAndRegister(apply_filters(PLATFORM_THEME_FILTER_LAYOUT, get_option(PLATFORM_THEME_ADMIN, 'oengine-admin'), 1));
            } else {
                $this->data_active = $this->findAndRegister(apply_filters(PLATFORM_THEME_FILTER_LAYOUT, get_option(PLATFORM_THEME_WEB, 'oengine-none'), 0));
            }
            if ($this->data_active == null) {
                $this->data_active = $this->findAndRegister('none');
            }
        }
        return $this->data_active;
    }
    public function Layout($layout = '')
    {
        $theme = $this->ThemeCurrent();
        if ($theme) {
            if (!$this->layout) {
                $this->layout = 'theme::' .   ($theme['layout'] ?? 'layout');
            }
        }
        if ($layout != '') {
            return $layout;
        }
        return $this->layout;
    }
}
