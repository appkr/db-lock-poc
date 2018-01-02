<?php

namespace Myshop\Common\Exception;

use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

abstract class HttpDomainException extends DomainException implements HttpExceptionInterface
{
    /**
     * {@inheritdoc}
     */
    abstract public function getStatusCode();

    /**
     * {@inheritdoc}
     */
    abstract public function getHeaders();
}