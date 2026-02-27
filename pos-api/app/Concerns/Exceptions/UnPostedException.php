<?php

namespace App\Concerns\Exceptions;

class UnPostedException extends \Exception
{
    public function __construct($message = "Transaction is already unposted.", $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}