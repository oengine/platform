<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Symfony\Component\Finder\SplFileInfo;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use OEngine\Platform\Facades\Action;
use OEngine\Platform\Facades\Filter;
use OEngine\Platform\Facades\Theme;
use OEngine\Platform\Models\Option;


if (!function_exists('platform_encode')) {
    function platform_encode($data)
    {
        return base64_encode(urlencode(json_encode($data ?? [])));
    }
}
if (!function_exists('platform_decode')) {
    function platform_decode($data)
    {
        return  json_decode(urldecode(base64_decode($data)),true);
    }
}
if (!function_exists('platform_component')) {
    function platform_component($component, $params = [])
    {
        return platform_encode([
            'component' => $component,
            'params' => $params
        ]);
    }
}
if (!function_exists('platform_view')) {
    function platform_view($view, $params = [])
    {
        return platform_encode([
            'view' => $view,
            'params' => $params
        ]);
    }
}
if (!function_exists('platform_action')) {
    function platform_action($action, $params = [])
    {
        return platform_encode([
            'action' => $action,
            'params' => $params
        ]);
    }
}

if (!function_exists('add_action')) {
    /**
     * @param  string | array  $hook
     * @param $callback
     * @param  int  $priority
     * @param  int  $arguments
     */
    function add_action($hook, $callback, int $priority = 20, int $arguments = 1)
    {
        Action::addListener($hook, $callback, $priority, $arguments);
    }
}

if (!function_exists('remove_action')) {
    /**
     * @param  string  $hook
     */
    function remove_action($hook, $callback = null)
    {
        Action::removeListener($hook, $callback);
    }
}
if (!function_exists('do_action')) {
    /**
     * @param  string  $hook
     */
    function do_action()
    {
        $args = func_get_args();
        Action::fire(array_shift($args), $args);
    }
}

if (!function_exists('add_filter')) {
    /**
     * @param  string | array  $hook
     * @param $callback
     * @param  int  $priority
     * @param  int  $arguments
     */
    function add_filter($hook, $callback, int $priority = 20, int $arguments = 1)
    {
        Filter::addListener($hook, $callback, $priority, $arguments);
    }
}
if (!function_exists('remove_filter')) {
    /**
     * @param  string  $hook
     */
    function remove_filter($hook, $callback)
    {
        Filter::removeListener($hook, $callback);
    }
}

if (!function_exists('apply_filters')) {
    /**
     * @return mixed
     */
    function apply_filters()
    {
        $args = func_get_args();

        return Filter::fire(array_shift($args), $args);
    }
}

if (!function_exists('get_hooks')) {
    /**
     * @param  string|null  $name
     * @param  bool  $isFilter
     * @return array
     */
    function get_hooks(?string $name = null, bool $isFilter = true): array
    {
        if ($isFilter) {
            $listeners = Filter::getListeners();
        } else {
            $listeners = Action::getListeners();
        }

        if (empty($name)) {
            return $listeners;
        }

        return Arr::get($listeners, $name, []);
    }
}

if (!function_exists('theme_layout')) {
    /**
     * @param  string 
     */
    function theme_layout()
    {
        return apply_filters(PLATFORM_THEME_LAYOUT, 'none');
    }
}
if (!function_exists('path_by')) {
    /**
     * @param  string 
     */
    function path_by($name, $path)
    {
        return base_path(config('platform.appdir.root') . '/' . config('platform.appdir.' . $name) . '/' . $path);
    }
}

if (!function_exists('run_cmd')) {
    /**
     * @param  string 
     */
    function run_cmd($path, $cmd)
    {
        chdir($path);
        passthru($cmd);
    }
}

if (!function_exists('callAfterResolving')) {
    function callAfterResolving($name, $callback, $app = null)
    {
        if (!$app) $app = app();
        $app->afterResolving($name, $callback);

        if ($app->resolved($name)) {
            $callback($app->make($name), $app);
        }
    }
}

