<?php

namespace App\Models;

use App\Enums\TaxTypes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Supports\Models\HasUuid;
use App\Supports\Models\RouteModelBinding;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tax extends Model
{
    use HasFactory;

    use RouteModelBinding;
    use HasUuid, HasUuids {
        HasUuid::newUniqueId insteadof HasUuids;
        HasUuid::uniqueIds insteadof HasUuids;
    }
    protected $table = 'taxes';

    protected $fillable = [
        'tax_agency_id',
        'code',
        'name',
        'description',
        'rate',
        'chart_account_id',
        'class_id',
        'type',
        'rate_type',
        'parent_id'
    ];

    protected $hidden = [
        // 'id',
        // 'tax_agency_id',
        // 'chart_account_id',
    ];

    protected $casts = [
        'tax_type' => TaxTypes::class,
    ];

    protected $appends = [
        'rate_label',
    ];

    public function agency()
    {
        return $this->belongsTo(TaxAgency::class);
    }

    public function sales()
    {
        return $this->belongsTo(TaxAgency::class);
    }

    public function chartAccount()
    {
        return $this->belongsTo(ChartAccount::class);
    }

    public function class(): BelongsTo
    {
        return $this->belongsTo(AccountClass::class, 'class_id');
    }

    public function taxAgency(): BelongsTo
    {
        return $this->belongsTo(TaxAgency::class, 'tax_agency_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Tax::class);
    }

    public function children()
    {
        return $this->hasMany(Tax::class, 'parent_id')->with([
            'children.chartAccount',
            'children.taxAgency',
            'children.class',
            'children.parent',
            'parent'
        ]);
    }
    public function rateLabel(): Attribute
    {
        return Attribute::make(
            get: function () {
                $label = '';
                if ($this->rate_type == 'percent') {
                    $label = '%';
                }

                return $this->rate . $label;
            }
        );
    }
}
