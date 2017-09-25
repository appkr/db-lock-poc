<?php

namespace Myshop\Common\Dto;

use Myshop\Common\Model\Money;
use Myshop\Common\Model\PriceRange;

class ProductSearchParam
{
    private $keyword;
    private $priceRange;
    private $sortBy;
    private $sortDirection;
    private $page;
    private $size;

    public function __construct(
        string $keyword = null,
        PriceRange $priceRange = null,
        string $sortBy = null,
        string $sortDirection = null,
        int $page = null,
        int $size = null
    ) {
        $this->keyword = $keyword;
        $this->priceRange = $priceRange;
        $this->sortBy = $sortBy ?: 'created_at';
        $this->sortDirection = $sortDirection ?: 'desc';
        $this->page = $page ?: 1;
        $this->size = $size ?: 10;
    }

    public function getKeyword()
    {
        return $this->keyword;
    }

    public function getPriceRange()
    {
        return $this->priceRange;
    }

    public function getSortBy()
    {
        return $this->sortBy;
    }

    public function getSortDirection()
    {
        return $this->sortDirection;
    }

    public function getPage()
    {
        return $this->page;
    }

    public function getSize()
    {
        return $this->size;
    }
}