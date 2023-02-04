<?php

namespace OEngine\Platform\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use OEngine\Platform\Traits\WithPermission;
use OEngine\Platform\Traits\WithSlug;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use WithPermission, WithSlug;
    public $FieldSlug = "name";
    protected $fillable = ["*"];
    public function isActive()
    {
        return $this->status == 1;
    }
    public function isSuperAdmin(): bool
    {
        return $this->hasRole(config('platform.model.role')::SupperAdmin());
    }
    public function isBlock()
    {
        return !$this->isActive();
    }
    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            if (Hash::needsRehash($model->password)) {
                $model->password = Hash::make($model->password);
            }
        });
        self::updating(function ($model) {
            if ($model->password && Hash::needsRehash($model->password)) {
                $model->password = Hash::make($model->password);
            }
        });
    }
}
