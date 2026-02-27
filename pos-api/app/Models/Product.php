<?php

namespace App\Models;

use App\Enums\AccountUsageType as EnumAccountUsageType;
use App\Supports\Models\Common;
use App\Supports\Models\RouteModelBinding;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute as CastAttribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Supports\Models\HasUuid;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Exceptions\HttpResponseException;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;
    use RouteModelBinding;
    use HasUuid, HasUuids {
        HasUuid::newUniqueId insteadof HasUuids;
        HasUuid::uniqueIds insteadof HasUuids;
    }

    protected $fillable = [
        'sku',
        'name',
        'category_id',
        'description',
        'price',
        'income_id',
        'photo_id',
        'purchase_description',
        'cost',
        'expense_id',
        'vendor_id',
        'depository_id',
        'payable_id',
        'sales_tax_id',
        'wth_tax_id',
        'receivable_id'
    ];

    protected $hidden = [
        'id',
        'category_id',
        'photo_id',
        'expense_id',
        'depository_id',
        'payable_id',
        'payment_id',
        'vendor_id',
        'income_id',
        'deleted_at',
        'sales_tax_id',
        'wth_tax_id',
        'receivable_id'
    ];

    protected $casts = [
        'created_at' => 'datetime: m/d/Y H:i:s',
        'updated_at' => 'datetime: m/d/Y H:i:s',
        'deleted_at' => 'datetime: m/d/Y H:i:s',
        'price' => 'decimal:4',
        'cost' => 'decimal:4',
        'sku' => 'string',
    ];

    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class, 'photo_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function incomeAccount(): BelongsTo
    {
        return $this->belongsTo(ChartAccount::class, 'income_id');
    }

    public function expenseAccount(): BelongsTo
    {
        return $this->belongsTo(ChartAccount::class, 'expense_id');
    }

    public function depositoryAccount(): BelongsTo
    {
        return $this->belongsTo(ChartAccount::class, 'depository_id');
    }

    public function payableAccount()
    {
        return $this->belongsTo(ChartAccount::class, 'payable_id');
    }

    public function receivableAccount(): BelongsTo
    {
        return $this->belongsTo(ChartAccount::class, 'receivable_id');
    }

    public function vendor()
    {
        return $this->belongsTo(Contact::class);
    }

    public function income()
    {
        return $this->belongsTo(ChartAccount::class);
    }
    public function expense()
    {
        return $this->belongsTo(ChartAccount::class);
    }

    public function depository()
    {
        return $this->belongsTo(ChartAccount::class);
    }

    public function payable()
    {
        return $this->belongsTo(ChartAccount::class);
    }

    public function salesTax(): BelongsTo
    {
        return $this->belongsTo(Tax::class);
    }

    public function withholdingTax(): BelongsTo
    {
        return $this->belongsTo(Tax::class, 'wth_tax_id');
    }
}
