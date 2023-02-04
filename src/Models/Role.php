<?php

namespace OEngine\Platform\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OEngine\Core\Traits\WithSlug;

class Role extends Model
{
    use HasFactory, WithSlug;
    public $FieldSlug = "name";
    private static $role_supper_admin = null;
    public static function SupperAdmin()
    {
        return self::$role_supper_admin ?? (self::$role_supper_admin = apply_filters(PLATFORM_ROLE_SUPPER_ADMIN, 'admin'));
    }
    protected $fillable = [
        'name',
        'slug'
    ];
    public function isSuperAdmin(): bool
    {
        return $this->name == self::SupperAdmin();
    }
    public function permissions()
    {
        return $this->belongsToMany(config('platform.model.permission'), 'roles_permissions');
    }

    public function users()
    {
        return $this->belongsToMany(config('platform.model.user'), 'users_roles');
    }
}
