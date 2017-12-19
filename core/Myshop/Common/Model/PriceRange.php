<?php

namespace Myshop\Common\Model;

class PriceRange
{
    private $bottom;
    private $top;

    public function __construct(Money $bottom = null, Money $top = null)
    {
        $this->bottom = $bottom;
        $this->top = $top;
    }

    public function getBottom()
    {
        return $this->bottom;
    }

    public function getTop()
    {
        return $this->top;
    }

    public function range()
    {
        return $this->top->subtract($this->bottom);
    }

    public function isInBetween(Money $test)
    {
        $isTestBiggerThanBottom = $this->bottom->isEqualOrBiggerThan($test);
        $isTestSmallerThanTop = $this->top->isEqualOrSmallerThan($test);

        return $isTestBiggerThanBottom && $isTestSmallerThanTop;
    }
}