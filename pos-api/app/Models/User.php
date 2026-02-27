<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Supports\Models\Common;
use App\Supports\Models\RouteModelBinding;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Supports\Models\HasUuid;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Http\Exceptions\HttpResponseException;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, TwoFactorAuthenticatable;
    use RouteModelBinding;
    use Common;
    use SoftDeletes;
    use HasApiTokens;
    use HasUuid, HasUuids {
        HasUuid::newUniqueId insteadof HasUuids;
        HasUuid::uniqueIds insteadof HasUuids;
    }
    use Concerns\HasPermission;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'address',
        'contacts',
        'image_id',
        'avatar',
        'department',
        'position',
        'password',
        'oauth_email',
        'oauth_token',
        'oauth_refresh_token',
        'oauth_expired_in'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'id',
        'image_id',
    ];


    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'created_at' => 'datetime:m/d/Y H:i:s'
        ];
    }

    protected $appends = [
        'photo'
    ];

    public function oauths(): MorphToMany
    {
        return $this->morphToMany(OAuth::class, "oauthable");
    }

    public function roles(): MorphMany
    {
        return $this->morphMany(
            UserRole::class,
            'rollable',
        );
    }


    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class, 'image_id');
    }    

    public function audits(): HasMany
    {
        return $this->hasMany(Audit::class, 'user_id');
    }    

    public function rolesAssigned(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->roles->pluck('slug')->toArray()
        );
    }

    public function photo(): Attribute
    {
        return Attribute::make(
            get: fn() => optional($this->file)->url
        );
    }
}
