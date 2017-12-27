<?php

namespace App\Support;

use Symfony\Component\HttpFoundation\Response;

class ResponseScrambler extends AbstractScrambler implements ExtractorVisitor
{
    private $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function visit(Extractor $extractor): array
    {
        $genuineData = $extractor->extract($this->response);

        return $this->scramble($genuineData);
    }

    public function getPatterns(): array
    {
        return [
            '/\bpassword\b/i',
        ];
    }

    public function getPlaceholder(): string
    {
        return '[FILTERED]';
    }
}
