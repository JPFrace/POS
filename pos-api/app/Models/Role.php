<?php

namespace App\Models;

use App\Supports\Cache\Module;
use App\Supports\Models\RouteModelBinding;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Supports\Models\HasUuid;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Role extends Model
{
    use HasFactory;
    use RouteModelBinding;
    use HasUuid, HasUuids {
        HasUuid::newUniqueId insteadof HasUuids;
        HasUuid::uniqueIds insteadof HasUuids;
    }

    protected $fillable = [
        'slug',
        'name',
        'description',
        'is_inactive',
    ];

    protected $hidden = [
        'id',
        'updated_at',
    ];

    protected $casts = [
        'created_at' => 'datetime: m/d/y',
        'is_inactive' => 'boolean'
    ];

    protected $appends = [
        'display_name',
    ];

    public static function booted()
    {
        static::saved(function (Model $role) {
            $module = new Module(Role::class);
            $module->forget();
        });

        static::deleted(function (Model $role) {
            $module = new Module(Role::class);
            $module->forget();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function displayName(): Attribute
    {
        return Attribute::make(
            get: fn() => str($this->name)->ucfirst()
        );
    }

    public function permissions()
    {
        return $this->morphMany(Permission::class, 'grantable');
    }

    public function userRoles()
    {
        return $this->hasMany(UserRole::class, 'role_id');
    }

    public function hasConstraints()
    {
        return $this->userRoles()->exists();
    }
    public function getRouteKeyName()
    {
        return 'uuid';
    }
}
