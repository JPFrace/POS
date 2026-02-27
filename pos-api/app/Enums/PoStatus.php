<?php

namespace App\Enums;

enum PoStatus: string
{
    case OPEN = 'Open';
    case PARTIAL = 'Partially Billed';
    case COMPLETED = 'Completed';
    case CANCELLED = 'Cancelled';
}
