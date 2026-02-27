<?php

namespace App\Models;

use App\Enums\ContactType;
use App\Enums\TransType;
use App\Models\TransType as TransactionType;
use App\Services\Reports\BeginningEndingBalance;
use App\Supports\Utils\Amount;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Facades\SystemConfig;

use App\Supports\Models\HasUuid;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Journal extends Auditable
{
    use HasFactory;
    use HasUuid, HasUuids {
        HasUuid::newUniqueId insteadof HasUuids;
        HasUuid::uniqueIds insteadof HasUuids;
    }

    protected $fillable = [
        'trans_type',
        'ref_no',
        'chart_account_id',
        'dept_id',
        'trans_code',
        'debit',
        'credit',
        'description',
        'creator_id',
        'contact_type',
        'contact_name',
        'contact_idno',
        'sub_contact_idno',
        'transactable_type',
        'transactable_id',
        'seq',
        'posted_by',
        'posted_at',
    ];

    protected $casts = [
        'debit' => 'decimal:4',
        'credit' => 'decimal:4',
        'posted_at' => 'datetime:m/d/Y',
        'trans_type' => TransType::class,
        'contact_type' => ContactType::class
    ];

    protected $hidden = [
        'id',
        'chart_account_id',
        'dept_id',
        'creator_id',
        'posted_by',
        'transactable_type',
        'transactable_id',
        'deleted_at',
    ];

    protected $appends = [
        'trans_description'
    ];

    protected static function booted()
    {
        static::saving(function ($model) {
            $model->debit = Amount::acceptable($model->debit);
            $model->credit = Amount::acceptable($model->credit);
        });

        static::saved(function ($model) {
            BeginningEndingBalance::generate(
                $model->chartAccount,
                $model->posted_at->startOfDay(),
                $model->posted_at->endOfDay()
            )->runtime();
        });

        static::deleted(function ($model) {
            BeginningEndingBalance::generate(
                $model->chartAccount,
                $model->posted_at->startOfDay(),
                $model->posted_at->endOfDay()
            )->runtime();
        });
    }

    public function chartAccount()
    {
        return $this->belongsTo(ChartAccount::class);
    }

    public function transType()
    {
        return $this->belongsTo(TransactionType::class, 'trans_type', 'code');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function postedBy()
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class)->where('trans_type', TransType::DISBURSEMENT);
    }

    public function transactable()
    {
        return $this->morphTo();
    }

    public function financialCode()
    {
        return $this->belongsTo(FinancialTransCode::class, 'trans_type', 'trans_type');
    }

    public function transDescription(): Attribute
    {
        return Attribute::make(
            get: function () {
                return match (true) {
                    $this->transactable instanceof Payment => $this->transactable->contact_name,
                    $this->transactable instanceof OfficialReceipt => $this->transactable->contact_name,
                    default => $this->description
                };
            }
        );
    }
}