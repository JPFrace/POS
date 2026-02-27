<?php

namespace App\Models;

use App\Models\ChartAccount;
use Illuminate\Database\Eloquent\Model;

class ChartAccountBalance extends Model
{
    protected $fillable = [
        'start_at',
        'end_at',
        'beginning',
        'ending',
        'chart_account_id'
    ];

    protected $casts = [
        'start_at' => 'date:m/d/Y',
        'end_at' => 'date:m/d/Y',
    ];

    public function chartAccount()
    {
        return $this->belongsTo(ChartAccount::class);
    }
}