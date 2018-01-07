<?php

namespace Myshop\Common\Exception;

use Exception;
use Illuminate\Http\Response;
use RuntimeException;

abstract class DomainException extends RuntimeException implements HasLogLevel
{
    public function __construct(string $message = null, Exception $previous = null)
    {
        if (empty($message)) {
            $message = $this->getMessage();
        }

        if (empty($message) && null !== $previous) {
            $message = $previous->getMessage() ?: '알 수 없는 오류가 발생했습니다.';
        }

        $code = Response::HTTP_INTERNAL_SERVER_ERROR;

        if (null !== $previous) {
            $code = ($previous instanceof HttpDomainException)
                ? $previous->getStatusCode() : $previous->getCode();
        }

        parent::__construct($message, $code, $previous);
    }

    abstract public function getLogLevel(): LogLevel;
}