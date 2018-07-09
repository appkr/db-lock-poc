<?php

namespace App\Support;

use Illuminate\Http\Request;

class RequestExtractor implements Extractor
{
    public function accept(ExtractorVisitor $visitor): array
    {
        return $visitor->visit($this);
    }

    public function extract(Request $request)
    {
        return [
            'fingerprint' => $this->extractFingerprint($request),
            'clientIp' => $request->getClientIp(),
            'method' => $request->getMethod(),
            'host' => $request->getHttpHost(),
            'uri' => $request->getRequestUri(),
            'ajax' => $request->ajax(),
            'query' => $request->query(),
            'contentType' => $request->getContentType(),
            'content' => $this->extractContent($request),
            'contentLength' => $request->header('Content-Length'),
            'authorization' => $request->header('Authorization'),
        ];
    }

    private function extractContent(Request $request)
    {
        $content = $request->getContent();

        if ($request->getContentType() === 'json') {
            return json_decode($content, true);
        }

        return $content;
    }

    private function extractFingerprint(Request $request)
    {
        try {
            return $request->fingerprint();
        } catch (\RuntimeException $e) {
            return $e->getMessage();
        }
    }
}
