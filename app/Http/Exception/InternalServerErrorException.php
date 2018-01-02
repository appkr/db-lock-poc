<?php

namespace App\Http\Exception;

use Illuminate\Http\Response;
use Myshop\Common\Exception\HttpDomainException;
use Myshop\Common\Exception\LogLevel;

class InternalServerErrorException extends HttpDomainException
{
    protected $message = 'Internal Server Error';

    public function getLogLevel(): LogLevel
    {
        return LogLevel::ERROR();
    }

    public function getStatusCode()
    {
        return Response::HTTP_INTERNAL_SERVER_ERROR;
    }

    public function getHeaders()
    {
        return [];
    }
}