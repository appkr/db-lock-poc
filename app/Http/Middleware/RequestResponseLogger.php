<?php

namespace App\Http\Middleware;

use App\Support\RequestScrambler;
use App\Support\RequestExtractor;
use App\Support\ResponseExtractor;
use App\Support\ResponseScrambler;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;

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

    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }

    public function terminate(Request $request, Response $response)
    {
        if ($this->shouldSkipLogging($request) || $request->getMethod() === 'OPTIONS') {
            return;
        }

        // [2017-12-27 12:36:12] local.DEBUG: Request & Response:
        // {
        //     "request": {
        //         "method": "POST",
        //         "host": "localhost",
        //         "uri": "/api/auth/login",
        //         "content": {
        //             "email": "member@example.com",
        //             "password": "[FILTERED]" <= 로그에서 민감한 데이터 필터링
        //         }
        //     },
        //     "response": {
        //         "code": 200,
        //         "content": {...}
        //     }
        // }  {"fingerprint":"713ab3949c9955c27ed9c7e01aeb4e367bed3561"}
        //
        // extract() 함수에서 scramble() 함수를 호출해서 필터링된 로그를 쉽게 얻을 수도 있지만,
        // 공부 목적으로 Visitor Pattern을 적용하고, 확장 가능한 구조를 선택했습니다.
        //
        //                       1. call accept(visitor: ExtractorVisitor)
        // RequestResponseLogger --------------------------------> Extractor
        //                       <--------------------------------
        //                                         6. array (== 5)
        //
        //                       2. call visit(extractor: Extractor)
        // Extractor             --------------------------------> ExtractorVisitor
        //                       <--------------------------------
        //                            5. array with Scrambled Data
        //
        //                       3. call extract(request: Request)
        // ExtractorVisitor      --------------------------------> Extractor
        //                       <--------------------------------
        //                               4.array with Genuine Data
        $data = [
            'request' => $this->requestExtractor->accept(new RequestScrambler($request)),
            'response' => $this->responseExtractor->accept(new ResponseScrambler($response)),
        ];

        $this->logger->debug('Request & Response:' . PHP_EOL . json_encode($data,
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    }

    private function shouldSkipLogging($request)
    {
        return Str::startsWith($request->getRequestUri(), $this->skipList);
    }
}
