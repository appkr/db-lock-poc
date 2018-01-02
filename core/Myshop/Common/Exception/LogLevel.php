<?php

namespace Myshop\Common\Exception;

use MyCLabs\Enum\Enum;

/**
 * Class LogLevel
 * @package Myshop\Common\Exception
 * for de-facto @see \Psr\Log\LogLevel
 *
 * @method static LogLevel EMERGENCY()
 * @method static LogLevel ALERT()
 * @method static LogLevel CRITICAL()
 * @method static LogLevel ERROR()
 * @method static LogLevel WARNING()
 * @method static LogLevel NOTICE()
 * @method static LogLevel INFO()
 * @method static LogLevel DEBUG()
 */
class LogLevel extends Enum
{
    const EMERGENCY = 0; // Emergency: system is unusable
    const ALERT = 1;     // Alert: action must be taken immediately
    const CRITICAL = 2;  // Critical: critical conditions
    const ERROR = 3;     // Error: error conditions
    const WARNING = 4;   // Warning: warning conditions
    const NOTICE = 5;    // Notice: normal but significant condition
    const INFO = 6;      // Info: informational messages
    const DEBUG = 7;     // Debug: debug-level messages
}