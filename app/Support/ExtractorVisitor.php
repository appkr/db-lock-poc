<?php

namespace App\Support;

interface ExtractorVisitor
{
    public function visit(Extractor $extractor): array;
}
