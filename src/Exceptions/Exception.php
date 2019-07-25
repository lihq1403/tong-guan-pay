<?php

namespace Lihq1403\TongGuanPay\Exceptions;

class Exception extends \Exception
{
    const UNKNOWN_ERROR = 9999;

    const ERROR_HTTP_REQUEST = 1;

    const INVALID_CONFIG = 2;

    const INVALID_ARGUMENT = 3;


    /**
     * Raw error info.
     *
     * @var array
     */
    public $raw;

    /**
     * @param string       $message
     * @param array|string $raw
     * @param int|string   $code
     */
    public function __construct($message = '', $raw = [], $code = self::UNKNOWN_ERROR)
    {
        $message = $message === '' ? 'Unknown Error' : $message;
        $this->raw = is_array($raw) ? $raw : [$raw];

        parent::__construct($message, intval($code));
    }
}
