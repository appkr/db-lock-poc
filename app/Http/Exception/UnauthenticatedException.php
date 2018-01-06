<?php

namespace App\Http\Exception;

use Illuminate\Http\Response;
use Myshop\Common\Exception\HttpDomainException;
use Myshop\Common\Exception\LogLevel;

class UnauthenticatedException extends HttpDomainException
{
    protected $message = 'Unauthorized';

    public function getLogLevel(): LogLevel
    {
        return LogLevel::WARNING();
    }

    public function getStatusCode()
    {
        return Response::HTTP_UNAUTHORIZED;
    }

    public function getHeaders()
    {
        return [];
    }
}