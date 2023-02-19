<?php

namespace OEngine\Platform;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use OEngine\LaravelPackage\ServicePackage;
use OEngine\Platform\Directives\PlatformBladeDirectives;
use OEngine\Platform\Facades\Module;
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
        Theme::BootApp();
    }
    public function bootingPackage()
    {
        Module::LoadApp();
        Theme::LoadApp();
        Theme::RegisterTheme();
        Plugin::LoadApp();
    }

    public function packageRegistered()
    {
        $this->registerBladeDirectives();
        add_action(PLATFORM_HEAD_BEFORE, function () {
            echo Theme::getHeaderInfo();
        });
        add_action(PLATFORM_HEAD_AFTER, function () {
            echo Theme::loadAsset(PLATFORM_HEAD_AFTER);
        });
        add_action(PLATFORM_BODY_AFTER, function () {
            echo Theme::loadAsset(PLATFORM_BODY_AFTER);
            echo "
            <script type='text/javascript'>
            setTimeout(function(){
                ModulePlatform.\$debug=" . (env('MODULE_PLATFORM_DEBUG', false) ? 'true' : 'false') . ";
                ModulePlatform.\$config=" . json_encode(apply_filters(PLATFORM_CONFIG_JS, ['url' => url(''), 'platform_url' => route('__platform__'), 'csrf_token' => csrf_token()])) . ";
            },0)
            </script>
            ";
        });
    }
}
