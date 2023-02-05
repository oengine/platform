<?php

namespace OEngine\Platform;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use OEngine\LaravelPackage\ServicePackage;
use OEngine\Platform\Directives\PlatformBladeDirectives;
use OEngine\Platform\Facades\Module;
use OEngine\Platform\Facades\Theme;
use OEngine\Platform\Traits\WithServiceProvider;

class PlatformServiceProvider extends ServiceProvider
{
    use WithServiceProvider;

    public function configurePackage(ServicePackage $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         */
        $package
            ->name('platform')
            ->hasConfigFile()
            ->hasViews()
            ->hasHelpers()
            ->hasAssets()
            ->hasTranslations()
            ->runsMigrations()
            ->runsSeeds();
    }

    protected function registerBladeDirectives()
    {
        //Blade directives
        Blade::directive('platformBody', [PlatformBladeDirectives::class, 'PlatformBody']);
        Blade::directive('platformHead', [PlatformBladeDirectives::class, 'PlatformHead']);
        Blade::directive('role',  [PlatformBladeDirectives::class, 'CheckRole']);
        Blade::directive('endrole', [PlatformBladeDirectives::class, 'EndIf']);
    }

    public function packageBooted()
    {
        if (!$this->app->runningInConsole()) {
            $this->registerBladeDirectives();
        }
        Module::BootApp();
    }
    public function bootingPackage()
    {
        Module::RegisterApp();
    }

    public function packageRegistered()
    {

        Theme::LoadApp();
        Module::LoadApp();
    }
}
