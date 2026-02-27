<?php

namespace App\Concerns\Exceptions;

class NoTaxException extends \Exception
{
    public function __construct($message = "Tax account is required.", $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}