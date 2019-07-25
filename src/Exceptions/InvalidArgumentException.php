<?php

namespace Lihq1403\TongGuanPay\Exceptions;

class InvalidArgumentException extends Exception
{
    public function __construct($message, $raw = [])
    {
        parent::__construct('INVALID_ARGUMENT: '.$message, $raw, self::INVALID_ARGUMENT);
    }
}
