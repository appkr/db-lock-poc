<?php

namespace App\Http\Middleware;

use App\ApplicationContext;
use App\Http\XHttpHeader;
use Closure;

class AttachTransactionIdResponseHeader
{
    private $appContext;

    public function __construct(ApplicationContext $appContext)
    {
        $this->appContext = $appContext;
    }

    public function handle($request, Closure $next)
    {
        $response = $next($request);
        $response->headers->set(XHttpHeader::TRANSACTION_ID , $this->appContext->getTransactionId());

        return $response;
    }
}
