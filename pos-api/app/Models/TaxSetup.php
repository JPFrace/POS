<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Supports\Models\HasUuid;
use App\Supports\Models\RouteModelBinding;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class TaxSetup extends Model
{
    use HasFactory;
    use RouteModelBinding;
    use HasUuid, HasUuids {
        HasUuid::newUniqueId insteadof HasUuids;
        HasUuid::uniqueIds insteadof HasUuids;
    }
    protected $table = 'tax_setup';


    protected $fillable = [
        'calendar_id',
        'tax_id',
        'period',
        'start_tax_period',
        'start_tax_at',
        'reporting_method',
        'regno',
        'created_at',
        'updated_at',
        'payroll_payable_account_id',
        'sales_payable_account_id',
        'income_payable_account_id'
    ];

    protected $hidden = [
        // 'id',
        // 'calendar_id',
        // 'tax_id'
    ];

    public function calendar()
    {
        return $this->belongsTo(Calendar::class, 'calendar_id');
    }

    public function tax()
    {
        return $this->belongsTo(Tax::class, "tax_id");
    }
}
