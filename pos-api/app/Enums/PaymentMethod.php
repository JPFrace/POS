<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case CASH = 'CASH';
    case CHECK = 'CHECK';
    case CREDIT_CARD = 'CREDIT CARD';
    case BANK_TRANSFER = 'BANK TRANSFER';
    case PETTY_CASH = 'PETTY CASH';
}
