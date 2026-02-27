<?php

namespace App\Enums;

enum ContactSubTypes: string
{
    case INDIVIDUAL = 'Individual';
    case CORPORATION = 'Corporation';
    case SOLEPROPRIETORSHIP = 'Sole Proprietorship';
    case PARTNERSHIP = 'Partnership';
}
