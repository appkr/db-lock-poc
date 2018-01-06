<?php

namespace App\Http\Exception;

use Illuminate\Http\Response;
use Myshop\Common\Exception\HttpDomainException;
use Myshop\Common\Exception\LogLevel;

class BadRequestException extends HttpDomainException
{
    protected $message = 'Bad Request';

    public function getLogLevel(): LogLevel
    {
        return LogLevel::WARNING();
    }

    public function getStatusCode()
    {
        return Response::HTTP_BAD_REQUEST;
    }

    public function getHeaders()
    {
        return [];
    }
}