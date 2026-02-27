<?php

namespace App\Concerns\Exceptions;

class CalendarClosedException extends \Exception
{
    public function __construct($message = "Calendar is closed.", $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}