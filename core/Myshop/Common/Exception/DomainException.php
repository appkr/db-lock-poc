<?php

namespace Myshop\Common\Exception;

use Exception;
use RuntimeException;

abstract class DomainException extends RuntimeException implements HasLogLevel
{
    public function __construct(Exception $e = null)
    {
        if (null === $e) {
            return parent::__construct('알 수 없는 오류가 발생했습니다.');
        }

        $code = ($e instanceof HttpDomainException)
            ? $e->getStatusCode() : $e->getCode();

        parent::__construct($e->getMessage(), $code, $e);
    }

    abstract public function getLogLevel(): LogLevel;
}