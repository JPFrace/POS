<?php

namespace App\Enums;

enum TransType: string
{
    case JOURNAL = '10';
    case COLLECTION = '20';
    case DISBURSEMENT = '30';
    case INVOICE = '40';
    case AP = '50';
    case BEGINNING = '60';
    case DEPOSIT = '70';
}
