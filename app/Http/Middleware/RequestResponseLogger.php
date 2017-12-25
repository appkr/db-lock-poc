<?php

namespace App\Http\Middleware;

use App\Support\RequestExtractor;
use App\Support\ResponseExtractor;
use Closure;
use Illuminate\Support\Str;
use Psr\Log\LoggerInterface;

class RequestResponseLogger
{
    private $requestExtractor;
    private $responseExtractor;
    private $logger;
    private $skipList;

    public function __construct(
        RequestExtractor $requestExtractor,
        ResponseExtractor $responseExtractor,
        LoggerInterface $logger,
        array $skipList = []
    ) {
        $this->requestExtractor = $requestExtractor;
        $this->responseExtractor = $responseExtractor;
        $this->logger = $logger;
        $this->skipList = $skipList;
    }

    public function handle($request, Closure $next)
    {
        return $next($request);
    }

    public function terminate($request, $response)
    {
        if ($this->shouldSkipLogging($request) || $request->getMethod() === 'OPTIONS') {
            return;
        }

        $data = [
            // TODO @appkr
            // 1. Inject Sensitive information(like password) filter
            // 2. Find best design to apply it(Decorator, Visitor...)
            'request' => $this->requestExtractor->extract($request),
            'response' => $this->responseExtractor->extract($response),
        ];

        $this->logger->debug('Request & Response:' . PHP_EOL . json_encode($data,
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    }

    private function shouldSkipLogging($request)
    {
        return Str::startsWith($request->getRequestUri(), $this->skipList);
    }
}
