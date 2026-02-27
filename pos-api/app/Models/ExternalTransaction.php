<?php

namespace App\Models;

use App\Models\Concerns\Externals\RevertableJournal;
use App\Models\Concerns\Externals\CreatableJournal;
use App\Supports\Models\HasUuid;
use App\Supports\Models\RouteModelBinding;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Exceptions\HttpResponseException;

class ExternalTransaction extends Model
{
    use HasFactory;
    use SoftDeletes;
    use RouteModelBinding;
    use HasUuid, HasUuids {
        HasUuid::newUniqueId insteadof HasUuids;
        HasUuid::uniqueIds insteadof HasUuids;
    }

    use CreatableJournal;
    use RevertableJournal;

    protected $fillable = [
        'header_id',
        'code',
        'credit',
        'debit',
        'document_date',
        'cost_center',
        'contact_name',
        'contact_id_no',
        'reference_no',
    ];

    protected $hidden = [
        'id',
        'header_id',
        'deleted_at',
    ];

    protected $casts = [
        'document_date' => 'date:m-d-Y',
        'created_at' => 'datetime:m-d-Y H:i:s',
        'updated_at' => 'datetime:m-d-Y H:i:s',
    ];

    public static function booted()
    {
        static::created(function (ExternalTransaction $model) {
            $model->debit();
            $model->credit();
        });

        static::deleted(function (ExternalTransaction $model) {
            $model->revertDebit();
            $model->revertCredit();
        });
    }

    public function parent()
    {
        return $this->belongsTo(External::class, 'header_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'cost_center', 'code');
    }
}
