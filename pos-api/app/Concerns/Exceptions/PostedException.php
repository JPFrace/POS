<?php

namespace App\Concerns\Exceptions;

class PostedException extends \Exception
{
    public function __construct($message = "Transaction is already posted.", $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}