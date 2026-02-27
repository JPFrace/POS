<?php

namespace App\Models;

use App\Enums\ContactSubTypes;
use App\Enums\ContactType;
use App\Supports\Models\HasUuid;
use App\Supports\Models\RouteModelBinding;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Auditable
{
    use HasFactory;
    use SoftDeletes;
    use RouteModelBinding;
    use HasUuid, HasUuids {
        HasUuid::newUniqueId insteadof HasUuids;
        HasUuid::uniqueIds insteadof HasUuids;
    }

    protected $fillable = [
        'sub_type_id',
        'class_id',
        'id_no',
        'type',
        'name',
        'first_name',
        'last_name',
        'middle_name',
        'suffix',
        'email',
        'billing_address',
        'country_id',
        'zip_code',
        'contact_number',
        'file_id',
        'tax_id',
        'created_by'
    ];
    protected $hidden = [
        'id',
        'file_id',
        'tax_id',
        'sub_type_id',
        'class_id',
        'country_id',
        'created_by',
        'deleted_at',
    ];

    protected $casts = [
        'type' => ContactType::class,
        'created_at' => 'datetime:m/d/Y'
    ];

    protected $appends = [
        'full_name',
        'type_label',
    ];

    public function contacts(): HasMany
    {
        return $this->hasMany(ContactDetail::class, 'contact_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function subType(): BelongsTo
    {
        return $this->belongsTo(ContactSubType::class, 'sub_type_id');
    }

    public function class(): BelongsTo
    {
        return $this->belongsTo(ContactClass::class, 'class_id');
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'customer_idno', 'id_no');
    }

    public function bills(): HasMany
    {
        return $this->hasMany(Bill::class, 'vendor_idno', 'id_no');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'vendor_idno', 'id_no');
    }

    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function tax(): BelongsTo
    {
        return $this->belongsTo(Tax::class, 'tax_id');
    }

    public function journals(): HasMany
    {
        return $this->hasMany(Journal::class, 'contact_idno', 'id_no');
    }

    public function subTypeName(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->subType?->name ?? ''
        );
    }

    public function fullName(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->sub_type_name == ContactSubTypes::INDIVIDUAL->value) {
                    return $this->last_name . ", " . $this->first_name;
                }

                return $this->name;
            }
        );
    }

    public function typeLabel(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->type->name
        );
    }
}