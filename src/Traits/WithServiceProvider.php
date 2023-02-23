<?php

namespace OEngine\Platform\Traits;

use Illuminate\Support\Facades\Route;
use OEngine\LaravelPackage\WithServiceProvider as WithServiceProviderBase;
use OEngine\Platform\Facades\Platform;
use OEngine\Platform\Facades\Plugin;
use OEngine\Platform\Facades\Theme;

trait WithServiceProvider
{
    use WithServiceProviderBase {
        register as protected registerBase;
        boot as protected bootBase;
    }
    public function configurePackaged()
    {
        if ($name = Platform::Current()) {
            $this->package->name($name);
        }
    }
    public function register()
    {
        $this->ExtendPackage();
        $this->registerBase();
        Theme::Load($this->package->basePath('/../themes'));
        Plugin::Load($this->package->basePath('/../plugins'));

        if (file_exists($this->package->basePath('/../routes/api.php')))
            Route::middleware(apply_filters(PLATFORM_MIDDLEWARE_API, ['api', \OEngine\Platform\Middleware\Platform::class]))
                ->prefix('api')
                ->group($this->package->basePath('/../routes/api.php'));

        if (file_exists($this->package->basePath('/../routes/web.php')))
            Route::middleware(apply_filters(PLATFORM_MIDDLEWARE_WEB, ['web', \OEngine\Platform\Middleware\Platform::class]))
                ->group($this->package->basePath('/../routes/web.php'));

        if (file_exists($this->package->basePath('/../routes/admin.php')))
            Route::middleware(apply_filters(PLATFORM_MIDDLEWARE_ADMIN, ['web', \OEngine\Platform\Middleware\Authenticate::class, \OEngine\Platform\Middleware\ThemeAdmin::class, \OEngine\Platform\Middleware\Platform::class]))
                ->prefix(adminUrl())
                ->group($this->package->basePath('/../routes/admin.php'));


        $this->packageRegistered();
        return $this;
    }


    public function boot()
    {
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
