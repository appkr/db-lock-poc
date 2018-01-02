<?php

namespace Myshop\Infrastructure\Exception;

use Myshop\Common\Exception\DomainException;
use Myshop\Common\Exception\LogLevel;

class OptimisticLockingFailureException extends DomainException
{
    protected $message = '데이터를 조회한 후에 다른 사용자에 의해 데이터가 변경되었습니다.';

    public function getLogLevel(): LogLevel
    {
        return LogLevel::ERROR();
    }
}