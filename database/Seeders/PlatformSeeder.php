<?php

namespace OEngine\Platform\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use OEngine\Platform\Models\Role;

class PlatformSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Load All Perrmission
        self::UpdatePermission();
        //
        $roleAdmin = new Role();
        $roleAdmin->name = Role::SupperAdmin();
        $roleAdmin->slug = Role::SupperAdmin();
        $roleAdmin->save();
        $userAdmin =  config('');
        $userAdmin->name = "nguyen van hau";
        $userAdmin->email = env('OENGINE_CORE_EMAIL', "admin@oengine.local");
        $userAdmin->password = env('OENGINE_CORE_PASSWORD', "AdMin@123");
        $userAdmin->status = 1;
        $userAdmin->save();
        $userAdmin->roles()->sync([$roleAdmin->id]);

        set_option('page_admin_theme', 'oengine-admin');
    }
    private const routerExcept = [
        'sanctum.',
        'login',
        'register',
        'ignition.',
        'livewire.',
        'core.table.slug',
        'core.dashboard',
    ];
    private static $permisisonCode = [];
    public static function SetPermission($name, $router = null)
    {
        $check = false;
        foreach (self::routerExcept as $r) {
            if (str_contains($name, $r)) {
                $check = true;
                break;
            }
        }
        if ($check) return;
        $arrCode = [$name];
        if ($router != null && ((is_numeric($router) && $router == 1) || (!str_contains($router->action['controller'], '@') && in_array(WithTableIndex::class, class_uses_recursive($router->action['controller']))))) {
            array_push($arrCode, "{$name}.add");
            array_push($arrCode, "{$name}.edit");
            array_push($arrCode, "{$name}.remove");
            array_push($arrCode, "{$name}.inport");
            array_push($arrCode, "{$name}.export");
        }
        foreach ($arrCode as $code) {
            self::$permisisonCode[] = $code;
            if (!config('core.auth.permission', \OEngine\Platform\Models\Permission::class)::where('slug', $code)->exists()) {
                config('core.auth.permission', \OEngine\Platform\Models\Permission::class)::create([
                    'name' => $code,
                    'slug' => $code,
                    'group' => 'core'
                ]);
            }
        }
    }
    public static function UpdatePermission()
    {
        $routeCollection = Route::getRoutes();
        self::$permisisonCode = [];

        foreach ($routeCollection as $value) {
            $name = $value->getName();
            //skip with prexfix '_'
            if (!$name || Str::startsWith($name, '_') || !in_array(Illuminate\Auth\Middleware\Authenticate::class, $value->gatherMiddleware())) continue;
            self::SetPermission($name, $value);
        }
        $temp = apply_filters(PLATFORM_PERMISSION_CUSTOME, []);
        if ($temp != null) {
            foreach ($temp as $key) {
                self::SetPermission($key);
            }
        }
        config('core.auth.permission', \OEngine\Platform\Models\Permission::class)::query()->whereNotIn('slug', self::$permisisonCode)->delete();
        self::$permisisonCode = [];
    }
}
