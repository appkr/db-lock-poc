<?php

namespace App\Support;

use Illuminate\Http\Request;

class RequestScrambler extends AbstractScrambler implements ExtractorVisitor
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function visit(Extractor $extractor): array
    {
        $genuineData = $extractor->extract($this->request);

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
