<?php

namespace Myshop\Common\Exception;

use RuntimeException;

abstract class DomainException extends RuntimeException implements HasLogLevel
{
    public function __construct(DomainException $e)
    {
        $code = ($e instanceof HttpDomainException)
            ? $e->getStatusCode() : $e->getCode();

        parent::__construct($e->getMessage(), $code, $e);
    }

    abstract public function getLogLevel(): LogLevel;
}