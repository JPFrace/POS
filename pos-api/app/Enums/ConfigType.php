<?php

namespace App\Enums;

enum ConfigType: string
{
    case NONE = 'none';
    case JSON = 'json';
    case STRING = 'string';
    case BOOLEAN = 'boolean';
    case INT = 'int';
    case DECIMAL = 'decimal';
}