if (!function_exists('loadViewsFrom')) {
    function loadViewsFrom($path, $namespace = 'platform', $app = null)
    {
        callAfterResolving('view', function ($view, $app) use ($path, $namespace) {
            if (
                isset($app->config['view']['paths']) &&
                is_array($app->config['view']['paths'])
            ) {
                foreach ($app->config['view']['paths'] as $viewPath) {
                    if (is_dir($appPath = $viewPath . '/vendor/' . $namespace)) {
                        $view->addNamespace($namespace, $appPath);
                    }
                }
            }
            $view->addNamespace($namespace, $path);
        }, $app);
    }
}

if (!function_exists('AllFile')) {
    function AllFile($directory, $callback = null, $filter = null)
    {
        if (!File::isDirectory($directory)) {
            return [];
        }
        if ($callback) {
            if ($filter) {
                collect(File::allFiles($directory))->filter($filter)->each($callback);
            } else {
                collect(File::allFiles($directory))->each($callback);
            }
        } else {
            return File::allFiles($directory);
        }
    }
}


if (!function_exists('AllClass')) {
    function AllClass($directory, $namespace, $callback = null, $filter = null)
    {
        $files = AllFile($directory);
        if (!$files || !is_array($files)) return [];

        $classList = collect($files)->map(function (SplFileInfo $file) use ($namespace) {
            return (string) Str::of($namespace)
                ->append('\\', $file->getRelativePathname())
                ->replace(['/', '.php'], ['\\', '']);
        });
        if ($callback) {
            if ($filter) {
                $classList = $classList->filter($filter);
            }
            $classList->each($callback);
        } else {
            return $classList;
        }
    }
}

if (!function_exists('AllDirectory')) {
    function AllDirectory($directory, $callback = null, $filter = null)
    {
        if (!File::isDirectory($directory)) {
            return [];
        }
        if ($callback) {
            if ($filter) {
                collect(File::directories($directory))->filter($filter)->each($callback);
            } else {
                collect(File::directories($directory))->each($callback);
            }
        } else {
            return File::directories($directory);
        }
    }
}
if (!function_exists('checkPermission')) {
    function checkPermission($per = '')
    {
        return apply_filters(PLATFORM_CHECK_PERMISSION, ($per == '' || Gate::check($per, [auth()->user()])), $per);
    }
}

if (!function_exists('checkRole')) {
    function checkRole($per = '')
    {
        return apply_filters(PLATFORM_CHECK_ROLE, ($per == '' || Gate::check($per, [auth()->user()])), $per);
    }
}
if (!function_exists('adminUrl')) {
    function adminUrl()
    {
        return  apply_filters(PLATFORM_URL_ADMIN, "");
    }
}
if (!function_exists('page_title')) {
    function page_title()
    {
        return  apply_filters(PLATFORM_URL_ADMIN, Theme::getTitle());
    }
}


if (!function_exists('set_option')) {
    function set_option($key, $value = null, $locked = null)
    {
        try {
            Cache::forget($key);
            $setting = Option::where('key', $key)->first();
            if ($value !== null) {
                $setting = $setting ?? new Option(['key' => $key]);
                $setting->value = $value;
                $setting->locked = $locked === true;
                $setting->save();
                Cache::forever($key, $setting->value);
            } else if ($setting != null) {
                $setting->delete();
            }
        } catch (\Exception $e) {
        }
    }
}
if (!function_exists('get_option')) {
    /**
     * Get Value: get_option("seo_key")
     * Get Value Or Default: get_option("seo_key","value_default")
     */
    function get_option($key, $default = null)
    {
        try {
            if (Cache::has($key) && Cache::get($key) != '') return Cache::get($key);
            $setting = Option::where('key', trim($key))->first();
            if ($setting == null) {
                return $default;
            }
            //Set Cache Forever
            Cache::forever($key, $setting->value);
            return $setting->value ?? $default;
        } catch (\Exception $e) {
            return $default;
        }
    }
}
