<?php

namespace Lihq1403\TongGuanPay\Exceptions;

class HttpException extends Exception
{
    public function __construct($message, $raw = [])
    {
        parent::__construct('ERROR_HTTP_REQUEST: '.$message, $raw, self::ERROR_HTTP_REQUEST);
    }
}
