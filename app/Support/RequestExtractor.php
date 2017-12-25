<?php

namespace App\Support;

use Illuminate\Http\Request;

class RequestExtractor
{
    public function extract(Request $request)
    {
        return [
            'fingerprint' => $request->fingerprint(),
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
            return json_decode($content);
        }

        return $content;
    }
}