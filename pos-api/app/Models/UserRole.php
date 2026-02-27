<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class UserRole extends Model
{
    use HasFactory;

    protected $table = "user_roles";

    protected $fillable = [
        'role_id'
    ];

    protected $hidden = [
        'rollable_id',
        'rollable_type',
        'role_id',
        'user_id',
        'id'
    ];

    public function rollable(): MorphTo
    {
        return $this->morphTo();
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }
}
