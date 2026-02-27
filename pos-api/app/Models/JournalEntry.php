<?php

namespace App\Models;

use App\Contracts\Business\CanPost;
use App\Contracts\Business\Transactable;
use App\Supports\Models\RouteModelBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;

use App\Supports\Models\HasUuid;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class JournalEntry extends Relations\JournalEntry implements Transactable, CanPost
{
    use RouteModelBinding;
    use HasFactory;
    use SoftDeletes;
    use HasUuid, HasUuids {
        HasUuid::newUniqueId insteadof HasUuids;
        HasUuid::uniqueIds insteadof HasUuids;
    }

    use Concerns\CanPost;

    protected $fillable = [
        'je_no',
        'ref_no',
        'date',
        'memo',
        'status_id',
        'file_id',
        'creator_id',
        'posted_by',
        'posted_at',
        'unposted_by',
        'unposted_at'
    ];

    protected $casts = [
        'date' => 'datetime:m/d/Y',
        'posted_at' => 'datetime',
        'unposted_at' => 'datetime',
    ];

    protected $hidden = [
        'id',
        'file_id',
        'status_id',
        'creator_id',
        'deleted_at',
    ];

    protected $appends = [
        'amount',
        'url'
    ];

    public static function booted()
    {
        static::creating(function (JournalEntry $model) {
            if (empty($model->je_no)) {
                $model->je_no = (JournalEntry::latest()->first()?->id ?? 0) + 1;
            }
        });
        static::creating(function (JournalEntry $model) {
            if (empty($model->ref_no)) {
                $model->ref_no = (JournalEntry::latest()->first()?->id ?? 0) + 1;
            }
        });
    }

    public function getRefNo(): mixed
    {
        return $this->ref_no;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getDate(): ?Carbon
    {
        return $this->date;
    }

    /**
     * Check if posted
     * @return bool
     */
    public function isPosted(): bool
    {
        return !empty($this->posted_at);
    }


    public function getAmountAttribute()
    {
        return $this->details()->sum('debit');
    }
    public function url(): Attribute
    {
        return Attribute::make(
            get: fn() => '/business/journal-entry/' . $this->uuid
        );
    }
}