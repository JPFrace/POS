<?php

namespace App\Models\Relations;

use App\Models\Auditable;
use App\Models\BillDetail;
use App\Models\BillStatus;
use App\Models\BillTerm;
use App\Models\Contact;
use App\Models\File;
use App\Models\Journal;
use App\Models\Taxonomy;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

abstract class Bill extends Auditable
{
    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class);
    }
    public function vendor()
    {
        return $this->belongsTo(Contact::class, 'vendor_idno', 'id_no');
    }

    public function details(): HasMany
    {
        return $this->hasMany(BillDetail::class, 'bill_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Taxonomy::class, 'status_id');
    }

    public function term(): BelongsTo
    {
        return $this->belongsTo(BillTerm::class);
    }

    public function postedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    public function unpostedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'unposted_by');
    }


    public function journals(): MorphMany
    {
        return $this->morphMany(
            Journal::class,
            'transactable'
        );
    }
}