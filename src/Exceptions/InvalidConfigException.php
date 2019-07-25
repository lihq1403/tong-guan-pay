<?php

namespace Lihq1403\TongGuanPay\Exceptions;

class InvalidConfigException extends Exception
{
    public function __construct($message, $raw = [])
    {
        parent::__construct('INVALID_CONFIG: '.$message, $raw, self::INVALID_CONFIG);
    }
}
