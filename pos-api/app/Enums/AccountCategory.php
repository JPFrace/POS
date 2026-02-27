<?php

namespace App\Enums;

enum AccountCategory: string
{
    case ASSETS = 'Assets';
    case LIABILITIES = 'Liabilities';
    case EQUITY = 'Equity';
    case REVENUE = 'Revenue';
    case EXPENSES = 'Expenses';
}
