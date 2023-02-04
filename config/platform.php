<?php

return [
    'platform' => [
        'root' =>  env('PLATFORM_ROOT', 'platform'),
        'theme' => env('PLATFORM_THEME', 'themes'),
        'module' => env('PLATFORM_MODULE', 'modules'),
    ],
    'model' => [
        'user' => OEngine\Platform\Models\User::class,
        'role' => OEngine\Platform\Models\Role::class,
        'permission' => OEngine\Platform\Models\Permission::class,
    ]
];
