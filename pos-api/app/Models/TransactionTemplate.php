<?php

namespace App\Models;

use App\Supports\Models\HasUuid;
use App\Supports\Models\RouteModelBinding;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TransactionTemplate extends Model
{
    use HasFactory;
    use RouteModelBinding;
    use SoftDeletes;
    use HasUuid, HasUuids {
        HasUuid::newUniqueId insteadof HasUuids;
        HasUuid::uniqueIds insteadof HasUuids;
    }

    protected $fillable = [
        'name',
        'description',
        'is_inactive',
        'creator_id',
    ];

    protected $casts = [
        'is_inactive' => 'boolean',
    ];

    protected $hidden = [
        'id',
        'creator_id',
        'deleted_at',
    ];

    protected $with = ['details.product'];

    protected static function booted(): void
    {
        static::deleting(function (TransactionTemplate $template) {
            $template->details()->delete();
        });
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function details(): HasMany
    {
        return $this->hasMany(TransactionTemplateDetail::class, 'template_id');
    }
}
