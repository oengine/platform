<?php

namespace Platform\Themes\None;

use Illuminate\Support\ServiceProvider;
use OEngine\LaravelPackage\ServicePackage;
use OEngine\Platform\Traits\WithServiceProvider;

class NoneServiceProvider extends ServiceProvider
{
    use WithServiceProvider;

    public function configurePackage(ServicePackage $package): void
    {
        echo '123';
        /*
         * This class is a Package Service Provider
         *
         */
        $package
            ->name('none')
            ->hasConfigFile()
            ->hasViews()
            ->hasHelpers()
            ->hasAssets()
            ->hasTranslations()
            ->runsMigrations();
    }
    public function extending()
    {
    }
    public function packageRegistered()
    {
        $this->extending();
    }
    private function bootGate()
    {
        if (!$this->app->runningInConsole()) {
            add_filter(PLATFORM_PERMISSION_CUSTOME, function ($prev) {
                return [
                    ...$prev
                ];
            });
        }
    }
    public function packageBooted()
    {
        $this->bootGate();
    }
}
