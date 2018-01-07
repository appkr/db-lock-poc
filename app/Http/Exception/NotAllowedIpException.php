<?php

namespace App\Http\Exception;

use Illuminate\Http\Response;
use Myshop\Common\Exception\HttpDomainException;
use Myshop\Common\Exception\LogLevel;

class NotAllowedIpException extends HttpDomainException
{
    protected $message = '허용되지 않은 IP 주소로 접근했습니다.';

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