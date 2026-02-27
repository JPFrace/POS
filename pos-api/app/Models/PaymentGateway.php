<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentGateway extends Model
{
    use HasFactory;

    protected $fillable = [
        "code",
        "name",
        "groups",
        "channels",
        "active"
    ];

    protected $casts = [
        "groups" => "array",
        "channels" => "array",
        "active" => "boolean"
    ];

}
