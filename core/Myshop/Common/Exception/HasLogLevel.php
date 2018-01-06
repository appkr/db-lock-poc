<?php

namespace Myshop\Common\Exception;

interface HasLogLevel
{
    public function getLogLevel(): LogLevel;
}