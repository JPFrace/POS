<?php

namespace App\Enums;

use Illuminate\Support\Str;

enum PostingStatus: string
{
    case POSTED = 'Posted';
    case UNPOSTED = 'Unposted';
}
