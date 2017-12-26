<?php

namespace App\Support;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ResponseExtractor implements Extractor
{
    public function accept(ExtractorVisitor $visitor): array
    {
        return $visitor->visit($this);
    }

    public function extract(Response $response)
    {
        $statusCode = $response->getStatusCode();
        $content = 'Non-json Response';

        if ($response instanceof JsonResponse) {
            $content = $response->getData(true);
        }

        return [
            'code' => $statusCode,
            'content' => $content,
        ];
    }
}