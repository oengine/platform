<?php

namespace OEngine\Platform\Livewire;

use Illuminate\Support\Str;
use OEngine\Platform\Livewire\Contracts\SkipLoad;
use OEngine\Platform\Livewire\Widget;
use Livewire\Component;
use Livewire\Livewire;
use ReflectionClass;

class LivewireLoader
{
    private static $widgets = [];
    public static function getWidgets()
    {
        return collect(self::$widgets);
    }
    public static function RegisterWidget($path, $namespace, $aliasPrefix = '')
    {
        $aliasPrefix = 'widget-' . $aliasPrefix;
        AllClass(
            $path,
            $namespace,
            function ($class) use ($namespace, $aliasPrefix) {
                $alias = $aliasPrefix . Str::of($class)
                    ->after($namespace . '\\')
                    ->replace(['/', '\\'], '.')
                    ->explode('.')
                    ->map([Str::class, 'kebab'])
                    ->implode('.');
                // fix class namespace
                $alias_class = trim(Str::of($class)
                    ->replace(['/', '\\'], '.')
                    ->explode('.')
                    ->map([Str::class, 'kebab'])
                    ->implode('.'), '.');
                if (Str::endsWith($class, ['\Index', '\index'])) {
                    self::$widgets[] = Str::beforeLast($alias, '.index');
                    Livewire::component(Str::beforeLast($alias, '.index'), $class);
                    Livewire::component(Str::beforeLast($alias_class, '.index'), $class);
                } else {
                    self::$widgets[] = $alias;
                }
                Livewire::component($alias_class, $class);
                Livewire::component($alias, $class);
            },
            function ($class) {
                if (!class_exists($class)) return false;
                $refClass = new ReflectionClass($class);
                return $refClass && !$refClass->isAbstract() && !$refClass->implementsInterface(SkipLoad::class) && $refClass->isSubclassOf(Widget::class);
            }
        );
    }
    public static function Register($path, $namespace, $aliasPrefix = '')
    {
        AllClass(
            $path,
            $namespace,
            function ($class) use ($namespace, $aliasPrefix) {
                $alias = $aliasPrefix . Str::of($class)
                    ->after($namespace . '\\')
                    ->replace(['/', '\\'], '.')
                    ->explode('.')
                    ->map([Str::class, 'kebab'])
                    ->implode('.');
                // fix class namespace
                $alias_class = trim(Str::of($class)
                    ->replace(['/', '\\'], '.')
                    ->explode('.')
                    ->map([Str::class, 'kebab'])
                    ->implode('.'), '.');
                if (Str::endsWith($class, ['\Index', '\index'])) {
                    Livewire::component(Str::beforeLast($alias, '.index'), $class);
                    Livewire::component(Str::beforeLast($alias_class, '.index'), $class);
                }
                Livewire::component($alias_class, $class);
                Livewire::component($alias, $class);
            },
            function ($class) {
                if (!class_exists($class)) return false;
                $refClass = new ReflectionClass($class);
                return  $refClass && !$refClass->isAbstract() && !$refClass->implementsInterface(SkipLoad::class) && $refClass->isSubclassOf(Component::class);
            }
        );
    }
}
