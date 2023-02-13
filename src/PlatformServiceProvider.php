<?php

namespace OEngine\Platform;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use OEngine\LaravelPackage\ServicePackage;
use OEngine\Platform\Directives\PlatformBladeDirectives;
use OEngine\Platform\Facades\Module;
use OEngine\Platform\Facades\Platform;
use OEngine\Platform\Facades\Plugin;
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
        Module::BootApp();
        Plugin::BootApp();
    }
    public function bootingPackage()
    {
        Module::RegisterApp();
        Plugin::RegisterApp();
    }

    public function packageRegistered()
    {
        $this->registerBladeDirectives();
        add_action(PLATFORM_HEAD_BEFORE, function () {
            echo Theme::getHeaderInfo();
        });
        add_action(PLATFORM_HEAD_AFTER, function () {
            if (class_exists(\Livewire\Livewire::class))
                echo \Livewire\Livewire::styles();
            echo Theme::loadAsset('head');
        });
        add_action(PLATFORM_BODY_AFTER, function () {
            if (class_exists(\Livewire\Livewire::class))
                echo \Livewire\Livewire::scripts();
            echo Theme::loadAsset('body');
            echo "
            <script type='text/javascript'>
                ModulePlatform.\$config=" . json_encode(apply_filters(PLATFORM_CONFIG_JS, ['url' => url(''), 'platform_url' => route('__platform__'), 'csrf_token' => csrf_token()])) . ";
            </script>
            ";
        });
        add_filter(PLATFORM_THEME_LAYOUT, function ($prev) {
            return Theme::Layout();
        });
        Theme::LoadApp();
        Plugin::LoadApp();
        Module::LoadApp();
    }
}
