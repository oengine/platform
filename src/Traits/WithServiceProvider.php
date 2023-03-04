<?php

namespace OEngine\Platform\Traits;

use OEngine\LaravelPackage\JsonData;
use OEngine\LaravelPackage\WithServiceProvider as WithServiceProviderBase;
use OEngine\Platform\Facades\Module;
use OEngine\Platform\Facades\Plugin;
use OEngine\Platform\Facades\Theme;
use OEngine\Platform\RouteEx;

trait WithServiceProvider
{
    use WithServiceProviderBase {
        register as protected registerBase;
        boot as protected bootBase;
    }
    public $base_type = '';
    public function configurePackaged()
    {
    }

    public function register()
    {
        $this->ExtendPackage();
        $this->registerBase();
        foreach (['module', 'theme', 'plugin'] as $baseType) {
            if (file_exists($this->package->basePath('/../' . $baseType . '.json'))) {
                $this->base_type = $baseType;
                $info = JsonData::getJsonFromFile($this->package->basePath('/../' . $baseType . '.json'));
                if (!(isset($info['name']) && platform_by($this->base_type)->has($info['name']))) {
                    platform_by($this->base_type)->AddItem($this->package->basePath('/../'));
                }
                break;
            }
        }

        if (file_exists($this->package->basePath('/../public'))) {
            Module::addLink($this->package->basePath('/../public'), public_path($this->base_type . 's/' . $this->package->name));
        }
        Theme::Load($this->package->basePath('/../themes'));
        Plugin::Load($this->package->basePath('/../plugins'));
        RouteEx::Load($this->package->basePath('/../routes/'));

        $this->packageRegistered();
        return $this;
    }


    public function boot()
    {
        if ($this->base_type) {
            if (file_exists($this->package->basePath('/../public/js/app.js')))
                Theme::addScript(PLATFORM_BODY_AFTER, url($this->base_type . 's/' . $this->package->name . '/js/app.js'));
            if (file_exists($this->package->basePath('/../public/css/app.css')))
                Theme::addStyle(PLATFORM_HEAD_AFTER, url($this->base_type . 's/' . $this->package->name . '/css/app.css'));
        }
        if ($this->base_type == 'theme') {
            $this->package->name('theme');
        }
        $this->bootBase();
        if ($this->app->runningInConsole()) {
            if ($this->package->runsMigrations) {
                AllFile($this->package->basePath("/../database/migrations/"), function ($file) {
                    $this->loadMigrationsFrom($file->getRealPath());
                }, function ($file) {
                    return $file->getExtension() == "php";
                });
            }

            if ($this->package->runsSeeds) {
                AllFile($this->package->basePath("/../database/seeders/"), function ($file) {
                    include_once($file->getRealPath());
                }, function ($file) {
                    return $file->getExtension() == "php";
                });
            }
        }

        $this->packageBooted();

        return $this;
    }
}
