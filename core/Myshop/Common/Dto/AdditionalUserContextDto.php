<?php

namespace Myshop\Common\Dto;

class AdditionalUserContextDto
{
    private $httpHost;
    private $clientIp;
    private $userAgent;

    public function __construct(
        string $httpHost = null,
        string $clientIp = null,
        string $userAgent = null
    ) {
        $this->httpHost = $httpHost;
        $this->clientIp = $clientIp;
        $this->userAgent = $userAgent;
    }

    public function getHttpHost()
    {
        return $this->httpHost;
    }

    public function getClientIp()
    {
        return $this->clientIp;
    }

    public function getUserAgent()
    {
        return $this->userAgent;
    }
}