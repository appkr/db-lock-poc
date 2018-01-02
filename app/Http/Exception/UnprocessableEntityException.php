<?php

namespace App\Http\Exception;

use Illuminate\Http\Response;
use Myshop\Common\Exception\HttpDomainException;
use Myshop\Common\Exception\LogLevel;

class UnprocessableEntityException extends HttpDomainException
{
    protected $message = 'Unprocessable Entity';

    public function getLogLevel(): LogLevel
    {
        return LogLevel::WARNING();
    }

    public function getStatusCode()
    {
        return Response::HTTP_FORBIDDEN;
    }

    public function getHeaders()
    {
        return [];
    }
}