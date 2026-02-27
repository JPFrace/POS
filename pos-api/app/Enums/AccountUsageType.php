<?php

namespace App\Enums;

enum AccountUsageType: string
{
    case BANK = 'Bank';
    case CHEQUE = 'Cheque';
    case AR = 'Accounts Receivable A/R';
    case CASH_IN_BANK = 'Cash In Bank';
    case WTAX = 'Withholding Tax';
    case DEPOSITORY = 'Depository';

    case ACCOUNTS_PAYABLE = 'Accounts Payable A/P';

    case UNDEPOSITED = 'Undeposited';
}
