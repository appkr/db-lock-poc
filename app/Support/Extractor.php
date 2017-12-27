<?php

namespace App\Support;

interface Extractor
{
    public function accept(ExtractorVisitor $visitor): array;
}