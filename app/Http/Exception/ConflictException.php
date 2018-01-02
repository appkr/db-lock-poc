<?php

namespace App\Http\Exception;

use Illuminate\Http\Response;
use Myshop\Common\Exception\HttpDomainException;
use Myshop\Common\Exception\LogLevel;

class ConflictException extends HttpDomainException
{
    protected $message = 'Conflict';

    public function getLogLevel(): LogLevel
    {
        return LogLevel::WARNING();
    }

    public function getStatusCode()
    {
        return Response::HTTP_CONFLICT;
    }

    public function getHeaders()
    {
        return [];
    }
}