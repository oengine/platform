<?php

namespace OEngine\Platform\Traits;

use Illuminate\Support\Facades\Route;
use OEngine\LaravelPackage\WithServiceProvider as WithServiceProviderBase;
use OEngine\Platform\Facades\Theme;
use OEngine\Platform\Livewire\LivewireLoader;

trait WithServiceProvider
{
    use WithServiceProviderBase {
        register as protected registerBase;
        boot as protected bootBase;
    }
    public function register()
    {
        $this->ExtendPackage();
        $this->registerBase();
        Theme::Load($this->package->basePath('/../themes'));
        $this->packageRegistered();
        return $this;
    }


    public function boot()
    {
        $this->bootBase();
        if (class_exists('\\Livewire\\Component')) {
            LivewireLoader::Register($this->package->basePath('/Http/Livewire'), $this->getNamespaceName() . '\\Http\\Livewire', $this->package->shortName() . '::');
            LivewireLoader::RegisterWidget($this->package->basePath('/../widgets'), $this->getNamespaceName() . '\\Widget', $this->package->shortName() . '::');
        }
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

        if (file_exists($this->package->basePath('/../routes/api.php')))
            Route::middleware(...apply_filters(PLATFORM_MIDDLEWARE_API, ['api']))
                ->prefix('api')
                ->group($this->package->basePath('/../routes/api.php'));

        if (file_exists($this->package->basePath('/../routes/web.php')))
            Route::middleware(...apply_filters(PLATFORM_MIDDLEWARE_WEB, ['web', \OEngine\Platform\Middleware\Platform::class]))
                ->group($this->package->basePath('/../routes/web.php'));

        if (file_exists($this->package->basePath('/../routes/admin.php')))
            Route::middleware(...apply_filters(PLATFORM_MIDDLEWARE_ADMIN, ['web', \OEngine\Platform\Middleware\Authenticate::class, \OEngine\Platform\Middleware\Platform::class]))
                ->prefix(apply_filters(PLATFORM_URL_ADMIN, ""))
                ->group($this->package->basePath('/../routes/admin.php'));


        $this->packageBooted();

        return $this;
    }
}