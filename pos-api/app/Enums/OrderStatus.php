<?php

namespace App\Enums;

enum OrderStatus: string
{
    case UNPAID = 'UNPAID';
    case PENDING = 'PENDING';
    case PROCESSING = 'PROCESSING';
    case TO_CLAIM = 'TO CLAIM';
    case COMPLETED = 'COMPLETED';
    case CANCELLED = 'CANCELLED';
    case INVALID_PAYMENT = 'INVALID PAYMENT';
}
